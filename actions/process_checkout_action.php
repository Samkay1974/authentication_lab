<?php
session_start();
header('Content-Type: application/json');

// Debug helpers (local only) - enable to surface DB errors during development
ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';
require_once __DIR__ . '/../controllers/order_controller.php';

try {
    if (!isLoggedIn()) { echo json_encode(["status"=>"error","message"=>"Login required"]); exit; }

    if (empty($_SESSION['customer_id']) || !is_numeric($_SESSION['customer_id'])) {
        echo json_encode(["status"=>"error","message"=>"Invalid or missing customer session"]); exit;
    }

    $customer_id = intval($_SESSION['customer_id']);
    $items = get_user_cart_ctr($customer_id);
    if (empty($items)) { echo json_encode(["status"=>"error","message"=>"Cart empty"]); exit; }

    // compute total from DB fields to avoid client tampering
    $total = 0.0;
    foreach ($items as $it) {
        $unit = floatval($it['product_price'] ?? 0);
        $qty = intval($it['qty'] ?? $it['quantity'] ?? 0);
        $total += $unit * $qty;
    }

    // create order: generate numeric invoice_no (DB expects integer)
    $invoice_no = intval(time());
    $order_id = create_order_ctr($customer_id, $invoice_no, 'processing');
    if (!$order_id) { throw new Exception('Could not create order'); }

    // insert orderdetails
    $all_ok = true;
    foreach ($items as $it) {
        $pid = intval($it['product_id'] ?? $it['product_id']);
        $qty = intval($it['qty'] ?? 0);
        if ($qty <= 0) continue;
        $ok = add_order_detail_ctr($order_id, $pid, $qty);
        if (!$ok) $all_ok = false;
    }
    if (!$all_ok) { throw new Exception('Failed to save order details'); }

    // record simulated payment
    $pay_ok = record_payment_ctr($order_id, $total, $customer_id, 'GHS');
    if (!$pay_ok) { throw new Exception('Failed to record payment'); }

    // clear cart
    $emptied = empty_cart_ctr($customer_id);
    if (!$emptied) {
        echo json_encode(["status"=>"warning","message"=>"Order placed but cart not cleared", "order_id"=>$order_id]);
        exit;
    }

    // Prepare items summary for the response (product, unit price, qty, subtotal)
    $order_items = [];
    foreach ($items as $it) {
        $unit = floatval($it['product_price'] ?? 0);
        $qty = intval($it['qty'] ?? 0);
        if ($qty <= 0) continue;
        $order_items[] = [
            'product_id' => intval($it['product_id'] ?? 0),
            'product_title' => $it['product_title'] ?? '',
            'unit_price' => $unit,
            'quantity' => $qty,
            'subtotal' => $unit * $qty,
        ];
    }

    // Return success + URL to view order (relative). order_view expects ?id=<order_id>
    $order_view = 'View/order_view.php?id=' . urlencode($order_id);
    $orders_page = 'View/orders.php';

    echo json_encode([
        "status"=>"success",
        "message"=>"Order placed",
        "order_id"=>$order_id,
        "invoice_no"=>$invoice_no,
        "amount"=>$total,
        "items"=>$order_items,
        "order_view"=>$order_view,
        "orders_page"=>$orders_page
    ]);

} catch (Throwable $e) {
    // Return exception message for debugging (safe for local dev)
    echo json_encode(["status"=>"error","message"=>"Exception: " . $e->getMessage()]);
    exit;
}
