<?php
require_once 'php/db.php';
require_once 'php/auth.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare('SELECT * FROM books WHERE id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
if (!$book) { echo 'Book not found.'; exit; }
$user = is_logged_in() ? current_user() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> | Library</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>.star {font-size:1.2em;cursor:pointer;}</style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Library</a>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <?php if ($book['cover_image']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($book['cover_image']); ?>" class="img-fluid rounded shadow" alt="Book Cover">
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <h2><?php echo htmlspecialchars($book['title']); ?></h2>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['isbn']); ?></p>
                <p><strong>Publisher:</strong> <?php echo htmlspecialchars($book['publisher']); ?></p>
                <p><strong>Year:</strong> <?php echo htmlspecialchars($book['year']); ?></p>
                <p><strong>Total Copies:</strong> <?php echo $book['total_copies']; ?></p>
                <p><strong>Available:</strong> <?php echo $book['available_copies']; ?></p>
                <div class="mb-3">
                    <strong>QR Code:</strong><br>
                    <img src="php/qr.php?id=<?php echo $book['id']; ?>" alt="QR Code for this book" style="width:120px;height:120px;">
                </div>
                <div class="mb-2"><strong>Average Rating:</strong> <span id="avg-rating"></span></div>
                <div id="review-list" class="mb-3"></div>
                <?php if ($user): ?>
                <div class="card p-3 mb-3">
                    <h5>Submit Your Review</h5>
                    <div class="mb-2">
                        <?php for ($i=1; $i<=5; $i++): ?>
                            <input type="radio" name="rating" id="star<?php echo $i; ?>" value="<?php echo $i; ?>">
                            <label for="star<?php echo $i; ?>" class="star">&#9733;</label>
                        <?php endfor; ?>
                    </div>
                    <textarea id="review-text" class="form-control mb-2" placeholder="Write your review (optional)"></textarea>
                    <button class="btn btn-primary" onclick="submitReview(<?php echo $book['id']; ?>)">Submit</button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="js/review.js"></script>
    <script>loadReviews(<?php echo $book['id']; ?>);</script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>
</html> 