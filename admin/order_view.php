<?php
require_once __DIR__ . '/../settings/core.php';
// ensure only admins can access
if (!function_exists('isAdmin') || !isAdmin()) {
  header('Location: ../login/login.php');
  exit;
}
$root = '..';
// This admin order detail view is intentionally disabled. The checkout flow provides order details to the customer-facing payment modal.
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Order Details - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php $showBack = true; $showBackTarget = 'admin/product.php'; require_once __DIR__ . '/../includes/header.php'; ?>
<div class="container py-4">
  <div class="alert alert-info">Order admin view is disabled. Order details are provided to the customer payment modal only.</div>
  <a href="product.php" class="btn btn-secondary">Back to products</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
