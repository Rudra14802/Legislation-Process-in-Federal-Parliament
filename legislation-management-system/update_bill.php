<?php
session_start();
 $errors="";
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
  }
// Ensure user is authenticated
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Restrict access: Only Members of Parliament & Administrators can update
if ($_SESSION["role"] !== "Member of Parliament" && $_SESSION["role"] !== "Administrator") {
    
        $errors = "access denied";
    
}

require_once "bill_repository.php";

$billRepo = new BillRepository();
$billId = $_GET['id'] ?? null;

if (!$billId) {
    die("Invalid bill ID.");
}

// Fetch the bill to update
$bill = $billRepo->getBillById($billId);

if (!$bill) {
    die("Bill not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $initial_draft = $_POST["initial_draft"];

    $billRepo->updateBill($billId, $title, $description, $initial_draft);
    header("Location: view_bill.php"); // Redirect to the list after updating
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Bill</title>
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

<div class="container mt-4">
    <h2 class="text-center">Update Bill</h2>
    <?php if ($errors == "access denied"): ?>
        <div class="alert alert-warning text-center">Access Denied</div>
    <?php else: ?>
    <form method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($bill['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required><?php echo htmlspecialchars($bill['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Initial Draft</label>
            <textarea name="initial_draft" class="form-control" required><?php echo htmlspecialchars($bill['initial_draft']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 150px;">Update Bill</button><br>
        <a href="view_bill.php" class="btn btn-secondary" style="width: 150px;">Cancel</a>
    </form>
</div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
