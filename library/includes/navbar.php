<?php
require_once __DIR__ . '/../php/auth.php';
$user = is_logged_in() ? current_user() : null;
// Determine logout and profile paths based on current script location
$isAdmin = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false);
$logoutPath = $isAdmin ? '../php/logout.php' : 'php/logout.php';
$profilePath = $isAdmin ? '../profile.php' : 'profile.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="bi bi-book"></i> Library</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house"></i> Home</a></li>
        <?php if ($user && $user['role'] === 'user'): ?>
          <li class="nav-item">
            <a class="nav-link position-relative" href="cart.php">
              <i class="bi bi-cart"></i> Cart
              <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="cart-count">0</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#wishlistModal" title="Book Wishlist">
              <i class="bi bi-heart"></i>
            </a>
          </li>
        <?php endif; ?>
        <?php if ($user): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo $profilePath; ?>"><i class="bi bi-person"></i> Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo $logoutPath; ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php"><i class="bi bi-person-plus"></i> Register</a></li>
        <?php endif; ?>
        <li class="nav-item">
          <button class="btn btn-outline-light ms-2" id="darkmode-toggle" title="Toggle dark mode">
            <span class="bi bi-moon"></span>
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>
<link rel="stylesheet" href="css/darkmode.css">
<script src="js/darkmode.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> 