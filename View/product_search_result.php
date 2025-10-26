<?php
require_once __DIR__ . '/controllers/product_controller.php';
$q = trim($_GET['q'] ?? '');
$products = [];
if ($q !== '') {
    $products = search_products_ctr($q, 0, 0);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Search results for "<?= htmlspecialchars($q) ?>"</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/product_store.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h3>Search results for "<?= htmlspecialchars($q) ?>"</h3>
    <div class="row">
      <?php if (empty($products)): ?>
        <div class="col-12"><div class="alert alert-info">No results found.</div></div>
      <?php else: ?>
        <?php foreach ($products as $p): ?>
          <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="store-card">
              <a href="single_product.php?id=<?= $p['product_id'] ?>"><img src="<?= $p['product_image'] ?: 'images/default_pizza.png' ?>" class="store-img"></a>
              <div class="store-body">
                <small class="text-muted"><?= htmlspecialchars($p['brand_name'] . ' • ' . $p['cat_name']) ?></small>
                <h5 class="store-title"><a href="single_product.php?id=<?= $p['product_id'] ?>"><?= htmlspecialchars($p['product_title']) ?></a></h5>
                <div class="store-price">₵ <?= number_format($p['product_price'],2) ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
