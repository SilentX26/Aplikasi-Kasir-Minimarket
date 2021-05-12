<?php
class Auth extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        if(!in_array($this->uri->segment(2), ['login', 'logout']) && !$this->user)
            setAlertResult('danger', 'Ups, autentikasi dibutuhkan. Silahkan login terlebih dahulu.', 'auth/login');
    }

    function test()
    {
        unset($_SESSION['cart']);
    }

    function pengaturan()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post_action = $this->input->post('action', TRUE);
            switch($post_action) {
                case 'umum':
                    $post_nama_lengkap = filter($this->input->post('nama_lengkap', TRUE));
                    $post_password = filter($this->input->post('password', TRUE));

                    if(!password_verify($post_password, $this->user->password)) {
                        setAlertResult('danger', 'Ups, password yang anda masukkan salah.');
                        
                    } else {
                        if(!empty($_FILES['foto-profile']['name'])) {
                            unlink("assets/img/akun/{$this->user->data->foto_profile}");
                            $this->load->library('upload', [
                                'upload_path' => './assets/img/akun/',
                                'allowed_types' => 'jpg|png|svg',
                                'file_name' => $this->user->username
                            ]);
                            if($this->upload->do_upload('foto-profile'))
                                $this->user->data->foto_profile = $this->upload->data('file_name');
                            else
                                setAlertResult('danger', 'Ups, proses upload foto profile gagal.');
                        }
                    }

                    $update = $this->Main_model->json('akun', 'set', [
                        'data' => [
                            'nama_lengkap' => $post_nama_lengkap,
                            'foto_profile' => $this->user->data->foto_profile 
                        ]
                    ], ['username' => $this->user->username]);
                    if($update !== FALSE)
                        setAlertResult('success', 'Perubahan berhasil disimpan.');
                    else
                        setAlertResult('danger', 'Terjadi kesalahan, sistem error.');
                    break;
                case 'password':
                    $this->form_validation->set_rules([[
                        'field' => 'new_password',
                        'rules' => 'min_length[4]|max_length[12]',
                        'label' => 'password baru'
                    ], [
                        'field' => 'new_password_2',
                        'rules' => 'matches[new_password]',
                        'label' => 'konfirmasi password baru'
                    ]]);
                    if($this->form_validation->run() !== FALSE) {
                        $post_new_password = filter($this->input->post('new_password', TRUE));
                        $post_password = filter($this->input->post('password', TRUE));

                        if(!password_verify($post_password, $this->user->password)) {
                            setAlertResult('danger', 'Ups, password yang anda masukkan salah.');

                        } else {
                            $hash_new_password = password_hash($post_new_password, PASSWORD_BCRYPT);
                            $update = $this->Main_model->update('akun', ['password' => $hash_new_password], ['username' => $this->user->username]);
                            if($update !== FALSE)
                                setAlertResult('success', 'Perubahan berhasil disimpan.');
                            else
                                setAlertResult('danger', 'Terjadi kesalahan, sistem error.');
                        }
 
                    } else {
                        $this->render_view('karyawan/pengaturan-akun', [
                            'pageTitle' => 'Pengaturan Akun'
                        ]); 
                    }
                    break;

                default:
                    setAlertResult('danger', 'Terjadi kesalahan, aksi tidak dapat dikenali.');
            }

        } else {
            $this->render_view('karyawan/pengaturan-akun', [
                'pageTitle' => 'Pengaturan Akun'
            ]);
        }
    }

    function index()
    {
        $data_statistik_today = $this->Main_model->get_row('transaksi', "COUNT(id) AS jumlah, SUM(JSON_UNQUOTE(JSON_EXTRACT(total, '$.harga'))) AS total", ['DATE(tanggal) = ' => get_time('date')]);
        $data_statistik = $this->Main_model->get_row('transaksi', "COUNT(id) AS jumlah, SUM(JSON_UNQUOTE(JSON_EXTRACT(total, '$.harga'))) AS total");

        $this->render_view('karyawan/dashboard', [
            'pageTitle' => 'Dashboard',
            'jsFiles' => callJsFiles([
                'pages' => ['karyawan/dashboard'],
                'vendor' => ['datatables/jquery.dataTables.min', 'datatables/dataTables.bootstrap4.min']
            ], TRUE),
            'cssFiles' => callCssFiles([
                'pages' => ['dashboard-karyawan'],
                'vendor' => ['datatables/dataTables.bootstrap4.min']
            ], TRUE),
            'data_statistik_today' => $data_statistik_today,
            'data_statistik' => $data_statistik
        ]);
    }

    function login()
    {
        if($this->user !== FALSE)
            redirect();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post_username = filter($this->input->post('username', TRUE));
            $post_password = filter($this->input->post('password', TRUE));

            $data_user = $this->Main_model->get_row('akun', "password, JSON_UNQUOTE(JSON_EXTRACT(data, '$.nama_lengkap')) AS nama_lengkap", ['username' => $post_username]);
            if(!$data_user) {
                setAlertResult('danger', 'Ups, username yang anda masukkan tidak terdaftar.');
            } else if(!password_verify($post_password, $data_user->password)) {
                setAlertResult('danger', 'Ups, password yang anda masukkan salah.');

            } else {
                $login_token = hash('sha512', random_string('alnum', 25));
                $hash_login_token = hash('gost', $login_token);

                $update = $this->Main_model->update('akun', ['login_token' => $hash_login_token], ['username' => $post_username]);
                if($update !== FALSE) {
                    setcookie('login_token', $login_token, time()+60*60*24*30*6, '/');
                    setAlertResult('success', "Proses login berhasil, selamat datang kembali {$data_user->nama_lengkap}.", '');

                } else {
                    setAlertResult('danger', 'Terjadi kesalahan, error sistem.');
                }
            }

        } else {
            $this->load->view('login', [
                'webConfig' => $this->webConfig
            ]);
        }
    }

    function logout()
    {
        setcookie('login_token', NULL, time()-60*60*24*30*6, '/');
        setAlertResult('danger', 'Ups, autentikasi dibutuhkan. Silahkan login terlebih dahulu.', 'auth/login');
    }
}