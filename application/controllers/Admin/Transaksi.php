<?php
class Transaksi extends MY_Controller
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
                $data_transaksi = $this->Main_model->get_rows('transaksi', [
                    'order_by' => ['id', $post_order_by],
                    'limit' => [$post_jumlah]
                ]);
                foreach($data_transaksi as $key => $value) {
                    $value['data'] = json_decode($value['data'], TRUE);
                    $value['total'] = json_decode($value['total'], TRUE);

                    $data_transaksi[$key] = [
                        0 => $value['id'],
                        1 => $value['username'],
                        2 => '',
                        3 => 'Rp ' . formatted('currency', $value['total']['harga']),
                        4 => 'Rp ' . formatted('currency', $value['total']['pajak']),
                        5 => 'Rp ' . formatted('currency', $value['total']['bayar']),
                        6 => 'Rp ' . formatted('currency', $value['total']['laba']),
                        7 => formatted('datetime', $value['tanggal'])
                    ];

                    foreach($value['data'] as $key_data => $value_data) {
                        $jumlah = formatted('currency', $value_data['jumlah']);
                        $data_transaksi[$key][2] .= "{$value_data['nama']}: {$jumlah}\n";
                    }
                }

                array_unshift($data_transaksi, ['id', 'username', 'data', 'total_harga', 'total_pajak', 'total_bayar', 'total_laba', 'tanggal']);
                $this->load->library('Excel_Reader');
                $export = $this->excel_reader->write('Data Transaksi', $data_transaksi, [
                    'B' => 15,
                    'C' => 50,
                    'D' => 20,
                    'E' => 20,
                    'F' => 20,
                    'G' => 20,
                    'H' => 25
                ]);

                header("Content-Disposition: attachment; filename=\"Data Transaksi.xlsx\"");
                echo $export;
            }

        } else {
            $this->render_view('admin/transaksi/export', [
                'pageTitle' => 'Export Data Transaksi'
            ]);
        }
    }

    function hapus()
    {
        if($_SERVER['REQUEST_METHOD'] != 'POST')
            redirect('error/403');

        $post_id = filter($this->input->post('id', TRUE));
        $hapus = $this->Main_model->delete('transaksi', ['id' => $post_id]);
        if($hapus !== FALSE) {
            $output = ['status' => 1, 'message' => 'Data transaksi berhasil dihapus.'];

        } else {
            $output = ['status' => 0, 'message' => 'Terjadi kesalahn, sistem error.'];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    function detail($id = '')
    {
        $data_transaksi = $this->Main_model->get_row('transaksi', '*', ['id' => $id]);
        if(empty($id) || !$data_transaksi)
            setAlertResult('danger', "Ups, data transaksi dengan ID {$id} tidak dapat ditemukan", 'transaksi/riwayat');

        $data_transaksi->data = json_decode($data_transaksi->data);
        $data_transaksi->total = json_decode($data_transaksi->total);

        $this->render_view('admin/transaksi/detail', [
            'pageTitle' => 'Detail Transaksi',
            'data_transaksi' => $data_transaksi
        ]);
    }

    function index()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->load->library('Datatables_ssp');
            $where = [];

            if(!empty($_GET['tgl_mulai'])) {
                $tgl = filter($this->input->get('tgl_mulai', TRUE));
                array_push($where, "DATE(tanggal) >= '{$tgl}'");
            }

            if(!empty($_GET['tgl_akhir'])) {
                $tgl = filter($this->input->get('tgl_akhir', TRUE));
                array_push($where, "DATE(tanggal) <= '{$tgl}'");
            }

            $columns = [
                [
                    'select' => 'transaksi.id',
                    'column' => 'id',
                    'search' => TRUE
                ],
                [
                    'select' => "JSON_UNQUOTE(JSON_EXTRACT(akun.data, '$.nama_lengkap')) AS nama_lengkap",
                    'column' => 'nama_lengkap',
                    'search' => TRUE
                ],
                [
                    'select' => 'transaksi.data',
                    'column' => 'data',
                    'formatted' => function($param) {
                        $array_param = json_decode($param, TRUE);
                        return formatted('currency', count($array_param));
                    }
                ],
                [
                    'select' => "CAST(JSON_UNQUOTE(JSON_EXTRACT(transaksi.total, '$.bayar')) AS UNSIGNED) AS total_bayar",
                    'column' => 'total_bayar',
                    'formatted' => function($param) {
                        return 'Rp ' . formatted('currency', $param);
                    }
                ],
                [
                    'select' => 'transaksi.tanggal',
                    'column' => 'tanggal',
                    'formatted' => function($param) {
                        return formatted('datetime', $param);
                    }
                ],
                [
                    'select' => 'transaksi.id',
                    'column' => 'id',
                    'formatted' => function($param) {
                        $detail_url = base_url("admin/transaksi/detail/{$param}");
                    
                        return "
                            <a href='{$detail_url}' class='btn btn-info btn-sm' data-toggle='tooltip' title='Lihat Detail'><i class='fas fa-info-circle'></i></a>
                            <a href='javascript:hapus(`{$param}`);' class='btn btn-danger btn-sm mt-2 mt-lg-0' data-toggle='tooltip' title='Hapus Data'><i class='fas fa-trash'></i></a>
                        ";
                    }
                ]
            ];
            
            $this->output
                ->set_content_type('application/json')
                ->set_output( $this->datatables_ssp->exec('transaksi', $columns, [
                    'join' => ['table' => 'akun', 'on' => 'akun.username = transaksi.username', 'param' => 'LEFT'],
                    'where' => $where
                ]) );


        } else {
            $this->render_view('admin/transaksi/transaksi.php', [
                'pageTitle' => 'Kelola Transaksi',
                'jsFiles' => callJsFiles([
                    'vendor' => ['datatables/jquery.dataTables.min', 'datatables/dataTables.bootstrap4.min'],
                    'pages' => ['admin/transaksi']
                ], TRUE),
                'cssFiles' => callCssFiles([
                    'vendor' => ['datatables/dataTables.bootstrap4.min']
                ], TRUE)
            ]);
        }
    }
}