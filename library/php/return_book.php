<?php
require_once 'auth.php';
require_once 'db.php';
require_once 'log.php';
if (!is_logged_in()) {
    header('Location: ../login.php');
    exit();
}
if (!isset($_POST['request_id'])) {
    header('Location: ../mybooks.php');
    exit();
}
$request_id = intval($_POST['request_id']);
// Get book_id, approval_date
$stmt = $conn->prepare('SELECT book_id, approval_date FROM book_requests WHERE id=? AND status="approved" AND return_date IS NULL');
$stmt->bind_param('i', $request_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if (!$row) {
    header('Location: ../mybooks.php');
    exit();
}
$book_id = $row['book_id'];
$approval_date = strtotime($row['approval_date']);
$due_days = 14;
$fine_per_day = 10;
$due = strtotime("+{$due_days} days", $approval_date);
$now = time();
$fine = 0;
if ($now > $due) {
    $days_overdue = ceil(($now - $due) / 86400);
    $fine = $days_overdue * $fine_per_day;
}
// Mark as returned
$stmt2 = $conn->prepare('UPDATE book_requests SET return_date=NOW(), status="returned", fine=? WHERE id=?');
$stmt2->bind_param('ii', $fine, $request_id);
$stmt2->execute();
// Increment available copies
$conn->query("UPDATE books SET available_copies = available_copies+1 WHERE id=$book_id");
// Notify next in reservation queue
$stmt3 = $conn->prepare('SELECT id, user_id FROM reservations WHERE book_id=? AND notified=0 ORDER BY queue_position ASC LIMIT 1');
$stmt3->bind_param('i', $book_id);
$stmt3->execute();
$res = $stmt3->get_result();
if ($next = $res->fetch_assoc()) {
    // Mark as notified
    $conn->query("UPDATE reservations SET notified=1 WHERE id=".$next['id']);
    // Optionally, insert a notification or send email here
}
log_action($_SESSION['user_id'], 'return', 'book_id=' . $book_id);
// Set success message and redirect
$_SESSION['success_message'] = 'Book returned to library.';
$redirect = '../mybooks.php';
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'analytics.php') !== false) {
    $redirect = '../admin/analytics.php';
}
header('Location: ' . $redirect);
exit(); 