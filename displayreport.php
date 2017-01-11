
<?php
require ("config.php");
?>
<head>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<div id="header"><h1><?php echo $config_sitename; ?></h1></div>
<div id="menu" align="left">
    &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $config_basedir; ?>report.php">Home</a>
</div>

<div id="container">



    <?php
    $id = $_GET['id'];
    $todate = $_GET['tyear'] . "-" . $_GET['tmonth'] . "-" . $_GET['tday'];
    $fromdate = $_GET['fyear'] . "-" . $_GET['fmonth'] . "-" . $_GET['fday'];

    if ($id == 'Report1') {
        //echo "true";

        $query1 = "SELECT APN.PID ,P.PNAME ,P.PTYPE FROM APPEARS_IN AS APN INNER JOIN PRODUCT AS P ON P.PID= APN.PID INNER JOIN CART AS C ON C.CartID=APN.CartID WHERE DATE>='" . $fromdate . "' AND DATE<= '" . $todate . "' AND C.Status=2 GROUP BY PID ORDER BY COUNT(*) DESC LIMIT 1";
        echo "<h2>Result for most frequently sold products.</h2>";
        echo "<h3>From Date:".$fromdate." To Date:".$todate."</h3>";
        $querq1res = mysql_query($query1);
        echo '<table border="1" cellpadding="5">';
        echo '<tr>';
        echo '<th style="width: 150px;">PRODUCT ID</th>';
        echo '<th style="width: 150px;">PRODUCT NAME</th>';
        echo '<th style="width: 150px;">PRODUCT TYPE</th>';
        echo '</tr>';
        while ($query1row = mysql_fetch_assoc($querq1res)) {
            echo '<tr>';
            echo '<td style="width: 150px;" align="center">' . $query1row['PID'] . '</td>';
            echo '<td style="width: 150px;" align="center">' . $query1row['PNAME'] . '</td>';
            echo '<td style="width: 150px;" align="center">' . $query1row['PTYPE'] . '</td>';
            echo '</tr>';
        }
        echo '</table><br><br>';
    }
    if ($id == 'Report2') {
        //echo "true";
        $query1 = "SELECT APN.PID, PNAME, PTYPE FROM APPEARS_IN APN INNER JOIN CART C ON C.CartID = APN.CartID INNER JOIN PRODUCT P ON P.PID = APN.PID WHERE DATE>='" . $fromdate . "' AND DATE<= '" . $todate . "' AND C.Status=2 GROUP BY PID ORDER BY COUNT(DISTINCT CID) DESC LIMIT 1";
        echo "<h2>Result for products which are sold to the highest number of distinct customers.</h2>";
        echo "<h3>From Date:".$fromdate." To Date:".$todate."</h3>";
        $querq1res = mysql_query($query1);
        echo '<table border="1" cellpadding="5">';
        echo '<tr>';
        echo '<th style="width: 150px;">PRODUCT ID</th>';
        echo '<th style="width: 150px;">PRODUCT NAME</th>';
        echo '<th style="width: 150px;">PRODUCT TYPE</th>';
        echo '</tr>';
        while ($query1row = mysql_fetch_assoc($querq1res)) {
            echo '<tr>';
            echo '<td style="width: 150px;" align="center">' . $query1row['PID'] . '</td>';
            echo '<td style="width: 150px;" align="center">' . $query1row['PNAME'] . '</td>';
            echo '<td style="width: 150px;" align="center">' . $query1row['PTYPE'] . '</td>';
            echo '</tr>';
        }
        echo '</table><br><br>';
    }
    if ($id == 'Report3') {
        //echo "true";
        $query1 = "SELECT CT.FName, CT.LName, SUM(TOTAL) FROM CART C INNER JOIN CUSTOMER CT ON CT.CID = C.CID WHERE DATE>='" . $fromdate . "' AND DATE<= '" . $todate . "' AND C.Status=2 GROUP BY C.CID ORDER BY SUM(TOTAL) DESC LIMIT 10";
        echo "<h2>Result for 10 best customers (in terms of money spent) in descending order.</h2>";
        echo "<h3>From Date:".$fromdate." To Date:".$todate."</h3>";
        $querq1res = mysql_query($query1);
        echo '<table border="1" cellpadding="5">';
        echo '<tr>';
        echo '<th style="width: 150px;">FIRST NAME</th>';
        echo '<th style="width: 150px;">LAST NAME</th>';
        echo '<th style="width: 150px;">TOTAL SPENT</th>';
        echo '</tr>';
        while ($query1row = mysql_fetch_assoc($querq1res)) {
            echo '<tr>';
            $round=round($query1row['SUM(TOTAL)'],2);
            echo '<td style="width: 150px;" align="center">' . $query1row['FName'] . '</td>';
            echo '<td style="width: 150px;" align="center">' . $query1row['LName'] . '</td>';
            echo '<td style="width: 150px;" align="center">' . $round . '</td>';
            echo '</tr>';
        }
        echo '</table><br><br>';
    }
    if ($id == 'Report4') {
        //echo "true";
        $query1 = "SELECT ZIP FROM CART C INNER JOIN SHIPPING_ADDRESS SA ON SA.SAName = C.SAName AND SA.CID = C.CID WHERE DATE>='" . $fromdate . "' AND DATE<= '" . $todate . "' AND C.Status=2 GROUP BY Zip ORDER BY COUNT(ZIP) DESC LIMIT 5";
        echo "<h2>Result for 5 best zip codes (in terms of shipments made).</h2>";
        echo "<h3>From Date:".$fromdate." To Date:".$todate."</h3>";
        $querq1res = mysql_query($query1);
        echo '<table border="1" cellpadding="5">';
        echo '<tr>';
        echo '<th style="width: 150px;">ZIP CODE</th>';
        echo '</tr>';
        while ($query1row = mysql_fetch_assoc($querq1res)) {
            echo '<tr>';
            echo '<td style="width: 150px;" align="center">' . $query1row['ZIP'] . '</td>';
            echo '</tr>';
        }
        echo '</table><br><br>';
    }
    if ($id == 'Report5') {
        //echo "true";
        $query1 = "SELECT PTYPE, AVG(SUM) FROM (SELECT APN.PID, PTYPE, SUM(PriceSold) AS SUM FROM APPEARS_IN AS APN INNER JOIN PRODUCT AS P ON P.PID= APN.PID INNER JOIN CART AS C ON C.CartID=APN.CartID WHERE DATE>='" . $fromdate . "' AND DATE<= '" . $todate . "' GROUP BY PTYPE,APN.PID) AS CTE GROUP BY PTYPE";
        echo "<h2>Result for average selling product price per product type for desktops, laptops and printers.</h2>";
        echo "<h3>From Date:".$fromdate." To Date:".$todate."</h3>";
        $querq1res = mysql_query($query1);
        echo '<table border="1" cellpadding="5">';
        echo '<tr>';
        echo '<th style="width: 150px;">PRODUCT TYPE</th>';
        echo '<th style="width: 150px;">AVERAGE</th>';
        echo '</tr>';
        while ($query1row = mysql_fetch_assoc($querq1res)) {
            echo '<tr>';
            $round=round($query1row['AVG(SUM)'],2);
            echo '<td style="width: 150px;" align="center">' . $query1row['PTYPE'] . '</td>';
            echo '<td style="width: 150px;" align="center">' . $query1row['AVG(SUM)'] . '</td>';
            echo '</tr>';
        }
        echo '</table><br><br>';
    }
    ?>

    <a href="report.php">Back to Generate Report page</a>


