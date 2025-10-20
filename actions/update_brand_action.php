<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$brand_id = intval($_POST['brand_id'] ?? 0);
$brand_name = trim($_POST['brand_name'] ?? '');

if ($brand_id <= 0 || $brand_name === '') {
    echo json_encode(["status" => "error", "message" => "Missing brand ID or name"]);
    exit;
}

$user_id = $_SESSION['customer_id'];
$result = update_brand_ctr($user_id, $brand_id, $brand_name);

if ($result) {
    echo json_encode(["status" => "success", "message" => "Brand updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Brand could not be updated"]);
}
