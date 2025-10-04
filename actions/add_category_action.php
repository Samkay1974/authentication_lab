<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';

// Ensuring user is logged in
if (!isLoggedIn()) {
    echo json_encode([
        "status" => "error",
        "message" => "You must be logged in to add a category."
    ]);
    exit;
}


file_put_contents('debug_post.txt', print_r($_POST, true));


if (empty($_POST['cat_name'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Please provide a category name."
    ]);
    exit;
}

$user_id = $_SESSION['customer_id'];
$category_name = trim($_POST['cat_name']);


$result = add_category_ctr($user_id, $category_name);

if ($result) {
    echo json_encode([
        "status" => "success",
        "message" => "Category added successfully."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Category could not be added. It may already exist."
    ]);
}
?>
