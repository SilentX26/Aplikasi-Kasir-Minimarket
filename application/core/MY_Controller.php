<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public $user;
	public $webConfig;

	function __construct()
	{
		parent::__construct();

        $this->user = logged_in();
        $this->webConfig = $this->Main_model->get_rows('konfigurasi');
        $this->webConfig = array_combine( array_column($this->webConfig, 'kode'), array_column($this->webConfig, 'value') );
        $this->webConfig = (object) $this->webConfig;
    }

    function render_view($content, $data = [])
    {
        if($this->user !== FALSE)
            $data['user'] = $this->user;

        $data['webConfig'] = $this->webConfig;
        $data['content'] = $this->load->view($content, $data, TRUE);
        $this->load->view('core', $data);
    }
}