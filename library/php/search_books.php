<?php
require_once 'db.php';
header('Content-Type: application/json');
$q = isset($_GET['q']) ? '%' . $conn->real_escape_string($_GET['q']) . '%' : '%';
$dept = isset($_GET['department']) ? $conn->real_escape_string($_GET['department']) : '';
$sql = 'SELECT * FROM books WHERE (title LIKE ? OR author LIKE ? OR isbn LIKE ? OR publisher LIKE ?)';
$params = [$q, $q, $q, $q];
$types = 'ssss';
if ($dept) {
    $sql .= ' AND (department=? OR department="" OR department IS NULL)';
    $params[] = $dept;
    $types .= 's';
}
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$books = [];
while ($row = $result->fetch_assoc()) $books[] = $row;
echo json_encode($books); 