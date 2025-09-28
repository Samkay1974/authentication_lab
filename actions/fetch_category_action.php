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

$user_id = get_user_id();

// Fetch categories created by this user
$categories = get_categories_by_user_ctr($user_id);

// Return as JSON
if ($categories && is_array($categories)) {
    echo json_encode($categories);
} else {
    echo json_encode([]);
}
?>
