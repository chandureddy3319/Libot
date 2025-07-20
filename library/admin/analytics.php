<?php
require_once '../php/auth.php';
require_once '../php/db.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
// Totals
$total_books = $conn->query('SELECT COUNT(*) FROM books')->fetch_row()[0];
$issued_books = $conn->query('SELECT COUNT(*) FROM book_requests WHERE status="approved" AND return_date IS NULL')->fetch_row()[0];
$avail_books = $conn->query('SELECT SUM(available_copies) FROM books')->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics | Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <h3 class="mb-4">Library Analytics</h3>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Books</h5>
                        <p class="display-6"><?php echo $total_books; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Issued Books</h5>
                        <p class="display-6"><?php echo $issued_books; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <h5 class="card-title">Available Books</h5>
                        <p class="display-6"><?php echo $avail_books; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow p-4 mb-4">
            <h5>Currently Issued Books (with User Info)</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Book Title</th>
                            <th>User Name</th>
                            <th>USN</th>
                            <th>Return Label</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = 'SELECT br.id, b.title, u.username, u.usn FROM book_requests br JOIN books b ON br.book_id = b.id JOIN users u ON br.user_id = u.id WHERE br.status = "approved" AND br.return_date IS NULL';
                    $res = $conn->query($sql);
                    while ($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['usn']); ?></td>
                            <td>
                                <form method="POST" action="../php/return_book.php" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-arrow-90deg-left"></i> Return
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card shadow p-4">
            <h5>Top Issued Books</h5>
            <canvas id="topBooksChart" height="100"></canvas>
        </div>
    </div>
    <script>
    fetch('../php/analytics_data.php')
        .then(r => r.json())
        .then(data => {
            new Chart(document.getElementById('topBooksChart'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Times Issued',
                        data: data.counts,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
    <?php include '../includes/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 