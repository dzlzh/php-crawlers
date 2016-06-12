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
    echo $jobsUrl;
    $jsonData = json_decode(curlHtml($jobsUrl), true);
    $jobs = $jsonData['content']['positionResult']['result'];
    foreach ($jobs as $job) {
        $jobUrl .= $job['positionId'] . '.html';
        $jobData = curlHtml($jobUrl);
        echo $jobUrl;
        var_dump($jobData);
        die;
        $param = array(
            'uuid'              => getUUID(),
            'positionId'        => $job['positionId'],
            'positionName'      => $job['positionName'],
            'positionType'      => $job['positionType'],
            'positionAdvantage' => $job['positionAdvantage'],
            'companyName'       => $job['companyName'],
            'companyShortName'  => $job['companyShortName'],
            'companySize'       => $job['companySize'],
            'companyLabelList'  => implode(',', $job['companyLabelList']),
            'companyHome'       => '公司主页',
            'industryField'     => $job['industryField'],
            'financeStage'      => $job['financeStage'],
            'city'              => $job['city'],
            'district'          => $job['district'],
            'businessZones'     => implode(',', $job['businessZones']),
            'address'           => '具体地址',
            'salary'            => $job['salary'],
            'workYear'          => $job['workYear'],
            'education'         => $job['education'],
            'jobNature'         => $job['jobNature'],
            'jobDescription'    => '职位描述',
            'createTime'        => $job['createTime'],
            'jobUrl'            => $jobUrl,
            'collectionTime'    => date("Y-m-d H:i:s"),
        );   
        print_r($param);
        die;
    }
    print_r($jobs);
die;
}

// var_dump($pdo->findOne('select UUID();'));
// var_dump($pdo->insert('lagou',$param));
// var_dump($pdo->del('lagou'));
