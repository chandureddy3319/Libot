<?php
require_once '../php/auth.php';
require_once '../php/db.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $isbn = trim($_POST['isbn']);
    $publisher = trim($_POST['publisher']);
    $year = intval($_POST['year']);
    $total = intval($_POST['total_copies']);
    $available = intval($_POST['available_copies']);
    $cover = null;
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
        $cover = uniqid('cover_') . '.' . $ext;
        move_uploaded_file($_FILES['cover_image']['tmp_name'], '../uploads/' . $cover);
    } else {
        // Assign a random default cover if none uploaded
        $default_covers = ['cover1.jpg', 'cover2.jpg', 'cover3.jpg', 'cover4.jpg', 'cover5.jpg'];
        $cover = $default_covers[array_rand($default_covers)];
    }
    $stmt = $conn->prepare('INSERT INTO books (title, author, isbn, publisher, year, total_copies, available_copies, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssiis', $title, $author, $isbn, $publisher, $year, $total, $available, $cover);
    if ($stmt->execute()) {
        $msg = '<div class="alert alert-success">Book added successfully!</div>';
    } else {
        $msg = '<div class="alert alert-danger">Error adding book. ISBN may already exist.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book | Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
        <h3 class="mb-4">Add New Book</h3>
        <?php echo $msg; ?>
        <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Author</label>
                    <input type="text" name="author" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>ISBN</label>
                    <input type="text" name="isbn" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Publisher</label>
                    <input type="text" name="publisher" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Year</label>
                    <input type="number" name="year" class="form-control" min="1000" max="9999">
                </div>
                <div class="col-md-4">
                    <label>Total Copies</label>
                    <input type="number" name="total_copies" class="form-control" min="1" value="1" required>
                </div>
                <div class="col-md-4">
                    <label>Available Copies</label>
                    <input type="number" name="available_copies" class="form-control" min="0" value="1" required>
                </div>
                <div class="col-md-12">
                    <label>Cover Image</label>
                    <input type="file" name="cover_image" class="form-control">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Add Book</button>
                <a href="books.php" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 