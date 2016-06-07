<?PHP
/**
 *  +--------------------------------------------------------------
 *  | Copyright (c) 2016 DZLZH All rights reserved.
 *  +--------------------------------------------------------------
 *  | Author: DZLZH <dzlzh@null.net>
 *  +--------------------------------------------------------------
 *  | Filename: PDOMysql.class.php
 *  +--------------------------------------------------------------
 *  | Last modified: 2016-06-03 17:00
 *  +--------------------------------------------------------------
 *  | Description: 
 *  +--------------------------------------------------------------
 */


class PDOMysql
{
    private $con = null;

    /**
     * connect
     *
     * @return $con
     */
    public function connect($dbconfig)
    {
        $dsn = $dbconfig['type'] . ':host=' . $dbconfig['host'] . ';port=' . $dbconfig['port'] . ';dbname=' . $dbconfig['dbname'];
        try {
            $this->con = new PDO($dsn, $dbconfig['dbuser'], $dbconfig['dbpwd'], array(PDO::ATTR_PERSISTENT => true));
            $setcharset = 'set names ' . $dbconfig['dbcharset'];
            $this->con->exec($setcharset);
            return true;
        } catch (PDOException $e) {
            if($dbconfig['dbdebug']) {
                echo $e->getMessage();
            }
            return false;
        }
    }

    /**
     * query
     *
     * @return $stmt
     */
    private function query($sql, $param)
    {
        $stmt = $this->con->prepare($sql);
        if (is_array($param)) {
            foreach ($param as $key => $value) {
                $stmt->bindValue($key, $value);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    /**
     * findAll
     *
     * @return void
     */
    public function findAll($sql, $param = null)
    {
        $stmt = $this->query($sql, $param);
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    /**
     * findOne
     *
     * @return void
     */
    public function findOne($sql, $param = null)
    {
        $stmt = $this->query($sql, $param);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * insert
     *
     * @return void
     */
    public function insert($table, $param, $insertID = false)
    {
        $keys = array_keys($param);
        $fields = $keys;
        array_walk($fields, array('PDOMysql', 'addSpecialChar'));
        $fields = implode(',', $fields);
        $parameters = $keys;
        foreach ($parameters as $key => $value) {
            $parameters[$key] = ':' . $value;
        }
        $parameters = implode(',', $parameters);
        $sql = 'INSERT INTO `' . $table . '` (' . $fields . ') VALUE (' . $parameters . ')';
        $stmt = $this->query($sql, $param);
        if ($insertID) {
            return $this->con->lastInsertId();
        } else {
            return self::errorMsg($stmt);
        }
        
    }

    /**
     * update
     *
     * @return void
     */
    public function update($table, $param, $where = null)
    {
        $keys = array_keys($param);
        $fields = $keys;
        array_walk($fields, array('PDOMysql', 'addSpecialChar'));
        foreach ($keys as $key => $value) {
            $parameters[$key] = $fields[$key] . '=:' . $value;
        }
        $parameters = implode(',', $parameters);
        $sql = 'UPDATE `' . $table . '` SET ' . $parameters;
        if (!empty($where)) {
            $sql .= ' WHERE ' . $where;
        }
        $stmt = $this->query($sql, $param);
        return self::errorMsg($stmt);
        
    }

    /**
     * del
     *
     * @return void
     */
    public function del($table, $where = null)
    {
        $sql = 'DELETE FROM `' . $table . '`';
        if (!empty($where)) {
            $sql .= ' WHERE ' . $where;
        }
        $stmt = $this->query($sql, null);
        return self::errorMsg($stmt);
    }
    
    
    /**
     * addSpecialChar
     *
     * @return param
     */
    private function addSpecialChar(&$param)
    {
        if ($param !== '*' || strpos($param, '.') === false || strpos($param, '`') === false) {
            $param = '`' . trim($param) . '`';
        }
        return $param;
    }

    /**
     * errorMsg
     *
     * @return void
     */
    private function errorMsg($stmt)
    {
        $errorCode = $stmt->errorCode();
        if ($errorCode !== '00000') {
            $errorInfo = $stmt->errorInfo();
            $errorMsg = 'ERROR ' . $errorInfo[1] . ' (' . $errorInfo[0] . '):' . $errorInfo[2];
            return $errorMsg;
        } else {
            return $stmt->rowCount();
        }
    }
    
    
    
    
}



require_once 'config.php';
date_default_timezone_set('Asia/Shanghai');
$pdo = new PDOMysql();
$pdo->connect($dbconfig);
$param = array(
    'uuid'              => 'UUID',
    'positionId'        => '1',
    'positionName'      => '职位名称',
    'positionType'      => '职位类型',
    'positionAdvantage' => '职位诱惑',
    'companyName'       => '公司名称',
    'companyShortName'  => '公司简称',
    'companySize'       => '公司规模',
    'companyHome'       => '公司主页',
    'industryField'     => '行业领域',
    'financeStage'      => '融资阶段',
    'city'              => '城市',
    'district'          => '区域',
    'businessZone'      => '商业区',
    'address'           => '具体地址',
    'salary'            => '薪水',
    'workYear'          => '工作经验',
    'education'         => '学历要求',
    'jobNature'         => '工作性质',
    'jobDescription'    => '职位描述',
    'createTime'        => date("Y-m-d H:i:s"),
    'collectionTime'    => date("Y-m-d H:i:s"),
);   
// var_dump($pdo->findOne('select UUID();'));
// var_dump($pdo->insert('lagou',$param));
// var_dump($pdo->del('lagou'));
