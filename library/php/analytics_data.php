<?php
require_once 'db.php';
header('Content-Type: application/json');
$sql = 'SELECT b.title, COUNT(*) as cnt FROM book_requests br JOIN books b ON br.book_id = b.id WHERE br.status IN ("approved","returned") GROUP BY br.book_id ORDER BY cnt DESC LIMIT 10';
$result = $conn->query($sql);
$labels = [];
$counts = [];
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['title'];
    $counts[] = (int)$row['cnt'];
}
echo json_encode(['labels'=>$labels, 'counts'=>$counts]); 