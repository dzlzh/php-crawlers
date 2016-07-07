<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: config.example.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-07-07 14:07
 *  +--------------------------------------------------------------
 *  | Description: 
 *  +--------------------------------------------------------------
 */

require_once 'function.php';

define("ROOT_DIRECTORY_PATH", dirname(__FILE__));
define("DIRECTORY_NAME", '极客学院Wiki');
define("DIRECTORY_PATH", ROOT_DIRECTORY_PATH . DIRECTORY_SEPARATOR . DIRECTORY_NAME);

$jkxyWikiUrl = 'http://wiki.jikexueyuan.com';
$userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36';
$cookie = '';
