<?php

    /**
     * 压缩图片分辨率和大小
     */
    function compress($file_origin,$file_des,$compression_type,$compression_quality=100)
    {
        $img = new Imagick($file_origin);

        $im = $img->clone();

        $w = $im->getImageWidth()*0.2;
        $h = $im->getImageHeight()*0.2;

        $im->setImageCompression($compression_type);
        $im->setImageCompressionQuality($compression_quality);
        $im->stripImage();
        $im->thumbnailImage($w,$h);
        $im->writeImage($file_des);
    }


    function crop($file_path,$data)
    {


        $resImg = new Imagick($file_path);

        $resImg = $resImg->clone();

        $resImg->cropImage($data['w'],$data['h'],$data['x'],$data['y']);

        $resImg->thumbnailImage(295,412);//一寸标准大小

        $resImg->writeImage($file_path);
    }


