<?php
require_once __DIR__ . '/../settings/core.php';
if (!isLoggedIn()) header('Location: ../login/login.php');
?>
<!doctype html>
<html>
<head>
  <title>Your Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h2>Your Cart</h2>
  <table class="table">
    <thead><tr><th>Image</th><th>Title</th><th>Price</th><th>Qty</th><th></th></tr></thead>
    <tbody id="cartBody">
      <!-- filled by js -->
    </tbody>
  </table>
  <button id="emptyCartBtn" class="btn btn-warning">Empty Cart</button>
  <a href="checkout.php" class="btn btn-primary">Checkout</a>
</div>
<script src="../js/cart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
