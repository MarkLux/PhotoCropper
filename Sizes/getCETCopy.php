<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 16-9-12
 * Time: 下午6:31
 */
require 'process.php';
require 'config.php';

function getFileType($filename) {
    return substr($filename, strrpos($filename, '.') + 1);
}

/**
 * 生成四六级照片副本
 */

$paths = scandir(INPUT_DIR);

foreach ($paths as $item)
{
    if(getFileType($item)==ACCEPT_FILE_TYPE)
    {
        process($item,4);
    }
}
