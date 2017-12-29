<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 16-9-12
 * Time: 下午6:43
 */

require 'graph.php';
require 'excelProcess.php';
require 'config.php';

$xlsData = getDataFromXls(XLS_NAME);

$rowData = array_search(6375,array_column($xlsData,'code'));

var_dump($rowData);