<?php
require_once '../php/auth.php';
require_once '../php/db.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
if (!isset($_GET['id'])) {
    header('Location: books.php');
    exit();
}
$id = intval($_GET['id']);
// Get cover image filename
$stmt = $conn->prepare('SELECT cover_image FROM books WHERE id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
if ($book) {
    // Delete book
    $stmt = $conn->prepare('DELETE FROM books WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    // Delete cover image file if exists
    if ($book['cover_image']) {
        $file = '../uploads/' . $book['cover_image'];
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
header('Location: books.php');
exit(); 