<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: crawler.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-06-20 15:19
 *  +--------------------------------------------------------------
 *  | Description: 
 *  +--------------------------------------------------------------
 */


require_once 'config.php';

echo "\n\n=-=-=-=-=-\t", DATE, "\t-=-=-=-=-=\n\n";

$pdo = new PDOMysql();
$pdo->connect($dbconfig);

foreach ($parameters as $key => $value) {
    if (!empty($value)) {
        $jobsBaseUrl .= $key . '=' . $value . '&';
    }
}

$dataArr = $pdo->findAll('SELECT `positionId`, `createTime` FROM lagou;');
foreach ($dataArr as $key => $value) {
    $idTime[$value['positionId']] = $value['createTime'];
}

for ($i = 1; $i <= 5; $i++) {
    echo "====================================\n";
    echo "pn:", $i, "\n";
    echo "------------------------------------\n";
    $jobsUrl = $jobsBaseUrl . 'pn=' . $i;
    $jsonData = json_decode(curlHtml($jobsUrl), true);
    if ($jsonData['content']['positionResult']['pageSize'] <= 0) {
        break;
    }
    $jobs = $jsonData['content']['positionResult']['result'];
    foreach ($jobs as $job) {
        if (array_key_exists($job['positionId'], $idTime)) {
            if ($job['createTime'] == $idTime[$job['positionId']]) {
                echo $job['positionId'], "——", $job['createTime'], "\t-----\texist\n";
            } else {
                $where = "positionId = '" . $job['positionId'] . "'";
                $param = array(
                    'createTime'        => $job['createTime'],
                    'collectionTime'    => date("Y-m-d H:i:s"),
                );
                $result = $pdo->update('lagou', $param, $where);
                if (strpos($result, 'ERROR') === false) {
                    echo $job['positionId'], "——", $job['createTime'], "\t-----\ttiem update\n";
                } else {
                    echo $result, "\n";
                }
            }
            continue;
        } else {
            $jobUrl = $jobBaseUrl . $job['positionId'] . '.html';
            $jobData = curlHtml($jobUrl);
            $isMatched = preg_match('/var\spositionAddress\s=\s\'([^\']+)\'/', $jobData, $matches);
            $address = $isMatched ? $matches[1] : 'unknown';
            $isMatched = preg_match('/<dd\sclass="job_bt">\s*<h3[^>]*>.*<\/h3>((?:.|\n)*?)<\/dd>/', $jobData, $matches);
            $jobDescription = $isMatched ? preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/", "", strip_tags($matches[1])) : 'unknown';
            $param = array(
                'uuid'              => getUUID(),
                'positionId'        => $job['positionId'],
                'positionName'      => $job['positionName'],
                'positionType'      => $job['positionType'],
                'positionAdvantage' => $job['positionAdvantage'],
                'companyName'       => $job['companyName'],
                'companyShortName'  => $job['companyShortName'],
                'companySize'       => $job['companySize'],
                'companyLabelList'  => empty($job['companyLabelList']) ? 'unknown' : implode(',', $job['companyLabelList']),
                'industryField'     => $job['industryField'],
                'financeStage'      => $job['financeStage'],
                'city'              => $job['city'],
                'district'          => empty($job['district']) ? 'unknown' : $job['district'],
                'businessZones'     => empty($job['businessZones']) ? 'unknown' : implode(',', $job['businessZones']),
                'address'           => $address,
                'salary'            => $job['salary'],
                'workYear'          => $job['workYear'],
                'education'         => $job['education'],
                'jobNature'         => $job['jobNature'],
                'jobDescription'    => $jobDescription,
                'createTime'        => $job['createTime'],
                'jobUrl'            => $jobUrl,
                'collectionTime'    => date("Y-m-d H:i:s"),
            );
            $result = $pdo->insert('lagou',$param);
            if (strpos($result, 'ERROR') === false) {
                $positionIds[] = $job['positionId'];
                echo $jobUrl, "\t\t--\tsucceed\n";
            } else {
                echo $result, "\n";
            }
        }
    }
}
