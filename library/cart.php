<?php
require_once 'php/auth.php';
require_once 'php/db.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}
$user = current_user();
?>
<?php include 'includes/navbar.php'; ?>
    <div class="container mt-4">
        <h3 class="mb-4">My Cart</h3>
        <div id="cart-list"></div>
        <form id="checkout-form" class="card p-4 shadow-sm mt-4" style="display:none;">
            <h5>Checkout</h5>
            <div class="mb-3">
                <label>USN</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['usn']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label>Department</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['department']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="intended_return_date">Intended Return Date *</label>
                <input type="date" class="form-control" name="intended_return_date" id="intended_return_date" required>
            </div>
            <button type="submit" class="btn btn-success">Request Books</button>
        </form>
        <div id="checkout-msg" class="mt-3"></div>
    </div>
    <script src="js/cart.js"></script>
    <script>
    // Fetch book details for cart
    function fetchBooks(ids, cb) {
        if (ids.length === 0) return cb([]);
        fetch('php/get_books.php?ids=' + ids.join(','))
            .then(r => r.json())
            .then(cb);
    }
    function renderCart() {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let list = document.getElementById('cart-list');
        let form = document.getElementById('checkout-form');
        let msg = document.getElementById('checkout-msg');
        if (cart.length === 0) {
            list.innerHTML = '<div class="alert alert-info">Your cart is empty.</div>';
            form.style.display = 'none';
            return;
        }
        fetchBooks(cart, function(books) {
            let html = '<table class="table table-bordered"><thead><tr><th>Title</th><th>Author</th><th>Action</th></tr></thead><tbody>';
            books.forEach(book => {
                html += `<tr><td>${book.title}</td><td>${book.author}</td><td><button class='btn btn-danger btn-sm' onclick='removeFromCart(${book.id});renderCart();'>Remove</button></td></tr>`;
            });
            html += '</tbody></table>';
            list.innerHTML = html;
            form.style.display = '';
        });
    }
    renderCart();
    document.getElementById('checkout-form').onsubmit = function(e) {
        e.preventDefault();
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let intendedReturnDate = document.getElementById('intended_return_date').value;
        fetch('php/checkout.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({books: cart, intended_return_date: intendedReturnDate})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                localStorage.setItem('cart', '[]');
                renderCart();
                document.getElementById('checkout-msg').innerHTML = '<div class="alert alert-success">Request sent for admin approval!</div>';
                updateCartCount();
            } else {
                document.getElementById('checkout-msg').innerHTML = '<div class="alert alert-danger">'+data.message+'</div>';
            }
        });
    };
    </script>
    <script src="js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>
</body>
</html> 