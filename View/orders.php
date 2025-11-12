<?php
require_once __DIR__ . '/../settings/core.php';
if (!isLoggedIn()) header('Location: ../login/login.php');
$root = '..';
require_once __DIR__ . '/../controllers/order_controller.php';

$customer_id = $_SESSION['customer_id'];
$orders = get_orders_by_customer_ctr($customer_id, true); // include items for list totals
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Your Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php $showBack = true; $showBackTarget = 'index.php'; require_once __DIR__ . '/../includes/header.php'; ?>
<div class="container py-4">
  <h2>Your Orders</h2>
  <?php if (empty($orders)): ?>
    <div class="alert alert-info">You have no past orders.</div>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($orders as $o):
        $total = 0;
        if (!empty($o['items'])) {
          foreach ($o['items'] as $it) { $total += floatval($it['product_price'] ?? 0) * intval($it['qty'] ?? 0); }
        }
      ?>
      <div class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <strong>Invoice:</strong> <?= htmlspecialchars($o['invoice_no']) ?>
          <div class="small text-muted">Date: <?= htmlspecialchars($o['order_date']) ?> — Status: <?= htmlspecialchars($o['order_status']) ?></div>
        </div>
        <div class="text-end">
          <div>Items: <?= intval(count($o['items'] ?? [])) ?></div>
          <div class="fw-bold">₵ <?= number_format($total,2) ?></div>
          <a href="order_view.php?id=<?= urlencode($o['order_id']) ?>" class="btn btn-sm btn-outline-primary mt-2">View</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
