<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  // Redirect to the login page
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - Banking Report</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ORLY</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="index">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="view">View Logs</a>
        </li>
      </ul>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="logout">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h1>Dashboard</h1>
  <form method="GET" class="mb-3">
    <div class="form-row">
      <div class="col-md-6 mb-3">
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" class="form-control">
      </div>
      <div class="col-md-6 mb-3">
        <button type="submit" class="btn btn-primary">Filter</button>
      </div>
    </div>
  </form>

  <?php
require_once 'config.php';

// Create a database connection using the configuration variables
$conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Get the selected date from the query string
  $selectedDate = isset($_GET['date']) ? $_GET['date'] : '';

  // SQL query to retrieve the summary data
  $sql = "SELECT SUM(amount) AS total_amount, name FROM products WHERE date = '$selectedDate' GROUP BY name";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<h2>Summary for $selectedDate</h2>";
    echo "<table class='table'>";
    echo "<thead><tr><th>Shop Name</th><th>Total Amount</th></tr></thead>";
    echo "<tbody>";

    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>" . $row['name'] . "</td><td>" . $row['total_amount'] . "</td></tr>";
    }

    echo "</tbody>";
    echo "</table>";
  } else {
    echo "<p>please select the date $selectedDate.</p>";
  }

  // Calculate the total amount banked for the selected date
  $totalAmountSql = "SELECT SUM(amount) AS total_amount FROM products";
  if (!empty($selectedDate)) {
    $totalAmountSql .= " WHERE date = '$selectedDate'";
  }
  $totalAmountResult = $conn->query($totalAmountSql);
  $totalAmount = $totalAmountResult->fetch_assoc()['total_amount'];

  // Display the total amount banked
  echo "<h3>Total Amount Banked: UGX " . number_format($totalAmount) . "</h3>";

  // Close the database connection
  $conn->close();
  ?>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
