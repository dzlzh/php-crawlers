<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: jkxywikimkdir.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-07-05 15:36
 *  +--------------------------------------------------------------
 *  | Description: 
 *  +--------------------------------------------------------------
 */

require_once 'config.php';

$jkxyWikiUrlList = array();
$wikiHtml = curlHtml($jkxyWikiUrl, $userAgent);
$wikiHtmlIsMatched = preg_match('/<ul\sclass="aside-cList"\sid="jdropdown">\s*<!--\s+-->[\s\S]*<!--\s+-->\s*<\/ul>/', $wikiHtml, $wikiListHtml);
if ($wikiHtmlIsMatched) {
    $wikiListHtml = $wikiListHtml[0];
    $wikiListIsMatched = preg_match_all('/<dt\sclass="hd"><a[^>]+>([^<]+)<\/a><\/dt>/', $wikiListHtml, $wikiList);
    $wikiListHtmlArrayIsMatched = preg_match_all('/\s*<li>\s*<div>\s*<dl\s*class="list-nav">\s*<dt\s*class="hd"><a\s*class="active"\s*href="[^"]+">[^<]+<\/a><\/dt>\s*<\/dl>\s*<textarea\s*class="menu-item-wrap\s*none">\s*<div\s*class="list-show">\s*<div>\s*<dl>\s*<dd\s*class="cf">(\s*<a\s*href="[^"]+">[^<]+<\/a>)+\s*<\/dd>\s*<\/dl>\s*<\/div>\s*<\/div><!--\s*list-show\s*-->\s*<\/textarea>\s*<\/div>\s*<\/li>/', $wikiListHtml, $wikiListHtmlArray);
    if ($wikiListIsMatched && $wikiListHtmlArrayIsMatched) {
        foreach ($wikiList[1] as $key => $value) {
            $value = str_replace(array('&amp;',' '), array('&',''), $value);
            $listHtml = $wikiListHtmlArray[0][$key];
            $listIsMatched = preg_match_all('/<a\shref="([^"]+)">([^<]+)<\/a>/', $listHtml, $list);
            if ($listIsMatched) {
                foreach ($list[0] as $k => $v) {
                    $jkxyWikiUrlList[$value][str_replace(array('&amp;',' '), array('&',''), $list[2][$k])] = str_replace(array('&amp;',' '), array('&',''), $list[1][$k]);
                }
            }
        }
    }
}

foreach ($jkxyWikiUrlList as $key => $value) {
    foreach ($value as $k => $v) {
        $path = DIRECTORY_PATH . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR . $k . DIRECTORY_SEPARATOR;
        echo makeDirectory($path . 'pdf') && makeDirectory($path . 'epub') ? $path . ' -- successful' . PHP_EOL : $path . ' -- failure' . PHP_EOL;
        echo '|-- ', $key, PHP_EOL;
        echo '  |-- ', $k, PHP_EOL;
        $projectListUrl = $jkxyWikiUrl . $v;
        $projectListHtml = curlHtml($projectListUrl, $userAgent);
        $projectListIsMatched = preg_match_all('/<a\sclass="cell\scf"\shref="([^"]+)"\starget="_blank">/', $projectListHtml, $projectList);
        if ($projectListIsMatched) {
            foreach ($projectList[1] as $key => $value) {
                $projectHtml = curlHtml($value, $userAgent, $cookie); 
                $isMatched = preg_match_all('/<a\shref="([^"]+)"\starget="_blank"\sclass="download-\w+\sblue-btn">/', $projectHtml, $downloadUrl);
                if ($isMatched) {
                    foreach ($downloadUrl[1] as $value) {
                        $downloadUrl = $jkxyWikiUrl . trim($value);
                        $downloadHtml = curlHtml($downloadUrl, $userAgent, $cookie);
                        if (strstr($downloadHtml, 'http_code:404')) {
                            echo $downloadUrl, ' -- 404', PHP_EOL;
                            continue;
                        }
                        $fileUrlIsMatched = preg_match('/<a[^>]+>([^<]+)<\/a>/', $downloadHtml, $fileUrl);
                        if ($fileUrlIsMatched) {
                            $fileUrl = $fileUrl[1];
                            $fileName = pathinfo(urldecode($fileUrl), PATHINFO_BASENAME);
                            $fileStyle = pathinfo(urldecode($fileUrl), PATHINFO_EXTENSION);
                            $fileNameIsMatched = preg_match('/[^\?]+\?attname=(.*)/', $fileName, $fileName);
                            if ($fileNameIsMatched) {
                                $fileName = $fileName[1];
                                $filePath = $path . $fileStyle . DIRECTORY_SEPARATOR . $fileName;
                                if (curlHtml($fileUrl, $userAgent, $cookie, null, $filePath)) {
                                    echo '    |-- ', $fileName, PHP_EOL;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

