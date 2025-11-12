<?php
require_once __DIR__ . '/../settings/core.php';
// ensure only admins can access
if (!function_exists('isAdmin') || !isAdmin()) {
  header('Location: ../login/login.php');
  exit;
}
$root = '..';
// Orders listing is not enabled in this installation. This page is left intentionally minimal.
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Orders - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php $showBack = false; require_once __DIR__ . '/../includes/header.php'; ?>
<div class="container py-4">
  <div class="alert alert-info">Orders listing is not enabled in this installation. The checkout flow returns order details to the customer payment modal only.</div>
  <a href="product.php" class="btn btn-secondary">Back to products</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
