<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 16-9-12
 * Time: 下午5:11
 */

require 'vendor/autoload.php';

//实例化对象

function getDataFromXls($inputFileName)
{

    try {
        $type = PHPExcel_IOFactory::identify($inputFileName);
        $reader = PHPExcel_IOFactory::createReader($type);
        $worker = $reader->load($inputFileName);

    } catch (Exception $e) {
        die("Error :" . $e->getMessage() . "\n");
    }

//设置读取参数

    $sheet = $worker->getSheet(0);
    $rowCount = $sheet->getHighestRow();
    $data = $sheet->toArray();

    $result = array();

    for ($i = 1; $i < $rowCount; $i++) {
        $result[$i - 1] = [
            'name' => $data[$i][6],
            'stuNum' => $data[$i][9],
            'phone' => $data[$i][10],
            'code' => $data[$i][14],
            'oneSizeNum' => $data[$i][15],
            'twoSizeNum' => $data[$i][16]
        ];
    }

    return $result;
}

