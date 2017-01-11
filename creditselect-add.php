<?php
ob_start();
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
require ("config.php");
if ($_POST['submit']) {
    echo $id;
    if ($_POST['addselecBox'] == "") {
        header("Location:" . $config_basedir . "creditselect-add.php?&error=1");
    } else {
        $card= "UPDATE CART SET CCNumber =" . $_POST['addselecBox'] . " WHERE CartID = " . $_SESSION['SESS_ORDERNUM'] . " AND CID=" . $_SESSION['SESS_USERID'];
        mysql_query($card);
        header("Location:" . $config_basedir . "checkout-pay.php");
    }
} else {
    ?>
    <form action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="POST">
        <?php
        require("header.php");
        if (isset($_SESSION['SESS_LOGGEDIN'])) {
            $getcrd = "SELECT * FROM CREDIT_CARD, STORED_CARD WHERE CID=" . $_SESSION['SESS_USERID'] . " AND CREDIT_CARD.CCNumber=STORED_CARD.CCNumber;;";
            $getcard = mysql_query($getcrd);
            $itemnumrows = mysql_num_rows($getcard);
            if ($_GET['error'] == 1) {
                echo '<h2>You have not selected credit card</h2><br><br>';
            }
            echo '<strong><h2>Please select credit card:</h2></strong><hr>';
            $counter = 1;
            while ($itemsrow = mysql_fetch_assoc($getcard)) {
                echo '<table>';
                echo "<tr>";
                echo '<td><input type = "radio" name = "addselecBox" value = "' . $itemsrow['CCNumber'] . '" ><u>Card ' . $counter . '</u> </td><td>:' . $itemsrow['CCNumber'] . '</td>';
                echo "</tr>";
                echo '<tr><td>&nbsp&nbsp&nbsp&nbsp<u>Owner Name</u>  </td><td>:' . $itemsrow['OwnerName'] . '</td></tr>';
                echo '<tr><td>&nbsp&nbsp&nbsp&nbsp<u>redit Card Type</u>  </td><td>:' . $itemsrow['CCType'] . '</td></tr>';
                echo '<tr><td>&nbsp&nbsp&nbsp&nbsp<u>Expiry Date</u>  </td><td>:' . $itemsrow['ExpDate'] . '</td></tr><br>';
                $counter = $counter + 1;
                echo '</table><hr>';
            }
            ?>
            <br><br><a href="addcard.php">Add Card</a><br><br>
            <input type="submit" name="submit" value="Select" />
        </form>
        <?php
    } else {
        echo '<h2><strong>Please Login/register before proceeding.</strong></h2><br>';
        echo '<a href="login.php">Login/Register</a><br><br><br><br>';
    }
}
echo '<br><br>';
require ("footer.php");
?>
