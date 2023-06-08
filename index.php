<!DOCTYPE html>
<html>
<head>
  <title>BANKING REPORT</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <style>
    h1 {
      margin-bottom: 20px;
    }

    .navbar {
      margin-bottom: 30px;
    }

    form label {
      font-weight: bold;
    }

    form input[type="text"],
    form input[type="file"],
    form input[type="date"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    form input[type="submit"] {
      padding: 10px 20px;
      background-color: #007bff;
      border: none;
      color: #fff;
      cursor: pointer;
    }

    .container {
      max-width: 700px;
    }
  </style>
</head>
<body>

  <?php
    if (isset($_GET['success']) && $_GET['success'] == 1) {
      echo "<div class='success-message'>Submitted successfully!</div>";
    }

    function logAction($userId, $action) {
      require_once 'config.php';

  
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
  
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
  
      // Prepare and execute the SQL statement
      $stmt = $conn->prepare("INSERT INTO logs (timestamp, user_id, action) VALUES (?, ?, ?)");
      $stmt->bind_param("sds", date("Y-m-d H:i:s"), $userId, $action);
      $stmt->execute();
  
      // Close the prepared statement and database connection
      $stmt->close();
      $conn->close();
  }
  
  ?>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">ORLY </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="http://localhost/bank">Home</a>
          </li>
        </ul>
      </div>
      
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="view">view</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <h1>BANKING REPORT</h1>

    <form action="insert.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Shop name:</label>
        <input type="text" placeholder="eg. kabalagala" name="name" required>
      </div>

      <div class="form-group">
        <label for="images">Bank slip:</label>
        <input type="file" name="images" required>
      </div>

      <div class="form-group">
        <label for="amount">Amount:</label>
        <input type="text" placeholder="1234567" name="amount" required>
        <small>Format: 1234567</small>
      </div>

      <div class="form-group">
        <label for="date">Date:</label>
        <input type="date" name="date" required>
      </div><br>

      <input type="submit" value="Submit" class="btn btn-primary">
    </form>
  </div>

  <script src="js/bootstrap.min.js"></script>
</body>
</html>
