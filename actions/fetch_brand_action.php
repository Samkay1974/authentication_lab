<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['customer_id'];
$brands = get_brands_by_user_ctr($user_id);

echo json_encode($brands ?: []);
