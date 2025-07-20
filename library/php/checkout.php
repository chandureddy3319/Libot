<?php
require_once 'auth.php';
require_once 'db.php';
require_once 'log.php';
header('Content-Type: application/json');
if (!is_logged_in()) {
    echo json_encode(['success'=>false, 'message'=>'Not logged in']);
    exit;
}
$user = current_user();
$data = json_decode(file_get_contents('php://input'), true);
$books = isset($data['books']) ? $data['books'] : [];
$intended_return_date = isset($data['intended_return_date']) ? $data['intended_return_date'] : null;
if (!$books || !is_array($books)) {
    echo json_encode(['success'=>false, 'message'=>'No books selected']);
    exit;
}
if (!$intended_return_date) {
    echo json_encode(['success'=>false, 'message'=>'Intended return date is required']);
    exit;
}
$errors = [];
foreach ($books as $book_id) {
    $book_id = intval($book_id);
    // Check for duplicate pending/approved requests
    $stmt = $conn->prepare('SELECT id FROM book_requests WHERE user_id=? AND book_id=? AND status IN ("pending","approved")');
    $stmt->bind_param('ii', $user['id'], $book_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Already requested book ID $book_id";
        continue;
    }
    // Check if book is available
    $stmt2 = $conn->prepare('SELECT available_copies FROM books WHERE id=?');
    $stmt2->bind_param('i', $book_id);
    $stmt2->execute();
    $stmt2->bind_result($avail);
    $stmt2->fetch();
    $stmt2->close();
    // Insert request (even if not available, for reservation)
    $stmt3 = $conn->prepare('INSERT INTO book_requests (user_id, book_id, status, intended_return_date) VALUES (?, ?, "pending", ?)');
    $stmt3->bind_param('iis', $user['id'], $book_id, $intended_return_date);
    $stmt3->execute();
    log_action($user['id'], 'checkout', 'book_id=' . $book_id);
}
if (count($errors) === count($books)) {
    echo json_encode(['success'=>false, 'message'=>'No new requests.']);
} else {
    echo json_encode(['success'=>true]);
} 