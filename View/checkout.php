<?php
// view/checkout.php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';

if (!isLoggedIn()) {
    header('Location: ../login/login.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];
$items = get_user_cart_ctr($customer_id);

// compute total server-side as a fallback
$total = 0.0;
foreach ($items as $it) {
    $price = floatval($it['product_price'] ?? 0);
    $qty   = intval($it['qty'] ?? 0);
    $total += $price * $qty;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Checkout - PizzaHub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <a href="../View/cart.php" class="btn btn-outline-secondary mb-3">← Back to Cart</a>
    <h2>Checkout</h2>

    <div class="card mb-4">
      <div class="card-body">
        <h5>Items</h5>
        <?php if (empty($items)): ?>
          <div class="alert alert-warning">Your cart is empty.</div>
        <?php else: ?>
          <table class="table">
            <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Line</th></tr></thead>
            <tbody>
              <?php foreach ($items as $it): 
                $line = floatval($it['product_price']) * intval($it['qty']);
              ?>
                <tr>
                  <td><?= htmlspecialchars($it['product_title']) ?></td>
                  <td>₵ <?= number_format($it['product_price'],2) ?></td>
                  <td><?= intval($it['qty']) ?></td>
                  <td>₵ <?= number_format($line,2) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="text-end">
            <h4>Total: ₵ <span id="checkoutTotal"><?= number_format($total,2) ?></span></h4>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="mb-4">
      <h5>Payment (Simulated)</h5>
      <p class="text-muted">This demo simulates payment; no real payment gateway is used.</p>
      <button id="simulatePayBtn" class="btn btn-primary" <?= empty($items) ? 'disabled' : '' ?>>Simulate Payment</button>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm Payment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>You are about to pay <strong>₵ <span id="modalAmount"><?= number_format($total,2) ?></span></strong>.</p>
            <p>Payment will be simulated. Click Confirm to complete checkout.</p>
            <div class="mb-3">
              <label class="form-label">Currency</label>
              <select id="paymentCurrency" class="form-select">
                <option value="GHS">GHS</option>
                <option value="USD">USD</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button id="cancelPaymentBtn" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button id="confirmPaymentBtn" type="button" class="btn btn-success">Confirm Payment</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /Payment Modal -->

  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/checkout.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
