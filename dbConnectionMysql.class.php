<?php

class dbConnectionMysql
{

    protected $username;

    protected $password;

    protected $host;

    protected $database;

    protected static $connectionInstance = null;

    public function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->connect();
    }

    public function __destruct()
    {
        self::$connectionInstance = null;
    }

    public function connect()
    {
        if (self::$connectionInstance === null) {
            try {
                $dsn = "mysql:host=$this->host;dbname=$this->database";
                self::$connectionInstance = new PDO($dsn, $this->username, $this->password);
                self::$connectionInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connectionInstance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (Exception $ex) {
                echo "ERROR: " . $ex->getMessage();
            }
        }
        return self::$connectionInstance;
    }

    /**
     *
     * @param string $sql            
     * @param array $param            
     * @return execute()
     */
    public function query($sql, $param = array())
    {
        $q = self::$connectionInstance->prepare($sql);
        if (is_array($param) && $param) {
            $q->execute($param);
        } else {
            $q->execute();
        }
        return $q;
    }

    /**
     *
     * @param string $q            
     * @param array $params            
     * @param rows/count $return            
     */
    function sql($q, $params = array(), $return = "rows")
    {
        $stmt = $this->query($q, $params);
        if ($return == "rows") {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($return == "row") {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (isset($result[0])) {
                return $result[0];
            }
        } elseif ($return == "count") {
            return $stmt->rowCount();
        }
        return false;
    }
}
