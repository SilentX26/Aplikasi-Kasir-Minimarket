<?php
class Transaksi extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        if(!$this->user)
            setAlertResult('danger', 'Ups, autentikasi dibutuhkan. Silahkan login terlebih dahulu.', 'auth/login');
    }

    function detail($id = '')
    {
        $data_transaksi = $this->Main_model->get_row('transaksi', '*', ['id' => $id]);
        if(empty($id) || !$data_transaksi)
            setAlertResult('danger', "Ups, data transaksi dengan ID {$id} tidak dapat ditemukan", 'transaksi/riwayat');

        $data_transaksi->data = json_decode($data_transaksi->data);
        $data_transaksi->total = json_decode($data_transaksi->total);

        $this->render_view('karyawan/transaksi/detail', [
            'pageTitle' => 'Detail Transaksi',
            'data_transaksi' => $data_transaksi
        ]);
    }

    function riwayat()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->load->library('Datatables_ssp');
            $columns = [
                ['column' => 'id', 'search' => TRUE],
                [
                    'column' => 'data',
                    'formatted' => function($param) {
                        $array_param = json_decode($param, TRUE);
                        return formatted('currency', count($array_param));
                    }
                ],
                [
                    'select' => "CAST(JSON_UNQUOTE(JSON_EXTRACT(total, '$.bayar')) AS UNSIGNED) AS total_bayar",
                    'column' => 'total_bayar',
                    'formatted' => function($param) {
                        return 'Rp ' . formatted('currency', $param);
                    }
                ],
                [
                    'column' => 'tanggal',
                    'formatted' => function($param) {
                        return formatted('datetime', $param);
                    }
                ],
                [
                    'column' => 'id',
                    'formatted' => function($param) {
                        $detail_url = base_url("transaksi/detail/{$param}");
                        return "<a href='{$detail_url}' class='btn btn-info btn-sm' data-toggle='tooltip' title='Lihat Detail'><i class='fas fa-info-circle'></i></a>";
                    }
                ]
            ];
            
            $this->output
                ->set_content_type('application/json')
                ->set_output( $this->datatables_ssp->exec('transaksi', $columns) );

        } else {
            $this->render_view('karyawan/transaksi/riwayat', [
                'pageTitle' => 'Riwayat Transaksi',
                'jsFiles' => callJsFiles([
                    'vendor' => ['datatables/jquery.dataTables.min', 'datatables/dataTables.bootstrap4.min'],
                    'pages' => ['karyawan/transaksi/riwayat']
                ], TRUE),
                'cssFiles' => callCssFiles([
                    'vendor' => ['datatables/dataTables.bootstrap4.min']
                ], TRUE)
            ]);
        }
    }

    function ajax()
    {
        if($_SERVER['REQUEST_METHOD'] != 'POST')
            redirect('error/403');
        
        $post_action = $this->input->post('action', TRUE);
        switch($post_action) {
            case 'reset-keranjang':
                $this->load->model('Produk_model');
                foreach($_SESSION['cart']['items'] as $key => $value)
                    $this->Produk_model->set_stok($key, '+', $value['jumlah']);

                unset($_SESSION['cart']);
                $output = ['status' => 1, 'message' => 'Keranjang belanja berhasil di reset.'];

                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($output));
                break;

            case 'edit-jumlah':
                $post_id = filter($this->input->post('id', TRUE));
                $data_produk = $this->Main_model->get_row('produk', 'stok, harga', ['id' => $post_id]);
                $post_operasi = filter($this->input->post('operasi', TRUE));

                if($post_operasi == 'tambah' && $data_produk->stok == 0) {
                    $output = ['status' => 0, 'message' => 'Ups, stok untuk produk ini kosong.'];

                } else {
                    $data_produk->harga = json_decode($data_produk->harga);
                    $stok_sekarang = ($post_operasi == 'tambah') ? $data_produk->stok - 1 : $data_produk->stok + 1;

                    $update = $this->Main_model->update('produk', ['stok' => $stok_sekarang], ['id' => $post_id]);
                    if($update !== FALSE) {
                        $harga_jual_per_item = $data_produk->harga->jual;
                        $total_laba = $data_produk->harga->jual - $data_produk->harga->beli;
                        
                        if($post_operasi == 'kurang' && $_SESSION['cart']['items'][$post_id]['jumlah'] <= 1) {
                            unset($_SESSION['cart']['items'][$post_id]);

                        } else if($post_operasi == 'tambah') {
                            $_SESSION['cart']['items'][$post_id]['jumlah'] += 1;
                            $_SESSION['cart']['items'][$post_id]['harga_jual'] += $harga_jual_per_item;
                            $_SESSION['cart']['total_laba'] += $total_laba;
                            
                        } else {
                            $_SESSION['cart']['items'][$post_id]['jumlah'] -= 1;
                            $_SESSION['cart']['items'][$post_id]['harga_jual'] -= $harga_jual_per_item;
                            $_SESSION['cart']['total_laba'] -= $total_laba;
                        }

                        $output = ['status' => 1, 'message' => 'Jumlah pesanan berhasil dirubah.'];

                    } else {
                        $output = ['status' => 0, 'message' => 'Terjadi kesalahan, sistem error.'];
                    }
                }

                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($output));
                break;

            case 'tambah-produk':
                $post_id = filter($this->input->post('id_produk', TRUE));
                $post_jumlah = filter($this->input->post('jumlah', TRUE));
                $data_produk = $this->Main_model->get_row('produk', 'nama, gambar, harga, stok', ['id' => $post_id]);

                if(!$data_produk) {
                    $output = ['status' => 0, 'message' => 'Terjadi kesalahan, data produk tidak dapat ditemukan.'];
                } else if($data_produk->stok <= 0) {
                    $output = ['status' => 0, 'message' => 'Ups, stok produk ini sudah habis.'];

                } else {
                    $data_produk->harga = json_decode($data_produk->harga);
                    $stok_sekarang = $data_produk->stok - $post_jumlah;
                    $stok_sekarang = ($stok_sekarang >= 1) ? $stok_sekarang : 0;
                    $post_jumlah = ($stok_sekarang >= 1) ? $post_jumlah : $data_produk->stok;

                    $update = $this->Main_model->update('produk', ['stok' => $stok_sekarang], ['id' => $post_id]);
                    if($update !== FALSE) {
                        $harga_jual = $data_produk->harga->jual * $post_jumlah;
                        $total_laba = $harga_jual - ($data_produk->harga->beli * $post_jumlah);

                        if(isset($_SESSION['cart'][$post_id])) {
                            $_SESSION['cart']['total_laba'] =+ $total_laba;
                            $_SESSION['cart']['items'][$post_id]['harga_jual'] =+ $harga_jual;
                            $_SESSION['cart']['items'][$post_id]['jumlah'] =+ $post_jumlah;

                        } else {
                            $_SESSION['cart']['total_laba'] = $total_laba;
                            $_SESSION['cart']['items'][$post_id] = [ 
                                'nama' => $data_produk->nama,
                                'gambar' => (!empty($data_produk->gambar)) ? base_url("assets/img/produk/{$data_produk->gambar}") : base_url("assets/img/{$this->webConfig->icon}"),
                                'harga_jual' => $harga_jual,
                                'jumlah' => $post_jumlah
                            ];
                        }
                        $output = ['status' => 1, 'message' => 'Produk berhasil ditambahkan ke keranjang belanja.'];

                    } else {
                        $output = ['status' => 0, 'message' => 'Terjadi kesalahan, sistem error.'];
                    }
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
                                ? "<button onclick='tambah_produk(`{$data['id']}`, `{$data['nama']}`)' class='btn btn-success btn-sm' data-toggle='tooltip' title='Tambah ke Keranjang'><i class='fas fa-plus'></i></button>"
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