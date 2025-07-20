<?php
require_once 'auth.php';

if (!is_logged_in()) {
    header('Location: ../login.php');
    exit();
}

$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $department = $_POST['department'];
        if (update_profile($user['id'], $email, $username, $department)) {
            $msg = 'Profile updated successfully!';
        } else {
            $msg = 'Error updating profile.';
        }
    }
    if (isset($_POST['change_password'])) {
        $new_password = $_POST['new_password'];
        if (change_password($user['id'], $new_password)) {
            $msg = 'Password changed successfully!';
        } else {
            $msg = 'Error changing password.';
        }
    }
    $user = current_user(); // Refresh user data
}
?> 