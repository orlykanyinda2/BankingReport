<?php
require_once 'config.php';


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Retrieve the form data
$name = $_POST['name'];
$images = $_FILES['images']['name'];
$amount = $_POST['amount'];
$date = $_POST['date'];

// Upload image file
$targetDir = "images/";
$targetFile = $targetDir . basename($_FILES['images']['name']);
move_uploaded_file($_FILES['images']['tmp_name'], $targetFile);

// Prepare and execute the SQL statement
$stmt = $conn->prepare("INSERT INTO products (name, images, amount, date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssds", $name, $images, $amount, $date);
$stmt->execute();

// Close the prepared statement and database connection
$stmt->close();
$conn->close();

// Redirect back to the index page with a success message
header("Location: index.php?success=1");
exit();
?>
