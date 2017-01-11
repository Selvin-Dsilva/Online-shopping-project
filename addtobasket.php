<?php

session_start();
ob_start();
require("config.php");
$prodsql = "SELECT * FROM PRODUCT LEFT OUTER JOIN OFFER_PRODUCT ON PRODUCT.PID=OFFER_PRODUCT.PID WHERE PRODUCT.PID = " . $_GET['id'] . ";";
$prodres = mysql_query($prodsql);
$numrows = mysql_num_rows($prodres);
$prodrow = mysql_fetch_assoc($prodres);
if ($numrows == 0) {
    header("Location: " . $config_basedir);
} else {
    if ($_POST['submit']) {
        $qtychk = mysql_query("SELECT * FROM PRODUCT WHERE PID=" . $_GET['id']);
        $qty = mysql_fetch_assoc($qtychk);
        if ($qty['PQuantity'] > $_POST['amountBox']) {
            if (isset($_SESSION['SESS_ORDERNUM'])) {
                $itemchecksql = mysql_query("SELECT * FROM APPEARS_IN WHERE PID=" . $_GET['id'] . " AND CartID=" . $_SESSION['SESS_ORDERNUM']);
                $itemcheckrows = mysql_num_rows($itemchecksql);
                if ($itemcheckrows >= 1) {
                    $itemcheck = mysql_fetch_assoc($itemchecksql);
                    $totalqty = $itemcheck['QUANTITY'] + $_POST['amountBox'];
                    if ($prodrow['OfferPrice'] == "") {
                        $amt = $totalqty * $prodrow['PPrice'];
                    } else {
                        $amt = $totalqty * $prodrow['OfferPrice'];
                    }
                    $itemsql = mysql_query("UPDATE APPEARS_IN SET QUANTITY=" . $totalqty . ", PriceSold=" . $amt . " WHERE PID=" . $_GET['id'] . " AND CartID=" . $_SESSION['SESS_ORDERNUM']);
                } else {
                    if ($prodrow['OfferPrice'] == "") {
                        $amt = $_POST['amountBox'] * $prodrow['PPrice'];
                        $itemsql = "INSERT INTO APPEARS_IN(CartID,PID, QUANTITY,PriceSold)VALUES(" . $_SESSION['SESS_ORDERNUM'] . ", " . $_GET['id'] . ", " . $_POST['amountBox'] . ", " . $amt . ")";
                        mysql_query($itemsql);
                    } else {
                        $amt = $_POST['amountBox'] * $prodrow['OfferPrice'];
                        $itemsql = "INSERT INTO APPEARS_IN(CartID,PID, QUANTITY,PriceSold)VALUES(" . $_SESSION['SESS_ORDERNUM'] . ", " . $_GET['id'] . ", " . $_POST['amountBox'] . ", " . $amt . ")";
                        mysql_query($itemsql);
                    }
                }
            } else {
                if (isset($_SESSION['SESS_LOGGEDIN'])) {
                    echo "in login";
                    $sql = "INSERT INTO CART(CID,Registered, Date)VALUES(" . $_SESSION['SESS_USERID'] . ", 1, NOW() )";
                    mysql_query($sql);
                    session_register("SESS_ORDERNUM");
                    $_SESSION['SESS_ORDERNUM'] = mysql_insert_id();
                    if ($prodrow['OfferPrice'] == "") {
                        $amt = $_POST['amountBox'] * $prodrow['OfferPrice'];
                        $itemsql = "INSERT INTO APPEARS_IN(CartID, PID, QUANTITY,PriceSold)
                        VALUES(" . $_SESSION['SESS_ORDERNUM'] . ", " . $_GET['id'] . ", " . $_POST['amountBox'] . ", " . $amt . ")";
                        mysql_query($itemsql);
                    } else {
                        $amt = $_POST['amountBox'] * $prodrow['PPrice'];
                        $itemsql = "INSERT INTO APPEARS_IN(CartID, PID, QUANTITY,PriceSold)
                        VALUES(" . $_SESSION['SESS_ORDERNUM'] . ", " . $_GET['id'] . ", " . $_POST['amountBox'] . ", " . $amt . ")";
                        mysql_query($itemsql);
                    }
                } else {
                    $sql = "INSERT INTO CART(Registered,Date, Session)VALUES(" . "0, NOW(), '" . session_id() . "')";
                    mysql_query($sql);
                    session_register("SESS_ORDERNUM");
                    $_SESSION['SESS_ORDERNUM'] = mysql_insert_id();
                    if ($prodrow['OfferPrice'] == "") {
                        $amt = $_POST['amountBox'] * $prodrow['OfferPrice'];
                        $itemsql = "INSERT INTO APPEARS_IN(CartID, PID, QUANTITY,PriceSold)VALUES(" . $_SESSION['SESS_ORDERNUM'] . ", " . $_GET['id'] . ", " . $_POST['amountBox'] . ", " . $amt . ")";
                        mysql_query($itemsql);
                    } else {
                        $amt = $_POST['amountBox'] * $prodrow['PPrice'];
                        $itemsql = "INSERT INTO APPEARS_IN(CartID, PID, QUANTITY,PriceSold)VALUES(" . $_SESSION['SESS_ORDERNUM'] . ", " . $_GET['id'] . ", " . $_POST['amountBox'] . ", " . $amt . ")";
                        mysql_query($itemsql);
                    }
                }
            }
            if (isset($_SESSION['SESS_LOGGEDIN'])) {
                $getcust = mysql_query("SELECT * from CUSTOMER WHERE CID=" . $_SESSION['SESS_USERID'] . ";");
                $custrow = mysql_fetch_assoc($getcust);
                if ($custrow['Status'] == 'gold' && $prodrow['Status'] == 'gold') {
                    $totalprice = $prodrow['OfferPrice'] * $_POST['amountBox'];
                    $updsql = "UPDATE CART SET Total = Total + " . $totalprice . " WHERE CartID = " . $_SESSION['SESS_ORDERNUM'] . ";";
                    mysql_query($updsql);
                } elseif ($custrow['Status'] == 'platinum' && $prodrow['Status'] == 'platinum') {
                    $totalprice = $prodrow['OfferPrice'] * $_POST['amountBox'];
                    $updsql = "UPDATE CART SET Total = Total + " . $totalprice . " WHERE CartID = " . $_SESSION['SESS_ORDERNUM'] . ";";
                    mysql_query($updsql);
                } else {
                    $totalprice = $prodrow['PPrice'] * $_POST['amountBox'];
                    $updsql = "UPDATE CART SET Total = Total + " . $totalprice . " WHERE CartID = " . $_SESSION['SESS_ORDERNUM'] . ";";
                    mysql_query($updsql);
                }
            } else {
                $totalprice = $prodrow['PPrice'] * $_POST['amountBox'];
                $updsql = "UPDATE CART SET Total = Total + " . $totalprice . " WHERE CartID = " . $_SESSION['SESS_ORDERNUM'] . ";";
                mysql_query($updsql);
            }
            header("Location:" . $config_basedir . "showcart.php");
        } else {
            header("Location:" . $config_basedir . "addtobasket.php?id=" . $_GET['id'] . "&error=1");
        }
    } else {
        $error = "";
        if (isset($_SESSION['SESS_LOGGEDIN'])) {
            $getcust = mysql_query("SELECT * from CUSTOMER WHERE CID=" . $_SESSION['SESS_USERID'] . ";");
            $custrow = mysql_fetch_assoc($getcust);
            if ($custrow['Status'] == 'gold' && $prodrow['Status'] == 'gold') {

                require("header.php");
                echo "<form action='addtobasket.php?id=" . $_GET['id'] . "' method='POST'>";
                echo "<table cellpadding='10'>";
                echo "<tr>";
                if (empty($prodrow['Image'])) {
                    echo '<th rowspan="2"><td><img src="dummy.jpg"/></td></th>';
                } else {
                    echo '<td><img src="./productimages/' . $prodrow['Image'] . '"/></td>';
                }
                echo "<td>" . $prodrow['PName'] . "</td>";
                echo "<td>Select Quantity <select name='amountBox'>";
                for ($i = 1; $i <= 100; $i++) {
                    echo "<option>" . $i . "</option>";
                }
                echo "</select></td>";
                echo "<td><strong>&dollar;" . sprintf('%.2f', $prodrow['OfferPrice']) . "</strong></td>";
                echo "<td>Available Qty:" . $prodrow['PQuantity'] . "</td>";
                echo "<td><input type='submit'name='submit' value='Add to basket'></td>";
                echo "</tr>";
                echo "<tr>";
                if ($_GET['error'] == 1) {
                    echo "<td>Out Of Stock Please check Quantity<td>";
                }
                echo "</tr>";
                echo "</table>";
                echo "</form>";
            } elseif ($custrow['Status'] == 'platinum' && $prodrow['Status'] == 'platinum') {
                require("header.php");
                echo "<form action='addtobasket.php?id=" . $_GET['id'] . "' method='POST'>";
                echo "<table cellpadding='10'>";
                echo "<tr>";
                if (empty($prodrow['Image'])) {
                    echo '<th rowspan="2"><td><td><img src="dummy.jpg"/></td></th>';
                } else {
                    echo '<td><img src="./productimages/' . $prodrow['Image'] . '"/></td>';
                }
                echo "<td>" . $prodrow['PName'] . "</td>";
                echo "<td>Select Quantity <select name='amountBox'>";
                for ($i = 1; $i <= 100; $i++) {
                    echo "<option>" . $i . "</option>";
                }
                echo "</select></td>";
                echo "<td><strong>&dollar;" . sprintf('%.2f', $prodrow['OfferPrice']) . "</strong></td>";
                echo "<td>Available Qty:" . $prodrow['PQuantity'] . "</td>";
                echo "<td><input type='submit'name='submit' value='Add to basket'></td>";
                echo "</tr>";
                echo "<tr>";
                if ($_GET['error'] == 1) {
                    echo "<td>Out Of Stock<td>";
                }
                echo "</tr>";
                echo "</table>";
                echo "</form>";
            } else {
                require("header.php");
                echo "<form action='addtobasket.php?id=" . $_GET['id'] . "' method='POST'>";
                echo "<table cellpadding='10'>";
                echo "<tr>";
                if (empty($prodrow['Image'])) {
                    echo '<th rowspan="2"><td><td><img src="dummy.jpg"/></td></th>';
                } else {
                    echo '<td><img src="./productimages/' . $prodrow['Image'] . '"/></td>';
                }
                echo "<td>" . $prodrow['PName'] . "</td>";
                echo "<td>Select Quantity <select name='amountBox'>";
                for ($i = 1; $i <= 100; $i++) {
                    echo "<option>" . $i . "</option>";
                }
                echo "</select></td>";
                echo "<td><strong>&dollar;" . sprintf('%.2f', $prodrow['PPrice']) . "</strong></td>";
                echo "<td>Available Qty:" . $prodrow['PQuantity'] . "</td>";
                echo "<td><input type='submit'name='submit' value='Add to basket'></td>";
                echo "</tr>";
                echo "<tr>";
                if ($_GET['error'] == 1) {
                    echo "<td>Out Of Stock<td>";
                }
                echo "</tr>";
                echo "</table>";
                echo "</form>";
            }
        } else {
            require("header.php");
            echo "<form action='addtobasket.php?id=" . $_GET['id'] . "' method='POST'>";
            echo "<table cellpadding='10'>";
            echo "<tr>";
            if (empty($prodrow['Image'])) {
                echo '<th rowspan="2"><td><td><img src="dummy.jpg"/></td></th>';
            } else {
                echo '<td><img src="./productimages/' . $prodrow['Image'] . '"/></td>';
            }
            echo "<td>" . $prodrow['PName'] . "</td>";
            echo "<td>Select Quantity <select name='amountBox'>";
            for ($i = 1; $i <= 100; $i++) {
                echo "<option>" . $i . "</option>";
            }
            echo "</select></td>";
            echo "<td><strong>&dollar;" . sprintf('%.2f', $prodrow['PPrice']) . "</strong></td>";
            echo "<td>Available Qty:" . $prodrow['PQuantity'] . "</td>";
            echo "<td><input type='submit'name='submit' value='Add to basket'></td>";
            echo "</tr>";
            echo "<tr>";
            if ($_GET['error'] == 1) {
                echo "<td>Out Of Stock<td>";
            }
            echo "</tr>";
            echo "</table>";
            echo "</form>";
        }
    }
}
require("footer.php");
?>
