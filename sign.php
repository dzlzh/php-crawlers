<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: sign.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-05-27 16:49
 *  +--------------------------------------------------------------
 *  | Description: 
 *  +--------------------------------------------------------------
 */


//引入配置文件
require_once 'config.php';

$signHtml = curlHtml($URL['v2ex'], null, $COOKIE['v2ex'], $userAgent);
$isMatched = preg_match('/\/mission\/daily\/(redeem\?once=\w*)/', $signHtml, $matches);
if ($isMatched) {
    $signURL = $URL['v2ex'] . $matches[1];
    $sign = curlHtml($signURL, null, $COOKIE['v2ex'], $userAgent);
    if (strstr($sign, '302') !== false) {
        $signHtml = curlHtml($URL['v2ex'], null, $COOKIE['v2ex'], $userAgent);
        echo $signHtml;
    }
}

function curlHtml($url, $param = null, $cookie = null, $userAgent = null)
{
    for ($i = 0; $i < 3; $i++) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        if($userAgent != null) {
            curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        }
        if($param != null) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        }
        if($cookie != null) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        if($info['http_code'] == 200) {
            return $data;
        }
        usleep(1000000 * $i);
    }
    $error = 'Error::http_code:' . $info['http_code'] . ';url:' . $info['url'];
    if (is_array($param)) {
        $error .= ';param:' . '(' . serialize($param) . ')';
    }
    return $error;
}
