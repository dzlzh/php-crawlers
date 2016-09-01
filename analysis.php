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

$totalSql = 'SELECT count(uuid) AS number FROM lagou WHERE city="北京"';
$total = $pdo->findOne($totalSql)['number'];
echo 'Total:', $total, "\n";
echo "-------------------------\n";

$sql = array(
    'district'     => 'SELECT district, COUNT(district) AS number FROM lagou WHERE city="北京" GROUP BY district ORDER BY number DESC',
    'financeStage' => 'SELECT financeStage, COUNT(financeStage) AS number FROM lagou WHERE city="北京" GROUP BY financeStage ORDER BY number DESC',
    'workYear'     => 'SELECT workYear, COUNT(workYear) AS number FROM lagou WHERE city="北京" GROUP BY workYear ORDER BY number DESC',
    'education'    => 'SELECT education, COUNT(education) AS number FROM lagou WHERE city="北京" GROUP BY education ORDER BY number DESC',
);

foreach ($sql as $key => $value) {
    echo "\n\t", $key, "\n\n";
    $datas = $pdo->findAll($value);
    $data = array();
    foreach ($datas as $v) {
        $data[$v[$key]] = $v['number'];
    }
    output($data, $total);
    echo "-------------------------\n";
}

echo "\n\tindustryField\n\n";
$industryFieldSql = 'SELECT industryField FROM lagou';
$industryFieldData = $pdo->findAll($industryFieldSql);
foreach ($industryFieldData as $key => $value) {
    if (strstr($value['industryField'], ',') !== false) {
        $industryField = explode(',',$value['industryField']);
    } else {
        $industryField = explode(' · ',$value['industryField']);
    }
    $industryFieldData[$key] = trim($industryField[0]);
    for ($i = 1; $i < count($industryField); $i++) {
        $industryFieldData[] = trim($industryField[$i]);
    }
}
$industryFieldData = array_count_values($industryFieldData);
arsort($industryFieldData);
output($industryFieldData, $total);
echo "-------------------------\n";

echo "\n\tsalary\n\n";
$salarySql = 'SELECT salary FROM lagou';
$salaryData = $pdo->findAll($salarySql);
output(salary($salaryData), $total);
echo "-------------------------\n";

echo "\n\twork year —— salary\n\n";
$workYear = array(
    '不限',
    '应届毕业生',
    '1年以下',
    '1-3年',
    '3-5年',
    '5-10年',
    '10年以上',
);

foreach ($workYear as $value) {
    echo "\n",$value,"\n";
    $workYearSalarySql = 'SELECT salary FROM lagou WHERE workYear="' . $value . '"';
    $workYearSalaryData = $pdo->findAll($workYearSalarySql);
    output(salary($workYearSalaryData), count($workYearSalaryData));
    echo "-------------\n";
}


function output($data, $total)
{
    foreach ($data as $key => $value) {
        if ($value > 0) {
            $proportion = round(($value/$total)*100, 2) . '%';
            echo '|', $key, '|', $value, "|", $proportion, "|\n";
        }
    }
}

function salary($data)
{
    $salaryNum = array(
        '2k以下'  => 0,
        '2k-5k'    => 0,
        '5k-10k'   => 0,
        '10k-15k'  => 0,
        '15k-25k'  => 0,
        '25k-50k'  => 0,
        '50k以上' => 0,
    );
    foreach ($data as $value) {
        $isMatched = preg_match('/^(\d+)k/', $value['salary'], $matches);
        if ($isMatched) {
            if ($matches[1] < 2) {
                ++$salaryNum['2k以下'];
            } elseif ($matches[1] < 5) {
                ++$salaryNum['2k-5k'];
            } elseif ($matches[1] < 10) {
                ++$salaryNum['5k-10k'];
            } elseif ($matches[1] < 15) {
                ++$salaryNum['10k-15k'];
            } elseif ($matches[1] < 25) {
                ++$salaryNum['15k-25k'];
            } elseif ($matches[1] < 50) {
                ++$salaryNum['25k-50k'];
            } else {
                ++$salaryNum['50k以上'];
            }
        }
    }
    return $salaryNum;
}

// $keywords = array(
    // 'php', 'mysql', 'web', 'linux', 'css',
    // 'javascript', 'html', 'ajax', 'jquery', 'sql',
    // 'mvc', 'lamp', 'js', 'apache', 'xml',
    // 'unix', 'div', 'nginx', 'yii', 'thinkphp',
    // 'redis', 'xhtml', 'shell', 'oop', 'json',
    // 'memcache', 'zend', 'java', 'api', 'ci',
    // 'svn', 'python', 'codeigniter', 'html5', 'nosql',
    // 'discuz', 'smarty', 'mongodb', 'cms', 'oracle',
    // 'w3c', 'framework', 'lbs', 'git', 'memcached',
    // 'tcp', 'lnmp', 'cakephp', 'rest', 'crm',
    // 'android', 'uml', 'css3', 'webservice', 'php5',
    // 'tp', 'dhtml', 'ecshop', 'symfony', 'erp',
    // 'windows', 'sns', 'wordpress', 'seo', 'phpcms',
    // 'bootstrap', 'drupal', 'cache', 'o2o', 'ui',
    // 'postgresql', 'perl', 'github', 'oa', 'yaf',
// );
function dataCount($data)
{
    global $keywordsCount;
    foreach ($data as $value) {
        $value = strtolower($value);
        $keywordsCount[$value] = array_key_exists($value, $keywordsCount) ? $keywordsCount[$value] + 1 : 1;
    }
}
// $keywordsCount = array();
// $sql = 'SELECT `jobDescription` FROM lagou LIMIT 0, 2;';
// $sql = 'SELECT `jobDescription` FROM lagou WHERE uuid="57BF9109-3229-7C0A-37A1-4CFA734E2EDF" and hex(jobDescription) not regexp "^([0-7][0-9A-F])*$"';
// $sql = 'SELECT hex(jobDescription) FROM lagou WHERE uuid="DDD7DDBA-89B6-5965-222D-8E15A68165CE" and hex(jobDescription) not regexp "^([0-7][0-9A-F])*$"';
// $sql = 'SELECT `jobDescription` FROM lagou WHERE uuid="0096C736-E31A-159F-5D43-24FEAD570480" and hex(jobDescription) not regexp "^([0-7][0-9A-F])*$"';
// $sql = 'SELECT `jobDescription` FROM lagou WHERE hex(jobDescription) not regexp "^([0-7][0-9A-F])*$"';
// $datas = $pdo->findAll($sql);
// foreach ($datas as $key=>$value) {
    // print_r($value);
    // die;
    // $isMatched = preg_match_all('/\b[a-zA-Z.]+\d?\b/', $value['jobDescription'], $matches);
    // if ($isMatched) {
        // print_r(array_unique($matches[0]));
        // dataCount(array_unique($matches[0]));
    // }
// }
$keywordsCount = array(
    'php' => 6078,
    'mysql' => 5130,
    'linux' => 2957,
    'web' => 2771,
    'javascript' => 2492,
    'css' => 2176,
    'html' => 1930,
    'redis' => 1654,
    'ajax' => 1509,
    'jquery' => 1322,
    'memcache' => 1242,
    'mvc' => 1152,
    'sql' => 1088,
    'thinkphp' => 1059,
    'lamp' => 1010,
    'yii' => 979,
    'lnmp' => 885,
    'nosql' => 872,
    'nginx' => 763,
    'shell' => 749,
    'xml' => 658,
    'mongodb' => 626,
    'python' => 623,
    'app' => 588,
    'apache' => 583,
    'js' => 579,
    'http' => 567,
    'div' => 543,
    'unix' => 511,
    'java' => 509,
    'html5' => 458,
    'oop' => 457,
    'git' => 404,
    'svn' => 399,
    'xhtml' => 381,
    'json' => 372,
    'ci' => 359,
    'c' => 344,
    'api' => 344,
    'laravel' => 336,
    'smarty' => 257,
    'bug' => 250,
    'tcp' => 228,
    'ecshop' => 222,
    'css3' => 191,
    'zend' => 188,
    'phpcms' => 163,
    'zendframework' => 161,
    'bootstrap' => 148,
    'yaf' => 147,
    'oracle' => 143,
    'codeigniter' => 139,
    'discuz' => 127,
    'node.js' => 126,
    'go' => 122,
    'cms' => 120,
    'dedecms' => 101,
    'codereview' => 97,
    'perl' => 95,
    'symfony' => 90,
    'tp' => 86,
    'h5' => 84,
    'php5' => 76,
    'cakephp' => 75,
    'ui' => 69,
    'github' => 67,
    'hadoop' => 64,
    'socket' => 63,
    'ucenter' => 61,
    'ruby' => 38,
    'angular' => 37,
    'cache' => 37,
    'angularjs' => 33,
    'websocket' => 30,
    'vim' => 27,
    'mangodb' => 23,
    'phpmvc' => 21,
);
// arsort($keywordsCount);
print_r($keywordsCount);
die;
