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
            $this->con = new PDO($dsn, $dbconfig['dbuser'], $dbconfig['dbpwd']);
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
    public function insert($table, $param, $insertID = true)
    {
        $fields = array_keys($param);
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
    
    
    
}



require_once 'config.php';
$pdo = new PDOMysql();
$pdo->connect($dbconfig);
var_dump($pdo->findOne('select UUID();'));
