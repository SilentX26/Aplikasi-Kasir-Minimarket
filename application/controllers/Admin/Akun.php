<?php
class Akun extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        if(!$this->user)
            setAlertResult('danger', 'Ups, autentikasi dibutuhkan. Silahkan login terlebih dahulu.', 'auth/login');
        elseif($this->user->data->level != 'Admin')
            redirect('error/403');
    }

    function export()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post_jumlah = filter($this->input->post('jumlah', TRUE));
            $post_order_by = filter($this->input->post('order_by', TRUE));

            if($post_jumlah <= 0) {
                setAlertResult('danger', 'Ups, jumlah data minimal ialah 1 data');
            } else if($post_jumlah > 100) {
                setAlertResult('danger', 'Ups, jumlah data maksimal ialah 100 data');

            } else {
                $data_akun = $this->Main_model->get_rows('akun', [
                    'select' => "username, password, JSON_UNQUOTE(JSON_EXTRACT(data, '$.nama_lengkap')) AS nama_lengkap, JSON_UNQUOTE(JSON_EXTRACT(data, '$.level')) AS level",
                    'order_by' => ['id', $post_order_by],
                    'limit' => [$post_jumlah]
                ]);
                $data_akun = array_map('array_values', $data_akun);
                array_unshift($data_akun, ['username', 'password', 'nama_lengkap', 'level']);

                $this->load->library('Excel_Reader');
                $export = $this->excel_reader->write('Data Akun', $data_akun, [
                    'A' => 15,
                    'B' => 45,
                    'C' => 40,
                    'D' => 15
                ]);

                header("Content-Disposition: attachment; filename=\"Data Akun.xlsx\"");
                echo $export;
            }

        } else {
            $this->render_view('admin/akun/export', [
                'pageTitle' => 'Export Data Akun'
            ]);
        }
    }

    function import()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!empty($_FILES['file']['name'])) {
                $this->load->library('upload', [
                    'file_name' => 'IMPORT-DATA-AKUN',
                    'allowed_types' => 'xls|xlsx',
                    'upload_path' => './assets/tmp/'
                ]);
                if($this->upload->do_upload('file') !== FALSE) {
                    $file_name = $this->upload->data('file_name');
                    $this->load->library('Excel_reader');

                    $excel_data = $this->excel_reader->read("assets/tmp/{$file_name}");
                    unset($excel_data[0]);
                    unlink("assets/tmp/{$file_name}");
                    
                    $data_insert = [];
                    foreach($excel_data as $key => $value) {
                        $cek_user = $this->Main_model->get_row('akun', 'id', ['username' => $value[0]]);
                        if(!$cek_user)
                            array_push($data_insert, [
                                'username' => $value[0],
                                'password' => password_hash($value[1], PASSWORD_BCRYPT),
                                'data' => json_encode([
                                    'nama_lengkap' => $value[2],
                                    'foto_profile' => '',
                                    'level' => $value[3]
                                ]),
                                'login_token' => ''
                            ]);
                    }

                    $insert = $this->Main_model->insert_batch('akun', $data_insert);
                    if($insert !== FALSE) {
                        $rows_insert = formatted('currency', $insert);
                        setAlertResult('success', "{$rows_insert} akun berhasil ditambahkan.");

                    } else {
                        setAlertResult('danger', 'Terjadi kesalahan, sistem error.');
                    }

                } else {
                    setAlertResult('danger', 'Ups, proses upload file gagal.');
                }

            } else {
                setAlertResult('danger', 'Ups, anda tidak mengirimkan file apapun untuk diimport.');
            }

        } else {
            $this->render_view('admin/akun/import', [
                'pageTitle' => 'Import Data Akun'
            ]);
        }
    }

    function tambah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->form_validation->set_rules([[
                'field' => 'password',
                'rules' => 'min_length[4]|max_length[12]',
            ], [
                'field' => 'confirm_password',
                'rules' => 'matches[password]',
                'label' => 'konfirmasi baru'
            ]]);
            if($this->form_validation->run() !== FALSE) {
                $post_username = filter($this->input->post('username', TRUE));
                $post_nama_lengkap = filter($this->input->post('nama_lengkap', TRUE));
                $post_password = filter($this->input->post('password', TRUE));
                $post_level = filter($this->input->post('level', TRUE));

                $cek_user = $this->Main_model->get_row('akun', 'id', ['username' => $post_username]);
                if($cek_user !== FALSE) {
                    setAlertResult('danger', 'Ups, username yang anda masukkan sudah digunakan.');
                    
                } else {
                    $image_user = '';
                    if(!empty($_FILES['foto-profile']['name'])) {
                        $this->load->library('upload', [
                            'upload_path' => './assets/img/akun/',
                            'allowed_types' => 'jpg|png|svg',
                            'file_name' => $this->user->username
                        ]);
                        if($this->upload->do_upload('foto-profile'))
                            $image_user = $this->upload->data('file_name');
                    }

                    $insert = $this->Main_model->insert('akun', [
                        'username' => $post_username,
                        'password' => password_hash($post_password, PASSWORD_BCRYPT),
                        'data' => json_encode([
                            'nama_lengkap' => $post_nama_lengkap,
                            'foto_profile' => $image_user,
                            'level' => $post_level
                        ]),
                        'login_token' => ''
                    ]);
                    if($insert !== FALSE) {
                        setAlertResult('success', 'Akun baru berhasil ditambahkan.');
                    } else {
                        setAlertResult('danger', 'Terjadi kesalahan, sistem error.');
                    } 
                }
            }

        } else {
            $this->render_view('admin/akun/tambah', [
                'pageTitle' => 'Tambah Akun'
            ]);
        }
    }

    function edit($id = '')
    {
        $data_akun = $this->Main_model->get_row('akun', 'username, data', ['id' => $id]);
        if(empty($id) || !$data_akun)
            setAlertResult('danger', "Ups, data akun dengan ID {$id} tidak dapat ditemukan.", 'admin/akun');

        $data_akun->data = json_decode($data_akun->data);

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post_action = $this->input->post('action', TRUE);
            switch($post_action) {
                case 'umum':
                    $post_nama_lengkap = filter($this->input->post('nama_lengkap', TRUE));
                    $post_level = filter($this->input->post('level', TRUE));

                    if(!empty($_FILES['foto-profile']['name'])) {
                        unlink("assets/img/akun/{$this->user->data->foto_profile}");
                        $this->load->library('upload', [
                            'upload_path' => './assets/img/akun/',
                            'allowed_types' => 'jpg|png|svg',
                            'file_name' => $this->user->username
                        ]);
                        if($this->upload->do_upload('foto-profile')) {
                            $this->user->data->foto_profile = $this->upload->data('file_name');
                        } else {
                            setAlertResult('danger', 'Ups, proses upload foto profile gagal.');
                        }
                    }

                    $update = $this->Main_model->json('akun', 'set', [
                        'data' => [
                            'nama_lengkap' => $post_nama_lengkap,
                            'foto_profile' => $this->user->data->foto_profile,
                            'level' => $post_level 
                        ]
                    ], ['username' => $this->user->username]);
                    if($update !== FALSE) {
                        setAlertResult('success', 'Perubahan berhasil disimpan.');
                    } else {
                        setAlertResult('danger', 'Terjadi kesalahan, sistem error.');
                    }
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
                        $hash_new_password = password_hash($post_new_password, PASSWORD_BCRYPT);

                        $update = $this->Main_model->update('akun', ['password' => $hash_new_password], ['username' => $this->user->username]);
                        if($update !== FALSE) {
                            setAlertResult('success', 'Perubahan berhasil disimpan.');
                        } else {
                            setAlertResult('danger', 'Terjadi kesalahan, sistem error.');
                        }
 
                    } else {
                        $this->render_view('admin/akun/edit', [
                            'pageTitle' => 'Edit Akun',
                            'data_akun' => $data_akun
                        ]); 
                    }
                    break;

                default:
                    setAlertResult('danger', 'Terjadi kesalahan, aksi tidak dapat dikenali.');
            }

        } else {
            $this->render_view('admin/akun/edit', [
                'pageTitle' => 'Edit Akun',
                'data_akun' => $data_akun
            ]);
        }
    }

    function hapus()
    {
        $post_id = filter($this->input->post('id', TRUE));
        $data_akun = $this->Main_model->get_row('akun', "JSON_UNQUOTE(JSON_EXTRACT(data, '$.foto_profile')) AS foto_profile", ['id' => $post_id]);
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('error/403');
        } else if(!$data_akun) {
            $output = ['status' => 0, 'message' => "Terjadi kesalahan, data akun dengan ID {$id} tidak dapat ditemukan."];

        } else {
            $hapus = $this->Main_model->delete('akun', ['id' => $post_id]);
            if($hapus !== FALSE) {
                if(!empty($data_akun->foto_profile))
                    unlink("assets/img/akun/{$data_akun->foto_profile}");
                $output = ['status' => 1, 'message' => 'Data akun berhasil dihapus.'];

            } else {
                $output = ['status' => 0, 'message' => 'Terjadi kesalahn, sistem error.'];
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    function index()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->load->library('Datatables_ssp');
            $where = [];

            if(!empty($_GET['level'])) {
                $level = filter($this->input->get('level', TRUE));
                array_push($where, "JSON_UNQUOTE(JSON_EXTRACT(data, '$.level')) = '{$level}'");
            }

            $columns = [
                [
                    'select' => "JSON_UNQUOTE(JSON_EXTRACT(data, '$.foto_profile')) AS foto_profile",
                    'column' => 'foto_profile',
                    'formatted' => function($param) {
                        $image_url = (!empty($param))    
                            ? base_url("assets/img/akun/{$param}")
                            : base_url("assets/img/{$this->webConfig->icon}");
                        return "<img src='{$image_url}' style='height: 3rem;'>";
                    } 
                ],
                ['column' => 'username', 'search' => TRUE],
                [
                    'select' => "JSON_UNQUOTE(JSON_EXTRACT(data, '$.nama_lengkap')) AS nama_lengkap",
                    'column' => 'nama_lengkap',
                    'search' => TRUE
                ],
                [
                    'select' => "JSON_UNQUOTE(JSON_EXTRACT(data, '$.level')) AS level",
                    'column' => 'level',
                    'search' => TRUE
                ],
                [
                    'column' => 'id',
                    'formatted' => function($param) {
                        $edit_url = base_url("admin/akun/edit/{$param}");
                    
                        return "
                            <a href='{$edit_url}' class='btn btn-warning btn-sm' data-toggle='tooltip' title='Edit Data'><i class='fas fa-edit'></i></a>
                            <a href='javascript:hapus(`{$param}`);' class='btn btn-danger btn-sm mt-2 mt-lg-0' data-toggle='tooltip' title='Hapus Data'><i class='fas fa-trash'></i></a>
                        ";
                    }
                ]
            ];
            
            $this->output
                ->set_content_type('application/json')
                ->set_output( $this->datatables_ssp->exec('akun', $columns, ['where' => $where]) );

        } else {
            $this->render_view('admin/akun/akun', [
                'pageTitle' => 'Kelola Akun',
                'jsFiles' => callJsFiles([
                    'vendor' => ['datatables/jquery.dataTables.min', 'datatables/dataTables.bootstrap4.min'],
                    'pages' => ['admin/akun']
                ], TRUE),
                'cssFiles' => callCssFiles([
                    'vendor' => ['datatables/dataTables.bootstrap4.min']
                ], TRUE)
            ]);
        }
    }
}