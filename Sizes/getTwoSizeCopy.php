<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 16-9-7
 * Time: 下午5:09
 */

require '../GraphicalProcess/process.php';
require '../Conf/config.php';

/**
 * 获取文件扩展名
 */
function getFileType($filename)
{
    return substr($filename, strrpos($filename, '.') + 1);
}

/**
 * 批量生成两寸照片的副本
 */

$paths = scandir(INPUT_DIR);

foreach ($paths as $item) {
    if (getFileType($item) == ACCEPT_FILE_TYPE) {
        process($item, 2);
    }
}