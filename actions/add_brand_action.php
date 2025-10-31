<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$brand_name = trim($_POST['brand_name'] ?? '');
if ($brand_name === '') {
    echo json_encode(["status" => "error", "message" => "Brand name required"]);
    exit;
}

$user_id = $_SESSION['customer_id'];
$result = add_brand_ctr($user_id, $brand_name);
$name = trim($_POST['brand_name']);

$existing = get_brand_by_name_ctr($name);
if ($existing) {
    echo json_encode([
        "status" => "error",
        "message" => "This brand already exists!"
    ]);
    exit;
}

$ok = add_brand_ctr($name, $user_id);

if ($result) {
    echo json_encode(["status" => "success", "message" => "Brand added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Brand already exists or could not be added"]);
    exit;
}



