<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] != "Reviewer") {
    echo '<div class="alert alert-danger text-center">Access Denied.</div>';
    exit;
}
$success = "";
require_once "bill_repository.php";

$billRepo = new BillRepository();
$billId = $_GET['id'] ?? null;
$bill = $billRepo->getBillById($billId);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $billId = $_POST["bill_id"];
    $comment = $_POST["comments"];
    $suggestedChanges = $_POST["suggested_changes"];
    $reviewerId=$_POST["reviewer_id"];
    $billRepo->makeAmendment($billId, $comment, $suggestedChanges,$reviewerId);
    header("Location: view_bill.php");
    exit();
}
?>

<html>
    <head>
        <title>Make Amendments</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
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
    <h2 class="text-center">Suggest an Amendment</h2>
    <?php if ($success!==""): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
    <form method="POST">
    <input type="hidden" name="bill_id" value="<?php echo $bill['id']; ?>">


        <div class="mb-3">
            <label for="suggested_changes" class="form-label">Suggested Changes:</label>
            <textarea name="suggested_changes" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="comments" class="form-label">Comments (Optional):</label>
            <textarea name="comments" class="form-control" rows="2"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Amendment</button>
    </form>
</div>
    </body>
</html>