<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';
require_once __DIR__ . '/../controllers/order_controller.php';

if (!isLoggedIn()) { echo json_encode(["status"=>"error","message"=>"Login required"]); exit; }

$customer_id = $_SESSION['customer_id'];
$items = get_user_cart_ctr($customer_id);
if (empty($items)) { echo json_encode(["status"=>"error","message"=>"Cart empty"]); exit; }

// compute total from DB fields to avoid client tampering
$total = 0.0;
foreach ($items as $it) {
    $unit = floatval($it['product_price'] ?? 0);
    $qty = intval($it['qty'] ?? $it['quantity'] ?? 0);
    $total += $unit * $qty;
}

// create order: generate invoice_no
$invoice_no = 'INV-' . date('YmdHis') . '-' . bin2hex(random_bytes(3));
$order_id = create_order_ctr($customer_id, $invoice_no, 'processing');
if (!$order_id) { echo json_encode(["status"=>"error","message"=>"Could not create order"]); exit; }

// insert orderdetails
$all_ok = true;
foreach ($items as $it) {
    $pid = intval($it['product_id'] ?? $it['product_id']);
    $qty = intval($it['qty'] ?? 0);
    if ($qty <= 0) continue;
    $ok = add_order_detail_ctr($order_id, $pid, $qty);
    if (!$ok) $all_ok = false;
}
if (!$all_ok) { echo json_encode(["status"=>"error","message"=>"Failed to save order details"]); exit; }

// record simulated payment
$pay_ok = record_payment_ctr($order_id, $total, $customer_id, 'GHS');
if (!$pay_ok) { echo json_encode(["status"=>"error","message"=>"Failed to record payment"]); exit; }

// clear cart
$emptied = empty_cart_ctr($customer_id);

if (!$emptied) {
    echo json_encode(["status"=>"warning","message"=>"Order placed but cart not cleared", "order_id"=>$order_id]);
    exit;
}

echo json_encode(["status"=>"success","message"=>"Order placed", "order_id"=>$order_id, "invoice_no"=>$invoice_no, "amount"=>$total]);
