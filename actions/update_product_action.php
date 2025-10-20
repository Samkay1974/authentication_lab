<?php
require_once __DIR__ . '/../controllers/product_controller.php';
header('Content-Type: application/json');

$id = $_POST['product_id'];
$cat_id = $_POST['product_cat'];
$brand_id = $_POST['product_brand'];
$title = $_POST['product_title'];
$price = $_POST['product_price'];
$desc = $_POST['product_desc'];
$keywords = $_POST['product_keywords'];
$image = $_POST['product_image_old'];

if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
    $uploadDir = "../uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $image = $uploadDir . basename($_FILES['product_image']['name']);
    move_uploaded_file($_FILES['product_image']['tmp_name'], $image);
}

if (update_product_ctr($id, $cat_id, $brand_id, $title, $price, $desc, $image, $keywords)) {
    echo json_encode(["status" => "success", "message" => "Product updated successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update product."]);
}
?>
