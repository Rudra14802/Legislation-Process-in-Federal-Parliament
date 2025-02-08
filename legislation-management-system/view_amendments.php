<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
  }
// Ensure the user is authenticated
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

require_once "bill_repository.php";
$billRepo = new BillRepository();
$billId = $_GET['id'] ?? null;
$bill = $billRepo->getBillById($billId);

if (!$bill) {
    die("Bill not found.");
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
    <h2 class="text-center">View Amendment</h2>
    <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Bill Id</th>
                        <th>Comment</th>
                        <th>Suggested Changes</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
        <?php foreach ($bill['amendments'] as $amendment){?>
                            <td><?php echo htmlspecialchars($bill['id']); ?></td>
                            <td><?php echo htmlspecialchars($amendment['comment']); ?></td>
                            <td><?php echo htmlspecialchars($amendment['suggested_changes']); ?></td>
                            <td><?php echo htmlspecialchars($amendment['timestamp']); ?></td>
        <?php }?>
        
        </tbody>
        </table>
        <a href="view_bill.php" class="btn btn-secondary">Back</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
