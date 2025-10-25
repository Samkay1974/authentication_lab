<?php
require_once __DIR__ . '/../controllers/product_controller.php';
require_once __DIR__ . '/../settings/core.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

$user_id = $_SESSION['customer_id'];

$product_id = $_POST['product_id'];
$cat_id = $_POST['product_cat'];
$brand_id = $_POST['product_brand'];
$title = trim($_POST['product_title']);
$price = $_POST['product_price'];
$desc = trim($_POST['product_desc']);
$keywords = trim($_POST['product_keywords']);

$product = get_single_product_ctr($product_id);
if (!$product) {
    echo json_encode(["status" => "error", "message" => "Product not found."]);
    exit;
}

// Handle image upload (optional)
$image_path = $product['product_image'];
if (!empty($_FILES['product_image']['name'])) {
    $uploadDir = "../uploads/u{$user_id}/p{$product_id}/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imageName = basename($_FILES['product_image']['name']);
    $targetPath = $uploadDir . $imageName;

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetPath)) {
        $image_path = $targetPath;
        update_product_image_ctr($product_id, $image_path);
    }
}

// Update product
$ok = update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $desc, $image_path, $keywords);

if ($ok) {
    echo json_encode(["status" => "success", "message" => "Product updated successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Could not update product."]);
}
?>
