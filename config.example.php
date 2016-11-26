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

require_once 'class/Func.class.php';
require_once 'class/PDOMysql.class.php';

ini_set('memory_limit', '-1');
date_default_timezone_set('Asia/Shanghai');

//当前时间
define('DATE', date('Y-m-d H:i:s'));
//分隔符
define('DS', DIRECTORY_SEPARATOR);
//根目录
define('ROOT_DIRECTORY_PATH', dirname(__FILE__));

//数据库配置
$dbconfig = array(
    'type'      => 'mysql',
    'host'      => '',
    'port'      => '3306',
    'dbname'    => '',
    'dbuser'    => '',
    'dbpwd'     => '',
    'dbcharset' => 'utf8',
    'dbdebug'   => true,
);

$userAgent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.87 Safari/537.36';

$url = array();
$cookie = array();
$param = array();
