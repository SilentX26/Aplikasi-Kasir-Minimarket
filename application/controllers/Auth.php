<?php
class Auth extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        if(!$this->user)
            setAlertResult('danger', 'Ups, autentikasi dibutuhkan. Silahkan login terlebih dahulu.', 'auth/login');

        $this->render_view('karyawan/dashboard', [
            'pageTitle' => 'Dashboard'
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