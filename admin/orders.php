<?php
require_once __DIR__ . '/../settings/core.php';
if (!function_exists('isAdmin') || !isAdmin()) header('Location: ../login/login.php');
$root = '..';
require_once __DIR__ . '/../controllers/order_controller.php';
$orders = get_all_orders_ctr();
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
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Orders <span class="badge bg-primary"><?= count($orders) ?></span></h2>
  </div>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>Order ID</th><th>Invoice</th><th>Customer</th><th>Date</th><th>Items</th><th>Status</th><th></th></tr></thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr><td colspan="7">No orders yet</td></tr>
        <?php else: foreach ($orders as $o): ?>
          <tr>
            <td>#<?= htmlspecialchars($o['order_id']) ?></td>
            <td><?= htmlspecialchars($o['invoice_no']) ?></td>
            <td><?= htmlspecialchars($o['customer_name'] ?? 'Guest') ?></td>
            <td><?= htmlspecialchars($o['order_date']) ?></td>
            <td><?= htmlspecialchars($o['item_count']) ?></td>
            <td><?= htmlspecialchars($o['order_status']) ?></td>
            <td><a href="order_view.php?id=<?= urlencode($o['order_id']) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
