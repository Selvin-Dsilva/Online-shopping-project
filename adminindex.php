<?php
//session_start();
ob_start();
require('config.php');

if (isset($_SESSION['SESS_LOGGEDIN']) == TRUE) {
    header("Location: " . $config_basedir);
}

if ($_POST['submit']) {
    $loginres = mysql_query("SELECT * FROM LOGINS where username = '$_POST[userBox]' AND password = '$_POST[passBox]'");
    $numrows = mysql_num_rows($loginres);
    $loginrow = mysql_fetch_assoc($loginres);
    if ($numrows == 1) {
        $statusres = mysql_query("SELECT * FROM CUSTOMER where CID =" . $loginrow['CID']);
        $statusrow = mysql_fetch_assoc($statusres);
        if ($statusrow['Status'] == 'admin') {
            header("location: report.php");
        }
    } else {
        header("location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?error=1");
        exit();
    }
} else {
    ?>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    <div id="header"><h1><?php echo $config_sitename; ?></h1></div>
    <div id="menu" align="left">
        &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $config_basedir; ?>index.php">Home</a>
    </div>

    <div id="container">
        <h1>Administrator Login</h1>
        <?php
        if (isset($_GET['error'])) {
            //if ($error == 1) {
            echo "<br><strong>Incorrect username/password</strong>";
        }
        ?>
        <form action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="POST">
            <table>
                <tbody>
                    <tr>
                        <td>Username</td>
                        <td><input type="text" name="userBox" /></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type="password" name="passBox" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="submit" value="Log in" /></td>
                    </tr>
                </tbody>
            </table>    
        </form>
        <?php
    }
    require("footer.php");
    echo '<div id="main">';
    ?>