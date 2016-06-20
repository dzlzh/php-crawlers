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

$sql = array(
    'district'     => 'SELECT district, COUNT(district) AS number FROM lagou GROUP BY district ORDER BY number DESC',
    'financeStage' => 'SELECT financeStage, COUNT(financeStage) AS number FROM lagou GROUP BY financeStage ORDER BY number DESC',
    'workYear'     => 'SELECT workYear, COUNT(workYear) AS number FROM lagou GROUP BY workYear ORDER BY number DESC',
    'education'    => 'SELECT education, COUNT(education) AS number FROM lagou GROUP BY education ORDER BY number DESC',
);

foreach ($sql as $key => $value) {
    echo "\n\t", $key, "\n\n";
    $data = $pdo->findAll($value);
    foreach ($data as $v) {
        $proportion = proportion($v['number'], $total);
        echo $v[$key], ':', $v['number'], " —— ", $proportion, "\n";
    }
    echo "-------------------------\n";
}

function proportion($num, $total)
{
    return round(($num/$total)*100, 2) . '%';
}

