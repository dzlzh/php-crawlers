<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: index.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-08-29 11:57
 *  +--------------------------------------------------------------
 *  | Description: 
 *  +--------------------------------------------------------------
 */

header('Content-Type: text/html; charset=utf-8');
$automaticSignLog = file_get_contents('automaticSign.log', FILE_USE_INCLUDE_PATH);
$lagouCrawlerLog  = file_get_contents('lagouCrawler.log', FILE_USE_INCLUDE_PATH);
echo '<pre>';
echo 'automatic-sign';
echo "\n";
echo $automaticSignLog;
echo "\n\n";
echo 'lagou-crawler';
echo "\n";
echo $lagouCrawlerLog;
echo '</pre>';
