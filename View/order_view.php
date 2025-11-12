<?php
require_once __DIR__ . '/../settings/core.php';
if (!isLoggedIn()) header('Location: ../login/login.php');
$root = '..';
require_once __DIR__ . '/../controllers/order_controller.php';

$customer_id = $_SESSION['customer_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) header('Location: orders.php');

// fetch customer's orders with items and find the requested order to ensure ownership
$orders = get_orders_by_customer_ctr($customer_id, true);
$order = null;
foreach ($orders as $o) if (intval($o['order_id']) === $id) { $order = $o; break; }
if (!$order) {
  // not found or not owned
  header('Location: orders.php'); exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Order #<?= htmlspecialchars($order['invoice_no']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php $showBack = true; $showBackTarget = '../admin/orders.php'; require_once __DIR__ . '/../includes/header.php'; ?>
<div class="container py-4">
  <h3>Order: <?= htmlspecialchars($order['invoice_no']) ?></h3>
  <p class="text-muted">Date: <?= htmlspecialchars($order['order_date']) ?> — Status: <?= htmlspecialchars($order['order_status']) ?></p>

  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
      <tbody>
        <?php $total = 0; foreach ($order['items'] as $it):
          $price = floatval($it['product_price'] ?? 0);
          $qty = intval($it['qty'] ?? 0);
          $sub = $price * $qty; $total += $sub;
        ?>
        <tr>
          <td><?= htmlspecialchars($it['product_title'] ?? '') ?></td>
          <td>₵ <?= number_format($price,2) ?></td>
          <td><?= $qty ?></td>
          <td>₵ <?= number_format($sub,2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr><th colspan="3" class="text-end">Total</th><th>₵ <?= number_format($total,2) ?></th></tr>
      </tfoot>
    </table>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
