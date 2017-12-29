<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 16-9-8
 * Time: 下午2:05
 *
 */
require 'process.php';
require 'config.php';
/**
 * 批量生成两寸四张版式
 */

function getFileType($filename) {
    return substr($filename, strrpos($filename, '.') + 1);
}

$paths = scandir(COPY_DIR);

foreach ($paths as $item)
{
    if(getFileType($item)==ACCEPT_FILE_TYPE)
    {
        if(substr($item,0,7) == "TwoSize")
        {
            makeTwoFour($item);
        }
    }
}