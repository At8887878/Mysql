<?php

header('Content-Type:text/html;charset=utf-8');
require_once('config/global.config.php');

/**
 * author At
 * time 2023-05-15
 */

class DB {
    private $conn;
    private $table;

    function __construct($table_name) {
        global $config;
        $mysql_config = $config['Mysql'];
        $this->table = $table_name;

        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $this->conn = new mysqli($mysql_config['host'], $mysql_config['user'], $mysql_config['password'], $mysql_config['database']);
        } catch (mysqli_sql_exception $e) {

            echo "数据库连接失败,请开启log查看报错信息!!!";

            // 记录错误消息
            $message = mb_convert_encoding("无法连接到数据库,错误信息: ", 'GBK', 'UTF-8'). $e->getMessage();
            addLog("\n".date("[Y-m-d H:i:s]").":\n". $message,'/sqlConError_' . date('Y-m-d') . '.txt');
            exit;
        }
        

    }

    function __destruct() {
        // 销毁
        if ($this->conn instanceof mysqli) {
            mysqli_close($this->conn);
        }
    }

    /**
     * 查询所有数据
     */
    function select($columns = '*', $where = '') {
        $sql = "SELECT $columns FROM $this->table";
        $sql .= " WHERE " . $this->parseWhere($where);
        try {
            $result = $this->conn->query($sql);
        } catch (mysqli_sql_exception $e) {
            $message = mb_convert_encoding("数据库操作失败,原因: ", 'GBK', 'UTF-8'). $this->conn->error;
            addLog("\n".date("[Y-m-d H:i:s]").":\n". $message, '/sqlOpError_' . date('Y-m-d') . '.txt');
            return null;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * 查询单条数据
     */
    function an($columns = '*', $where = ''){
        $sql = "SELECT $columns FROM $this->table";
        $sql .= " WHERE " . $this->parseWhere($where);
        $sql .= ' limit 1 ';
        try {
            $result = $this->conn->query($sql);
        } catch (mysqli_sql_exception $e) {
            $message = mb_convert_encoding("数据库操作失败,原因: ", 'GBK', 'UTF-8'). $this->conn->error;
            addLog("\n".date("[Y-m-d H:i:s]").":\n". $message, '/sqlOpError_' . date('Y-m-d') . '.txt');
            return null;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data[0];
    }

    /**
     * 插入数据(默认)
     * 返回数据(添加成功的条数)
     */
    function insert($params){
        if (!$params) {
            return null;
        }
        $columns = array();
        $values = array();
        foreach ($params as $key => $value) {
            $columns[] = $key;
            $values[] = $this->conn->real_escape_string($value);
        }
        $columns = implode(', ', $columns);
        $values = "'" . implode("', '", $values) . "'";
        $sql = "INSERT INTO $this->table ($columns) VALUES ($values)";
        try {
            $result = $this->conn->query($sql);
        } catch (mysqli_sql_exception $e) {
            $message = mb_convert_encoding("数据库操作失败,原因: ", 'GBK', 'UTF-8'). $this->conn->error;
            addLog("\n".date("[Y-m-d H:i:s]").":\n". $message, '/sqlOpError_' . date('Y-m-d') . '.txt');
            return null;
        }
        return $this->conn->affected_rows;
    }
    
    /**
     * 插入数据
     * 返回数据(返回自增id)
     */
    function insertGetId($params){
        if (!$params) {
            return null;
        }
        $columns = array();
        $values = array();
        foreach ($params as $key => $value) {
            $columns[] = $key;
            $values[] = $this->conn->real_escape_string($value);
        }
        $columns = implode(', ', $columns);
        $values = "'" . implode("', '", $values) . "'";
        $sql = "INSERT INTO $this->table ($columns) VALUES ($values)";
        try {
            $result = $this->conn->query($sql);
        } catch (mysqli_sql_exception $e) {
            $message = mb_convert_encoding("数据库操作失败,原因: ", 'GBK', 'UTF-8'). $this->conn->error;
            addLog("\n".date("[Y-m-d H:i:s]").":\n". $message, '/sqlOpError_' . date('Y-m-d') . '.txt');
            return null;
        }
        return $this->conn->insert_id;
    }

    /**
     * 插入数据(多条)
     * 返回数据(添加成功的条数)
     */
    function insertAll($params){
        if (!$params) {
            return null;
        }
        $columns = implode(',', array_keys($params[0]));
        $values = '';
        foreach ($params as $key => $value) {
            $values .= '(';
            foreach ($value as $key1 => $value1) {
                $values .= "'" . $this->conn->real_escape_string($value1) . "',";
            }
            $values = rtrim($values, ',') . '),';
        }
        $values = rtrim($values, ',');
        $sql = "INSERT INTO $this->table ($columns) VALUES $values";
        try {
            $result = $this->conn->query($sql);
        } catch (mysqli_sql_exception $e) {
            $message = mb_convert_encoding("数据库操作失败,原因: ", 'GBK', 'UTF-8'). $this->conn->error;
            addLog("\n".date("[Y-m-d H:i:s]").":\n". $message, '/sqlOpError_' . date('Y-m-d') . '.txt');
            return null;
        }
        return $this->conn->affected_rows;
    }

    /**
     * 更新数据
     * 返回数据(更新成功的条数)
     */
    function update($params, $where = ''){
        if (!$params || !$where) {
            return null;
        }
        $values = '';
        foreach ($params as $key => $value) {
            $values .= "$key = '" . $this->conn->real_escape_string($value) . "',";
        }
        $values = rtrim($values, ',');
        $sql = "UPDATE $this->table SET $values";
        $sql .= " WHERE " . $this->parseWhere($where);
        try {
            $result = $this->conn->query($sql);
        } catch (mysqli_sql_exception $e) {
            $message = mb_convert_encoding("数据库操作失败,原因: ", 'GBK', 'UTF-8'). $this->conn->error;
            addLog("\n".date("[Y-m-d H:i:s]").":\n". $message, '/sqlOpError_' . date('Y-m-d') . '.txt');
            return null;
        }
        return $this->conn->affected_rows;
    }

    /**
     * 删除数据
     * 
     */
    function delete($where = ''){
        if (!$where) {
            return null;
        }
        $sql = "DELETE FROM $this->table";
        $sql .= " WHERE " . $this->parseWhere($where);
        try {
            $result = $this->conn->query($sql);
        } catch (mysqli_sql_exception $e) {
            $log_dir = __DIR__ . '/log';
            $message = mb_convert_encoding("数据库操作失败,原因: ", 'GBK', 'UTF-8'). $this->conn->error;
            addLog("\n".date("[Y-m-d H:i:s]").":\n". $message, '/sqlOpError_' . date('Y-m-d') . '.txt');
            return null;
        }
        return $this->conn->affected_rows;
    }

    function getBySql($sql = '') {
        
        $pattern = '/^(\s)*(SELECT|INSERT|UPDATE|DELETE|REPLACE|ALTER|CREATE|DROP|TRUNCATE|LOAD\s+DATA|COPY\s+|GRANT|REVOKE|LOCK|UNLOCK)(\s)/i';
        // 为空||不为sql语句
        if (!$sql || !preg_match($pattern, $sql)) {
            return null;
        }
        try {
            $result = $this->conn->query($sql);
        } catch (mysqli_sql_exception $e) {
            $message = mb_convert_encoding("数据库操作失败,原因: ", 'GBK', 'UTF-8'). $this->conn->error;
            addLog("\n".date("[Y-m-d H:i:s]").":\n". $message, '/sqlOpError_' . date('Y-m-d') . '.txt');
            return null;
        }
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * 解析查询的数组条件
     */
    private function parseWhere($where) {
        if (is_array($where)) {
            $conditions = array();
            foreach ($where as $key => $value) {
                $conditions[] = "$key = '" . $this->conn->real_escape_string($value) . "'";
            }
            return implode(' AND ', $conditions);
        } else {
            return $where;
        }
    }
}

/**
 * 日志模式
 */
function addLog($text, $path){
    global $config;

    // 日志是否开启
    if (!$config['LOG_MODE']) {
        return false;
    }

    // 是否存在文件夹
    $log_dir = $config['LOG_PATH'];
    !file_exists($log_dir) && mkdir($log_dir, 0777, true);

    error_log($text, 3, $log_dir . $path);

    return true;
}

