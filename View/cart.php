<?php
require_once __DIR__ . '/../settings/core.php';
if (!isLoggedIn()) header('Location: ../login/login.php');
?>
<!doctype html>
<html>
<head>
  <title>Your Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php $root = '..'; $showBack = true; $showBackTarget = 'View/all_product.php'; require_once __DIR__ . '/../includes/header.php'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Your Cart</h2>
    <div></div>
  </div>
  <table class="table">
    <thead><tr><th>Image</th><th>Title</th><th>Price</th><th>Qty</th><th></th></tr></thead>
    <tbody id="cartBody">
      <!-- filled by js -->
    </tbody>
  </table>
  <button id="emptyCartBtn" class="btn btn-warning">Empty Cart</button>
  <a href="checkout.php" class="btn btn-primary">Checkout</a>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/cart.js"></script>
</body>
</html>
