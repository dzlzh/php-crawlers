<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: Func.class.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-11-26 13:53
 *  +--------------------------------------------------------------
 *  | Description: curl_html mk_dir get_UUID
 *  +--------------------------------------------------------------
 */

class Func
{
    public function curlHtml($url, $userAgent = null, $cookie = null, $param = null, $file = null, $header = 0)
    {
        for ($i = 0; $i < 3; $i++) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, $header);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
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
            if ($file != null) {
                $fp = fopen($file, 'w');
                curl_setopt($curl, CURLOPT_FILE, $fp);
            }
            $data = curl_exec($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);
            if ($file != null) {
                fclose($fp);
                $downloadFileSize = filesize($file);
                return $downloadFileSize == $info['size_download'];
            }
            $httpCodeIsMatched = preg_match('/4\d{2}|5\d{2}/', $info['http_code']);
            if (!$httpCodeIsMatched) {
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

    /**
     *  递归创建目录
     *  @param  strint  $path
     *  @param  int     $mode
     *  @return bool
     */ 
    public function makeDirectory($path, $mode = 777)
    {
        if (is_dir($path)) {
            return true;
        }
        return is_dir(dirname($path)) || mk_dir(dirname($path)) ? mkdir($path, $mode) : false;
    }

    /**
     * 生成UUID（GUID）
     * @return string
     */
    public function getUUID()
    {
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(mt_rand(), true)));
            //123-"{", 125-"}", 45-"-"
            $hyphen = chr(45);
            $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            //$uuid = chr(123) . $uuid . $chr(125);
            return $uuid;
        }
    }
}
