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
$bills = $billRepo->getBills();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bills</title>
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
    <h2 class="text-center">All Bills</h2>

    <?php if (empty($bills)): ?>
        <div class="alert alert-warning text-center">No bills found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Author</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bills as $bill): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bill['id']); ?></td>
                            <td><?php echo htmlspecialchars($bill['title']); ?></td>
                            <td><?php echo htmlspecialchars($bill['description']); ?></td>
                            <td><?php echo htmlspecialchars($bill['author']); ?></td>
                            <td><?php echo htmlspecialchars($bill['created_at']); ?></td>
                            <td><span class="btn btn-secondary btn-sm"><?php echo htmlspecialchars($bill['status']); ?></span></td>
                            <td>
                                <a href="update_bill.php?id=<?php echo $bill['id']; ?>" class="btn btn-success btn-sm">Update</a>
                                <?php if ($_SESSION["role"] === "Reviewer"): ?>
                                <a href="form_amendments.php?id=<?php echo $bill['id']; ?>" class="btn btn-primary btn-sm">Amendments</a>
                                <?php endif;?>
                                <?php if ($_SESSION["role"] !== "Reviewer"): ?>
                                    <?php if (isset($bill['amendments']) && count($bill['amendments']) > 0): ?>
    <a href="view_amendments.php?id=<?php echo $bill['id']; ?>" class="btn btn-primary btn-sm">View Amendments</a>
<?php endif; ?>

                                <?php endif;?>
                    </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
