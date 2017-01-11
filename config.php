<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "root";
$dbdatabase = "NEWARK_IT";
$config_basedir = "http://localhost/PhpProject1/";
$config_sitename = "NewarkIT";
$db = mysql_connect($dbhost, $dbuser, $dbpassword);
mysql_select_db($dbdatabase, $db);
?>

