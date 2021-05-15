<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    function dashboard_chart()
    {
        $date_start = date('Y-m-d', strtotime('-6 days'));
        $data = $this->db->select("COUNT(id) AS transaksi, SUM(JSON_UNQUOTE(JSON_EXTRACT(total, '$.laba'))) AS laba, DATE(tanggal) AS date")
            ->from('transaksi')
            ->where("DATE(tanggal) >= '{$date_start}'")
            ->get()->result_array();

        $result = [];
        for($i=6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $search = array_search($date, array_column($data, 'date'));

            $result[$date] = ($search !== FALSE)
                ? $data[$search]
                : ['transaksi' => 0, 'laba' => 0];
        }

        return $result;
    }

    function dashboard_statistik()
    {
        $date_today = get_time('date');
        $statistik_today = $this->db->select("COUNT(id) AS transaksi_jumlah, SUM(JSON_UNQUOTE(JSON_EXTRACT(total, '$.harga'))) AS transaksi_total, SUM(JSON_UNQUOTE(JSON_EXTRACT(total, '$.laba'))) AS laba")
            ->from('transaksi')
            ->where("DATE(tanggal) = '{$date_today}'")
            ->get()->row();
        $statistik_total = $this->db->select("COUNT(id) AS transaksi_jumlah, SUM(JSON_UNQUOTE(JSON_EXTRACT(total, '$.harga'))) AS transaksi_total, SUM(JSON_UNQUOTE(JSON_EXTRACT(total, '$.laba'))) AS laba")
            ->from('transaksi')
            ->get()->row();

        return (object) [
            'today' => $statistik_today,
            'total' => $statistik_total
        ];
    }
}