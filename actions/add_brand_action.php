<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['customer_id'];
$brand_name = trim($_POST['brand_name'] ?? '');

if ($brand_name === '') {
    echo json_encode(["status" => "error", "message" => "Brand name required"]);
    exit;
}

// Check if brand already exists (for any user)
$existing = get_brand_by_name_ctr($brand_name);
if ($existing) {
    echo json_encode([
        "status" => "error",
        "message" => "This brand already exists!"
    ]);
    exit;
}

$result = add_brand_ctr($user_id, $brand_name);

if ($result) {
    echo json_encode([
        "status" => "success",
        "message" => "Brand added successfully."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to add brand!."
    ]);
}
