<?php

function showcart() {
    if (isset($_SESSION['SESS_ORDERNUM'])) {
        if (isset($_SESSION['SESS_LOGGEDIN'])) {
            $getorder = mysql_query("SELECT CartID FROM CART where CID = " . $_SESSION['SESS_USERID'] . " AND Status < 2;");
            $orderrow = mysql_fetch_assoc($getorder);
            if ($orderrow['CartID']) {
                session_register("SESS_ORDERNUM");
                $_SESSION['SESS_ORDERNUM'] = $orderrow['CartID'];
            }
            $custsql = "SELECT CartID, Status from CART WHERE CID = " . $_SESSION['SESS_USERID'] . " AND Status < 2;";
            $custres = mysql_query($custsql);
            $custrow = mysql_fetch_assoc($custres);
            $itemssql = "SELECT PRODUCT.*,PRODUCT.PID AS PPID, APPEARS_IN.*, APPEARS_IN.CartID AS itemid,OFFER_PRODUCT.* FROM  APPEARS_IN,PRODUCT LEFT OUTER JOIN OFFER_PRODUCT ON OFFER_PRODUCT.PID=PRODUCT.PID  WHERE APPEARS_IN.PID =PRODUCT.PID AND CartID =" . $custrow['CartID'];
            $itemsres = mysql_query($itemssql);
            $itemnumrows = mysql_num_rows($itemsres);
        } else {
            $custsql = "SELECT CartID, Status from CART WHERE Session = '" . session_id() . "' AND Status < 2;";
            $custres = mysql_query($custsql);
            $custrow = mysql_fetch_assoc($custres);
            $itemssql = "SELECT PRODUCT.*,PRODUCT.PID AS PPID, APPEARS_IN.*, APPEARS_IN.CartID AS itemid,OFFER_PRODUCT.* FROM  APPEARS_IN,PRODUCT LEFT OUTER JOIN OFFER_PRODUCT ON OFFER_PRODUCT.PID=PRODUCT.PID  WHERE APPEARS_IN.PID =PRODUCT.PID AND CartID =" . $custrow['CartID'];
            $itemsres = mysql_query($itemssql);
            $itemnumrows = mysql_num_rows($itemsres);
        }
    } else {
        $itemnumrows = 0;
    }
    if ($itemnumrows == 0) {
        echo "<br><br><h2>You have not added anything to your shopping cart yet.</h2>";
    } else {
        echo "<table cellpadding='10'>";
        echo "<tr>";
        echo "<td></td>";
        echo "<td><strong>Item</strong></td>";
        echo "<td><strong>Quantity</strong></td>";
        echo "<td><strong>Unit Price</strong></td>";
        echo "<td><strong>Total Price</strong></td>";
        echo "<td></td>";
        echo "</tr>";
        $getcust = mysql_query("SELECT * from CUSTOMER WHERE CID=" . $_SESSION['SESS_USERID'] . ";");
        $custrow = mysql_fetch_assoc($getcust);

        while ($itemsrow = mysql_fetch_assoc($itemsres)) {
            if (isset($_SESSION['SESS_LOGGEDIN'])) {
                if ($custrow['Status'] == 'gold' && $itemsrow['Status'] == 'gold') {
                    $quantitytotal = $itemsrow['OfferPrice'] * $itemsrow['QUANTITY'];
                    echo "<tr>";
                    if (empty($itemsrow['Image'])) {
                        echo '<td><img src="dummy.jpg"/></td>';
                    } else {
                        echo '<td><img src="./productimages/' . $itemsrow['Image'] . '"/></td>';
                    }
                    echo "<td>" . $itemsrow['PName'] . "</td>";
                    echo "<td>" . $itemsrow['QUANTITY'] . "</td>";
                    echo "<td><strong>&dollar;" . sprintf('%.2f', $itemsrow['OfferPrice']) . "</strong></td>";
                    echo "<td><strong>&dollar;" . sprintf('%.2f', $quantitytotal) . "</strong></td>";
                    echo "<td>[<a href='delete.php?id=" . $itemsrow['PPID'] . "'>X</a>]</td>";
                    echo "</tr>";
                    $total = $total + $quantitytotal;
                    $totalsql = "UPDATE CART SET Total = " . $total . " WHERE CartID = " . $_SESSION['SESS_ORDERNUM'];
                    $totalres = mysql_query($totalsql);
                } elseif ($custrow['Status'] == 'platinum' && $itemsrow['Status'] == 'platinum') {
                    $quantitytotal = $itemsrow['OfferPrice'] * $itemsrow['QUANTITY'];
                    echo "<tr>";
                    if (empty($itemsrow['Image'])) {
                        echo '<td><img src="dummy.jpg"/></td>';
                    } else {
                        echo '<td><img src="./productimages/' . $itemsrow['Image'] . '"/></td>';
                    }
                    echo "<td>" . $itemsrow['PName'] . "</td>";
                    echo "<td>" . $itemsrow['QUANTITY'] . "</td>";
                    echo "<td><strong>&dollar;" . sprintf('%.2f', $itemsrow['OfferPrice']) . "</strong></td>";
                    echo "<td><strong>&dollar;" . sprintf('%.2f', $quantitytotal) . "</strong></td>";
                    echo "<td>[<a href='delete.php?id=" . $itemsrow['PPID'] . "'>X</a>]</td>";
                    echo "</tr>";
                    $total = $total + $quantitytotal;
                    $totalsql = "UPDATE CART SET Total = " . $total . " WHERE CartID = " . $_SESSION['SESS_ORDERNUM'];
                    $totalres = mysql_query($totalsql);
                } else {
                    $quantitytotal = $itemsrow['PPrice'] * $itemsrow['QUANTITY'];
                    echo "<tr>";
                    if (empty($itemsrow['Image'])) {
                        echo '<td><img src="dummy.jpg"/></td>';
                    } else {
                        echo '<td><img src="./productimages/' . $itemsrow['Image'] . '"/></td>';
                    }
                    echo "<td>" . $itemsrow['PName'] . "</td>";
                    echo "<td>" . $itemsrow['QUANTITY'] . "</td>";
                    echo "<td><strong>&dollar;" . sprintf('%.2f', $itemsrow['PPrice']) . "</strong></td>";
                    echo "<td><strong>&dollar;" . sprintf('%.2f', $quantitytotal) . "</strong></td>";
                    echo "<td>[<a href='delete.php?id=" . $itemsrow['PPID'] . "'>X</a>]</td>";
                    echo "</tr>";
                    $total = $total + $quantitytotal;
                    $totalsql = "UPDATE CART SET Total = " . $total . " WHERE CartID = " . $_SESSION['SESS_ORDERNUM'];
                    $totalres = mysql_query($totalsql);
                }
            } else {
                $quantitytotal = $itemsrow['PPrice'] * $itemsrow['QUANTITY'];
                echo "<tr>";
                if (empty($itemsrow['Image'])) {
                    echo '<td><img src="dummy.jpg"/></td>';
                } else {
                    echo '<td><img src="./productimages/' . $itemsrow['Image'] . '"/></td>';
                }
                echo "<td>" . $itemsrow['PName'] . "</td>";
                echo "<td>" . $itemsrow['QUANTITY'] . "</td>";
                echo "<td><strong>&dollar;" . sprintf('%.2f', $itemsrow['PPrice']) . "</strong></td>";
                echo "<td><strong>&dollar;" . sprintf('%.2f', $quantitytotal) . "</strong></td>";
                echo "<td>[<a href='delete.php?id=" . $itemsrow['PPID'] . "'>X</a>]</td>";
                echo "</tr>";
                $total = $total + $quantitytotal;
                $totalsql = "UPDATE CART SET Total = " . $total . " WHERE CartID = " . $_SESSION['SESS_ORDERNUM'];
                $totalres = mysql_query($totalsql);
            }
        }
        echo "<hr><tr>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td>TOTAL</td>";
        echo "<td><strong>&dollar;" . sprintf('%.2f', $total) . "</strong></td>";
        echo "<td></td>";
        echo "</tr>";
        echo "</table>";
    }
}

?>