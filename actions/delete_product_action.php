<?php
require_once __DIR__ . '/../controllers/product_controller.php';
header('Content-Type: application/json');

$id = $_POST['product_id'] ?? null;

if ($id && delete_product_ctr($id)) {
    echo json_encode(["status" => "success", "message" => "Product deleted successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Could not delete product."]);
}
?>
