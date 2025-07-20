// Cart logic using localStorage
function getCart() {
    return JSON.parse(localStorage.getItem('cart') || '[]');
}
function setCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}
function addToCart(bookId) {
    let cart = getCart();
    if (!cart.includes(bookId)) {
        cart.push(bookId);
        setCart(cart);
        alert('Book added to cart!');
    } else {
        alert('Book already in cart!');
    }
}
function removeFromCart(bookId) {
    let cart = getCart().filter(id => id !== bookId);
    setCart(cart);
}
function updateCartCount() {
    let cart = getCart();
    let el = document.getElementById('cart-count');
    if (el) el.textContent = cart.length;
}
document.addEventListener('DOMContentLoaded', updateCartCount); 