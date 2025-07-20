<?php
require_once '../php/auth.php';
require_once '../php/db.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
// Fetch all pending requests
$sql = 'SELECT br.id, br.request_date, u.username, u.email, u.usn, u.department, b.title, b.author, b.isbn FROM book_requests br JOIN users u ON br.user_id = u.id JOIN books b ON br.book_id = b.id WHERE br.status = "pending" ORDER BY br.request_date ASC';
$result = $conn->query($sql);
$requests = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Requests | Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
        <h3 class="mb-4">Pending Book Requests</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>USN</th>
                        <th>Department</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Requested At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($req['username']); ?></td>
                            <td><?php echo htmlspecialchars($req['email']); ?></td>
                            <td><?php echo htmlspecialchars($req['usn']); ?></td>
                            <td><?php echo htmlspecialchars($req['department']); ?></td>
                            <td><?php echo htmlspecialchars($req['title']); ?></td>
                            <td><?php echo htmlspecialchars($req['author']); ?></td>
                            <td><?php echo htmlspecialchars($req['isbn']); ?></td>
                            <td><?php echo htmlspecialchars($req['request_date']); ?></td>
                            <td>
                                <a href="approve_request.php?id=<?php echo $req['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                <a href="deny_request.php?id=<?php echo $req['id']; ?>" class="btn btn-danger btn-sm">Deny</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($requests) === 0): ?>
                        <tr><td colspan="9" class="text-center">No pending requests.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 