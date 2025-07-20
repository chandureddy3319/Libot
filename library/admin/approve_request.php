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
// Get book_id
$stmt = $conn->prepare('SELECT book_id FROM book_requests WHERE id=? AND status="pending"');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($row) {
    $book_id = $row['book_id'];
    // Decrement available_copies if >0
    $conn->query("UPDATE books SET available_copies = GREATEST(available_copies-1,0) WHERE id=$book_id AND available_copies>0");
    // Approve request
    $stmt2 = $conn->prepare('UPDATE book_requests SET status="approved", approval_date=NOW() WHERE id=?');
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
}
header('Location: requests.php');
exit(); 