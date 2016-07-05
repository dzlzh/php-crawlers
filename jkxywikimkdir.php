<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: jkxywikimkdir.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-07-04 17:24
 *  +--------------------------------------------------------------
 *  | Description: 
 *  +--------------------------------------------------------------
 */

require_once 'config.php';

foreach ($jkxyWikiUrlList as $key => $value) {
    foreach ($value as $k => $v) {
        $path = DIRECTORY_PATH . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . $k . DIRECTORY_SEPARATOR;
        echo makeDirectory($path . 'PDF') && makeDirectory($path . 'ePub') ? $path . ' -- successful' . PHP_EOL : $path . ' -- failure' . PHP_EOL;
    }
}
