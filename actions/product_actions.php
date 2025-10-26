<?php
// actions/product_actions.php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/product_controller.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

// basic router
$action = $_REQUEST['action'] ?? 'list';
$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 12;
$page = isset($_REQUEST['page']) ? max(1, intval($_REQUEST['page'])) : 1;
$offset = ($page - 1) * $limit;

try {
    if ($action === 'list') {
        $products = view_all_products_ctr($limit, $offset);
        echo json_encode(['status' => 'success', 'products' => $products, 'page' => $page]);
    } elseif ($action === 'search') {
        $q = trim($_REQUEST['q'] ?? '');
        $products = search_products_ctr($q, $limit, $offset);
        echo json_encode(['status' => 'success', 'products' => $products, 'query' => $q]);
    } elseif ($action === 'filter_cat') {
        $cat = intval($_REQUEST['cat_id'] ?? 0);
        $products = filter_products_by_category_ctr($cat, $limit, $offset);
        echo json_encode(['status' => 'success', 'products' => $products]);
    } elseif ($action === 'filter_brand') {
        $brand = intval($_REQUEST['brand_id'] ?? 0);
        $products = filter_products_by_brand_ctr($brand, $limit, $offset);
        echo json_encode(['status' => 'success', 'products' => $products]);
    } elseif ($action === 'single') {
        $id = intval($_REQUEST['id'] ?? 0);
        $product = view_single_product_ctr($id);
        if ($product) {
            echo json_encode(['status' => 'success', 'product' => $product]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unknown action.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
