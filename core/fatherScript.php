<?php


class FatherScript
{
    public $pdo;
    public static $instance;
    private $prodaction;

    private $type;
    private $sql_query;
    private $values_for_exec;

    /**
     * __construct()
     * The constructor of the class in which the base variables are initialized.
     * $ prodaction = error mapping
     * $ array_option = array of settings for working with database
     */
    function __construct($prodaction = false, $array_option = array())
    {
        include_once('core/prolog.php');
        $this->sql_query = "";
        $this->values_for_exec = array();
        $this->prodaction = $prodaction;
        $opt = $this->set_option($this->prodaction);
        $this->pdo = new \PDO("mysql:host=localhost;dbname=".DB_NAME,DB_USER,DB_PASSWORD, $opt);
        $this->pdo->exec("SET CHARSET utf8");
    }

    /**
     * Instance()
     * Implementation of the pattern Singleton
     * $production = error display
     * $array_option = array of settings for working with the database
     */

    public static function Instance($prodaction = false, $array_option = array())
    {
        if (self::$instance == NULL) {
            self::$instance = new FatherScript($prodaction);
        }
        return self::$instance;
    }

    /**
     * Select()
     * Getting records from the database
     * $table - table name
     */
    public function select($table)
    {
        $this->sql_query = "SELECT * FROM `$table` ";
        $this->type = 'select';
        return $this;
    }

    /**
     * Insert()
     * Adding records to the database
     * $table - table name
     */
    public function insert($table)
    {
        $this->sql_query = "INSERT INTO `$table` ";
        $this->type = 'insert';
        return $this;
    }

    /**
     * Update()
     * Updating records in the database
     * $table - table name
     */
    public function update($table)
    {
        $this->sql_query = "UPDATE `$table` ";
        $this->type = 'update';
        return $this;
    }

    /**
     * Delete()
     * Deleting records from the database
     * $table - table name
     */
    public function delete($table)
    {
        $this->sql_query = "DELETE FROM `$table`";
        $this->type = 'delete';
        return $this;
    }

    /** order_by($val, $type)
     * Determining the procedure for obtaining records
     * $type - receive order (DESC or ASC)
     */
    public function order_by($val, $type)
    {
        $this->sql_query .= " ORDER BY `$val` $type ";
        return $this;
    }

    /** where($array, $op)
     * Setting the conditions for obtaining records
     * $where - unique variables for selection
     * $op - type of operation, default "="
     */
    public function where($where, $op = '=')
    {
        $vals = array();
        foreach ($where as $k => $v) {
            $vals[] = "`$k` $op :$k";
            $this->values_for_exec[":" . $k] = $v;
        }
        $str = implode(' AND ', $vals);
        $this->sql_query .= " WHERE " . $str . ' ';
        return $this;
    }

    /**
     * limit($from, $count)
     * Setting limits
     * $from - position number
     * $count - the number of entries
     */
    public function limit($from, $count = NULL)
    {
        $res_str = "";
        if ($count == NULL) {
            $res_str = $from;
        } else {
            $res_str = $from . "," . $count;
        }
        $this->sql_query .= " LIMIT " . $res_str;
        return $this;
    }

    /**
     * values()
     * Array of values for changing / adding records to the table
     * $arr_val - associative array of values
     */
    public function values($arr_val)
    {
        $cols = array();
        $masks = array();
        $val_for_update = array();

        foreach ($arr_val as $k => $v) {
            $value_mask = explode(' ', $v);
            $value_mask = $value_mask[0];
            $value_key = explode(' ', $k);
            $value_key = $value_key[0];
            $cols[] = "`$value_key`";
            $masks[] = ':' . $value_key;

            $val_for_update[] = "`$value_key`=:$value_key";
            $this->values_for_exec[":$value_key"] = $v;
        }
        if ($this->type == "insert") {
            $cols_all = implode(',', $cols);
            $masks_all = implode(',', $masks);
            $this->sql_query .= "($cols_all) VALUES ($masks_all)";
        } else if ($this->type == 'update') {
            $this->sql_query .= "SET ";
            $this->sql_query .= implode(',', $val_for_update);
        }

        return $this;
    }

    /**
     * execute()
     * Execution of the generated query
     */
    public function execute()
    {
        $q = $this->pdo->prepare($this->sql_query);
        $q->execute($this->values_for_exec);

        if ($q->errorCode() != PDO::ERR_NONE) {
            $info = $q->errorInfo();
            die($info[2]);
        }
        if ($this->type == "select") {
            $this->set_default();
            return $q->fetchall();
        } else if ($this->type == 'insert') {
            $this->set_default();
            return $this->pdo->lastInsertId();
        } else {
            $this->set_default();
            return true;
        }

    }

    /**
     * get_pdo()
     * Getting a database connection for an arbitrary query
     */
    public function get_pdo()
    {
        return $this->pdo;
    }

    /** set_defaults()
     * set_default values
     */
    private function set_default()
    {
        $this->type = "";
        $this->sql_query = "";
        $this->values_for_exec = array();
    }

    /**
     * set_option()
     * Setting options for working with DB
     * $ prodation - error mapping
     * $ array - array of settings
     */
    private function set_option($prodation, $array = array())
    {
        $opt = array();
        if (!$this->prodaction) {
            if ($array) {
                $opt = $array;
            } else {
                $opt[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                $opt[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
            }
        } else {
            if ($array) {
                $opt = $array;
            } else {
                $opt[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
            }
        }
        return $opt;
    }


}
