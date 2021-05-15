<?php
class Konfigurasi extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        if(!$this->user)
            setAlertResult('danger', 'Ups, autentikasi dibutuhkan. Silahkan login terlebih dahulu.', 'auth/login');
        elseif($this->user->data->level != 'Admin')
            redirect('error/403');
    }

    function lainnya()
    {
        foreach($_POST as $key => $value) {
            $value = filter($value);
            $this->Main_model->update('konfigurasi', ['value' => $value], ['kode' => $key]);
        }

        setAlertResult('success', 'Perubahan berhasil disimpan.', 'admin/konfigurasi');
    }

    function logo()
    {
        if($_SERVER['REQUEST_METHOD'] != 'POST')
            redirect('error/403');

        foreach($_FILES as $key => $value) {
            if(!empty($value['name'])) {
                $this->load->library('upload', [
                    'file_name' => $key,
                    'upload_path' => './assets/img/',
                    'allowed_types' => 'jpg|png|ico|svg'
                ]);
                if($this->upload->do_upload($key) !== FALSE) {
                    $file_name = $this->upload->data('file_name');
                    $this->Main_model->update('konfigurasi', ['value' => $file_name], ['kode' => $key]);
                }
            }
        }

        setAlertResult('success', 'Perubahan berhasil disimpan.', 'admin/konfigurasi');
    }

    function index()
    {
        $this->render_view('admin/konfigurasi', [
            'pageTitle' => 'Konfigurasi Website'
        ]);
    }
}