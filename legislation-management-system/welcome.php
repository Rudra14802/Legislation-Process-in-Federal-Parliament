<?php
session_start();

// Check if the user is logged in.  If not, redirect to login.
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: login.php");
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Welcome</title>
</head>
<body>

<h1>Welcome, <?php echo $_SESSION["username"]; ?></h1>
<p>Hello, <?php echo $_SESSION["role"]; ?></p>

<a href="?logout=true"><button>Logout</button></a> </body>
</html>