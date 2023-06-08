<?php
// Include your database connection code here

// Retrieve the data for export
$sql = "SELECT name, total_amount FROM products WHERE date = '$selectedDate' GROUP BY name";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
}

// Send the data as a JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
