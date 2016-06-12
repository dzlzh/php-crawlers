<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: crawler.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-06-08 11:00
 *  +--------------------------------------------------------------
 *  | Description: 
 *  +--------------------------------------------------------------
 */


require_once 'config.php';

$pdo = new PDOMysql();
$pdo->connect($dbconfig);

foreach ($parameters as $key => $value) {
    if (!empty($value)) {
        $jobsUrl .= $key . '=' . $value . '&';
    }
}

for ($i = 1; $i < 200; $i++) {
    $jobsUrl .= 'pn=' . $i;
    $jsonData = json_decode(curlHtml($jobsUrl), true);
    $jobs = $jsonData['content']['positionResult']['result'];
    foreach ($jobs as $job) {
        $jobUrl .= $job['positionId'] . '.html';
        $jobData = curlHtml($jobUrl);
        $isMatched = preg_match('/var\spositionAddress\s=\s\'([^\']+)\'/', $jobData, $matches);
        $address = $isMatched ? $matches[1] : null;
        $isMatched = preg_match('/<dd\sclass="job_bt">\s*<h3[^>]*>.*<\/h3>((?:.|\n)*?)<\/dd>/', $jobData, $matches);
        $jobDescription = $isMatched ? trim(strip_tags($matches[1]), " \t\n\r\0\x0B") : null;
        $param = array(
            'uuid'              => getUUID(),
            'positionId'        => $job['positionId'],
            'positionName'      => $job['positionName'],
            'positionType'      => $job['positionType'],
            'positionAdvantage' => $job['positionAdvantage'],
            'companyName'       => $job['companyName'],
            'companyShortName'  => $job['companyShortName'],
            'companySize'       => $job['companySize'],
            'companyLabelList'  => empty($job['companyLabelList']) ? null : implode(',', $job['companyLabelList']),
            'industryField'     => $job['industryField'],
            'financeStage'      => $job['financeStage'],
            'city'              => $job['city'],
            'district'          => $job['district'],
            'businessZones'     => empty($job['businessZones']) ? null : implode(',', $job['businessZones']),
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
        print_r($param);
        var_dump($pdo->insert('lagou',$param));
        die;
    }
    print_r($jobs);
die;
}

// var_dump($pdo->findOne('select UUID();'));
// var_dump($pdo->insert('lagou',$param));
// var_dump($pdo->del('lagou'));
