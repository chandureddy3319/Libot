<?php
require_once '../php/auth.php';
require_once '../php/db.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
// Fetch all wishlist entries
$sql = 'SELECT bw.*, u.username, u.email, u.usn FROM book_wishlist bw JOIN users u ON bw.user_id = u.id ORDER BY bw.created_at DESC';
$result = $conn->query($sql);
$wishlist = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $wishlist[] = $row;
    }
}
?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>
<?php include '../includes/navbar.php'; ?>
<div class="container mt-4">
    <h3 class="mb-4">Book Wishlist</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>USN</th>
                    <th>Email</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wishlist as $w): ?>
                    <tr>
                        <td><?= htmlspecialchars($w['username']) ?></td>
                        <td><?= htmlspecialchars($w['usn']) ?></td>
                        <td><?= htmlspecialchars($w['email']) ?></td>
                        <td><?= htmlspecialchars($w['title']) ?></td>
                        <td><?= htmlspecialchars($w['author']) ?></td>
                        <td><?= htmlspecialchars($w['status']) ?></td>
                        <td><?= htmlspecialchars($w['created_at']) ?></td>
                        <td>
                            <form method="POST" action="wishlist_update_status.php" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="wishlist_id" value="<?= $w['id'] ?>">
                                <select name="status" class="form-select form-select-sm">
                                    <option <?= $w['status']=='Pending'?'selected':'' ?>>Pending</option>
                                    <option <?= $w['status']=='Reviewed'?'selected':'' ?>>Reviewed</option>
                                    <option <?= $w['status']=='Approved'?'selected':'' ?>>Approved</option>
                                    <option <?= $w['status']=='Ordered'?'selected':'' ?>>Ordered</option>
                                    <option <?= $w['status']=='Rejected'?'selected':'' ?>>Rejected</option>
                                    <option <?= $w['status']=='Added'?'selected':'' ?>>Added</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($wishlist)): ?>
                    <tr><td colspan="8" class="text-center text-muted">No wishlist entries found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 