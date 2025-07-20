<?php
require_once '../php/auth.php';
require_once '../php/db.php';
if (!is_logged_in() || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
if (!isset($_GET['id'])) {
    header('Location: books.php');
    exit();
}
$id = intval($_GET['id']);
$stmt = $conn->prepare('SELECT * FROM books WHERE id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
if (!$book) {
    header('Location: books.php');
    exit();
}
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $publisher = trim($_POST['publisher']);
    $year = intval($_POST['year']);
    $total = intval($_POST['total_copies']);
    $available = intval($_POST['available_copies']);
    $cover = $book['cover_image'];
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
        $cover = uniqid('cover_') . '.' . $ext;
        move_uploaded_file($_FILES['cover_image']['tmp_name'], '../uploads/' . $cover);
    } elseif (!$cover) {
        // Assign a random default cover if none exists
        $default_covers = ['cover1.jpg', 'cover2.jpg', 'cover3.jpg', 'cover4.jpg', 'cover5.jpg'];
        $cover = $default_covers[array_rand($default_covers)];
    }
    $stmt = $conn->prepare('UPDATE books SET title=?, author=?, publisher=?, year=?, total_copies=?, available_copies=?, cover_image=? WHERE id=?');
    $stmt->bind_param('ssssii si', $title, $author, $publisher, $year, $total, $available, $cover, $id);
    if ($stmt->execute()) {
        $msg = '<div class="alert alert-success">Book updated successfully!</div>';
        // Refresh book data
        $stmt2 = $conn->prepare('SELECT * FROM books WHERE id=?');
        $stmt2->bind_param('i', $id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $book = $result2->fetch_assoc();
    } else {
        $msg = '<div class="alert alert-danger">Error updating book.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book | Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">
        <h3 class="mb-4">Edit Book</h3>
        <?php echo $msg; ?>
        <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label>Author</label>
                    <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label>ISBN</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($book['isbn']); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label>Publisher</label>
                    <input type="text" name="publisher" class="form-control" value="<?php echo htmlspecialchars($book['publisher']); ?>">
                </div>
                <div class="col-md-4">
                    <label>Year</label>
                    <input type="number" name="year" class="form-control" min="1000" max="9999" value="<?php echo htmlspecialchars($book['year']); ?>">
                </div>
                <div class="col-md-4">
                    <label>Total Copies</label>
                    <input type="number" name="total_copies" class="form-control" min="1" value="<?php echo $book['total_copies']; ?>" required>
                </div>
                <div class="col-md-4">
                    <label>Available Copies</label>
                    <input type="number" name="available_copies" class="form-control" min="0" value="<?php echo $book['available_copies']; ?>" required>
                </div>
                <div class="col-md-12">
                    <label>Cover Image (leave blank to keep current)</label>
                    <input type="file" name="cover_image" class="form-control">
                    <?php if ($book['cover_image']): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($book['cover_image']); ?>" style="height:80px;width:80px;object-fit:cover;margin-top:8px;" alt="Current Cover">
                    <?php endif; ?>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Update Book</button>
                <a href="books.php" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 