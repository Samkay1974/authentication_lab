<?php
// expects $root variable like '' or '..' to prefix links correctly from the including page
if (!isset($root)) $root = '';

// normalize root: remove trailing slash if any
$root = rtrim($root, '/');

function _url($path) {
  global $root;
  $p = ltrim($path, '/');
  return ($root === '' ? $p : ($root . '/' . $p));
}

?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
  <a href="<?= _url('index.php') ?>" class="navbar-brand fw-bold" style="color:#d35400;">PizzaHub 🍕</a>
  <?php if (!empty($showBack)): ?>
    <?php $backTarget = $showBackTarget ?? 'View/all_product.php'; ?>
    <a href="<?= _url($backTarget) ?>" class="btn btn-link ms-3 d-none d-lg-inline">← Back</a>
  <?php endif; ?>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="mainNav">
    <ul class="navbar-nav ms-auto align-items-center">
      <li class="nav-item"><a href="<?= _url('View/all_product.php') ?>" class="nav-link">All Products</a></li>
      <?php if (!function_exists('isLoggedIn') || !isLoggedIn()): ?>
        <li class="nav-item"><a href="<?= _url('index.php') ?>" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="<?= _url('login/register.php') ?>" class="nav-link">Join Us</a></li>
        <li class="nav-item"><a href="<?= _url('login/login.php') ?>" class="nav-link">Sign In</a></li>
        
      <?php else: ?>
          <?php if (function_exists('isAdmin') && isAdmin()): ?>
            <li class="nav-item"><a href="<?= _url('admin/category.php') ?>" class="nav-link">Category</a></li>
            <li class="nav-item"><a href="<?= _url('admin/brand.php') ?>" class="nav-link">Brand</a></li>
            <li class="nav-item"><a href="<?= _url('admin/product.php') ?>" class="nav-link">Manage Products</a></li>
            <?php
              // show orders badge for admins
              require_once __DIR__ . '/../controllers/order_controller.php';
              $ordersCount = 0;
              try { $ordersCount = count_orders_ctr(); } catch (Exception $ex) { $ordersCount = 0; }
            ?>
            <li class="nav-item d-flex align-items-center me-2">
              <a href="<?= _url('admin/orders.php') ?>" class="nav-link position-relative">
                <i class="fa fa-receipt"></i>
                <span id="ordersCount" class="badge bg-info ms-1"><?= intval($ordersCount) ?></span>
              </a>
            </li>
          <?php endif; ?>
        <li class="nav-item d-flex align-items-center me-2">
          <a href="<?= _url('View/cart.php') ?>" class="nav-link position-relative">
            <i class="fa fa-shopping-cart"></i>
            <span id="cartCount" class="badge bg-danger ms-1">0</span>
          </a>
        </li>
  <li class="nav-item"><a href="<?= _url('login/logout.php') ?>" class="btn btn-sm btn-outline-danger ms-3">Logout</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
<script>
  // Expose site root to client scripts so relative AJAX paths work from pages in different folders
  window.SITE_ROOT = <?= json_encode($root) ?>;
</script>
