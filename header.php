
<?php
session_start();
if (isset($_SESSION['SESS_CHANGEID']) == TRUE) {
    session_unset();
    session_regenerate_id();
}
require("config.php");
?>
<head>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<div id="header"><h1><?php echo $config_sitename; ?></h1></div>
<div id="menu" align="left">
    &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $config_basedir; ?>index.php">Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="<?php echo $config_basedir; ?>showcart.php">ViewBasket/Checkout</a>
</div>

<div id="container">
    <div id="bar">
        <?php
        require("bar.php");
        echo"<hr/>";
        if (isset($_SESSION['SESS_LOGGEDIN']) == TRUE) {
            $query = mysql_query("SELECT Status FROM CUSTOMER WHERE CID=" . $_SESSION['SESS_USERID']);
            $queryrow = mysql_fetch_assoc($query);
            if ($queryrow['Status'] == "gold") {
                echo 'Logged in as <strong>' . $_SESSION['SESS_USERNAME'] . '</strong> (Gold Member)<a href="logout.php"><br>Logout</a>';
            } elseif ($queryrow['Status'] == "platinum") {
                echo 'Logged in as <strong>' . $_SESSION['SESS_USERNAME'] . '</strong> (Platinum Member)  <a href="logout.php"><br>Logout</a>';
            } else {
                echo 'Logged in as <strong>' . $_SESSION['SESS_USERNAME'] . '</strong><a href="logout.php"><br>Logout</a>';
            }
        } else {
            echo '<a href="login.php">Login/Register</a>';
        }
        ?>
    </div>
    <div id="main">

