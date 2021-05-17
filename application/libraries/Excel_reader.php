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
        $spreadsheet->getProperties()
            ->setTitle($file_name)
            ->setSubject($file_name);

        $spreadsheet->getDefaultStyle()->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        if($custom_width !== NULL) {
            foreach($custom_width as $key => $value)
                $spreadsheet->getActiveSheet()->getColumnDimension($key)->setWidth($value);
        }
        
        $spreadsheet->getActiveSheet()->fromArray($data);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save("assets/tmp/{$file_name}.xlsx");

        $output = file_get_contents("assets/tmp/{$file_name}.xlsx");
        unlink("assets/tmp/{$file_name}.xlsx");
        return $output;
    }
}