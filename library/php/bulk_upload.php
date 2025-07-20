<?php
require_once 'auth.php';
require_once 'db.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    header('Location: ../admin/bulk_upload.php?error=File upload failed');
    exit();
}
$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
if ($ext !== 'csv') {
    header('Location: ../admin/bulk_upload.php?error=Only CSV files are supported');
    exit();
}
$handle = fopen($_FILES['file']['tmp_name'], 'r');
if (!$handle) {
    header('Location: ../admin/bulk_upload.php?error=Could not read file');
    exit();
}
$header = fgetcsv($handle);
$count = 0;
while (($row = fgetcsv($handle)) !== false) {
    $data = array_map('trim', $row);
    // title, author, isbn, publisher, year, total_copies, available_copies, department
    if (count($data) < 7) continue;
    $stmt = $conn->prepare('INSERT INTO books (title, author, isbn, publisher, year, total_copies, available_copies, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssiiis', $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7] ?? null);
    $stmt->execute();
    $count++;
}
fclose($handle);
header('Location: ../admin/bulk_upload.php?success=1');
exit(); 