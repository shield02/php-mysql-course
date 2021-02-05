<?php

    ob_start(); // turn on output buffering

    // Define document directory constants
    // __FILE__ returns the current path to this file
    // dirname() returns the path to the parent directory
    define("FILE_PATH", __FILE__);
    define("SCRIPT_PATH", dirname(FILE_PATH));
    define("PROJECT_PATH", dirname(SCRIPT_PATH));
    define("APP_PATH", PROJECT_PATH . '/app');
    define("LAYOUT_PATH", APP_PATH . '/layouts');

    // Define the root URL for admin
    $admin_end = strpos($_SERVER['SCRIPT_NAME'], '/app/admin') + 10;
    $admin_root = substr($_SERVER['SCRIPT_NAME'], 0, $admin_end);
    define("ADMIN_ROOT", $admin_root);

    // Define the root URL for app
    $app_end = strpos($_SERVER['SCRIPT_NAME'], '/app') + 4; 
    $doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $app_end);
    define("APP_ROOT", $doc_root);


    require_once('functions.php');
    require_once('database_functions.php');
    require_once('validations.php');
    require_once('query_functions.php');

    $db_conn = db_connect(); 
    $errors = [];

