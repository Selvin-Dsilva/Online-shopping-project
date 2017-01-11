<?php
session_start();
ob_start();
require("config.php");
require("functions.php");
$credit="SELECT * FROM CART,SILVER_AND_ABOVE WHERE CART.CID=".$_SESSION['SESS_USERID']." AND CART.CID=SILVER_AND_ABOVE.CID AND CART.CartID=".$_SESSION['SESS_ORDERNUM'];
//$credit = "SELECT * FROM CART,SILVER_AND_ABOVE WHERE CART.CID=106 AND CART.CID=SILVER_AND_ABOVE.CID AND CART.CartID=74";
//mysql_query($credit);
$creditres = mysql_query($credit);
$creditrow = mysql_fetch_assoc($creditres);
$check = $creditrow['CreditLine'] - $creditrow['Total'];
if ($check >= 0) {
    if ($_POST['placesubmit']) {
        $ord = mysql_query("select sum(QUANTITY) QTY,PID from APPEARS_IN where CartID=" . $_SESSION['SESS_ORDERNUM'] . " group by PID");
        while ($ordrow = mysql_fetch_assoc($ord)) {
            $prdqty = mysql_query("SELECT * FROM PRODUCT WHERE PID=" . $ordrow['PID']);
            $prdrow = mysql_fetch_assoc($prdqty);
            $totalQty = $prdrow['PQuantity'] - $ordrow['QTY'];
            $ordqty = "UPDATE PRODUCT SET PQuantity =" . $totalQty . " WHERE PID=" . $ordrow['PID'];
            $prdqtyupd = mysql_query($ordqty);
        }
        $crd = "UPDATE SILVER_AND_ABOVE SET CreditLine=" . $check . " WHERE CID=" . $_SESSION['SESS_USERID'];
        mysql_query($crd);
        $upsql = "UPDATE CART SET Status = 2 ,Payment_Type = 1 WHERE CartID = " . $_SESSION['SESS_ORDERNUM'];
        $upres = mysql_query($upsql);
        if ($_SESSION['SESS_LOGGEDIN']) {
            unset($_SESSION['SESS_ORDERNUM']);
        } else {
            session_register("SESS_CHANGEID");
            $_SESSION['SESS_CHANGEID'] = 1;
        }
        header("Location:thankyou.php");
    } else if ($_POST['cancelsubmit']) {
        $upsql = "UPDATE CART SET Status = 3 WHERE CartID = " . $_SESSION['SESS_ORDERNUM'];
        $upres = mysql_query($upsql);
        if ($_SESSION['SESS_LOGGEDIN']) {
            unset($_SESSION['SESS_ORDERNUM']);
        } else {
            session_register("SESS_CHANGEID");
            $_SESSION['SESS_CHANGEID'] = 1;
        }
        header("Location:cancel.php");
        ?>

        <?php
    } else {
        require("header.php");
        echo "<h1>Payment using credit line</h1>";
        showcart();
        ?>
        <h2>Confirm Order</h2>
        <form action='creditline.php' method='POST'>
            <table cellspacing=10>
                <tr>
                    <td><input type="submit" name="placesubmit" value="Place Order"></td>

                    <td><input type="submit" name="cancelsubmit" value="Cancel Order"></td>
                </tr>

            </table>
        </form>
        <?php
    }
} else {
    if ($_POST['placesubmit']) {

        header("Location:selectmethod.php");
    } else {
        require("header.php");
        //echo "<h1>Payment</h1>";
        ?>
        <h2>Your credit limit is less than total amount. Please go to previous page and select credit card for payment.</h2>
        <table border="1">
            <tr>
                <th>Credit Limit</th>
                <th>Total Amount</th>
            </tr>
            <tr>
                <td><?php echo "$" . $creditrow['CreditLine'] ?></td>
                <td><?php echo "$" . $creditrow['Total'] ?></td>
            </tr>
        </table>
        <form action='creditline.php' method='POST'>
            <table cellspacing=10>
                <tr>
                    <td><input type="submit" name="placesubmit" value="Go Back"></td>
                </tr>

            </table>
        </form>
        <?php
    }
}
?>