<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: function.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-07-05 14:58
 *  +--------------------------------------------------------------
 *  | Description: makeDirectory
 *  +--------------------------------------------------------------
 */


/**
 *  递归创建目录
 *  @param  strint  $path
 *  @param  int     $mode
 *  @return bool
 */ 

function makeDirectory($path, $mode = 777)
{
    if(is_dir($path)) {
        return true;
    }

    return is_dir(dirname($path)) || makeDirectory(dirname($path)) ? mkdir($path, $mode) : false;
}

