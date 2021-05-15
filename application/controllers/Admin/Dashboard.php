<?php
class Dashboard extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        if(!$this->user)
            setAlertResult('danger', 'Ups, autentikasi dibutuhkan. Silahkan login terlebih dahulu.', 'auth/login');
        elseif($this->user->data->level != 'Admin')
            redirect('error/403');
    }

    function index()
    {
        $this->load->model('Admin_model');
        $this->render_view('admin/dashboard', [
            'pageTitle' => 'Admin Panel',
            'jsFiles' => callJsFiles([
                'pages' => ['admin/dashboard'],
                'vendor' => ['chart.js/Chart.min']
            ], TRUE),
            'data_statistik' => $this->Admin_model->dashboard_statistik(),
            'data_chart' => $this->Admin_model->dashboard_chart()
        ]);
    }
}