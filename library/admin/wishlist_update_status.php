<?php
session_start();
require '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wishlist_id'], $_POST['status'])) {
    $wishlist_id = intval($_POST['wishlist_id']);
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE book_wishlist SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $wishlist_id);
    $stmt->execute();
    $_SESSION['success'] = "Wishlist status updated.";
}
header("Location: wishlist.php");
exit(); 