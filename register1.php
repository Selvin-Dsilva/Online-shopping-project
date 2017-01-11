<?php
require 'header.php';
require 'functions.php';
session_start();
ob_start();
$UnameErr = $FnameErr = $LnameErr = $EmailErr = $PhoneErr = $PassErr1 = $PassErr2 = "";
$Uname = $Fname = $Lname = $Email = $Phone = $Pass1 = $Pass2 = "";
$flag = "";
$error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flag = 0;
    if (empty($_POST["Uname"])) {
        $UnameErr = "Name is required";
        $flag = 1;
    } else {
        $Uname = test_input($_POST["Uname"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $Uname)) {
            $UnameErr = "Only letters and white space allowed";
            $flag = 1;
        }
    }

    if (empty($_POST["Fname"])) {
        $FnameErr = "Name is required";
        $flag = 1;
    } else {
        $Fname = test_input($_POST["Fname"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $Fname)) {
            $FnameErr = "Only letters and white space allowed";
            $flag = 1;
        }
    }

    if (empty($_POST["Lname"])) {
        $LnameErr = "Name is required";
        $flag = 1;
    } else {
        $Lname = test_input($_POST["Lname"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $Lname)) {
            $LnameErr = "Only letters and white space allowed";
            $flag = 1;
        }
    }

    if (empty($_POST["email"])) {
        $EmailErr = "Email is required";
        $flag = 1;
    } else {
        $Email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $EmailErr = "Invalid email format";
            $flag = 1;
        }
    }

    if (empty($_POST["phone"])) {
        $PhoneErr = "Phone number is required";
        $flag = 1;
    } else {
        $Phone = test_input($_POST["phone"]);
    }


    if (empty($_POST["pass1"])) {
        $PassErr1 = "Password required";
        $flag = 1;
        //header("Location: " . $basedir . "register.php");
    } elseif (empty($_POST["pass2"])) {
        $PassErr2 = "Please re-type the password";
        $flag = 1;
        //header("Location: " . $basedir . "register.php");
    }

    if (!empty($_POST["pass1"]) && ($_POST["pass1"] == $_POST["pass2"])) {
        $Pass1 = test_input($_POST["pass1"]);
        $Pass2 = test_input($_POST["pass2"]);
        if (strlen($_POST["pass1"]) <= '8') {
            $PassErr1 = "Your Password Must Contain At Least 8 Characters!";
            $flag = 1;
        } elseif (!preg_match("#[0-9]+#", $Pass1)) {
            $PassErr1 = "Your Password Must Contain At Least 1 Number!";
            $flag = 1;
        } elseif (!preg_match("#[A-Z]+#", $Pass1)) {
            $PassErr1 = "Your Password Must Contain At Least 1 Capital Letter!";
            $flag = 1;
        } elseif (!preg_match("#[a-z]+#", $Pass1)) {
            $PassErr1 = "Your Password Must Contain At Least 1 Lowercase Letter!";
            $flag = 1;
        }
    } elseif (!empty($_POST["pass1"])) {
        $PassErr2 = "Please Check You've Entered Or Confirmed Your Password!";
        $flag = 1;
    }

    $sqlchk = mysql_query("SELECT * FROM CUSTOMER");
    while ($custrow = mysql_fetch_assoc($sqlchk)) {
        if ($custrow['FName'] == $Fname && $custrow['LName'] == $Lname) {
            $error = "You are already registered, please login";
            $flag = 1;
        }
    }


    if ($flag == 0) {
        $addcust = "INSERT INTO CUSTOMER(FName, LName, EMail, Phone)VALUES('" . $Fname . "', '" . $Lname . "', '" . $Email . "', " . $Phone . ")";
        mysql_query($addcust);
        $custid = mysql_insert_id();
        $addlogin = "INSERT INTO LOGINS(CID, username, password)VALUES(" . $custid . ", '" . $Uname . "', '" . $Pass1 . "')";
        mysql_query($addlogin);

        session_register("SESS_LOGGEDIN");
        session_register("SESS_USERNAME");
        session_register("SESS_USERID");
        $_SESSION['SESS_LOGGEDIN'] = 1;
        $_SESSION['SESS_USERNAME'] = $Uname;
        $_SESSION['SESS_USERID'] = $custid;

        $getord = mysql_query("SELECT * FROM CART WHERE Session='" . session_id() . "' AND CartID=" . $_SESSION['SESS_ORDERNUM']);
        $numrow = mysql_num_rows($getord);
        $getrow = mysql_fetch_assoc($getord);
        if ($numrow >= 1) {
            $updord = "UPDATE CART SET CID=" . $custid . ",Registered=1,Session='' WHERE Session='" . session_id() . "' AND CartID=" . $_SESSION['SESS_ORDERNUM'];
            mysql_query($updord);
        }
        header("Location:" . $config_basedir . "index.php");
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
            <td>User Name: </td>
            <td><input type="text" name="Uname" value="<?php echo $Uname; ?>"></td>
            <td><span class="error">* <?php echo $UnameErr; ?></span></td>
        </tr>
        <tr>
            <td>First Name: </td>
            <td><input type="text" name="Fname" value="<?php echo $Fname; ?>"></td>
            <td><span class="error">* <?php echo $FnameErr; ?></span></td>
        </tr>
        <tr>
            <td>Last Name: </td>
            <td><input type="text" name="Lname" value="<?php echo $Lname; ?>"></td>
            <td><span class="error">* <?php echo $LnameErr; ?></span></td>
        </tr>
        <tr>
            <td>E-mail: </td>
            <td><input type="text" name="email" value="<?php echo $Email; ?>"></td>
            <td><span class="error">* <?php echo $EmailErr; ?></span></td>
        </tr>    
        <tr>
            <td>Phone Number: </td>
            <td><input type="text" name="phone" value="<?php echo $Phone; ?>"></td>
            <td><span class="error">* <?php echo $PhoneErr; ?></span></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="pass1" /></td>
            <td><span class="error">* <?php echo $PassErr1; ?></span></td>
        </tr>
        <tr>
            <td>Confirm Password:</td>
            <td><input type="password" name="pass2"></td>
            <td><span class="error">* <?php echo $PassErr2; ?></span></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit"></td>
        </tr>
    </table>
</form>
<?php
echo '<h2>' . $error . '</h2><br><br><br>';
require('footer.php');
?>



