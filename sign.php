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

//StartSS sign
if (!empty($URL['StartSS'])) {
    $StartSSSignURL = $URL['StartSS'];
    $StartSSSign = curlHtml($StartSSSignURL, '1', $COOKIE['StartSS'], $userAgent);
    $StartSSSign = json_decode($StartSSSign, true);
    if ($StartSSSign['ret'] === 1) {
        echo $StartSSSign['msg'], "\n";
    } else {
        echo $StartSSSign['msg'], "\n";
    }
}

//v2dn sign
if (!empty($URL['v2dn'])) {
    $v2dnSignURL = $URL['v2dn'] . 'checkIn.php';
    $v2dnSign = curlHtml($v2dnSignURL, null, $COOKIE['v2dn'], $userAgent);
    if (strstr($v2dnSign, '302') !== false) {
        $v2dnURL = $URL['v2dn'] . 'mypoints.php';
        $v2dnHtml = curlHtml($v2dnURL, null, $COOKIE['v2dn'], $userAgent);
        $isMatched = preg_match('/((((1[6-9]|[2-9]\d)\d{2})-(1[02]|0?[13578])-([12]\d|3[01]|0?[1-9]))|(((1[6-9]|[2-9]\d)\d{2})-(1[012]|0?[13456789])-([12]\d|30|0?[1-9]))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(1\d|2[0-8]|0?[1-9]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))/', $v2dnHtml, $matches);
        if ($isMatched && $matches[0] == substr(DATE, 0, 10)) {
            $isMatched = preg_match('/<strong>(\d+)/', $v2dnHtml, $matches);
            if ($isMatched) {
                echo "每日登录奖励$matches[1]\n";
            } else {
                echo "v2dn签到成功\n";
            }
        } else {
            echo "v2dn签到失败\n";
        }
    }
}

//v2ex sign
if (!empty($URL['v2ex'])) {
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
            echo "v2ex签到成功\n";
        } else {
            echo "v2ex签到失败\n";
        }
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
