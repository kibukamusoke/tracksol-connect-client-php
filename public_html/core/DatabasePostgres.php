<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/27/18
 * Time: 1:28 AM
 */

class DatabasePostgres
{
    public $connect, $query;
    private $DB_host, $DB_user, $DB_pass, $DB_name;
    public $DB_prefix;

    // Construct the information we need
    public function __construct($_config)
    {
        $this->DB_prefix = $_config['DB_prefix'];
        $this->DB_host = "host=" . $_config['DB_host'];
        $this->DB_user = "user=" . $_config['DB_user'];
        $this->DB_pass = "password=" . $_config['DB_pass'];
        $this->DB_name = "dbname=" . $_config['DB_name'];
        $this->DB_port = "port=" . $_config['DB_port'];
        $this->DB_Conn = "$this->DB_host $this->DB_port $this->DB_name $this->DB_user $this->DB_pass";
    }

    // The Conection function
    public function connect()
    {
        $this->connect = pg_connect($this->DB_Conn);
        if (!$this->connect) {
            die('Can\'t Connect The Server');
        } else {
            $this->query("SET NAMES 'utf8'");
        }
    }

    // Select The DataBase
    public function select_db()
    {
        $select_db = pg_dbname($this->connect);
        if (!$select_db) {
            die('The DataBase (' . $this->DB_name . ') Not exists');
        }
    }

    // Close the Conection $this->connect;
    public function close()
    {
        $close = pg_close($this->connect);
        return $close;
    }

    // PGSQL Queries
    public function query($sqlquery)
    {
        $sqlquery = str_ireplace("pex_", $this->DB_prefix, $sqlquery);
        $query = pg_query($this->connect, $sqlquery);
        if (!$query)
            die($sqlquery . '<br><br>' . pg_errormessage($this->connect));
        else
            return $query;
    }

    // Select Query
    public function select($table, $fields = '*', $where = '', $orderby = '', $limit = '')
    {

        $query = "SELECT " . trim($fields) . " FROM " . $this->DB_prefix . "" . trim($table) . "";
        if ($where != '') {
            $query .= " WHERE " . trim($where);
        }
        if ($orderby != '') {
            $query .= " ORDER BY " . trim($orderby);
        }
        if ($limit != '') {
            $query .= " LIMIT " . trim($limit);
        }
        $select = $this->query($query);
        return $select;
    }

    // Fetch Functions
    public function fetch_assoc($query)
    {
        $fetch = pg_fetch_assoc($query);
        return $fetch;
    }

    public function fetch_array($query)
    {
        $fetch = pg_fetch_array($query);
        return $fetch;
    }

    public function fetch_object($query)
    {
        $fetch = pg_fetch_object($query);
        return $fetch;
    }

    // Count the result from the query
    public function num_rows($query)
    {
        $num = pg_num_rows($query);
        return $num;
    }

    // Clean the Memory from orders
    public function free_result($query)
    {
        $free = pg_free_result($query);
        return $free;
    }

    public function delete($table, $where)
    {
        $delete = $this->query("DELETE FROM " . $this->DB_prefix . "" . $table . " WHERE " . $where . "");
        return $delete;
    }

    // escape string function to protect from SQL Injection
    public function escape_string($str)
    {
        $escape = pg_escape_string($this->connect, $str);
        return $escape;
    }

    public function insert($table, $data)
    {
        if (is_array($data)) {
            $fields = "" . implode(",", array_keys($data)) . "";
            $values = implode("','", $data);
            $query = "INSERT INTO " . $this->DB_prefix . $table . " (" . $fields . ") VALUES ('" . $values . "')";
            return $this->query($query);
        } else {
            return $data . ' is not an array';

        }
    }

    public function insert_returning($table, $data, $column_to_return)
    {
        if (is_array($data)) {
            $fields = "" . implode(",", array_keys($data)) . "";
            $values = implode("','", $data);
            $query = "INSERT INTO " . $this->DB_prefix . $table . " (" . $fields . ") VALUES (" . $values . ") RETURNING " . $column_to_return;
            return $this->query($query);
        } else {
            die($data . ' is not an array');
        }
    }

    public function update($table, $fields, $where)
    {
        array_walk($fields, create_function('&$i,$k','$i=", $k=\"$i\"";'));
        $xFields = implode($fields,"");
        $xFields = substr($xFields,1);
        return $this->query("UPDATE `".$this->DB_prefix.$table."` SET ".$xFields." WHERE ".$where."");
    }

    public function lastID()
    {
        return pg_get_result($this->connect);
    }
}

?>
