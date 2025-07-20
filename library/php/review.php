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
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$review = isset($_POST['review']) ? trim($_POST['review']) : '';
if (!$book_id || $rating < 1 || $rating > 5) {
    echo json_encode(['success'=>false, 'message'=>'Invalid input']);
    exit;
}
// Check if already reviewed
$stmt = $conn->prepare('SELECT id FROM reviews WHERE user_id=? AND book_id=?');
$stmt->bind_param('ii', $user['id'], $book_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    // Update
    $stmt2 = $conn->prepare('UPDATE reviews SET rating=?, review=?, created_at=NOW() WHERE user_id=? AND book_id=?');
    $stmt2->bind_param('isii', $rating, $review, $user['id'], $book_id);
    $stmt2->execute();
} else {
    // Insert
    $stmt2 = $conn->prepare('INSERT INTO reviews (user_id, book_id, rating, review) VALUES (?, ?, ?, ?)');
    $stmt2->bind_param('iiis', $user['id'], $book_id, $rating, $review);
    $stmt2->execute();
}
echo json_encode(['success'=>true]); 