<?php
require_once 'php/profile.php';
?>
<?php include 'includes/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Library Management System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title mb-4 text-center">My Profile</h3>
                        <?php if (isset($msg)) echo '<div class="alert alert-info">'.$msg.'</div>'; ?>
                        <form method="POST" autocomplete="off">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>USN</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['usn']); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label>Department</label>
                                <input type="text" name="department" class="form-control" value="<?php echo htmlspecialchars($user['department']); ?>" required>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-success w-100">Update Profile</button>
                        </form>
                        <hr>
                        <form method="POST" autocomplete="off">
                            <div class="mb-3">
                                <label>New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-warning w-100">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html> 