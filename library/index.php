<?php
require_once 'php/auth.php';
require_once 'php/db.php';

// Fetch all books
$books = [];
$sql = 'SELECT * FROM books ORDER BY title';
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
$user = is_logged_in() ? current_user() : null;
?>
<?php include 'includes/navbar.php'; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>
    <?php if ($user && $user['role'] === 'user'): ?>
        <?php 
        $notifs = include 'php/notifications.php';
        if ($notifs && count($notifs)): ?>
            <div class="container mt-3">
                <?php foreach ($notifs as $n): ?>
                    <div class="alert alert-<?php echo $n['status']==='approved'?'success':'danger'; ?> alert-dismissible fade show" role="alert">
                        Book request for <strong><?php echo htmlspecialchars($n['title']); ?></strong> was <strong><?php echo $n['status']; ?></strong>.
                        <?php if ($n['status']==='approved'): ?>
                            Please collect your book from the library.
                        <?php endif; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($user && $user['role'] === 'user'): ?>
    <!-- Book Wishlist Modal -->
    <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="wishlistModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" action="wishlist_add.php">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="wishlistModalLabel">Add to Book Wishlist</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="title" class="form-label">Book Title *</label>
                <input type="text" class="form-control" name="title" required>
              </div>
              <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" name="author">
              </div>
              <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" name="isbn">
              </div>
              <div class="mb-3">
                <label for="edition" class="form-label">Edition</label>
                <input type="text" class="form-control" name="edition">
              </div>
              <div class="mb-3">
                <label for="publisher" class="form-label">Publisher</label>
                <input type="text" class="form-control" name="publisher">
              </div>
              <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" name="notes"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Submit to Wishlist</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php
    // Fetch user's wishlist
    $wishlist = [];
    $stmt = $conn->prepare("SELECT * FROM book_wishlist WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param('i', $user['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) $wishlist[] = $row;
    ?>
    <div class="container mb-4">
        <h5 class="mt-4">My Book Wishlist</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wishlist as $w): ?>
                        <tr>
                            <td><?= htmlspecialchars($w['title']) ?></td>
                            <td><?= htmlspecialchars($w['status']) ?></td>
                            <td><?= htmlspecialchars($w['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($wishlist)): ?>
                        <tr><td colspan="3" class="text-center text-muted">No wishlist entries yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
    <div class="container mt-4">
        <h2 class="mb-4 text-center">All Books</h2>
        <div class="row mb-3 justify-content-center">
            <div class="col-md-6">
                <div class="input-group search-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="search-box" class="form-control" placeholder="Search by title, author, ISBN, publisher...">
                </div>
            </div>
        </div>
        <style>
        .search-group .form-control:focus, .search-group .form-control:hover {
            box-shadow: 0 0 0 0.2rem #0d6efd33;
            border-color: #0d6efd;
        }
        .search-group .form-control {
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        </style>
        <div class="row" id="books-row"></div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/search.js"></script>
    <script>
var isUser = <?php echo ($user && $user['role'] === 'user') ? 'true' : 'false'; ?>;
</script>
    <script>
function reserveBook(bookId) {
    fetch('php/reserve.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'book_id=' + bookId
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
    });
}
</script>
<?php include 'includes/footer.php'; ?>
</body>
</html> 