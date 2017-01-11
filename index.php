<head>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <script language="javascript" type="text/javascript"> 
function windowClose() { 
window.open('','_parent',''); 
window.close();
} 
</script>
</head>
<?php
session_start();
require("header.php");
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
?>

<h1>Welcome!!</h1>
Welcome to the <strong>
    <?php
    echo $config_sitename;
    ?>
</strong> website. Click on one of the pages to explore the site. We have a wide range of different products available.
<?php
require("footer.php");
print "ajskdgkjhkqsjdn";
?>
<input type="button" value="Close this window" onclick="windowClose();">
