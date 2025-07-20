<?php
require_once '../php/auth.php';
require_once '../php/db.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
$sql = 'SELECT a.*, u.username FROM activity_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.log_time DESC LIMIT 100';
$result = $conn->query($sql);
$logs = [];
while ($row = $result->fetch_assoc()) $logs[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log | Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
        <h3 class="mb-4">Activity Log</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($log['username'] ?? 'System'); ?></td>
                            <td><?php echo htmlspecialchars($log['action']); ?></td>
                            <td><?php echo htmlspecialchars($log['details']); ?></td>
                            <td><?php echo htmlspecialchars($log['log_time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($logs) === 0): ?>
                        <tr><td colspan="4" class="text-center">No activity found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 