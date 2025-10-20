<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit;
}

$categories = get_all_categories_ctr($_SESSION['customer_id']);
$brands = get_all_brands_ctr($_SESSION['customer_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Products</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/product.js"></script>
</head>

<body class="p-5">
<div class="container">
<h2 class="mb-4">Add / Manage Products</h2>

<!-- CREATE / UPDATE FORM -->
<form id="productForm" enctype="multipart/form-data">
  <input type="hidden" id="product_id" name="product_id">
  <div class="mb-3">
    <label>Category</label>
    <select id="product_cat" name="product_cat" class="form-select" required>
      <option value="">Select Category</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= $c['cat_id'] ?>"><?= $c['cat_name'] ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Brand</label>
    <select id="product_brand" name="product_brand" class="form-select" required>
      <option value="">Select Brand</option>
      <?php foreach ($brands as $b): ?>
        <option value="<?= $b['brand_id'] ?>"><?= $b['brand_name'] ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3"><label>Title</label><input type="text" id="product_title" name="product_title" class="form-control" required></div>
  <div class="mb-3"><label>Price</label><input type="number" id="product_price" name="product_price" step="0.01" class="form-control" required></div>
  <div class="mb-3"><label>Description</label><textarea id="product_desc" name="product_desc" class="form-control" required></textarea></div>
  <div class="mb-3"><label>Image</label><input type="file" id="product_image" name="product_image" class="form-control"></div>
  <div class="mb-3"><label>Keywords</label><input type="text" id="product_keywords" name="product_keywords" class="form-control" required></div>
  <button type="submit" class="btn btn-success">Save Product</button>
</form>

<hr>
<h3 class="mt-4">Products</h3>
<div id="productContainer"></div>
</div>
</body>
</html>
