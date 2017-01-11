<?php
session_start();
ob_start();
require("config.php");
require("functions.php");
if ($_POST['placesubmit']) {
    $ord = mysql_query("select sum(QUANTITY) QTY,PID from APPEARS_IN where CartID=" . $_SESSION['SESS_ORDERNUM'] . " group by PID");
    while ($ordrow = mysql_fetch_assoc($ord)) {
        $prdqty = mysql_query("SELECT * FROM PRODUCT WHERE PID=" . $ordrow['PID']);
        $prdrow = mysql_fetch_assoc($prdqty);
        $totalQty = $prdrow['PQuantity'] - $ordrow['QTY'];
        $ordqty = "UPDATE PRODUCT SET PQuantity =" . $totalQty . " WHERE PID=" . $ordrow['PID'];
        $prdqtyupd = mysql_query($ordqty);
    }
    $upsql = "UPDATE CART SET Status = 2,Payment_Type = 2 WHERE CartID = " . $_SESSION['SESS_ORDERNUM'];
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
    echo "<h1>Payment using credit card</h1>";
    showcart();
    ?>
    <hr><h2>Confirm Order</h2>
    <form action='checkout-pay.php' method='POST'>
        <table cellspacing=10>
            <tr>
                <td><input type="submit" name="placesubmit" value="Place Order"></td>

                <td><input type="submit" name="cancelsubmit" value="Cancel Order"></td>
            </tr>

        </table>
    </form>
    <?php
}
require("footer.php");
?>
