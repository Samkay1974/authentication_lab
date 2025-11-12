<?php
require_once __DIR__ . '/../settings/core.php';
if (!function_exists('isAdmin') || !isAdmin()) header('Location: ../login/login.php');
$root = '..';
require_once __DIR__ . '/../controllers/order_controller.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$order = $id ? get_order_by_id_ctr($id) : null;
$items = $id ? get_order_details_ctr($id) : [];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Order #<?= $id ?> - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php $showBack = true; $showBackTarget = 'admin/orders.php'; require_once __DIR__ . '/../includes/header.php'; ?>
<div class="container py-4">
  <?php if (!$order): ?>
    <div class="alert alert-warning">Order not found.</div>
  <?php else: ?>
    <div class="card mb-3">
      <div class="card-body">
        <h4>Order #<?= htmlspecialchars($order['order_id']) ?> <small class="text-muted">Invoice: <?= htmlspecialchars($order['invoice_no']) ?></small></h4>
        <p>Customer: <?= htmlspecialchars($order['customer_name'] ?? 'Guest') ?> &lt;<?= htmlspecialchars($order['customer_email'] ?? '') ?>&gt;</p>
        <p>Date: <?= htmlspecialchars($order['order_date']) ?> — Status: <strong><?= htmlspecialchars($order['order_status']) ?></strong></p>
      </div>
    </div>

    <h5>Items</h5>
    <div class="table-responsive">
      <table class="table">
        <thead><tr><th>Image</th><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
          <?php $total = 0; foreach ($items as $it):
            $price = floatval($it['product_price']);
            $qty = intval($it['qty']);
            $sub = $price * $qty; $total += $sub;
            $img = $it['product_image'] ?: 'uploads/default_pizza.png';
            if (!str_starts_with($img, 'http') && !str_starts_with($img, '/')) $img = '../' . $img;
          ?>
          <tr>
            <td><img src="<?= htmlspecialchars($img) ?>" style="width:60px"></td>
            <td><?= htmlspecialchars($it['product_title']) ?></td>
            <td>₵ <?= number_format($price,2) ?></td>
            <td><?= $qty ?></td>
            <td>₵ <?= number_format($sub,2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr><th colspan="4" class="text-end">Total</th><th>₵ <?= number_format($total,2) ?></th></tr>
        </tfoot>
      </table>
    </div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
