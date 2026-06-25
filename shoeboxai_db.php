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
  $version = '8.0';
//
// connection: v5.0 or 5.2 or 8.0
//
  if ($version == "5.0") {
    if (!mysql_connect($db_host, $db_user, $db_pwd)) die("Can't connect to $db_name") ;
    if (!mysql_select_db($db_name)) die("Can't select $db_name") ;
  } else {
    $pdo = new PDO ("mysql:host = $db_host ; dbname = $db_name", $db_user, $db_pwd) or die ("Error");
  }
?>

