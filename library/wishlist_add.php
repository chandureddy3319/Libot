<?php
session_start();
require 'php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $edition = trim($_POST['edition']);
    $publisher = trim($_POST['publisher']);
    $notes = trim($_POST['notes']);

    $stmt = $conn->prepare("INSERT INTO book_wishlist (user_id, title, author, isbn, edition, publisher, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $user_id, $title, $author, $isbn, $edition, $publisher, $notes);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Book added to wishlist!";
    } else {
        $_SESSION['error'] = "Failed to add book to wishlist.";
    }
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php");
    exit();
} 