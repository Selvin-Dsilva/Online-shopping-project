<?php

session_start();
ob_start();
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
require("config.php");
require('header.php');
$itemsql = "SELECT * FROM APPEARS_IN WHERE CartID = " . $_SESSION['SESS_ORDERNUM'] . ";";
$itemres = mysql_query($itemsql) or die(mysql_error());
$numrows = mysql_num_rows($itemres);
if ($numrows == 0) {
    header("Location: showcart.php");
}
$itemrow = mysql_fetch_assoc($itemres);
$prodsql = "SELECT PPrice FROM PRODUCT WHERE PID = " . $itemrow['PID'] . ";";
$prodres = mysql_query($prodsql)or die(mysql_error());
$prodrow = mysql_fetch_assoc($prodres);
$sql = "DELETE FROM APPEARS_IN WHERE PID = " . $_GET['id'] . " AND CartID =" . $_SESSION['SESS_ORDERNUM'] . ";";
$del = mysql_query($sql)or die(mysql_error());
if ($del) {
    header("Location: showcart.php");
}
require('footer.php');
?>
