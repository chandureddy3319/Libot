<?php
require_once 'auth.php';
require_once 'db.php';
if (!is_logged_in()) return [];
$user = current_user();
$notifs = [];
// Fetch unread notifications (approved/denied requests)
$stmt = $conn->prepare('SELECT br.id, br.status, br.approval_date, b.title FROM book_requests br JOIN books b ON br.book_id = b.id WHERE br.user_id=? AND br.status IN ("approved","denied") AND (br.notified IS NULL OR br.notified=0) ORDER BY br.approval_date DESC LIMIT 10');
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) $notifs[] = $row;
// Mark as notified
if (count($notifs)) {
    $ids = implode(',', array_map('intval', array_column($notifs, 'id')));
    $conn->query("UPDATE book_requests SET notified=1 WHERE id IN ($ids)");
}
return $notifs; 