document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-box');
    const deptSelect = document.getElementById('department-filter');
    const booksRow = document.getElementById('books-row');
    function renderBooks(books) {
        let isUser = window.isUser || false;
        if (!books.length) {
            booksRow.innerHTML = '<div class="col-12"><div class="alert alert-info">No books found.</div></div>';
            return;
        }
        booksRow.innerHTML = books.map(book => `
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    ${book.cover_image ? `<img src="${book.cover_image.startsWith('http') ? book.cover_image : 'uploads/' + book.cover_image}" class="card-img-top book-cover" alt="Book Cover" loading="lazy" style="height:150px;object-fit:cover;">` : `<img src="https://via.placeholder.com/100x150?text=No+Image" class="card-img-top book-cover" alt="No Cover" loading="lazy" style="height:150px;object-fit:cover;">`}
                    <div class="card-body">
                        <h5 class="card-title">${book.title}</h5>
                        <p class="card-text mb-1"><strong>Author:</strong> ${book.author}</p>
                        <p class="card-text mb-1"><strong>ISBN:</strong> ${book.isbn}</p>
                        <p class="card-text mb-1"><strong>Publisher:</strong> ${book.publisher}</p>
                        <p class="card-text mb-1"><strong>Year:</strong> ${book.year}</p>
                        <p class="card-text mb-1"><strong>Total Copies:</strong> ${book.total_copies}</p>
                        <p class="card-text mb-1"><strong>Available:</strong> ${book.available_copies}</p>
                        ${isUser ? (
                            book.available_copies > 0
                            ? `<button class='btn btn-outline-primary mt-2 w-100 d-flex align-items-center justify-content-center' onclick='addToCartWithAlert(${book.id}, "${book.title.replace(/'/g, "\'")}")'><i class="bi bi-cart-plus me-2"></i> Add to Cart</button>`
                            : `<button class='btn btn-outline-warning mt-2 w-100' onclick='reserveBook(${book.id})'>Reserve</button>`
                        ) : ''}
                    </div>
                </div>
            </div>
        `).join('');
    }
    function searchBooks() {
        const q = searchInput ? searchInput.value : '';
        const department = deptSelect ? deptSelect.value : '';
        fetch(`php/search_books.php?q=${encodeURIComponent(q)}&department=${encodeURIComponent(department)}`)
            .then(r => r.json())
            .then(renderBooks);
    }
    if (searchInput) searchInput.addEventListener('input', searchBooks);
    if (deptSelect) deptSelect.addEventListener('change', searchBooks);
    searchBooks();
});

function addToCartWithAlert(bookId, bookTitle) {
    addToCart(bookId);
    alert(`'${bookTitle}' added to cart!`);
} 