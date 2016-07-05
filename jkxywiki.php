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
        echo makeDirectory($path . 'PDF') && makeDirectory($path . 'ePub') ? $path . ' -- successful' . PHP_EOL : $path . ' -- failure' . PHP_EOL;
        $projectListUrl = $jkxyWikiUrl . $v;
        $projectListHtml = curlHtml($projectListUrl, $userAgent);
        $projectListIsMatched = preg_match_all('/<a\sclass="cell\scf"\shref="([^"]+)"\starget="_blank">/', $projectListHtml, $projectList);
        if ($projectListIsMatched) {
            foreach ($projectList[1] as $key => $value) {
                $projectHtml = curlHtml($value, $userAgent, $cookie);
                $pdfIsMatched = preg_match('/<a\shref="([^"]+)"\starget="_blank"\sclass="download-pdf\sblue-btn">/', $projectHtml, $pdfDownloadUrl);
                $pdfDownloadUrl = $jkxyWikiUrl . trim($pdfDownloadUrl[1]);
                $pdf = curlHtml($pdfDownloadUrl, $userAgent, $cookie);
            }
        }
        die;
    }
}
