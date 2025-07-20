<?php
require_once 'db.php';
require_once 'log.php';
session_start();

// Hash password
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Register user
function register_user($email, $username, $password, $usn, $department) {
    global $conn;
    $hashed = hash_password($password);
    $stmt = $conn->prepare('INSERT INTO users (email, username, password, usn, department) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $email, $username, $hashed, $usn, $department);
    return $stmt->execute();
}

// Login user/admin
function login($email, $password) {
    global $conn;
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if (verify_password($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            log_action($user['id'], 'login', $email);
            return $user;
        }
    }
    return false;
}

// Check if logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Logout
function logout() {
    if (isset($_SESSION['user_id'])) {
        log_action($_SESSION['user_id'], 'logout', '');
    }
    session_unset();
    session_destroy();
}

// Get current user
function current_user() {
    global $conn;
    if (!is_logged_in()) return null;
    $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Update profile (except USN)
function update_profile($user_id, $email, $username, $department) {
    global $conn;
    $stmt = $conn->prepare('UPDATE users SET email=?, username=?, department=? WHERE id=?');
    $stmt->bind_param('sssi', $email, $username, $department, $user_id);
    return $stmt->execute();
}

// Change password
function change_password($user_id, $new_password) {
    global $conn;
    $hashed = hash_password($new_password);
    $stmt = $conn->prepare('UPDATE users SET password=? WHERE id=?');
    $stmt->bind_param('si', $hashed, $user_id);
    return $stmt->execute();
}

// Admin: set default admin if not exists
function ensure_default_admin() {
    global $conn;
    $default_email = 'admin@library.com';
    $default_pass = 'admin123'; // Change after first login
    $stmt = $conn->prepare('SELECT * FROM users WHERE email=? AND role="admin"');
    $stmt->bind_param('s', $default_email);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result->fetch_assoc()) {
        $hashed = hash_password($default_pass);
        $stmt2 = $conn->prepare('INSERT INTO users (email, username, password, usn, department, role) VALUES (?, ?, ?, ?, ?, "admin")');
        $username = 'Admin';
        $usn = 'ADMIN001';
        $department = 'Library';
        $stmt2->bind_param('sssss', $default_email, $username, $hashed, $usn, $department);
        $stmt2->execute();
    }
}

// Call on every page load
ensure_default_admin(); 