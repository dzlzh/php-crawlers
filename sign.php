<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: sign.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-05-30 10:05
 *  +--------------------------------------------------------------
 *  | Description: automatic sign
 *  +--------------------------------------------------------------
 */

//设置时区
date_default_timezone_set('Asia/Shanghai');
define('DATE', date('Y-m-d H:i:s'));
echo "---", DATE, "---\n";
//引入配置文件
require_once 'config.php';

//v2ex sign
if (!empty($COOKIE['v2ex'])) {
    $v2exHtml = curlHtml($URL['v2ex'], null, $COOKIE['v2ex'], $userAgent);
    $isMatched = preg_match('/\/mission\/daily\/(redeem\?once=\w*)/', $v2exHtml, $matches);
    if ($isMatched) {
        $v2exSignURL = $URL['v2ex'] . $matches[1];
        $v2exSign = curlHtml($v2exSignURL, null, $COOKIE['v2ex'], $userAgent);
        if (strstr($v2exSign, '302') !== false) {
            $v2exHtml = curlHtml($URL['v2ex'], null, $COOKIE['v2ex'], $userAgent);
            $isMatched = preg_match('/\x{6bcf}\x{65e5}\x{767b}\x{5f55}\x{5956}\x{52b1}\x{5df2}\x{9886}\x{53d6}/u', $v2exHtml);
            if ($isMatched) {
                echo "v2ex签到成功\n";
            } else {
                echo "v2ex签到失败\n";
            }
        } else {
            echo $v2exSign, "\n";
        }
    } else {
        $isMatched = preg_match('/\x{6bcf}\x{65e5}\x{767b}\x{5f55}\x{5956}\x{52b1}\x{5df2}\x{9886}\x{53d6}/u', $v2exHtml);
        if ($isMatched) {
            echo "v2ex 签到成功\n";
        } else {
            echo "v2ex 签到失败\n";
        }
    }
}

//v2dn sign
if (!empty($COOKIE['v2dn'])) {
    $v2dnSignURL = $URL['v2dn'] . 'checkIn.php';
    $v2dnSign = curlHtml($v2dnSignURL, null, $COOKIE['v2dn'], $userAgent);
    if (strstr($v2dnSign, '302') !== false) {
        $v2dnURL = $URL['v2dn'] . 'mypoints.php';
        $v2dnHtml = curlHtml($v2dnURL, null, $COOKIE['v2dn'], $userAgent);
        $isMatched = preg_match('/((((1[6-9]|[2-9]\d)\d{2})-(1[02]|0?[13578])-([12]\d|3[01]|0?[1-9]))|(((1[6-9]|[2-9]\d)\d{2})-(1[012]|0?[13456789])-([12]\d|30|0?[1-9]))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(1\d|2[0-8]|0?[1-9]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))/', $v2dnHtml, $matches);
        if ($isMatched && $matches[0] == substr(DATE, 0, 10)) {
            $isMatched = preg_match('/<strong>(\d+)/', $v2dnHtml, $matches);
            if ($isMatched) {
                echo "v2dn 每日登录奖励$matches[1]\n";
            } else {
                echo "v2dn 签到成功\n";
            }
        } else {
            echo "v2dn 签到失败\n";
        }
    }
}

//StartSS sign
if (!empty($COOKIE['StartSS'])) {
    $StartSSSignURL = $URL['StartSS'];
    $cookie = getCookie('https://startss.today/s?wd=%E5%B9%BD%E8%B0%B7%E6%B8%85%E6%B3%89');
    $cookie .= getCookie('https://startss.today/auth/login', $COOKIE['StartSS'], 'POST', $cookie) 
    $StartSSSign = curlHtml($StartSSSignURL, '1', $cookie, $userAgent);
    $StartSSSign = json_decode($StartSSSign, true);
    echo 'StartSS:', $StartSSSign['msg'], "\n";
}

//ssfastproxy sign
if (!empty($COOKIE['ssfastproxy'])) {
    $ssfastproxySignURL = $URL['ssfastproxy'];
    $cookie = getCookie('https://ssfastproxy.com/auth/login', $COOKIE['ssfastproxy'], 'POST');
    $ssfastproxySign = curlHtml($ssfastproxySignURL, '1', $cookie, $userAgent);
    $ssfastproxySign = json_decode($ssfastproxySign, true);
    echo 'ssfastproxy:', $ssfastproxySign['msg'], "\n";
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

function getCookie($url, $param = null, $request = 'GET', $cookie = null)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 1,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $request,
        CURLOPT_POSTFIELDS => $param,
        CURLOPT_COOKIE => $cookie,
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        preg_match('/Set-Cookie:([^;]+;)/', $response, $matches);
        return $matches[1];
    } 
}
