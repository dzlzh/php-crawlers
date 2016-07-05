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
$rootPath = $directory . DIRECTORY_SEPARATOR . $rootDirectoryName;
// print_r($jkxyWikiUrlList);
if (makeDirectory($rootPath) != 1) {
    foreach ($jkxyWikiUrlList as $key => $value) {
        $categoryPath = $rootPath . DIRECTORY_SEPARATOR . $key;
        if (makeDirectory($categoryPath) != 1) {
            foreach ($value as $k => $v) {
                $listPath = $categoryPath . DIRECTORY_SEPARATOR . $k;
                switch (makeDirectory($listPath)) {
                    case 0:
                        echo $listPath, " —— successful\n";
                        break;
                    case 1:
                        echo $listPath, " —— failure\n";
                        break;
                    case 2:
                        echo $listPath, " —— exists\n";
                        break;
                    default:
                        echo 'error';
                        exit;
                }
            }
        }
    }    
}

function makeDirectory($path)
{
    if (!file_exists($path)) {
        return mkdir($path, 777) ? 0 : 1;
        // return mkdir($path, 777) ? $path . ' —— successful' : $path . ' —— failure';
    }
    // return $path . ' —— exists';
    return 2;
}

