<?php
require_once __DIR__ . '/../controllers/product_controller.php';
header('Content-Type: application/json');

$products = get_all_products_ctr();

$structured = [];
foreach ($products as $p) {
    $brand = $p['brand_name'] ?? 'Unbranded';
    $cat = $p['cat_name'] ?? 'Uncategorized';

    if (!isset($structured[$brand])) $structured[$brand] = [];
    if (!isset($structured[$brand][$cat])) $structured[$brand][$cat] = [];

    $structured[$brand][$cat][] = $p;
}

echo json_encode($structured);
?>
