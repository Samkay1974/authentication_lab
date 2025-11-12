<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';

if (!isLoggedIn()) {
    echo json_encode(["status"=>"error","message"=>"Login required"]);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$items = get_user_cart_ctr($customer_id);
echo json_encode(["status"=>"success","items"=>$items]);
