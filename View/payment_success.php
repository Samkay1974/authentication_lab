<?php
// view/payment_success.php
require_once __DIR__ . '/../settings/core.php';

$order_id = intval($_GET['order_id'] ?? 0);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Payment Successful</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5 text-center">
    <h1 class="text-success">Payment Successful</h1>
    <?php if ($order_id): ?>
      <p>Your order (ID <strong><?= $order_id ?></strong>) was placed successfully. Check your orders page for details.</p>
    <?php else: ?>
      <p>Your payment was recorded successfully.</p>
    <?php endif; ?>
    <a href="../index.php" class="btn btn-primary mt-3">Continue shopping</a>
  </div>
</body>
</html>
