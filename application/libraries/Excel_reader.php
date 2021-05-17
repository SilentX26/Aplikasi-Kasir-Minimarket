<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Excel_Reader
{
    function read($file)
    {
        return \PhpOffice\PhpSpreadsheet\IOFactory::load($file)
            ->getActiveSheet()->toArray();
    }

    function write($file_name, $data, $custom_width = NULL)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\SpreadSheet;
        if($custom_width !== NULL) {
            foreach($custom_width as $key => $value)
                $spreadsheet->getActiveSheet()->getColumnDimension($key)->setWidth($value);
        }

        $spreadsheet->getActiveSheet()->fromArray($data);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        return $writer->save($file_name);
    }
}