<html>
<title>YRCW Package Tracker</title>

<head>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <!-- =============== Nav Bar of website ==================== -->
<ul>
  <img src="Logo.png" alt="YRC_Logo" style="height:inherit;float:left;">
  <li><a href="index.php" href="#index">Home</a></li>
  <li><a href="#contact">Contact</a></li>
  <li><a href="#about">About</a></li>
</ul>

<!-- ================ #Pro Number Entry Form =============== -->
<form action="index.php" method="post">
  <br>
  Enter Pro#:<br>
  <input type="text" name="pro">
  <input type="submit" value="Submit" required>
  <br>
</form>



<?php
/* ============== Connect using SQL Server Authentication.============== */
$conn = new PDO("sqlsrv:Server=ywsqldw01v\dw;Database=Datain");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$conn)
{
  echo "NO CONNECTION";
}
?>


<!-- ==================== Latest Status Box ====================== -->
<div class="Latest">
  <h2>
    <?php
    if (!$_POST){echo "N/A";}
    else {
    $PRO = $_POST["pro"];
    Status($conn, $PRO);
  }
     ?>


</h2>

</div>


<!-- ==================== Aditional Events Log ==================== -->


<?php

// ======= Concetion to Verizon Connect Local Host Database ============
$conn = mysqli_connect("localhost", "root", '', "connect");

$query = "SELECT Id, LastLocationAddress FROM Vzcon WHERE Id = $PRO"; //You don't need a ; like you do in SQL
$result = mysqli_query($conn, $query);

echo "<table> <tr> <th>ID</th> <th>Last Locatoin</th> </tr>"; // start a table tag in the HTML

while($row = $result->fetch_assoc()){   //Creates a loop to loop through results
echo "<tr><td>" . $row['Id'] . "</td><td>" . $row['LastLocationAddress'] . "</td></tr>";  //$row['index'] the index here is a field name
}

echo "</table>"; //Close the table in HTML
?>

<!-- ==================== Split of YRC TractorID ==================== -->
<?php
//Test to pull YRCTractorid and split it into 2 variables to make it queryable
if (!$_POST){echo "N/A";}
else {
  $query = "SELECT YRCTractorid FROM Vzcon WHERE Id = $PRO";
  $result = mysqli_query($conn, $query);
  while ($row = $result->fetch_assoc()) {
    echo $row['YRCTractorid'];

//YRCTractorid happens to be 2 variables from bdd concatonated
//This splits it into the base parts.
    $chars = preg_split('/([0-9])/', $row['YRCTractorid'], 2, PREG_SPLIT_DELIM_CAPTURE);
    print_r($chars);

//cmpny coresponds to Standard_Carrier_Alpha_CD & Numb to Equipment_unit_NB
    $cmpny = $chars[0];
    $numb = $chars[1] . $chars[2];
    echo $cmpny;
    echo $numb;
  }
}

mysqli_close($conn);
?>



<?php

/* Connect using SQL Server Authentication.
$conn = new PDO("sqlsrv:Server=ywsqldw01v\dw;Database=Datain");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$conn){echo "NO CONNECTION";}
*/

// ============= Current Status Bar Function ==============
function Status($conn,$PRO) {
  $sql = "SELECT TOP 1000 Shipment_KEY, Shipment_Due_DT FROM [DataIn].[OPS].[Shipment] WHERE Pro_NB = '$PRO'";
  foreach ($conn->query($sql) as $row) {
    print "PRO#:   " . "$PRO";
    echo str_repeat("&nbsp;", 5);
    print  "Key: " . $row['Shipment_KEY'] . "\t";
    echo str_repeat("&nbsp;", 5);
    print "Est.Delivery Date: " . $row['Shipment_Due_DT'] . "\t";
  }
}
 ?>

</body>

</html>
