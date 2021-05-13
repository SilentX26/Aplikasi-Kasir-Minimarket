<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends CI_Model
{
    function set_stok($id, $action, $value)
    {
        return $this->db->set('stok', "stok {$action} {$value}", FALSE)
            ->update('produk');
    }
}