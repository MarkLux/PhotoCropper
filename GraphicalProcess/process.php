<?php

//    require 'vendor/autoload.php';
    require 'getApiData.php';
    require 'graph.php';
    require 'excelProcess.php';

    /**
     * 压缩原图并且根据人脸位置裁剪成一寸或者两寸大小
     */

    function process($file_name,$size)
    {
        // $directory = '/output';
        $orgin_path = INPUT_DIR."/".$file_name;
        if($size ==1)
        {
            $thumb_path =  COPY_DIR. "/OneSizeCopy_".$file_name;
        }
        elseif($size == 2)
        {
            $thumb_path =  COPY_DIR. "/TwoSizeCopy_".$file_name;
        }
        elseif($size == 3)
        {
            $thumb_path = COPY_DIR. "/ReaderCopy_".$file_name;
        }
        elseif($size == 4)
        {
            $thumb_path = COPY_DIR. "/CETCopy_".$file_name;
        }
        else
        {
            die("Error Wrong Param in ".$file_name."\n");
        }

        $compression_type = Imagick::COMPRESSION_JPEG;

        compress($orgin_path, $thumb_path, $compression_type);

        $faceData = getApiData($thumb_path);

        if(empty($faceData)||$faceData===null)
        {
            die("Error process".$file_name.": failed to get response from api.\n");
        }

        $st_y = $faceData['top'];
        $st_x = $faceData['left'];
        $size_w = $faceData['width'];
        $size_h = $faceData['height'];

        /**
         *计算偏移量并调整数据
         */

        if($size == 1) {

            $top = $size_h * 0.8;
            $margin = $size_w * 0.6;

            $st_x = $st_x - $margin;
            $st_y = $st_y - $top;

            $width = $margin * 2 + $size_w;
            $height = 7 / 5 * $width;


            $resImg = new Imagick($thumb_path);

            $resImg = $resImg->clone();

            $resImg->cropImage($width, $height, $st_x, $st_y);

            $resImg->thumbnailImage(295, 412);//标准1寸分辨率
        }
        else if($size ==2)
        {
            $top = $size_h * 0.8;
            $margin = $size_w * 0.6;

            $st_x = $st_x - $margin;
            $st_y = $st_y - $top;

            $width = $margin * 2 + $size_w;
            $height = 626/413 * $width;

            $resImg = new Imagick($thumb_path);

            $resImg = $resImg->clone();

            $resImg->cropImage($width, $height, $st_x, $st_y);

            $resImg->thumbnailImage(413, 626);//标准2寸分辨率
        }
        elseif($size == 3)
        {
            $top = $size_h * 0.75;
            $margin = $size_w * 0.45;

            $st_x = $st_x - $margin;
            $st_y = $st_y - $top;

            $width = $margin * 2 + $size_w;
            $height = 7 / 5 * $width;


            $resImg = new Imagick($thumb_path);

            $resImg = $resImg->clone();

            $resImg->cropImage($width, $height, $st_x, $st_y);

            $resImg->thumbnailImage(354, 495);//读者证分辨率
        }
        else
        {
            $top = $size_h * 0.75;
            $margin = $size_w * 0.35;

            $st_x = $st_x - $margin;
            $st_y = $st_y - $top;

            $width = $margin * 2+ $size_w;
            $height = 4/3 * $width;


            $resImg = new Imagick($thumb_path);
            $resImg->setImageCompressionQuality(80);

            $resImg = $resImg->clone();

            $resImg->cropImage($width, $height, $st_x, $st_y);

            $resImg->thumbnailImage(240, 320);//四六级要求

        }

        $resImg->writeImage($thumb_path);

        $resImg->destroy();

        echo "Process succeed: ".$file_name."\n";
    }


    /**
     * 生成一寸八张
     */
    function makeOneEight($file_name)
    {
        //从xls中搜索

        $xlsData = getDataFromXls(XLS_NAME);

        if(empty($xlsData))
            die("Error: failed to get data from xls file,check your file path\n");

        $code = getFileCode($file_name);
        $code = floatval($code);

        $index = array_search($code,array_column($xlsData,'code'));

        $count = $xlsData[$index]['oneSizeNum'];

        if(empty($count))
            return;

        $file_path = COPY_DIR."/".$file_name;

        //生成画布
        $cavans = new Imagick;
        $pixel = new ImagickPixel('#ffffff');
        $draw = new ImagickDraw();
        $cavans->newImage(1500, 1050, $pixel);
        $cavans->setImageFormat('JPG');

        //读取要生成的照片本体
        $pic = new Imagick($file_path);

        //水印
        $draw->setFont('./wqy-microhei.ttc');
        $draw->setFontSize(15);


        if($xlsData[$index]==null)
            die("Erorr: failed to get data by photo code\n");

        $waterMark = $xlsData[$index]['name']."  ".$xlsData[$index]['stuNum'];

        $cavans->annotateImage($draw,1300, 1035, 0, $waterMark);

        //各种偏移量
        $st_x = 35;//左上角起始点x坐标
        $st_y = 55;//左上角起始点y坐标

        $pic_width = $pic->getImageWidth();
        $pic_height = $pic->getImageHeight();

        $margin_horizon = 80;
        $margin_vertical = 112;

        //绘制第一行的四张
        for ($i = 0; $i < 4; $i++) {
            $cavans->compositeImage($pic, Imagick::COMPOSITE_OVER, $st_x, $st_y);

            $st_x += ($margin_horizon + $pic_width);
        }

        //移动到第二行

        $st_x = 35;
        $st_y += ($pic_height + $margin_vertical);

        //绘制第二行的四张

        for ($i = 0; $i < 4; $i++) {
            $cavans->compositeImage($pic, Imagick::COMPOSITE_OVER, $st_x, $st_y);

            $st_x += ($margin_horizon + $pic_width);
        }

        for($i=1;$i<=$count;$i++)
        {
            $cavans->writeImage(PRODUCT_DIR."/".$i."_".$file_name);
        }

        echo "File update succeed: ".$file_name."\n";
    }

    /**
     * 生成两寸四张
     */
    function makeTwoFour($file_name)
    {
        //从xls中搜索

        $xlsData = getDataFromXls(XLS_NAME);

        if(empty($xlsData))
            die("Error: failed to get data from xls file,check your file path\n");

        $code = getFileCode($file_name);
        $code = floatval($code);


        $index = array_search($code,array_column($xlsData,'code'));


        if($xlsData[$index]==null)
            die("Erorr: failed to get data by photo code\n");

        $count = $xlsData[$index]['twoSizeNum'];

        if(empty($count))
            return;

        $file_path = COPY_DIR."/".$file_name;

        //生成画布
        $cavans = new Imagick;
        $draw = new ImagickDraw();
        $pixel = new ImagickPixel('#ffffff');
        $cavans->newImage(1500, 1050, $pixel);
        $cavans->setImageFormat('JPG');

        //读取要生成的照片本体
        $pic = new Imagick($file_path);

        $pic->rotateimage('#ffffff', -90.0);    //旋转-90度

        //水印
        $draw->setFont('./wqy-microhei.ttc');
        $draw->setFontSize(15);



        $waterMark = $xlsData[$index]['name']."  ".$xlsData[$index]['stuNum'];

        $cavans->annotateImage($draw,1300, 1035, 0, $waterMark);

        //设置偏移量，移动到第一行

        $st_x = 88; //起始x坐标
        $st_y = 55; //起始y坐标

        $pic_width = $pic->getImageWidth();
        $pic_height = $pic->getImageHeight();

        $margin_horizon = 72;
        $margin_vertical = 112;


        //打印第一行两张

        for($i = 0; $i < 2; $i++){
            $cavans->compositeImage($pic, Imagick::COMPOSITE_OVER, $st_x, $st_y);

            $st_x +=($margin_horizon+$pic_width);
        }


        //移动到第二行

        $st_x = 88;
        $st_y += ($margin_vertical + $pic_height);


        //打印第二行两张

        for($i = 0; $i < 2; $i++){
            $cavans->compositeImage($pic, Imagick::COMPOSITE_OVER, $st_x, $st_y);

            $st_x +=($margin_horizon+$pic_width);
        }

        for($i=1;$i<=$count;$i++)
        {
            $cavans->writeImage(PRODUCT_DIR."/".$i."_".$file_name);
        }

        echo "File update succeed: ".$file_name."\n";
    }

    /*
     *获取照片编号
     */
    function getFileCode($file_name)
    {
        return substr($file_name,strrpos($file_name, '_')+1,4);
    }

?>
