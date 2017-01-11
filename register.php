<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
require("config.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/* $statussql = "SELECT status FROM orders WHERE id = " .$_SESSION['SESS_ORDERNUM'];
  $statusres = mysql_query($statussql);
  $statusrow = mysql_fetch_assoc($statusres);
  $status = $statusrow['status']; */
$UnameErr = $FnameErr = $LnameErr = $EmailErr = $PhoneErr = $PassErr1 = $PassErr2 = "";
$Uname = $Fname = $Lname = $Email = $Phone = $Pass1 = $Pass2 = "";
if ($_POST['submit']) {
    if (empty($_POST["UnameBox"])) {
        $UnameErr = "User name is required";
        echo $UnameErr . '<br>';
        echo $config_basedir . '<br>';
        echo $_SERVER['SCRIPT_NAME'] . '<br>';
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        $Uname = test_input($_POST["UnameBox"]);
    }
    if (empty($_POST["FnameBox"])) {
        $FnameErr = "First name is required";
        header("Location: " . $_SERVER["PHP_SELF"]);
    } else {
        $Fname = test_input($_POST["FnameBox"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $Fname)) {
            $FnameErr = "Only letters and white space allowed";
            header("Location: " . $basedir . "register.php");
        }
    }
    if (empty($_POST["LnameBox"])) {
        $LnameErr = "Last name is required";
        header("Location: " . $basedir . "register.php");
    } else {
        $Lname = test_input($_POST["LnameBox"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/", $Lname)) {
            $LnameErr = "Only letters and white space allowed";
            header("Location: " . $basedir . "register.php");
        }
    }
    if (empty($_POST["EmailBox"])) {
        $EmailErr = "Email is required";
        header("Location: " . $basedir . "register.php");
    } else {
        $Email = test_input($_POST["EmailBox"]);
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $EmailErr = "Invalid email format";
            header("Location: " . $basedir . "register.php");
        }
    }
    if (empty($_POST["PhoneBox"])) {
        $PhoneErr = "Phone number is required";
        header("Location: " . $basedir . "register.php");
    } else {
        $Phone = test_input($_POST["PhoneBox"]);
    }
    /* if (empty($_POST["passBox1"])) {
      $PassErr1 = "Email is required";
      } else {
      $Pass1 = test_input($_POST["passBox1"]);
      }
      if (empty($_POST["passBox2"])) {
      $PassErr2 = "Email is required";
      } else {
      $Pass2 = test_input($_POST["passBox2"]);
      } */
    if (!empty($_POST["passBox1"]) && ($_POST["passBox1"] == $_POST["passBox2"])) {
        $Pass1 = test_input($_POST["passBox1"]);
        $Pass2 = test_input($_POST["passBox2"]);
        if (strlen($_POST["passBox1"]) <= '8') {
            $PassErr1 = "Your Password Must Contain At Least 8 Characters!";
            header("Location: " . $basedir . "register.php");
        } elseif (!preg_match("#[0-9]+#", $Pass1)) {
            $PassErr1 = "Your Password Must Contain At Least 1 Number!";
            header("Location: " . $basedir . "register.php");
        } elseif (!preg_match("#[A-Z]+#", $Pass1)) {
            $PassErr1 = "Your Password Must Contain At Least 1 Capital Letter!";
            header("Location: " . $basedir . "register.php");
        } elseif (!preg_match("#[a-z]+#", $Pass1)) {
            $PassErr1 = "Your Password Must Contain At Least 1 Lowercase Letter!";
            header("Location: " . $basedir . "register.php");
        }
    } elseif (!empty($_POST["passBox1"])) {
        $PassErr2 = "Please Check You've Entered Or Confirmed Your Password!";
        header("Location: " . $basedir . "register.php");
    }
} else {
    require("header.php");
    echo "<h1>Register</h1>";
    if (isset($_GET['error']) == TRUE) {
        echo "<strong>Please fill in the missing information from the form</strong>";
    }
    echo "<form action='" . $_SERVER['SCRIPT_NAME'] . "' method='POST'>";
    ?>
    <table>
        <tr>
            <td>User Name:</td>
            <td><input type="text" name="UnameBox" value="<?php echo $Uname; ?>"></td>
            <td><span>* <?php echo $UnameErr; ?></span></td>
        </tr>
        <tr>
        <tr>
            <td>First Name:</td>
            <td><input type="text" name="FnameBox" value="<?php echo $Fname; ?>"></td>
            <td><span>* <?php echo $FnameErr; ?></span></td>
        </tr>
        <tr>
            <td>Last Name:</td>
            <td><input type="text" name="LnameBox" value="<?php echo $Lname; ?>"></td>
            <td><span> <?php echo $LnameErr; ?></span></td>
        </tr>
        <tr>
            <td>Email Address:</td>
            <td><input type="text" name="EmailBox" value="<?php echo $Email; ?>"></td>
            <td><span>* <?php echo $EmailErr; ?></span></td>
        </tr>
        <tr>
            <td>Phone Number:</td>
            <td><input type="text" name="PhoneBox" value="<?php echo $Phone; ?>"></td>
            <td><span>* <?php echo $PhoneErr; ?></span></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="passBox1" /></td>
            <td><span>* <?php echo $PassErr1; ?></span></td>
        </tr>
        <tr>
            <td>Confirm Password:</td>
            <td><input type="password" name="passBox2"></td>
            <td><span>* <?php echo $PassErr2; ?></span></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit"></td>
        </tr>
    </table>
    </form>
    <?php
}
require("footer.php");
?>


