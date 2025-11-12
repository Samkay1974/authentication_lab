<?php
// view/payment_failed.php
require_once __DIR__ . '/../settings/core.php';

$msg = trim($_GET['msg'] ?? 'Payment failed. Please try again.');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Payment Failed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style> .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono", "Courier New", monospace; } </style>
</head>
<body class="bg-light">
  <div class="container py-5 text-center">
    <h1 class="text-danger">Payment Failed</h1>
    <p class="lead">We couldn't complete your payment.</p>
    <div class="alert alert-warning" role="alert">
      <strong>Error:</strong>
      <div class="mono mt-2"><?= htmlspecialchars($msg) ?></div>
    </div>
    <div class="mt-3">
      <a href="../View/orders.php" class="btn btn-secondary">View My Orders</a>
      <a href="../index.php" class="btn btn-primary ms-2">Continue shopping</a>
    </div>
  </div>
</body>
</html>
