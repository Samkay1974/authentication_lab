<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';


if (!isLoggedIn()) {
    echo json_encode(["status" => "error", "message" => "You must be logged in."]);
    exit;
}

$user_id = $_SESSION['customer_id'];


$title    = trim($_POST['product_title'] ?? '');
$price    = trim($_POST['product_price'] ?? '');
$desc     = trim($_POST['product_desc'] ?? '');
$cat      = intval($_POST['product_cat'] ?? 0);
$brand    = intval($_POST['product_brand'] ?? 0);
$keywords = trim($_POST['product_keywords'] ?? '');


if ($title === '' || $price === '' || $desc === '' || $cat <= 0 || $brand <= 0) {
    echo json_encode(["status" => "error", "message" => "Please fill all required fields (title, price, category, brand, description)."]);
    exit;
}


$price = floatval($price);
if ($price <= 0) {
    echo json_encode(["status" => "error", "message" => "Please provide a valid product price."]);
    exit;
}


$image_placeholder = ''; 
$product_id = add_product_ctr($cat, $brand, $title, $price, $desc, $image_placeholder, $keywords);


if (!$product_id) {
    echo json_encode(["status" => "error", "message" => "Could not create product."]);
    exit;
}


if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['product_image'];

   
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["status" => "error", "message" => "File upload error (code {$file['error']})."]);
        exit;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($mime, $allowed)) {
        echo json_encode(["status" => "error", "message" => "Invalid image type. Allowed: jpeg, png, webp, gif."]);
        exit;
    }

    $baseUploads = realpath(__DIR__ . '/../uploads'); 
    if ($baseUploads === false) {
        $created = @mkdir(__DIR__ . '/../uploads', 0755, true);
        if (!$created) {
            echo json_encode(["status" => "error", "message" => "Server error: uploads directory missing and could not be created."]);
            exit;
        }
        $baseUploads = realpath(__DIR__ . '/../uploads');
    }

    $userDir = $baseUploads . DIRECTORY_SEPARATOR . 'u' . intval($user_id);
    $productDir = $userDir . DIRECTORY_SEPARATOR . 'p' . intval($product_id);

    if (!is_dir($userDir)) mkdir($userDir, 0755, true);
    if (!is_dir($productDir)) mkdir($productDir, 0755, true);

 
    $originalName = basename($file['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $safeName = 'img_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $targetPath = $productDir . DIRECTORY_SEPARATOR . $safeName;


    $realBase = realpath($baseUploads);
    $realDestDir = realpath(dirname($targetPath));
    if (strpos($realDestDir, $realBase) !== 0) {
        echo json_encode(["status" => "error", "message" => "Upload destination invalid."]);
        exit;
    }


    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode(["status" => "error", "message" => "Failed to move uploaded file."]);
        exit;
    }

    // Build relative path to store in DB (web-facing)
    // NOTE: product.php earlier used paths like '../uploads/...' to render images; store relative from project root
    $relativePath = 'uploads/u' . intval($user_id) . '/p' . intval($product_id) . '/' . $safeName;

    // Update product image path via controller wrapper
    $ok = update_product_image_ctr($product_id, $relativePath);
    if (!$ok) {
        echo json_encode(["status" => "error", "message" => "Product created but failed to save image path to DB."]);
        exit;
    }

    echo json_encode(["status" => "success", "message" => "Product created and image uploaded.", "product_id" => $product_id, "image" => $relativePath]);
    exit;
}

// No image uploaded — done
echo json_encode(["status" => "success", "message" => "Product created (no image).", "product_id" => $product_id]);
exit;
