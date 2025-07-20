<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME'], 2) . "/book.php?id=$id";
// Use Google Chart API for QR code
header('Content-Type: image/png');
echo file_get_contents('https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' . urlencode($url)); 