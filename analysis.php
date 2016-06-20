<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: analysis.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-06-20 16:23
 *  +--------------------------------------------------------------
 *  | Description: analysis lagou php-beijing
 *  +--------------------------------------------------------------
 */


require_once 'config.php';

$pdo = new PDOMysql();
$pdo->connect($dbconfig);

$totalSql = 'SELECT count(uuid) AS number FROM lagou';
$total = $pdo->findOne($totalSql)['number'];
echo 'Total:', $total, "\n";
echo "-------------------------\n";

$districtSql = 'SELECT district, COUNT(district) AS number FROM lagou GROUP BY district ORDER BY number DESC';
$districts = $pdo->findAll($districtSql);
foreach ($districts as $value) {
    $proportion = round(($value['number']/$total)*100, 2) . '%';
    echo $value['district'], ':', $value['number'], " —— ", $proportion, "\n";
}
echo "-------------------------\n";

$financeStageSql = 'SELECT financeStage, COUNT(financeStage) AS number FROM lagou GROUP BY financeStage ORDER BY number DESC';
$financeStages = $pdo->findAll($financeStageSql);
foreach ($financeStages as $value) {
    $proportion = round(($value['number']/$total)*100, 2) . '%';
    echo $value['financeStage'], ':', $value['number'], " —— ", $proportion, "\n";
}
echo "-------------------------\n";
