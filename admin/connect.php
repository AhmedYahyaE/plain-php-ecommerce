<?php
// Database Connection

// $dsn  = 'mysql:host=localhost;dbname=shop'; // Data Source Name
// $dsn  = 'mysql:host=localhost;port=3306;dbname=shop'; // Data Source Name    // Specifying the MySQL Port Number
$dsn  = 'mysql:host=localhost;dbname=shop'; // Data Source Name    // Specifying the MySQL Port Number
$user = 'root';
$pass = '';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' 
);

try {
    $con = new PDO($dsn, $user, $pass, $options);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // to activate the Exception error handling mode
    // echo 'You Are Connected To the Database!<br>';
} catch (PDOException $e) { // $e is a PDOException class object which contains the error
    echo 'Failed To Connect To Database: ' . $e->getMessage() . '<br>';
}