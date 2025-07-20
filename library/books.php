<?php
require_once 'php/db.php';
$sql = 'SELECT * FROM books ORDER BY title';
$result = $conn->query($sql);
$books = [];
while ($row = $result->fetch_assoc()) $books[] = $row;
echo json_encode($books); 