<?php
require_once '../php/auth.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
$msg = '';
if (isset($_GET['success'])) {
    $msg = '<div class="alert alert-success">Books uploaded successfully!</div>';
} elseif (isset($_GET['error'])) {
    $msg = '<div class="alert alert-danger">'.htmlspecialchars($_GET['error']).'</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Upload | Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
        <h3 class="mb-4">Bulk Upload Books</h3>
        <?php echo $msg; ?>
        <form method="POST" action="../php/bulk_upload.php" enctype="multipart/form-data" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label for="file" class="form-label">Select CSV or Excel file</label>
                <input type="file" name="file" id="file" class="form-control" accept=".csv, .xlsx" required>
            </div>
            <button type="submit" class="btn btn-success">Upload</button>
        </form>
        <div class="mt-3">
            <strong>CSV Format:</strong> title, author, isbn, publisher, year, total_copies, available_copies, department
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 