<?php
class Produk extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        if(!$this->user)
            setAlertResult('danger', 'Ups, autentikasi dibutuhkan. Silahkan login terlebih dahulu.', 'auth/login');
        elseif($this->user->data->level != 'Admin')
            redirect('error/403');
    }

    function tambah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post_nama = filter($this->input->post('nama', TRUE));
            $post_harga_beli = filter($this->input->post('harga_beli', TRUE));
            $post_harga_jual = filter($this->input->post('harga_jual', TRUE));
            $post_stok = filter($this->input->post('stok', TRUE));

            $foto_produk = '';
            if(!empty($_FILES['foto-produk'])) {
                $this->load->library('upload', [
                    'upload_path' => './assets/img/produk/',
                    'allowed_types' => 'jpg|png|svg',
                    'file_name' => $this->Main_model->next_ai('produk')
                ]);
                if($this->upload->do_upload('foto-produk')) {
                    $foto_produk = $this->upload->data('file_name');
                }
            }

            $insert = $this->Main_model->insert('produk', [
                'nama' => $post_nama,
                'gambar' => $foto_produk,
                'harga' => json_encode([
                    'beli' => $post_harga_beli,
                    'jual' => $post_harga_jual
                ]),
                'stok' => $post_stok
            ]);
            if($insert !== FALSE) {
                setAlertResult('success', 'Produk baru berhasil ditambahkan.');
            } else {
                setAlertResult('danger', 'Terjadi kesalahan, sistem error.');
            }

        } else {
            $this->render_view('admin/produk/tambah', [
                'pageTitle' => 'Tambah Produk'
            ]);
        }
    }

    function edit($id = '')
    {
        $data_produk = $this->Main_model->get_row('produk', '*', ['id' => $id]);
        if(empty($id) || !$data_produk)
            setAlertResult('danger', "Ups, data produk dengan ID {$id} tidak dapat ditemukan.", 'admin/produk/');

        $data_produk->harga = json_decode($data_produk->harga);

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post_nama = filter($this->input->post('nama', TRUE));
            $post_harga_beli = filter($this->input->post('harga_beli', TRUE));
            $post_harga_jual = filter($this->input->post('harga_jual', TRUE));
            $post_stok = filter($this->input->post('stok', TRUE));

            if(!empty($_FILES['foto-produk']['name'])) {
                if(!empty($data_produk->gambar))
                    unlink("assets/img/produk/{$data_produk->gambar}");
                    
                $this->load->library('upload', [
                    'upload_path' => './assets/img/produk/',
                    'allowed_types' => 'jpg|png|svg',
                    'file_name' => $id
                ]);
                if($this->upload->do_upload('foto-produk')) {
                    $data_produk->gambar = $this->upload->data('file_name'); 
                }
            }

            $update = $this->Main_model->update('produk', [
                'nama' => $post_nama,
                'gambar' => $data_produk->gambar,
                'harga' => json_encode([
                    'beli' => $post_harga_beli,
                    'jual' => $post_harga_jual
                ]),
                'stok' => $post_stok
            ], ['id' => $id]);
            if($update !== FALSE) {
                setAlertResult('success', 'Perubahan berhasil disimpan');
            } else {
                setAlertResult('danger', 'Terjadi kesalahan, sistem error.');
            }

        } else {
            $this->render_view('admin/produk/edit', [
                'pageTitle' => 'Edit Produk',
                'data_produk' => $data_produk
            ]);
        }
    }

    function hapus()
    {
        $post_id = filter($this->input->post('id', TRUE));
        $data_produk = $this->Main_model->get_row('produk', 'gambar', ['id' => $post_id]);
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('error/403');
        } else if(!$data_produk) {
            $output = ['status' => 0, 'message' => "Terjadi kesalahan, data produk dengan ID {$post_id} tidak dapat ditemukan."];

        } else {
            $hapus = $this->Main_model->delete('produk', ['id' => $post_id]);
            if($hapus !== FALSE) {
                if(!empty($data_produk->gambar))
                    unlink("assets/img/produk/{$data_produk->gambar}");
                $output = ['status' => 1, 'message' => 'Data produk berhasil dihapus.'];

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
            $columns = [
                [
                    'column' => 'gambar',
                    'formatted' => function($param) {
                        $image_url = (!empty($param))   
                            ? base_url("assets/img/produk/{$param}")
                            : base_url("assets/img/{$this->webConfig->icon}");
                        return "<img src='{$image_url}' style='height: 3rem;'>";
                    } 
                ],
                ['column' => 'nama', 'search' => TRUE],
                [
                    'select' => "CAST(JSON_UNQUOTE(JSON_EXTRACT(harga, '$.beli')) AS UNSIGNED) AS harga_beli",
                    'column' => 'harga_beli',
                    'formatted' => function($param) {
                        return 'Rp ' . formatted('currency', $param);
                    }
                ],
                [
                    'select' => "CAST(JSON_UNQUOTE(JSON_EXTRACT(harga, '$.jual')) AS UNSIGNED) AS harga_jual",
                    'column' => 'harga_jual',
                    'formatted' => function($param) {
                        return 'Rp ' . formatted('currency', $param);
                    }
                ],
                [
                    'column' => 'stok',
                    'formatted' => function($param) {
                        return ($param >= 1)
                            ? formatted('currency', $param)
                            : "<button class='btn btn-danger btn-sm' style='cursor: not-allowed;' data-toggle='tooltip' title='Stok Habis'><i class='fas fa-ban'></i></button>";
                    }
                ],
                [
                    'column' => 'id',
                    'formatted' => function($param) {
                        $edit_url = base_url("admin/produk/edit/{$param}");
                    
                        return "
                            <a href='{$edit_url}' class='btn btn-warning btn-sm' data-toggle='tooltip' title='Edit Data'><i class='fas fa-edit'></i></a>
                            <a href='javascript:hapus(`{$param}`);' class='btn btn-danger btn-sm mt-2 mt-lg-0' data-toggle='tooltip' title='Hapus Data'><i class='fas fa-trash'></i></a>
                        ";
                    }
                ]
            ];
            
            $this->output
                ->set_content_type('application/json')
                ->set_output( $this->datatables_ssp->exec('produk', $columns) );

        } else {
            $this->render_view('admin/produk/produk', [
                'pageTitle' => 'Kelola Produk',
                'jsFiles' => callJsFiles([
                    'vendor' => ['datatables/jquery.dataTables.min', 'datatables/dataTables.bootstrap4.min'],
                    'pages' => ['admin/produk']
                ], TRUE),
                'cssFiles' => callCssFiles([
                    'vendor' => ['datatables/dataTables.bootstrap4.min']
                ], TRUE)
            ]);
        }
    }
}