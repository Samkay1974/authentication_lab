<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';

if (!isLoggedIn()) { echo json_encode(["status"=>"error","message"=>"Login required"]); exit; }

$customer_id = $_SESSION['customer_id'];
$product_id = intval($_POST['product_id'] ?? 0);
$qty = intval($_POST['quantity'] ?? 0);

if ($product_id <= 0 || $qty < 0) { echo json_encode(["status"=>"error","message"=>"Invalid data"]); exit; }

$ok = update_cart_item_ctr($customer_id, $product_id, $qty);
echo json_encode($ok ? ["status"=>"success","message"=>"Quantity updated"] : ["status"=>"error","message"=>"Update failed"]);
