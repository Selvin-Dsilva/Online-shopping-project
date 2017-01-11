<?php
require 'config.php';
$query = mysql_query("SELECT DISTINCT Ptype FROM PRODUCT");
?>
<h3>Product Catagory</h3>
<?php
echo "<table>";
while ($catrow = mysql_fetch_array($query)) {
    echo '<tr><td><a href="product.php?id=' . $catrow['Ptype'] . '">' . $catrow['Ptype'] . '</a></td></tr>';
}
echo "</table>";
?>