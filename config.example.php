<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: config.example.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-06-03 16:54
 *  +--------------------------------------------------------------
 *  | Description: 
 *  +--------------------------------------------------------------
 */


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

$parameters = array(
    'first'                 => 'true',
    'needAddtionalResult'   => 'false',
    //排序方式
    'px'                    => 'new',
    //搜索的职位
    'kd'                    => 'php',
    //工作地点
    'city'                  => '北京',
    //行政区
    'district'              => '',
    //商区
    'bizArea'               => '',
    //工作经验
    'gj'                    => '',
    //学历要求
    'xl'                    => '',
    //融资阶段
    'jd'                    => '',
    //行业领域
    'hy'                    => '',
    //月薪
    'yx'                    => '',
    //工作性质
    'gx'                    => '',
    //页码
    'pn'                    => '1',
);

$url = 'http://www.lagou.com/jobs/positionAjax.json?';
