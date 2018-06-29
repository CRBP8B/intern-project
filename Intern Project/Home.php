<html>
<title>YRCW Package Tracker</title>

<head>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <!-- Nav Bar of website-->
<ul>
  <img src="Logo.png" alt="YRC_Logo" style="height:inherit;float:left;">
  <li><a href="Home.php" href="#home">Home</a></li>
  <li><a href="#contact">Contact</a></li>
  <li><a href="#about">About</a></li>
</ul>

<!-- Input to form -->
<form action="Home.php" method="post">
  <br>
  Enter Pro#:<br>
  <input type="text" name="pro" value="0000000">
  <input type="submit" value="Submit">
  <br>
</form>

<!-- Latest Status Box -->
<div class="Latest">
  <h2>
    Pro#: <?php echo $_POST["pro"]; ?> &ensp;
    Current Status: Intransit &ensp;
    Est. Delivery Date: 6/27/18
</h2>

</div>

<!-- Aditional Events Log -->
<table>
  <tr>
    <th>Recent Status</th>
    <th>Date/Time</th>
  </tr>
  <tr>
    <td>Warehouse Austin, TX</td>
    <td>8:15 - 6/25/18</td>
  </tr>
  <tr>
    <td>Warehouse Kansas City, MO</td>
    <td>9:30 - 6/25/18</td>
  </tr>
  <tr>
    <td>On its way</td>
    <td>12:40 - 6/26/18</td>
  </tr>
</table>

</body>

</html>
