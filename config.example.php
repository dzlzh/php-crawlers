<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: config.example.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-05-30 10:06
 *  +--------------------------------------------------------------
 *  | Description: automatic sign config
 *  +--------------------------------------------------------------
 */

$URL = array(
    //'网站名称' => '签到地址',
    'v2ex' => 'https://www.v2ex.com/mission/daily/',
    'v2dn' => 'https://www.v2dn.net/',
    'StartSS' => 'https://startss.today/user/checkin',
    'ssfastproxy' => 'https://ssfastproxy.com/user/checkin',
);

$COOKIE = array(
    //'网站名称' => 'cookie',
    'v2ex' => '',
    'v2dn' => '',
    'StartSS' => '',
    'ssfastproxy' => '',
);

$userAgent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36';
