<?php

$db_host = 'localhost'; //database host
$db_user = 'root';//database username
$db_pwd = '';//database password
$db_database = 'db_products'; //database name

//connect to database
$connection = mysqli_connect($db_host, $db_user, $db_pwd) or die('Connection failed to mysql database. Check the database host, username and password.');

if (!$connection) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

//select database
mysqli_select_db($connection, $db_database) or die("Database could not be selected.");

?>