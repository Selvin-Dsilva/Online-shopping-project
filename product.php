<head>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<?php
session_start();
ob_start();
require 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
if (isset($_SESSION['SESS_LOGGEDIN'])) {
    $getcust = mysql_query("SELECT * from CUSTOMER WHERE CID=" . $_SESSION['SESS_USERID'] . ";");
    $custrow = mysql_fetch_assoc($getcust);
    if ($custrow['Status'] == 'gold') {
        require 'header.php';
        $prodcatsql = "SELECT *, PRODUCT.PID as PIDP FROM PRODUCT LEFT OUTER JOIN OFFER_PRODUCT ON PRODUCT.PID=OFFER_PRODUCT.PID WHERE PRODUCT.Ptype = '" . $_GET['id'] . "';";
        $prodcatres = mysql_query($prodcatsql);
        $numrows = mysql_num_rows($prodcatres);
        if ($numrows == 0) {
            echo "<h1>No products</h1>";
            echo "There are no products in this category.";
        } else {
            echo "";
            while ($prodrow = mysql_fetch_assoc($prodcatres)) {
                echo '<table cellpadding="10">';
                echo '<tbody><tr>';
                if ($prodrow['Image'] == "") {
                    echo '<th rowspan="6"><img src="./productimages/dummy.jpg"></th>';
                } else {
                    echo '<th rowspan="6"><img src="./productimages/' . $prodrow['Image'] . '"/></th>';
                }
                echo '<td>' . $prodrow['PName'] . '</td>';
                echo '<tr><td>Description: ' . $prodrow['Description'] . '</td></tr>';
                if ($prodrow['Status'] == 'gold') {
                    echo '<tr><td><strike>Our Price: &dollar;' . $prodrow['PPrice'] . '</strike></td></tr>';
                    echo '<tr><td>Offer Price: &dollar;' . $prodrow['OfferPrice'] . '</td></tr>';
                } else {
                    echo '<tr><td>Our Price: &dollar;' . $prodrow['PPrice'] . '</td></tr>';
                }
                echo '<tr><td>Available Quantity: ' . $prodrow['PQuantity'] . '</td></tr>';
                echo '<tr><td><a href="addtobasket.php?id=' . $prodrow['PIDP'] . '"> Buy</a></tr>';
                echo '</tr></table><br><hr>';
            }
        }
    } elseif ($custrow['Status'] == 'platinum') {
        require 'header.php';
        $prodcatsql = "SELECT *, PRODUCT.PID FROM PRODUCT LEFT OUTER JOIN OFFER_PRODUCT ON PRODUCT.PID=OFFER_PRODUCT.PID WHERE PRODUCT.Ptype = '" . $_GET['id'] . "';";
        $prodcatres = mysql_query($prodcatsql);
        $numrows = mysql_num_rows($prodcatres);
        if ($numrows == 0) {
            echo "<h1>No products</h1>";
            echo "There are no products in this category.";
        } else {
            echo "";
            while ($prodrow = mysql_fetch_assoc($prodcatres)) {
                echo '<table cellpadding="10">';
                echo '<tbody><tr>';
                if ($prodrow['Image'] == "") {
                    echo '<th rowspan="6"><img src="./productimages/dummy.jpg"></th>';
                } else {
                    echo '<th rowspan="6"><img src="./productimages/' . $prodrow['Image'] . '"/></th>';
                }
                echo '<td>' . $prodrow['PName'] . '</td>';
                echo '<tr><td>Description: ' . $prodrow['Description'] . '</td></tr>';
                if ($prodrow['Status'] == 'platinum') {
                    echo '<tr><td><strike>Our Price: &dollar;' . $prodrow['PPrice'] . '</strike></td></tr>';
                    echo '<tr><td>Offer Price: &dollar;' . $prodrow['OfferPrice'] . '</td></tr>';
                } else {
                    echo '<tr><td>Our Price: &dollar;' . $prodrow['PPrice'] . '</td></tr>';
                }
                echo '<tr><td>Available Quantity: ' . $prodrow['PQuantity'] . '</td></tr>';
                echo '<tr><td><a href="addtobasket.php?id=' . $prodrow['PIDP'] . '">Buy</a></tr>';
                echo '</tr></table><br><hr>';
            }
        }
    } else {
        require 'header.php';
        $prodcatsql = "SELECT * FROM PRODUCT WHERE Ptype = '" . $_GET['id'] . "';";
        $prodcatres = mysql_query($prodcatsql);
        $numrows = mysql_num_rows($prodcatres);
        if ($numrows == 0) {
            echo "<h1>No products</h1>";
            echo "There are no products in this category.";
        } else {
            echo "";
            while ($prodrow = mysql_fetch_assoc($prodcatres)) {
                echo '<table cellpadding="10">';
                echo '<tbody><tr>';
                if ($prodrow['Image'] == "") {
                    echo '<th rowspan="5"><img src="./productimages/dummy.jpg"></th>';
                } else {
                    echo '<th rowspan="5"><img src="./productimages/' . $prodrow['Image'] . '"/></th>';
                }
                echo '<td>' . $prodrow['PName'] . '</td>';
                echo '<tr><td>Description: ' . $prodrow['Description'] . '</td></tr>';
                echo '<tr><td>Our Price: &dollar;' . $prodrow['PPrice'] . '</td></tr>';
                echo '<tr><td>Available Quantity: ' . $prodrow['PQuantity'] . '</td></tr>';
                echo '<tr><td><a href="addtobasket.php?id=' . $prodrow['PID'] . '">Buy</a></tr>';
                echo '</tr></table><br><hr>';
            }
        }
    }
} else {
    require 'header.php';
    $prodcatsql = "SELECT * FROM PRODUCT WHERE Ptype = '" . $_GET['id'] . "';";
    $prodcatres = mysql_query($prodcatsql);
    $numrows = mysql_num_rows($prodcatres);
    if ($numrows == 0) {
        echo "<h1>No products</h1>";
        echo "There are no products in this category.";
    } else {
        echo "";
        while ($prodrow = mysql_fetch_assoc($prodcatres)) {
            echo '<table cellpadding="8">';
            echo '<tbody><tr>';
            if (empty($prodrow['Image'])) {
                echo '<th rowspan="5"><img src="./productimages/dummy.jpg"></th>';
            } else {
                echo '<th rowspan="5"><img src="./productimages/' . $prodrow['Image'] . '"/></th>';
            }
            echo '<td>' . $prodrow['PName'] . '</td>';
            echo '<tr><td><u><strong>Description:</strong></u> ' . $prodrow['Description'] . '</td></tr>';
            echo '<tr><td><u><strong>Our Price:</strong></u> &dollar;' . $prodrow['PPrice'] . '</td></tr>';
            echo '<tr><td><u><strong>Available Quantity:</strong></u> ' . $prodrow['PQuantity'] . '</td></tr>';
            echo '<tr><td><a href="addtobasket.php?id=' . $prodrow['PID'] . '">Buy</a></tr>';
            echo '</tr></table><br><hr>';
        }
    }
}
require("footer.php");
?>
