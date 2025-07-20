<?php
require_once 'php/auth.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $usn = trim($_POST['usn']);
    $department = trim($_POST['department']);
    if ($password !== $confirm) {
        $msg = '<div class="alert alert-danger">Passwords do not match.</div>';
    } else if (register_user($email, $username, $password, $usn, $department)) {
        $msg = '<div class="alert alert-success">Registration successful! <a href="login.php">Login here</a>.</div>';
    } else {
        $msg = '<div class="alert alert-danger">Registration failed. Email or USN may already exist.</div>';
    }
}
?>
<?php include 'includes/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Library Management System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title mb-4 text-center">User Registration</h3>
                        <?php echo $msg; ?>
                        <form method="POST" autocomplete="off">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>USN</label>
                                <input type="text" name="usn" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Department</label>
                                <input type="text" name="department" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="mt-3 text-center">
                            Already have an account? <a href="login.php">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html> 