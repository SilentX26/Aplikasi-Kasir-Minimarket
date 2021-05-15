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