<?php
require_once 'php/auth.php';
require_once 'php/db.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
$user = current_user();
// Fetch issued books
$sql = 'SELECT br.id, br.approval_date, br.return_date, b.title, b.author, b.isbn FROM book_requests br JOIN books b ON br.book_id = b.id WHERE br.user_id=? AND br.status="approved" AND br.return_date IS NULL';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$books = [];
while ($row = $result->fetch_assoc()) $books[] = $row;
// Fine config (default ₹10/day)
$fine_per_day = 10;
$due_days = 14;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Issued Books | Library</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>
    <div class="container mt-4">
        <h3 class="mb-4">My Issued Books</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
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
                            <td><?php echo htmlspecialchars($b['title']); ?></td>
                            <td><?php echo htmlspecialchars($b['author']); ?></td>
                            <td><?php echo htmlspecialchars($b['isbn']); ?></td>
                            <td><?php echo date('Y-m-d', $issued); ?></td>
                            <td><?php echo date('Y-m-d', $due); ?></td>
                            <td><?php echo $fine ? '₹' . $fine : '-'; ?></td>
                            <td>
                                <form method="POST" action="php/return_book.php" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $b['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Return</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($books) === 0): ?>
                        <tr><td colspan="7" class="text-center">No issued books.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html> 