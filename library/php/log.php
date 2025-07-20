<?php
require_once 'db.php';
function log_action($user_id, $action, $details = '') {
    global $conn;
    $stmt = $conn->prepare('INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)');
    $stmt->bind_param('iss', $user_id, $action, $details);
    $stmt->execute();
} 