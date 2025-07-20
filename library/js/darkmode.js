function setDarkMode(on) {
    document.body.classList.toggle('dark-mode', on);
    localStorage.setItem('darkMode', on ? '1' : '0');
}
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('darkmode-toggle');
    if (btn) {
        btn.onclick = function() {
            setDarkMode(!document.body.classList.contains('dark-mode'));
        };
    }
    if (localStorage.getItem('darkMode') === '1') setDarkMode(true);
}); 