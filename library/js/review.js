function renderStars(rating) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
        html += `<span class="star${i <= rating ? ' text-warning' : ''}" data-value="${i}">&#9733;</span>`;
    }
    return html;
}
function loadReviews(bookId) {
    fetch('php/get_reviews.php?book_id=' + bookId)
        .then(r => r.json())
        .then(data => {
            document.getElementById('avg-rating').innerHTML = data.avg ? renderStars(Math.round(data.avg)) + ` <span>(${data.avg})</span>` : 'No ratings yet';
            let list = data.reviews.map(r => `<div class='border-bottom py-2'><strong>${r.username}</strong> ${renderStars(r.rating)}<br>${r.review ? r.review : ''}<div class='text-muted small'>${r.created_at}</div></div>`).join('');
            document.getElementById('review-list').innerHTML = list || '<div class="text-muted">No reviews yet.</div>';
        });
}
function submitReview(bookId) {
    const rating = document.querySelector('input[name="rating"]:checked');
    const review = document.getElementById('review-text').value;
    if (!rating) { alert('Please select a rating'); return; }
    fetch('php/review.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `book_id=${bookId}&rating=${rating.value}&review=${encodeURIComponent(review)}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            loadReviews(bookId);
            document.getElementById('review-text').value = '';
            document.querySelectorAll('input[name="rating"]').forEach(r => r.checked = false);
        } else {
            alert(data.message || 'Error submitting review');
        }
    });
} 