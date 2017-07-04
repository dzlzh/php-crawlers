<?php
ini_set("memory_limit", "-1");
ini_set("max_exection_time", 0);

include_once 'config.php';
include_once 'simple_html_dom.php';
include_once 'rsync.class.php';

$db = $config['db'];
$conn = mysql_connect($db['host'], $db['user'], $db['pwd']) or die("Couldn't conn mysql:" . mysql_error());
if ($conn) {
    mysql_select_db($db['dbname'], $conn) or die("Couldn't conn database:" . mysql_error());
}

$olddirect = $columns;
$repdirect = $direct;

$article = new rsync($locationURL, $wapsever);
$list = $article->getArticleList($olddirect);

foreach ($list as $value) {
    sleep(2);
    $title = $value['title'];
    $times = $value['time'];
    $location = $repdirect[$value['location']];
    
    if (empty($location)) {
        echo "couldn't find location.$location \n";
        continue;
    }

    $sqlSelect = "SELECT auto_id FROM article WHERE title='" . iconv("UTF-8","gbk//TRANSILT", $title) . "' AND times like '{$times}%'";
    $res = mysql_query($sqlSelect);
    $rows = @mysql_fetch_row($res);
    if (!empty($rows[0])) {
        $sqlInsert = "INSERT INTO article_location SET location_id='{$location}',article_id={$rows[0]}";
        if (mysql_query($sqlInsert)) {
            echo $title . "--" . $location . "rewrite in arctile_location!\n";
        }else{
            echo $title . "--" . $location . "rewrite false!\n";	
        }
        continue;
    }

    $href = $value['href'];
    if (preg_match("((http|https)://)", $href)) {
        $futi = '<script>window.location.href="' . $href . '"</script>';
        $sqlInsert = "INSERT INTO article SET info_flag=10,
            own_id=1,
            main_location='{$location}',
            title='" . iconv("UTF-8","gbk//TRANSILT", $title) . "',
            futi ='{$futi}',
            times='{$times}',
            reg_date='{$times}',
            is_html=0";
        if (mysql_query($sqlInsert)) {
            $insertId = mysql_insert_id();
            $reslocation = mysql_query("INSERT INTO article_location SET article_id='{$insertId}',location_id='{$location}'");
            if (!$reslocation) {
                echo "Insert article_location mistake ----" . $title . "--" . $location . "\n";
                continue;
            }
        } else {
            echo $sqlInsert;
            echo "Insert article mistake ----" . $title . "--" . $location . "\n";
            continue;
        }
    } else {
        $content = $article->getArticleContent($href);
        if (empty($content['times'])) {
            $content['times'] = $times;
        }
        $sqlInsert = "INSERT INTO article SET info_flag=10,
            own_id=1,
            main_location='{$location}',
            title='" . iconv("UTF-8","gbk//TRANSILT", $content['title']) . "',
            futi='" . iconv("UTF-8","gbk//TRANSILT", $content['futi']) . "',
            author='" . iconv("UTF-8","gbk//TRANSILT", $content['author']) . "',
            times='{$content['times']}',
            content='" . mysql_real_escape_string(iconv("UTF-8","gbk//TRANSILT", $content['content'])) . "',
           	se_author='" . iconv("UTF-8","gbk//TRANSILT", $content['se_author']) . "',
            w_from='" . iconv("UTF-8","gbk//TRANSILT", $content['w_from']) . "',
			reg_date='{$content['times']}',
			is_html=0
            ";
        if (mysql_query($sqlInsert)) {
            $insertID = mysql_insert_id();
            $reslocation = mysql_query("INSERT INTO article_location SET article_id='{$insertID}', location_id='{$location}'");
            if (!$reslocation) {
                echo "Insert article_location mistake ----" . $titlle . "--" . $location . "\n";
                continue;
            }
            if (count($content['imageurl']) >= 1 && empty($content['imageurl']) === false) {
                foreach ($content['imageurl'] as $url) {
                    if ($url != "") {
                        preg_match_all('#http:\/\/jgsfy.chinacourt.org\/public\/showimage.php\?id=([0-9]*)#i', $url, $aid);
                        $imgid = $aid[1][0];
                        $mypicture = mysql_real_escape_string(file_get_contents($url));
                        $insertImage = "INSERT INTO images SET id='{$imgid}',
                            info_id='{$insertID}',
                            types=2,
                            images='{$mypicture}',
                            reg_date='{$content['times']}'";
                    }

                    if (mysql_query($insertImage)) {
                        mysql_query("INSERT INTO image_location SET image_id='{$imgid}',location_id='{$location}',article_id='{$insertID}',image_type='2'");
                    } else {
                        echo "Insert image_location mistake ----" . $title . "--" . $location . "--" . $url . "\n";
                        continue;
                    }
                }
            } else {
                echo $title . "no picture\n";
            }
            flush();
        }
    }
}
