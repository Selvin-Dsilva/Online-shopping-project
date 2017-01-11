<?php
ob_start();
session_start();
require ("config.php");

if ($_POST['submit']) {
    if ($_POST['addselecBox'] == "") {
        header("Location:" . $config_basedir . "checklogin-register.php?error=1");
    } else {
        $setaddsql = "UPDATE CART SET SAName = '" . $_POST['addselecBox'] . "', Status = 1 WHERE CartID = " . $_SESSION['SESS_ORDERNUM'];
        mysql_query($setaddsql);
        header("Location:" . $config_basedir . "creditselect-add.php");
        //header("Location:" . $config_basedir . "selectmethod.php");
    }
} else {
    ?>
    <form action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="POST">
        <?php
        require("header.php");
        if (isset($_SESSION['SESS_LOGGEDIN'])) {
            $getadd = "SELECT * FROM SHIPPING_ADDRESS WHERE CID=" . $_SESSION['SESS_USERID'] . ";";
            $getaddres = mysql_query($getadd);
            $itemnumrows = mysql_num_rows($getaddres);
            if ($_GET['error'] == 1) {
                echo '<h2>You have not selected shipping address</h2><br><br>';
            }
            echo '<h2><strong>Please select shipping address:</h2></strong><hr>';
            $counter = 1;
            while ($itemsrow = mysql_fetch_assoc($getaddres)) {
                echo '<table>';
                echo "<tr>";
                echo '<td><input type = "radio" name = "addselecBox" value = "' . $itemsrow['SAName'] . '" ><u>Address ' . $counter . '</u> </td><td> :' . $itemsrow['SAName'] . '</td>';
                echo "</tr>";
                echo '<tr><td>&nbsp&nbsp&nbsp&nbsp<u>Recepient Name</u>  </td><td>:' . $itemsrow['RecepientName'] . '</td></tr>';
                echo '<tr><td>&nbsp&nbsp&nbsp&nbsp<u>Street</u>  </td><td>:' . $itemsrow['Street'] . '</td></tr>';
                echo '<tr><td>&nbsp&nbsp&nbsp&nbsp<u>City</u>  </td><td>:' . $itemsrow['City'] . '</td></tr>';
                echo '<tr><td>&nbsp&nbsp&nbsp&nbsp<u>Zip</u>  </td><td>:' . $itemsrow['Zip'] . '</td></tr>';
                echo '<tr><td>&nbsp&nbsp&nbsp&nbsp<u>State</u>  </td><td>:' . $itemsrow['State'] . '</td></tr>';
                echo '<tr><td>&nbsp&nbsp&nbsp&nbsp<u>Country</u>  </td><td>:' . $itemsrow['Country'] . '</td></tr><br>';
                echo '</table><hr>';
                $counter = $counter + 1;
            }
            ?>
            <br><br><a href="checkout-address.php">Add Address</a><br><br>
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
