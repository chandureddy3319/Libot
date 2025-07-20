<?php
require_once 'auth.php';
require_once 'db.php';
header('Content-Type: application/json');
if (!is_logged_in()) {
    echo json_encode(['success'=>false, 'message'=>'Not logged in']);
    exit;
}
$user = current_user();
$book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
if (!$book_id) {
    echo json_encode(['success'=>false, 'message'=>'Invalid book']);
    exit;
}
// Check if already reserved
$stmt = $conn->prepare('SELECT id FROM reservations WHERE user_id=? AND book_id=?');
$stmt->bind_param('ii', $user['id'], $book_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success'=>false, 'message'=>'Already in reservation queue']);
    exit;
}
// Get next queue position
$stmt2 = $conn->prepare('SELECT MAX(queue_position) FROM reservations WHERE book_id=?');
$stmt2->bind_param('i', $book_id);
$stmt2->execute();
$stmt2->bind_result($max_pos);
$stmt2->fetch();
$queue_pos = $max_pos ? $max_pos + 1 : 1;
$stmt2->close();
// Insert reservation
$stmt3 = $conn->prepare('INSERT INTO reservations (user_id, book_id, queue_position) VALUES (?, ?, ?)');
$stmt3->bind_param('iii', $user['id'], $book_id, $queue_pos);
$stmt3->execute();
echo json_encode(['success'=>true, 'message'=>'Reserved. You will be notified when available.']); 