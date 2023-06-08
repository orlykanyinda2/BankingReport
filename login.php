<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Add your own logic to validate the credentials
  if ($username === "admin" && $password === "admin") {
    $_SESSION["loggedin"] = true;
    header("Location: view.php");
    exit;
  } else {
    $error = "Invalid username or password";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
    </div>
  </nav>
</head>
<body>
  <div class="d-flex align-items-center justify-content-center" style="height: 50vh;">
    <div class="container">
      <h1 class="text-center">Login</h1>
      <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>
      <form method="POST" action="login.php">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" class="form-control form-control" required>
        </div><br>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" class="form-control form-control" required>
        </div><br>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
    </div>
  </div>
</body>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>
