<?php
require_once __DIR__ . '/../controllers/product_controller.php';
$id = intval($_GET['id'] ?? 0);
$product = view_single_product_ctr($id);
if (!$product) {
    header('HTTP/1.1 404 Not Found');
    echo "Product not found";
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($product['product_title']) ?> - Product</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/product_store.css">
  <style>
    .single-img { width:100%; height:420px; object-fit:cover; border-radius:8px; }
    .meta { color:#6c757d; }
    .price-big { color:#d9534f; font-weight:800; font-size:1.6rem; }
  </style>
</head>
<body class="bg-light">
  <div class="container py-4">
    <a href="all_product.php" class="btn btn-link">← Back to all products</a>
    <div class="row g-4 mt-2">
      <div class="col-md-6">
        <img src="<?= $product['product_image'] ?: 'images/default_pizza.png' ?>" alt="" class="single-img">
      </div>
      <div class="col-md-6">
        <h1><?= htmlspecialchars($product['product_title']) ?></h1>
        <div class="meta mb-2"><?= htmlspecialchars($product['brand_name'] ?? '') ?> • <?= htmlspecialchars($product['cat_name'] ?? '') ?></div>
        <div class="price-big">₵ <?= number_format($product['product_price'],2) ?></div>
        <hr>
        <h5>Description</h5>
        <p><?= nl2br(htmlspecialchars($product['product_desc'])) ?></p>
        <h6>Keywords</h6>
        <p class="small text-muted"><?= htmlspecialchars($product['product_keywords']) ?></p>
        <div class="mt-4">
          <a href="#" class="btn btn-primary btn-lg">Add to cart</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
