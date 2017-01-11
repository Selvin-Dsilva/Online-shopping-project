<?php

session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
require("header.php");
require("functions.php");
echo "<h1>Your shopping cart</h1>";
showcart();
if (isset($_SESSION['SESS_ORDERNUM']) == TRUE) {
    $sql = "SELECT * FROM APPEARS_IN WHERE CartID = " . $_SESSION['SESS_ORDERNUM'] . ";";
    $result = mysql_query($sql);
    $numrows = mysql_num_rows($result);
    if ($numrows >= 1) {
        echo "<hr><h2><a href='checklogin-register.php'>Go to the checkout</a></h2>";
    }
}
require("footer.php");
?>
