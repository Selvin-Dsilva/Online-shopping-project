<?php
ob_start();
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
require ("config.php");

if ($_POST['submit']) {
    if ($_POST['addselecBox'] == "") {
        header("Location:" . $config_basedir . "report.php?error=1");
    } else {
        header("Location:" . $config_basedir . "displayreport.php?id=" . $_POST['addselecBox'] . "&fday=" . $_POST['fday'] . "&fmonth=" . $_POST['fmonth'] . "&fyear=" . $_POST['fyear'] . "&tday=" . $_POST['tday'] . "&tmonth=" . $_POST['tmonth'] . "&tyear=" . $_POST['tyear']);
    }
} else {
    ?>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    <div id="header"><h1><?php echo $config_sitename; ?></h1></div>
    <div id="menu" align="left">
        &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $config_basedir; ?>report.php">Home</a>
    </div>

    <div id="container">
        <form action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="POST">
            <?php
            if ($_GET['error'] == 1) {
                echo '<h2>You have not selected report type!</h2><br>';
            }
            echo '<strong>Please select report type:</strong><br>';
            $counter = 1;
            echo "<br><tr>";
            echo '<td><input type = "radio" name = "addselecBox" value = "Report1" checked>Report 1: The most frequently sold products.</td>';
            echo "</tr><br>";
            echo "<br><tr>";
            echo '<td><input type = "radio" name = "addselecBox" value = "Report2" >Report 2: The products which are sold to the highest number of distinct customers.</td>';
            echo "</tr><br>";
            echo "<br><tr>";
            echo '<td><input type = "radio" name = "addselecBox" value = "Report3" >Report 3: The 10 best customers (in terms of money spent) in descending order.</td>';
            echo "</tr><br>";
            echo "<br><tr>";
            echo '<td><input type = "radio" name = "addselecBox" value = "Report4" >Report 4: The 5 best zip codes (in terms of shipments made).</td>';
            echo "</tr><br>";
            echo "<br><tr>";
            echo '<td><input type = "radio" name = "addselecBox" value = "Report5" >Report 5: The average selling product price per product type for desktops, laptops and printers.</td>';
            echo "</tr><br>";
            ?>
            <br><br>
            <table>
                <tr>
                    <td>From Date:</td>
                    <td>
                        Day
                        <select name='fday'>
                            <?php
                            for ($i = 1; $i <= 31; $i++) {
                                echo "<option>" . $i . "</option>";
                            }
                            ?>
                        </select>                        
                        Month
                        <select name='fmonth'>
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                echo "<option>" . $i . "</option>";
                            }
                            ?>
                        </select>
                        Year
                        <select name='fyear'>
                            <?php
                            for ($i = 2000; $i <= 2099; $i++) {
                                echo "<option>" . $i . "</option>";
                            }
                            ?>
                        </select></td>
                    <td><span class="error"> <?php echo $dateErr; ?></span></td>
                </tr>
                <tr>
                    <td>To Date:</td>
                    <td>
                        Day
                        <select name='tday'>
                            <?php
                            for ($i = 1; $i <= 31; $i++) {
                                echo "<option>" . $i . "</option>";
                            }
                            ?>
                        </select>                        
                        Month
                        <select name='tmonth'>
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                echo "<option>" . $i . "</option>";
                            }
                            ?>
                        </select>
                        Year
                        <select name='tyear'>
                            <?php
                            for ($i = 2000; $i <= 2099; $i++) {
                                echo "<option>" . $i . "</option>";
                            }
                            ?>
                        </select></td>
                    <td><span class="error"> <?php echo $dateErr; ?></span></td>
                </tr>
            </table>
            <input type="submit" name="submit" value="Select" /><br><br><br>
            <a href="adminindex.php">Logout</a>


        </form>
        <?php
    }
    require ("footer.php");
    ?>
