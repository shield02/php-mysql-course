<?php
    require_once('database_credentials.php');

    function db_connect() 
    {
        $dsn = 'mysql:dbname=' . DB_NAME;'host=' . DB_SERVER;
        $user = DB_USER;
        $pass = DB_PASS;

        try {
            $db = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        return $db;
    }

    function db_disconnect($db) 
    {
        if (isset($db)) {
            $db = null;
        }
    }

    function confirm_result_set($sql, $stmt, $result="") 
    {
        if (!$result) {
            exit("Database query failed. " . $sql . " " . $stmt->errorInfo());
        }
    }


