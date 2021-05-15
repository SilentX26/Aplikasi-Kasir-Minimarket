<?php
class Err extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index($code = '404')
    {
        if(!in_array($code, ['404', '403', 'mt']))
            $code = '404';
        if(in_array($code, ['404', '403']))
            header("HTTP/1.1 {$code}");

        $title = (in_array($code, ['404', '403', 'mt']))
            ? strtr($code, ['404' => 'Halaman Tidak Ditemukan', '403' => 'Akses Diblokir', 'mt' => 'Sedang Maintenance'])
            : 'Halaman Tidak Ditemukan';
        switch($code) {
            case '403':
                $message = 'Ups, aksi anda diblokir. Sistem mendeteksi adanya upaya yang tidak wajar.';
                break;
            case 'mt':
                $message = 'Saat ini website sedang dalam tahap pemeliharaan, silahkan coba dalam beberapa saat lagi.';
                break;

            default:
                $message = 'Ups, halaman yang anda cari tidak dapat ditemukan. Pastikan tidak ada typo dalam penulisan url nya.';
        }

        $this->render_view('error', [
            'pageTitle' => $title,
            'code' => $code,
            'message' => $message
        ]);
    }
}