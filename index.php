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

$userAgent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36';
$url       = 'https://www.v2ex.com/mission/daily/';
// 邮件
$subject   = 'V2ex-' . DATE;
$APIKey    = '';
$domain    = '';
$mailTo    = '';

$cookie    = '';

//v2ex sign
if (!empty($cookie)) {
    $message   = '';
    $v2exHtml  = curlHtml($url, null, $cookie, $userAgent);
    $isMatched = preg_match('/\/mission\/daily\/(redeem\?once=\w*)/', $v2exHtml, $matches);
    if ($isMatched) {
        $v2exSignURL = $url . $matches[1];
        $v2exSign = curlHtml($v2exSignURL, null, $cookie, $userAgent);
        if (strstr($v2exSign, '302') !== false) {
            $v2exHtml = curlHtml($url, null, $cookie, $userAgent);
            $isMatched = preg_match('/\x{6bcf}\x{65e5}\x{767b}\x{5f55}\x{5956}\x{52b1}\x{5df2}\x{9886}\x{53d6}/u', $v2exHtml);
            if ($isMatched) {
                $message = "v2ex签到成功\n";
            } else {
                $message = "v2ex签到失败\n";
            }
        } else {
            $message = $v2exSign . "\n";
        }
    } else {
        $isMatched = preg_match('/\x{6bcf}\x{65e5}\x{767b}\x{5f55}\x{5956}\x{52b1}\x{5df2}\x{9886}\x{53d6}/u', $v2exHtml);
        if ($isMatched) {
            $message = "v2ex 签到成功\n";
        } else {
            $message = "v2ex 签到失败\n";
        }
    }
    echo $message;

    // 发送邮件
    $shell  = '';
    $shell .= "curl -s --user 'api:{$APIKey}' ";
    $shell .= "https://api.mailgun.net/v3/{$domain}/messages ";
    $shell .= "-F from='Notification <mailgun@{$domain}>' ";
    $shell .= "-F to={$mailTo} ";
    $shell .= "-F subject='{$subject}' ";
    $shell .= "-F text='{$message}'";
    echo `$shell`;
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
        preg_match_all('/Set-Cookie:([^;]+;)/', $response, $matches);
        $cookie = '';
        foreach ($matches[1] as $value) {
            $cookie .= $value;
        }
        return $cookie;
    } 
}
