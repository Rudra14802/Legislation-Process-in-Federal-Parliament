<?php
session_start();

// Check if the user is logged in and is a Member of Parliament



$error = "";
$success = "";
if (!isset($_SESSION["username"]) || ($_SESSION["role"] !== "Member of Parliament" && $_SESSION["role"] !== "Administrator")) {
    $error = "Access Denied.";
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
  }

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $author = $_SESSION["username"]; // Automatically set the author as the logged-in user
    $initial_draft = $_POST["initial_draft"];
    $status='In Progress';

    if (!empty($title) && !empty($description) && !empty($initial_draft)) {
        require_once "bill_repository.php";
        $billRepo = new BillRepository();
        $billRepo->saveBill($title, $description, $author, $initial_draft,$status);
        header("Location: view_bill.php");
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Bill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Parliament System</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="create_bill.php">Create Bill</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_bill.php">View Bills</a>
                </li>
                <li class="nav-item">
                <a href="?logout=true"><button>Logout</button></a> </body>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center">Create a New Bill</h2>

        <!-- Success & Error Alerts -->
        <?php if ($error!==""): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php else: ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Bill Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="initial_draft" class="form-label">Initial Draft</label>
                <textarea class="form-control" id="initial_draft" name="initial_draft" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Submit Bill</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
