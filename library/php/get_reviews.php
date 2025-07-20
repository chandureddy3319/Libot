<?php
require_once 'db.php';
header('Content-Type: application/json');
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
if (!$book_id) { echo json_encode(['reviews'=>[], 'avg'=>0]); exit; }
$stmt = $conn->prepare('SELECT r.rating, r.review, r.created_at, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.book_id=? ORDER BY r.created_at DESC');
$stmt->bind_param('i', $book_id);
$stmt->execute();
$result = $stmt->get_result();
$reviews = [];
$sum = 0; $count = 0;
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
    $sum += $row['rating'];
    $count++;
}
$avg = $count ? round($sum/$count,1) : 0;
echo json_encode(['reviews'=>$reviews, 'avg'=>$avg]); 