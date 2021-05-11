<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Excel_Reader
{
    function read($file)
    {
        $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
        return $reader->load($file)->getActiveSheet()->toArray();
    }
}