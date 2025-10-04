<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';

// Ensure user is logged in
if (!isLoggedIn()) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['customer_id'];

// Fetch categories created by this user
$categories = get_categories_by_user_ctr($user_id);

echo json_encode($categories ?: []);

?>
