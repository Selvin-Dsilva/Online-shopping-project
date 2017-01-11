<?php
session_start();
ob_start();
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
require('config.php');

if (isset($_SESSION['SESS_LOGGEDIN']) == TRUE) {
    header("Location: " . $config_basedir);
}

if ($_POST['submit']) {

    $loginres = mysql_query("SELECT * FROM LOGINS where username = '$_POST[userBox]' AND password = '$_POST[passBox]'") or die(mysql_error());

    $numrows = mysql_num_rows($loginres);
    if ($numrows == 1) {
        $loginrow = mysql_fetch_assoc($loginres);
        session_register("SESS_LOGGEDIN");
        session_register("SESS_USERNAME");
        session_register("SESS_USERID");
        $getorder = mysql_query("SELECT CartID FROM CART where CID = " . $loginrow['CID'] . " AND Status < 2;");
        $orderrow = mysql_fetch_assoc($getorder);

        $getord = mysql_query("SELECT * FROM CART WHERE Session='" . session_id() . "' AND CartID=" . $_SESSION['SESS_ORDERNUM']);
        $numrow = mysql_num_rows($getord);
        $getrow = mysql_fetch_assoc($getord);
        $sesoid = $getrow['CartID'];
        $_SESSION['SESS_LOGGEDIN'] = 1;
        $_SESSION['SESS_USERNAME'] = $loginrow['username'];
        $_SESSION['SESS_USERID'] = $loginrow['CID'];
        if ($orderrow['CartID']) {
            session_register("SESS_ORDERNUM");
            $_SESSION['SESS_ORDERNUM'] = $orderrow['CartID'];
            if ($numrow >= 1) {
                $updorditm = "UPDATE APPEARS_IN SET CartID=" . $_SESSION['SESS_ORDERNUM'] . " WHERE CartID=" . $sesoid;
                mysql_query($updorditm);
                $deleteord = "DELETE FROM CART WHERE CartID=" . $sesoid;
                mysql_query($deleteord);
            }
            $adjust = mysql_query("SELECT OI_ID,CartID,PID,SUM(QUANTITY) AS QUANTITY, SUM(PriceSold) AS PriceSold FROM Newark_IT.APPEARS_IN WHERE CartID=" . $_SESSION['SESS_ORDERNUM'] . " GROUP BY CartID,PID;");

            while ($adjustappear = mysql_fetch_assoc($adjust)) {
                $price = mysql_query("SELECT C.Status AS CStatus,P.PPrice,OP.Status AS OPStatus,OP.OfferPrice FROM CUSTOMER C,PRODUCT P LEFT OUTER JOIN OFFER_PRODUCT OP ON P.PID=OP.PID WHERE C.CID=" . $_SESSION['SESS_USERID'] . " AND P.PID =" . $adjustappear['PID']);
                $pricerow = mysql_fetch_assoc($price);
                if ($pricerow['CStatus'] == 'gold' && $pricerow['OPStatus'] == 'gold') {
                    $total = $pricerow['OfferPrice'] * $adjustappear['QUANTITY'];
                } elseif ($pricerow['CStatus'] == 'platinum' && $pricerow['OPStatus'] == 'platinum') {
                    $total = $pricerow['OfferPrice'] * $adjustappear['QUANTITY'];
                } else {
                    $total = $pricerow['PPrice'] * $adjustappear['QUANTITY'];
                }
                $updt = "UPDATE APPEARS_IN SET QUANTITY=" . $adjustappear['QUANTITY'] . ",PriceSold=" . $total . " WHERE CartID=" . $_SESSION['SESS_ORDERNUM'] . " AND OI_ID=" . $adjustappear['OI_ID'];
                mysql_query($updt);
                mysql_query("DELETE FROM Newark_IT.APPEARS_IN WHERE CartID=" . $_SESSION['SESS_ORDERNUM'] . " AND PID=" . $adjustappear['PID'] . " AND OI_ID NOT IN(" . $adjustappear['OI_ID'] . ")");
            }
        }



        if ($numrow >= 1) {
            $updord = "UPDATE CART SET CID=" . $loginrow['CID'] . ",Registered=1,Session='' WHERE Session='" . session_id() . "' AND CartID=" . $_SESSION['SESS_ORDERNUM'];
            mysql_query($updord);
        }
        header("Location:" . $config_basedir . "index.php");
    } else {
        header("location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?error=1");
        exit();
    }
} else {
    require("header.php");
    ?>
    <h1>Customer Login</h1>
    Please enter your username and password to log into the websites.
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
        New customer? <a href="register1.php">Start here.</a>
    </form>
    <?php
}
require("footer.php");
?>