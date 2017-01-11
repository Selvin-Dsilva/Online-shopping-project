<?php
session_start();
ob_start();
$ccnumErr = $scodeErr = $onameErr = $baddErr = $dateErr = $cardErr = "";
$ccnum = $scode = $oname = $badd = $date = $card = "";
$flag = "";

require('header.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flag = 0;
    if (empty($_POST["ccnum"])) {
        $ccnumErr = "Please enter credit card number";
        echo $ccnumErr;
        $flag = 1;
    } else {
        if ($_POST['Card'] == 'MasterCard') {
            $pattern = "/^([51|52|53|54|55]{2})([0-9]{14})$/"; //Mastercard
            if (preg_match($pattern, test_input($_POST["ccnum"]))) {
                $ccnum = test_input($_POST["ccnum"]);
            } else {
                $ccnumErr = "Please enter valid credit card number";
                $flag = 1;
            }
        } elseif ($_POST['Card'] == 'Visa') {
            $pattern = "/^([4]{1})([0-9]{12,15})$/"; //Visa
            if (preg_match($pattern, test_input($_POST["ccnum"]))) {
                $ccnum = test_input($_POST["ccnum"]);
            } else {
                $ccnumErr = "Please enter valid credit card number";
                $flag = 1;
            }
        } else {
            $ccnumErr = "Please select card type";
            $flag = 1;
        }
    }
    if (empty($_POST["scode"])) {
        $scodeErr = "Please enter security code";
        $flag = 1;
    } else {
        // check if name only contains letters and whitespace
        if (!preg_match("/^[0-9]*$/", test_input($_POST["scode"])) && (test_input($_POST["scode"])) . length > 3) {
            $scodeErr = "please enter three digit code";
            $flag = 1;
        } else {
            $scode = test_input($_POST["scode"]);
        }
    }
    if (empty($_POST["oname"])) {
        $onameErr = "Name is required";
        $flag = 1;
    } else {
        $oname = test_input($_POST["oname"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $oname)) {
            $onameErr = "Only letters and white space allowed";
            $flag = 1;
        }
    }
    if (empty($_POST["badd"])) {
        $baddErr = "Please enter billing address";
        $flag = 1;
    } else {
        $badd = test_input($_POST["badd"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $badd)) {
            $baddErr = "Invalid billing address";
            $flag = 1;
        }
    }
    if (empty($_POST["month"]) || empty($_POST["year"])) {
        $dateErr = "Please enter Exp date";
        $flag = 1;
    } else {
        $sysdatemonth = date("m");
        $sysdateyear = date("Y");
        $datemonth = $_POST["month"];
        $dateyear = $_POST["year"];
        if ($dateyear < $sysdateyear) {
            echo 'here';
            $dateErr = "Please enter valid date";
            $flag = 1;
        } elseif ($dateyear == $sysdateyear && $datemonth < $sysdatemonth) {
            echo 'here2';
            $dateErr = "Please enter valid date";
            $flag = 1;
        } elseif ($dateyear == $sysdateyear && $datemonth == $sysdatemonth) {
            echo 'here3';
            $dateErr = "Month should be greater than current month";
            $flag = 1;
        } elseif ($dateyear > $sysdateyear) {
            echo 'here4';
            $date= $_POST["year"]."-".$_POST["month"]."-01";
        }
    }

    if ($_POST['Card'] == "") {
        $flag = 1;
        $cardErr = "Please select a card";
    } else {
        $card = $_POST['Card'];
    }

    if ($flag == 0) {
        $addcard = "INSERT INTO CREDIT_CARD(CCNumber, SecNumber, OwnerName, CCType,CCAddress,ExpDate)VALUES(" . $ccnum . ", " . $scode . ", '" . $oname . "','" . $card . "','".$badd."','".$date."')";
        mysql_query($addcard);
        $storedcrd = "INSERT INTO STORED_CARD(CCNumber,CID)VALUES(".$ccnum.",".$_SESSION['SESS_USERID'].")";
        mysql_query($storedcrd);

        header("Location:" . $config_basedir . "creditselect-add.php");
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h2>PHP Form Validation Example</h2><br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

    <table>
        <tr>
            <td>Credit Card Number:</td>
            <td><input type="text" name="ccnum" value="<?php echo $ccnum; ?>"></td>
            <td>*<span class="error"> <?php echo $ccnumErr; ?></span></td>
        </tr>

        <tr>
            <td>Security Code:</td>
            <td><input type="text" name="scode" value="<?php echo $scode; ?>"></td>
            <td><span class="error">* <?php echo $scodeErr; ?></span></td>
        </tr>
        <tr>
            <td>Owner Name:</td>
            <td><input type="text" name="oname" value="<?php echo $oname; ?>"></td>
            <td><span class="error">* <?php echo $onameErr; ?></span></td>
        </tr>
        <tr>
            <td rowspan="2">Credit Card Type:</td>
            <td><input type="radio" name="Card" value="MasterCard" checked="checked"> Master Card</td>
        </tr>
        <tr>
            <td><input type="radio" name="Card" value="Visa"> Visa</td>
            <td><span class="error">* <?php echo $cardErr; ?></span></td>
        </tr>
        <tr>
            <td>Billing Address:</td>
            <td><input type="text" name="badd" value="<?php echo $badd; ?>"></td>
            <td><span class="error">* <?php echo $baddErr; ?></span></td>
        </tr>
        <tr>
            <td>Expiry Date:</td>
            <td>Month
                <select name='month'>
                    <?php
                    for ($i = 01; $i <= 12; $i++) {
                        echo "<option>" . $i . "</option>";
                    }
                    ?>
                </select>
                Year
                <select name='year'>
                    <?php
                    for ($i = 2015; $i <= 2099; $i++) {
                        echo "<option>" . $i . "</option>";
                    }
                    ?>
                </select></td>
            <td><span class="error">* <?php echo $dateErr; ?></span></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit"></td>
        </tr>
    </table>
</form>
<?php
require('footer.php');
?>


