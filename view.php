
<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  // Redirect to the login page
  header("Location: login.php");
  exit;
}
?>

<!-- Rest of your code for displaying the logs -->
<!DOCTYPE html>
<html>
<head>
  <title>banked</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .card-img-top {
      height: 200px;
      object-fit: cover;
    }

    .modal-body {
      text-align: center;
    }

    .modal-body img {
      max-width: 100%;
      max-height: 80vh;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ORLY </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="index">Home</a>
        </li>
      </ul>
    </div>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="dashboard">Dashboard</a>
        </li>
      </ul>
    </div>
  </div>
  <div class="collapse navbar-collapse">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="logout">Logout</a>
        </li>
      </ul>
    </div>
</nav>


<div class="container mt-4">
  <h1>BANKING REPORT</h1>

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

  // Pagination configuration
  $resultsPerPage = 12;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $offset = ($page - 1) * $resultsPerPage;

  // Get the selected date from the filter form
  $selectedDate = isset($_GET['date']) ? $_GET['date'] : '';

  // Retrieve records from the database ordered by date with pagination and date filter
  $sql = "SELECT name, images, amount, date FROM products";
  if (!empty($selectedDate)) {
    $sql .= " WHERE date = '$selectedDate'";
  }
  $sql .= " ORDER BY date DESC LIMIT $offset, $resultsPerPage";
  $result = $conn->query($sql);

  // Get total number of records with date filter
  $totalRecordsSql = "SELECT COUNT(*) AS total FROM products";
  if (!empty($selectedDate)) {
    $totalRecordsSql .= " WHERE date = '$selectedDate'";
  }
  $totalRecordsResult = $conn->query($totalRecordsSql);
  $totalRecords = $totalRecordsResult->fetch_assoc()['total'];

  // Calculate total number of pages
  $totalPages = ceil($totalRecords / $resultsPerPage);

  // Display the records
  if ($result->num_rows > 0) {
    echo "<div class='row row-cols-1 row-cols-md-4'>";
    $records = array_reverse($result->fetch_all(MYSQLI_ASSOC));
    $records = array_reverse($records); // Reverse the order of records to display from latest to oldest

    foreach ($records as $row) {
      echo "<div class='col mb-4'>";
      echo "<div class='card'>";
      echo "<img src='images/" . $row['images'] . "' class='card-img-top product-image' alt='Product Image' data-toggle='modal' data-target='#imageModal' data-image='images/" . $row['images'] . "' data-download='images/" . $row['images'] . "' style='cursor:pointer;'>";
      echo "<div class='card-body'>";
      echo "<h5 class='card-title'>" . $row['name'] . "</h5>";
      echo "<p class='card-text'>Amount: UGX " . number_format($row['amount']) . "</p>";
      echo "<p class='card-text'>Date: " . $row['date'] . "</p>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
    }

    echo "</div>";

    // Display pagination links
    echo "<nav aria-label='Pagination'>";
    echo "<ul class='pagination justify-content-center'>";

    if ($page > 1) {
      echo "<li class='page-item'><a class='page-link' href='view.php?page=" . ($page - 1);
      if (!empty($selectedDate)) {
        echo "&date=$selectedDate";
      }
      echo "'>Previous</a></li>";
    }

    for ($i = 1; $i <= $totalPages; $i++) {
      echo "<li class='page-item";
      if ($page == $i) {
        echo " active";
      }
      echo "'><a class='page-link' href='view.php?page=" . $i;
      if (!empty($selectedDate)) {
        echo "&date=$selectedDate";
      }
      echo "'>" . $i . "</a></li>";
    }

    if ($page < $totalPages) {
      echo "<li class='page-item'><a class='page-link' href='view.php?page=" . ($page + 1);
      if (!empty($selectedDate)) {
        echo "&date=$selectedDate";
      }
      echo "'>Next</a></li>";
    }

    echo "</ul>";
    echo "</nav>";
  } else {
    echo "<p>No records found.</p>";
  }

  // Close the database connection
  $conn->close();
  ?>

  <!-- Image Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <img id="modalImage" src="" alt="Product Image" class="img-fluid">
        </div>
        <div class="modal-footer">
          <a id="downloadLink" href="#" class="btn btn-primary">Download</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
  // Add event listener to handle image click
  document.addEventListener('DOMContentLoaded', function() {
    var modalImage = document.getElementById('modalImage');
    var downloadLink = document.getElementById('downloadLink');
    var productImages = document.getElementsByClassName('product-image');

    Array.prototype.forEach.call(productImages, function(image) {
      image.addEventListener('click', function() {
        var imageUrl = this.dataset.image;
        var downloadUrl = this.dataset.download;

        modalImage.src = imageUrl;
        downloadLink.href = downloadUrl;

        // Show the modal manually
        var modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
      });
    });
  });
</script>

</body>

</html>
