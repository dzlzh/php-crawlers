<?php
class rsync
{
    public $locationURL = '';
    public $wapsever = '';
    public $columns = '';
    public $indexDom = '';

    public function __construct($locationURL, $wapsever)
    {
        $this->indexDom = new simple_html_dom();
        $this->locationURL = $locationURL;
        $this->wapsever = $wapsever;
    }

    /**
     * 获取文章列表
     */
    public function getArticleList($colums)
    {
        $this->columns = $colums;
        $allList = array();
        foreach ($this->columns as $srcOldColumns => $srcLocation) {
            $page = $this->getNums($srcLocation);
            for ($pageNumber = 1; $pageNumber <= $page; $pageNumber++) {
                echo "p:$pageNumber\n";
                flush();
                $ii = 0;
                while ($ii < 3) {
                    $pageList = $this->getPageList($srcLocation, $pageNumber);
                    if ($pageList) {
                        break;
                    }
                    sleep(1 * $ii);
                    $ii++;
                }
                if (empty($pageList)) {
                    break;
                }
                foreach ($pageList as $p) {
                    $allList[] = $p;
                }
                sleep(2);
            }
            sleep(1);
        }
        return $allList;
    }

    /**
     * 获取栏目总页数
     */
    public function getNums($location)
    {
        $url = $this->locationURL . '?LocationID=' . $location;
        $data = $this->curlHtml($url);
        if (strpos($data, 'Error') !== false) {
            echo $data . " --- getNums\n";
            exit;
        }
        $indexDom = $this->indexDom->load($data);
        $indexDom = $indexDom->find('.td_pagebar', 0);
        if (empty($indexDom)) {
            return false;
        }
        $pagenum = $indexDom->find('font', 1)->plaintext;
        $indexDom->clear();
        return $pagenum;
    }

    /**
     * 获取栏目文章列表信息
     */
    public function getPageList($srcLocation, $pageNumber)
    {
        $pageNumber = intval($pageNumber);
        if ($pageNumber <= 1) {
            $url = $this->locationURL . '?LocationID=' . $srcLocation;
        } else {
            $url = $this->locationURL . '?p=' . $pageNumber . '&LocationID=' . $srcLocation . '&sub=';
        }
        $data = $this->curlHtml($url);
        if (strpos($data, 'Error') !== false) {
            echo $data . " --- getPageList\n";
            exit;
        }
        $indexDom = $this->indexDom->load($data);
        $indexDom = $indexDom->find('table', 29);
        foreach ($indexDom->find('tr') as $row) {
            $title = trim($row->find('a', 0)->plaintext);
            $title = trim(substr($title, 0, -14), '&nbsp; ');
            $href = $row->find('a', 0)->href;
            $time = $row->find('.td_time', 0)->plaintext;
            $time = trim($time, '[ ]');

            $list[] = array(
                'location' => $srcLocation,
                'title' => $title,
                'href'  => $href,
                'time'  => $time
            );
        }
        $indexDom->clear();
        return $list;
    }
    
    /**
     * 获取文章信息及图片信息
     */
    public function getArticleContent($url)
    {
        $content=array(
            'title'      => '',
            'futi' => '',
            'author'     => '',
            'times'    => '',
            'content'    => '',
            'imageurl'   => '',
            'se_author'  => '',
            'w_from'     => '',
        );
        $articleurl = $this->wapsever . $url;
        $data = $this->curlHtml($articleurl);
        if (strpos($data, 'Error') !== false) {
            echo $data . " --- getPageList\n";
            exit;
        }
        $indexDom = $this->indexDom->load($data);
        $indexDom = $indexDom->find('table', 12);
        if (empty($indexDom)) {
            return false;
        }
        $content['title'] = $indexDom->find('b', 0)->plaintext;
        $content['futi'] = $indexDom->find('p', 1)->plaintext;
        $time = $indexDom->find('p', 2)->plaintext; //文章发布时间，作者
        preg_match('/作者/', $time, $regs);
        if (!empty($regs[0])) {
            $content['author'] = substr($time, 9, -46);
        }

        preg_match_all('#(\d{2}|\d{4})(?:\-)?([0]{1}\d{1}|[1]{1}[0-2]{1})(?:\-)?([0-2]{1}\d{1}|[3]{1}[0-1]{1})(?:\s)?([0-1]{1}\d{1}|[2]{1}[0-3]{1})(?::)?([0-5]{1}\d{1})(?::)?([0-5]{1}\d{1})#i',$time,$regtime);
        if (!empty($regtime)) {
            $content['times'] = $regtime[0][0];
        }
        $dir = $indexDom->find('dir',0);
        if (empty($dir)) {
         return false;   
        }
        $dir->setAttribute('style','text-align:right;margin-right:20px;');

        $content['content'] = $indexDom->find('.detail_content',0)->outertext;//文章内容
        $img = $indexDom->find('img', 0);
        if (!empty($img)) {
            foreach ($indexDom->find('img') as $result) {
                $imageurl[] = $this->wapsever . $result->src;
            }
            $content['imageurl'] = $imageurl;
        }

        foreach ($indexDom->find('td') as $bjDom) {
            $bj = $bjDom->last_child();
            if (empty($bj)) {
                break;
            }
            $bianji = $bj->plaintext;
            preg_match("/编辑：/", $bianji, $regauthor);
            if (!empty($regauthor[0])) {
                $content['se_author'] = trim(substr($bianji, 9),'&nbsp;');
            }
            preg_match("/文章出处：/", $bianji, $regwfrom);
            if (!empty($regwfrom[0])) {
                $content['w_from'] = trim(substr($bianji, 15),'&nbsp;');
            }
        }
        return $content;
    }
    

    
    public function curlHtml($url, $param = null, $cookie = null, $userAgent = null)
    {

        for ($i = 0; $i < 3; $i++) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            if($userAgent != null) {
                curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
            }
            if($param != null) {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
            }
            if($cookie != null) {
                curl_setopt($curl, CURLOPT_COOKIE, $cookie);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);
            if($info['http_code'] == 200) {
                return iconv('gbk', 'utf-8//TRANSLIT', $data);
            }
            sleep(1 * $i);
        }
        $error = 'Error::http_code:' . $info['http_code'] . ';url:' . $info['url'];
        if (is_array($param)) {
            $error .= ';param:' . '(' . serialize($param) . ')';
        }
        return $error;
    }
}
