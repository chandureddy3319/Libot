<?php
require_once '../php/auth.php';
require_once '../php/db.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
// Fetch all issued books
$sql = 'SELECT br.id, br.approval_date, u.username, u.email, u.usn, b.title, b.author, b.isbn FROM book_requests br JOIN users u ON br.user_id = u.id JOIN books b ON br.book_id = b.id WHERE br.status="approved" AND br.return_date IS NULL';
$result = $conn->query($sql);
$books = [];
while ($row = $result->fetch_assoc()) $books[] = $row;
$fine_per_day = 10;
$due_days = 14;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issued Books | Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
        <h3 class="mb-4">All Issued Books</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>USN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Issued On</th>
                        <th>Due Date</th>
                        <th>Fine</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $b): ?>
                        <?php
                        $issued = strtotime($b['approval_date']);
                        $due = strtotime("+{$due_days} days", $issued);
                        $now = time();
                        $fine = 0;
                        if ($now > $due) {
                            $days_overdue = ceil(($now - $due) / 86400);
                            $fine = $days_overdue * $fine_per_day;
                        }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($b['username']); ?></td>
                            <td><?php echo htmlspecialchars($b['email']); ?></td>
                            <td><?php echo htmlspecialchars($b['usn']); ?></td>
                            <td><?php echo htmlspecialchars($b['title']); ?></td>
                            <td><?php echo htmlspecialchars($b['author']); ?></td>
                            <td><?php echo htmlspecialchars($b['isbn']); ?></td>
                            <td><?php echo date('Y-m-d', $issued); ?></td>
                            <td><?php echo date('Y-m-d', $due); ?></td>
                            <td><?php echo $fine ? '₹' . $fine : '-'; ?></td>
                            <td>
                                <form method="POST" action="../php/return_book.php" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $b['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-arrow-90deg-left"></i> Return
                                    </button>
                                </form>
                                <!-- Return label/modal trigger -->
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#returnLabelModal<?php echo $b['id']; ?>">
                                    <i class="bi bi-printer"></i> Return Label
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="returnLabelModal<?php echo $b['id']; ?>" tabindex="-1" aria-labelledby="returnLabelModalLabel<?php echo $b['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="returnLabelModalLabel<?php echo $b['id']; ?>">Return Label</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <strong>User Name:</strong> <?php echo htmlspecialchars($b['username']); ?><br>
                                                <strong>USN:</strong> <?php echo htmlspecialchars($b['usn']); ?><br>
                                                <strong>Book Title:</strong> <?php echo htmlspecialchars($b['title']); ?><br>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" onclick="window.print();">Print</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($books) === 0): ?>
                        <tr><td colspan="10" class="text-center">No issued books.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 