<?php

require 'vendor/autoload.php';
//require 'config.php';

function getApiData($file_path)
{
    /**
     * 调用api返回脸部基本信息
     */

    $url = 'https://api.projectoxford.ai/face/v1.0/detect?returnFaceId=true&returnFaceLandmarks=false';
    $headers = array(
        'Content-Type' => 'application/octet-stream',
        'Ocp-Apim-Subscription-Key' => API_KEY
    );
    $data = file_get_contents($file_path);
    $response = Requests::post($url, $headers, $data);

    $res = json_decode($response->body, true);

    if(!empty($res[0])) {
        $faceData = $res[0]['faceRectangle'];
        return $faceData;
    }
    else{
        return null;
    }
}
