<?php
require_once 'db.php';
header('Content-Type: application/json');
$ids = isset($_GET['ids']) ? $_GET['ids'] : '';
if (!$ids) { echo '[]'; exit; }
$idArr = array_map('intval', explode(',', $ids));
$in = implode(',', array_fill(0, count($idArr), '?'));
$stmt = $conn->prepare('SELECT id, title, author FROM books WHERE id IN (' . $in . ')');
$stmt->bind_param(str_repeat('i', count($idArr)), ...$idArr);
$stmt->execute();
$result = $stmt->get_result();
$books = [];
while ($row = $result->fetch_assoc()) $books[] = $row;
echo json_encode($books); 