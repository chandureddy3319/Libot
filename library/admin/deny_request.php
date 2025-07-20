<?php
require_once '../php/auth.php';
require_once '../php/db.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
if (!isset($_GET['id'])) {
    header('Location: requests.php');
    exit();
}
$id = intval($_GET['id']);
$stmt = $conn->prepare('UPDATE book_requests SET status="denied" WHERE id=? AND status="pending"');
$stmt->bind_param('i', $id);
$stmt->execute();
header('Location: requests.php');
exit(); 