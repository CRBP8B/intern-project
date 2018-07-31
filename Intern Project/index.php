<html>
<title>YRCW Package Tracker</title>

<?php
$conn = mysqli_connect("localhost", "root", '', "connect");
 ?>

<head>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <!-- Nav Bar of website-->
<ul>
  <img src="Logo.png" alt="YRC_Logo" style="height:inherit;float:left;">
  <li><a href="index.php" href="#home">Home</a></li>
  <li><a href="#contact">Contact</a></li>
  <li><a href="#about">About</a></li>
</ul>

<!-- Input to form -->
<form action="index.php" method="post">
  <br>
  Enter Pro#:<br>
  <input type="text" name="pro">
  <input type="submit" value="Submit">
  <br>
</form>

<!-- Latest Status Box -->
<div class="Latest">
  <h2>
    Pro#:
    <?php
    if (!$_POST){echo "N/A";}
    else {
      echo $_POST["pro"];
    $PRO = $_POST["pro"];
  }
     ?>
     &ensp;
    Current Status:
    <?php
    if (!$_POST){echo "N/A";}
    else {
      $query = "SELECT IsignitionOn FROM Vzcon WHERE Id = $PRO";
      $result = mysqli_query($conn, $query);
      while ($row = $result->fetch_assoc()) {
        echo $row['IsignitionOn']."<br>";
    }
}

    ?>

     &ensp;
    Est. Delivery Date: 6/27/18
</h2>

</div>

<!-- Aditional Events Log -->
<?php

$query = "SELECT Id, LastLocationAddress FROM Vzcon WHERE Id = $PRO"; //You don't need a ; like you do in SQL
$result = mysqli_query($conn, $query);

echo "<table> <tr> <th>ID</th> <th>Last Location</th> </tr>"; // start a table tag in the HTML

while($row = $result->fetch_assoc()){   //Creates a loop to loop through results
echo "<tr><td>" . $row['Id'] . "</td><td>" . $row['LastLocationAddress'] . "</td></tr>";  //$row['index'] the index here is a field name
}

echo "</table>"; //Close the table in HTML
?>

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
$serverName = "sqlsrv:Server=ywsqldw01v\dw;Database=Dataln";
$uid = "SVC.Intern_Project";
$pwd = "Df@#sd$&rty!";
$databaseName = "Dataln";

/* Connect using SQL Server Authentication. */
$conn = new PDO("sqlsrv:Server=ywsqldw01v\dw;Database=Datain");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$conn = odbc_connect('Test', $uid ,$pwd);
if (!$conn)
{
  echo "NO CONNECTION";
}
else {
  get($conn);
}

function get($conn) {
  $sql = 'SELECT Create_TS FROM [CSM].[Customer_Role_Type]';
  foreach ($conn->query($sql) as $row) {
    print $row['Create_TS'] . "\t";
  }
}
 ?>

</body>

</html>
