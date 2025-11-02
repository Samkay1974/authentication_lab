<?php
require_once __DIR__ . '/../controllers/product_controller.php';
require_once __DIR__ . '/../settings/core.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

$user_id = $_SESSION['customer_id'];

$product_id = intval($_POST['product_id'] ?? 0);
$cat_id     = intval($_POST['product_cat'] ?? 0);
$brand_id   = intval($_POST['product_brand'] ?? 0);
$title      = trim($_POST['product_title'] ?? '');
$price      = floatval($_POST['product_price'] ?? 0);
$desc       = trim($_POST['product_desc'] ?? '');
$keywords   = trim($_POST['product_keywords'] ?? '');

if (!$product_id || $title === '' || $price <= 0 || $desc === '' || $cat_id <= 0 || $brand_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Please fill all required fields."]);
    exit;
}

// Get existing product
$product = get_single_product_ctr($product_id);
if (!$product) {
    echo json_encode(["status" => "error", "message" => "Product not found."]);
    exit;
}

// Keep old image if no new one
$image_path = $product['product_image'] ?? '';

// ✅ Handle new image upload (optional)
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['product_image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["status" => "error", "message" => "File upload error (code {$file['error']})."]);
        exit;
    }

    // Validate image type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($mime, $allowed)) {
        echo json_encode(["status" => "error", "message" => "Invalid image type. Allowed: jpeg, png, webp, gif."]);
        exit;
    }

    // Define upload folders
    $baseUploads = __DIR__ . '/../uploads';
    $userDir = $baseUploads . "/u{$user_id}";
    $productDir = $userDir . "/p{$product_id}";

    if (!is_dir($userDir)) mkdir($userDir, 0775, true);
    if (!is_dir($productDir)) mkdir($productDir, 0775, true);

    // Delete old image if it exists
    if (!empty($image_path)) {
        $oldImagePath = __DIR__ . '/../' . $image_path;
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }

    // Generate a safe new name
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $safeName = 'img_' . time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
    $targetPath = $productDir . '/' . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode(["status" => "error", "message" => "Failed to save uploaded file."]);
        exit;
    }

    // ✅ Web-accessible path (not "../")
    $image_path = "uploads/u{$user_id}/p{$product_id}/{$safeName}";

    // Update image path in DB
    update_product_image_ctr($product_id, $image_path);
}

// ✅ Update product info
$ok = update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $desc, $image_path, $keywords);

if ($ok) {
    echo json_encode([
        "status" => "success",
        "message" => "Product updated successfully!",
        "image" => $image_path
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Could not update product."]);
}
