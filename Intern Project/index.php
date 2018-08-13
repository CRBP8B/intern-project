<html>
<title>YRCW Package Tracker</title>

<head>
  <link rel="stylesheet" href="style.css">
</head>

<body style="background:url('bg.png') repeat">

  <!-- =============== Nav Bar of website ==================== -->
<ul>
  <img src="Logo.png" alt="YRC_Logo" style="height:inherit;float:left;">
  <li><a href="index.php">Home</a></li>
  <li><a href="contact_form.html">Contact</a></li>
  <li><a href="About.html">About</a></li>
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
    echo "<br>";
    echo "<br>";
    DeliveryAddress($conn, $PRO);
    echo "<br>";
    echo "<br>";
    CurrentStatus($conn, $PRO);}
     ?>
   </h2>
</div>

<?php
PreviousStatus($conn,$PRO);
?>

<!-- ==================== Last Location of Tractor ==================== -->
<form action="index.php" method="post">
  <br>
  Enter TractorID#:<br>
  <input type="text" name="pro">
  <input type="submit" value="Submit" required>
  <br>
</form>

<?php

// ======= Concetion to Verizon Connect Local Host Database ============
$conn = mysqli_connect("localhost", "root", '', "connect");

$query = "SELECT Id, LastLocationAddress FROM Vzcon WHERE Id = $PRO"; //You don't need a ; like you do in SQL
$result = mysqli_query($conn, $query);

echo "<table> <tr> <th>ID</th> <th>Last Location</th> </tr>"; // start a table tag in the HTML

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
  $sql = "SELECT TOP 10000 Shipment_Due_DT, Pickup_DT FROM [DataIn].[OPS].[Shipment] WHERE Pro_NB = '$PRO'";
  foreach ($conn->query($sql) as $row) {
    print "PRO#:   " . "$PRO";
    echo str_repeat("&nbsp;", 5);
    print  "Date of Pickup: " . $row['Pickup_DT'] . "\t";
    echo str_repeat("&nbsp;", 5);
    print "Est.Delivery Date: " . $row['Shipment_Due_DT'] . "\t";
  }
}

/*
This is the query that will return all of the pro numbers that will work With both Our tracker and the YRC Frieght Tracker

SELECT Pro_NB
        FROM [DataIn].[OPS].[Operations_Shipment], [DataIn].[OPS].[Customer_Trap], [DataIn].[OPS].[Shipment_Customer]
        WHERE [DataIn].[OPS].[Operations_Shipment].Bus_ID = [DataIn].[OPS].[Customer_Trap].Bus_ID
        AND [DataIn].[OPS].[Operations_Shipment].Current_terminal_ID = [DataIn].[OPS].[Customer_Trap].Current_Terminal_ID
        AND [DataIn].[OPS].[Customer_Trap].Consignee_Address = [DataIn].[OPS].[Shipment_Customer].Street_Address_TX
        Group By Pro_NB
*/

function DeliveryAddress($conn,$PRO){
  $sql = "SELECT TOP 1 Consignee_Address, City_NM, State_Province_Abbreviation_CD
          FROM [DataIn].[OPS].[Operations_Shipment], [DataIn].[OPS].[Customer_Trap], [DataIn].[OPS].[Shipment_Customer]
          WHERE [DataIn].[OPS].[Operations_Shipment].Pro_NB = '$PRO'
          AND [DataIn].[OPS].[Operations_Shipment].Bus_ID = [DataIn].[OPS].[Customer_Trap].Bus_ID
          AND [DataIn].[OPS].[Operations_Shipment].Current_terminal_ID = [DataIn].[OPS].[Customer_Trap].Current_Terminal_ID
          AND [DataIn].[OPS].[Customer_Trap].Consignee_Address = [DataIn].[OPS].[Shipment_Customer].Street_Address_TX
          Order By Pro_NB desc";
  foreach ($conn->query($sql) as $row) {
    print  "Delivery Address: " . $row['Consignee_Address'] . ", " . $row['City_NM'] . ", " . $row['State_Province_Abbreviation_CD'] . "\t";
  }
}

function CurrentStatus($conn,$PRO){
  $sql = "SELECT TOP 1 Shipment_Status_Type_NM, Status_TS
          FROM [DataIn].[OPS].[Shipment], [DataIn].[OPS].[Shipment_Status], [DataIn].[OPS].[Shipment_Status_Type]
          WHERE [DataIn].[OPS].[Shipment].Pro_NB = '$PRO'
          AND [DataIn].[OPS].[Shipment].Shipment_KEY = [DataIn].[OPS].[Shipment_Status].Shipment_KEY
          AND [DataIn].[OPS].[Shipment_Status].Shipment_Status_Type_KEY = [DataIn].[OPS].[Shipment_Status_Type].Shipment_Status_Type_KEY
		      order by Status_TS desc";
  foreach ($conn->query($sql) as $row) {
    print  "Current Status: " . $row['Shipment_Status_Type_NM'] . ", as of " . $row['Status_TS'] . "\t";
  }
}

function PreviousStatus($conn,$PRO){
  $sql = "SELECT Shipment_Status_Type_NM, Status_TS
          FROM [DataIn].[OPS].[Shipment], [DataIn].[OPS].[Shipment_Status], [DataIn].[OPS].[Shipment_Status_Type]
          WHERE [DataIn].[OPS].[Shipment].Pro_NB = '$PRO'
          AND [DataIn].[OPS].[Shipment].Shipment_KEY = [DataIn].[OPS].[Shipment_Status].Shipment_KEY
          AND [DataIn].[OPS].[Shipment_Status].Shipment_Status_Type_KEY = [DataIn].[OPS].[Shipment_Status_Type].Shipment_Status_Type_KEY
          order by Status_TS desc";

          echo "<table> <tr> <th>Status</th> <th>Updated On</th> </tr>"; // start a table tag in the HTML

          foreach($conn->query($sql) as $row){   //Creates a loop to loop through results
          echo "<tr><td>" . $row['Shipment_Status_Type_NM'] . "</td><td>" . $row['Status_TS'] . "</td></tr>";  //$row['index'] the index here is a field name
          }

          echo "</table>";
        }

 ?>

</body>

</html>
