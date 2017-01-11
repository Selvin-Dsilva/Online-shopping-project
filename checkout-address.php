<?php

session_start();
ob_start();
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
require("config.php");
if ($_POST['submit']) {
    if ($_SESSION['SESS_LOGGEDIN']) {

        if (empty($_POST['SAnameBox']) || empty($_POST['RnameBox']) || empty($_POST['StreetBox']) || empty($_POST['SNBox']) || empty($_POST['CityBox']) || empty($_POST['ZipBox']) || empty($_POST['StateBox']) || empty($_POST['CountryBox'])) {
            header("Location: " . $config_basedir . "checkout-address.php?error=1");
            exit;
        }
        $addsql = "INSERT INTO SHIPPING_ADDRESS(CID, SAname, RecepientName, Street, Snumber, City, Zip, State, Country)VALUES('" . strip_tags(addslashes($_SESSION['SESS_USERID'])) . "', '" . strip_tags(addslashes($_POST['SAnameBox'])) . "', '" . strip_tags(addslashes($_POST['RnameBox'])) . "', '" . strip_tags(addslashes($_POST['StreetBox'])) . "', '" . strip_tags(addslashes($_POST['SNBox'])) . "', '" . strip_tags(addslashes($_POST['CityBox'])) . "', '" . strip_tags(addslashes($_POST['ZipBox'])) . "', '" . strip_tags(addslashes($_POST['StateBox'])) . "', '" . strip_tags(addslashes($_POST['CountryBox'])) . "')";
        mysql_query($addsql);
        header("Location: " . $config_basedir . "checklogin-register.php");
    }
} else {
    require("header.php");
    echo "<h1>Add a delivery address</h1>";
    if (isset($_GET['error']) == TRUE) {
        echo "<strong>Please fill in the missing information from the form</strong>";
    }
    echo "<form action='" . $_SERVER['SCRIPT_NAME'] . "' method='POST'>";
    if (isset($_SESSION['SESS_LOGGEDIN'])) {
        ?>
        <?php

    }
    ?>
    <table>
        <tr>
            <td>Shipping Address Name</td>
            <td><input type="text" name="SAnameBox"></td>
        </tr>
        <tr>
            <td>Recepient Name</td>
            <td><input type="text" name="RnameBox"></td>
        </tr>
        <tr>
            <td>Street</td>
            <td><input type="text" name="StreetBox"></td>
        </tr>
        <tr>
            <td> Street Number</td>
            <td><input type="text" name="SNBox"></td>
        </tr>
        <tr>
            <td>City</td>
            <td><input type="text" name="CityBox"></td>
        </tr>
        <tr>
            <td>Zip</td>
            <td><input type="text" name="ZipBox"></td>
        </tr>
        <tr>
            <td>State</td>
            <td><input type="text" name="StateBox"></td>
        </tr>
        <tr>
            <td>Country</td>
            <td><input type="text" name="CountryBox"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Add Address (press only once)"></td>
        </tr>
    </table>
    </form>
    <?php

}
require("footer.php");
?>


