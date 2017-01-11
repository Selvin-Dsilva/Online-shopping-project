<?php
session_start();
ob_start();
require("config.php");

if ($_POST['ccardsubmit']) {
    header("Location:creditselect-add.php");
} else if ($_POST['clinesubmit']) {
    header("Location:creditline.php");
} else {
    require("header.php");
    echo "<h1>Select Payment Method</h1>";
    ?>
    <form action='selectmethod.php' method='POST'>
        <table cellspacing=10>
            <tr>
                <td><input type="submit" name="ccardsubmit" value="Credit Card"></td>

                <td><input type="submit" name="clinesubmit" value="Credit Line"></td>
            </tr>

        </table>
    </form>
    <?php
}
require("footer.php");
?>
