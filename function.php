<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: function.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-07-05 15:40
 *  +--------------------------------------------------------------
 *  | Description: makeDirectory curlHtml
 *  +--------------------------------------------------------------
 */


/**
 *  递归创建目录
 *  @param  strint  $path
 *  @param  int     $mode
 *  @return bool
 */ 

function makeDirectory($path, $mode = 777)
{
    if(is_dir($path)) {
        return true;
    }

    return is_dir(dirname($path)) || makeDirectory(dirname($path)) ? mkdir($path, $mode) : false;
}


/**
 *  页面采集
 *  @param string $url
 *  @param string $userAgent
 *  @param string $cookie
 *  @param array  $param
 *  @return string
 */ 

function curlHtml($url, $userAgent = null, $cookie = null, $param = null)
{
    for ($i = 0; $i < 3; $i++) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        if ($userAgent != null) {
            curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        }
        if ($param != null) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        }
        if ($cookie != null) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $httpCodeIsMatched = preg_match('/4\d{2}|5\d{2}/', $info['http_code']);
        if (!$httpCodeIsMatched) {
            return $data;
        }
        usleep(1000000 * $i);
    }
    $error = 'Error::http_code:' . $info['http_code'] . ';url:'. $info['url'];
    if (is_array($param)) {
        $error .= ';param:' . '(' . serialize($param) . ')';
    }
    return $error;
}
