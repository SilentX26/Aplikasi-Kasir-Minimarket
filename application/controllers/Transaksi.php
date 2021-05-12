<?php
class Transaksi extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        if(!$this->user)
            setAlertResult('danger', 'Ups, autentikasi dibutuhkan. Silahkan login terlebih dahulu.', 'auth/login');
    }

    function ajax()
    {
        if($_SERVER['REQUEST_METHOD'] != 'POST')
            redirect('error/403');
        
        $post_action = $this->input->post('action', TRUE);
        switch($post_action) {
            case 'tambah-produk':
                $post_id = filter($this->input->post('id', TRUE));
                $data_produk = $this->Main_model->get_row('produk', "nama, gambar, JSON_UNQUOTE(JSON_EXTRACT(harga, '$.jual')) AS harga_jual", ['id' => $post_id]);

                if($data_produk !== FALSE) {
                    if(isset($_SESSION['cart'][$post_id])) {
                        $_SESSION['cart'][$post_id]['harga_jual'] =+ $data_produk->harga_jual;
                        $_SESSION['cart'][$post_id]['jumlah'] =+ 1;

                    } else {
                        $_SESSION['cart'][$post_id] = [
                            'nama' => $data_produk->nama,
                            'gambar' => (!empty($data_produk->gambar)) ? base_url("assets/img/produk/{$data_produk->gambar}") : base_url("assets/img/{$this->webConfig->icon}"),
                            'harga_jual' => $data_produk->harga_jual,
                            'jumlah' => 1
                        ];
                    }
                    $output = ['status' => 1, 'message' => 'Produk berhasil ditambahkan ke keranjang belanja.'];

                } else {
                    $output = ['status' => 0, 'message' => 'Terjadi kesalahan, data produk tidak dapat ditemukan.'];
                }

                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($output));
                break;
            case 'refresh-keranjang':
                $output = $this->load->view('karyawan/transaksi/refresh-keranjang', NULL, TRUE);
                $this->output
                    ->set_content_type('text/html')
                    ->set_output($output);
                break;
            case 'render-produk':
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
                        'select' => "CAST(JSON_UNQUOTE(JSON_EXTRACT(harga, '$.jual')) AS UNSIGNED) AS harga_jual",
                        'column' => 'harga_jual',
                        'formatted' => function($param) {
                            return 'Rp ' . formatted('currency', $param);
                        }
                    ],
                    [
                        'select' => 'stok, id',
                        'column' => 'stok',
                        'formatted' => function($param, $data) {
                            return ($param >= 1)
                                ? "<button onclick='tambah_produk(`{$data['id']}`)' class='btn btn-success btn-sm' data-toggle='tooltip' title='Tambah ke Keranjang'><i class='fas fa-plus'></i></button>"
                                : "<button class='btn btn-danger btn-sm' style='cursor: not-allowed;' data-toggle='tooltip' title='Stok Habis'><i class='fas fa-ban'></i></button>";
                        }
                    ]
                ];
            
                $this->output
                    ->set_content_type('application/json')
                    ->set_output( $this->datatables_ssp->exec('produk', $columns) );
                break;

            default:
                setAlertResult('danger', 'Terjadi kesalahan, aksi tidak dapat dikenali.', '');
        }
    }
}