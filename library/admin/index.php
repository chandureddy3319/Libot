<?php
require_once '../php/auth.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
$user = current_user();
?>
<?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
        <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <a href="books.php" class="btn btn-outline-primary w-100">Manage Books</a>
            </div>
            <div class="col-md-3">
                <a href="requests.php" class="btn btn-outline-success w-100">Book Requests</a>
            </div>
            <div class="col-md-3">
                <a href="analytics.php" class="btn btn-outline-info w-100">Analytics</a>
            </div>
            <div class="col-md-3">
                <a href="bulk_upload.php" class="btn btn-outline-warning w-100">Bulk Upload</a>
            </div>
            <div class="col-md-3">
                <a href="wishlist.php" class="btn btn-outline-danger w-100">Book Wishlist</a>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 