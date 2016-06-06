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
        $keys = array_keys($param);
        var_dump($keys);
        $fields = $keys;
        array_walk($fields, array('PDOMysql', 'addSpecialChar'));
        $fields = implode(',', $fields);
        $parameters = $keys;
        foreach ($parameters as $key => $value) {
            $parameters[$key] = ':' . $value;
        }
        $parameters = implode(',', $parameters);
        $sql = 'INSERT INTO `' . $table . '` (' . $fields . ') VALUE (' . $parameters . ')';
        echo $sql;
        var_dump($fields);
        var_dump($parameters);
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
$param = array(
    // '.' => '',
    // '*' => '',
    // '``' => '',
    'type'      => 'mysql',
    'host'      => '',
    'port'      => '3306',
    'dbname'    => '',
    'dbuser'    => '',
    'dbpwd'     => '',
    'dbcharset' => 'utf8',
    'dbdebug'   => true,
);   
// var_dump($pdo->findOne('select UUID();'));
$pdo->insert('1',$param);
