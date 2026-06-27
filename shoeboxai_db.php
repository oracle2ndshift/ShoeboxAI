<?php
// filename shoeboxai_db.php
// purpose  set up database connection, handle 2 mysql versions
// usage    include "shoeboxai_db.php";
// history  
// 	swhite	25-may-14	created
//
// database set up
//
  $db_host = 'localhost';
  $db_user = 'dba';
  $db_pwd  = 'ground0';
  $db_name = 'ShoeboxAI';
  // $version = '8.0';
//
// connection: v5 or 8
//
    if (function_exists('mysql_query')) {
    if (!mysql_connect($db_host, $db_user, $db_pwd)) die("Can't connect to $db_name") ;
    if (!mysql_select_db($db_name)) die("Can't select $db_name") ;
    $version = mysql_query('SELECT VERSION()')->fetchColumn();
  } else {
    $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false // Set to true or false
];
    $pdo = new PDO ("mysql:host = $db_host ; dbname = $db_name", $db_user, $db_pwd, $options) or die ("Error");
    $mysqli = new mysqli("localhost", $db_user, $db_pwd, $db_name);
    $dp = mysqli_connect("localhost", $db_user, $db_pwd, $db_name);
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
  }

  return [
    'version' => $version[0],
    'dp'      => $dp
];

?>

