<?php
// expects $root variable like '' or '..' to prefix links correctly from the including page
if (!isset($root)) $root = '';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
  <a href="<?= $root ?>/index.php" class="navbar-brand fw-bold" style="color:#d35400;">PizzaHub 🍕</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="mainNav">
    <ul class="navbar-nav ms-auto align-items-center">
      <li class="nav-item"><a href="<?= $root ?>/View/all_product.php" class="nav-link">All Products</a></li>
      <?php if (!function_exists('isLoggedIn') || !isLoggedIn()): ?>
        <li class="nav-item"><a href="<?= $root ?>/login/register.php" class="nav-link">Join Us</a></li>
        <li class="nav-item"><a href="<?= $root ?>/login/login.php" class="nav-link">Sign In</a></li>
      <?php else: ?>
        <?php if (function_exists('isAdmin') && isAdmin()): ?>
          <li class="nav-item"><a href="<?= $root ?>/admin/category.php" class="nav-link">Category</a></li>
          <li class="nav-item"><a href="<?= $root ?>/admin/brand.php" class="nav-link">Brand</a></li>
          <li class="nav-item"><a href="<?= $root ?>/admin/product.php" class="nav-link">Manage Products</a></li>
        <?php endif; ?>
        <li class="nav-item d-flex align-items-center me-2">
          <a href="<?= $root ?>/View/cart.php" class="nav-link position-relative">
            <i class="fa fa-shopping-cart"></i>
            <span id="cartCount" class="badge bg-danger ms-1">0</span>
          </a>
        </li>
        <li class="nav-item"><a href="<?= $root ?>/login/logout.php" class="btn btn-sm btn-outline-danger ms-3">Logout</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
