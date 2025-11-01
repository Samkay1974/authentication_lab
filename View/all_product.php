<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$categories = get_all_categories_ctr();
$brands = get_all_brands_ctr();

$baseUrl = "http://169.239.251.102:442/~samuel.ninson/";
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>All Products - Easy Pizza</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/product_store.css">
  <script>
  var BASE_URL = "http://169.239.251.102:442/~samuel.ninson/";
</script>
</head>

<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <a style="position:fixed; top: 20px; right: 20px;" href="../index.php" class="btn btn-outline-secondary">Back</a>
      <h2>All Products</h2>
      <div class="d-flex gap-2">
        <input id="productSearch" class="form-control" style="min-width:240px" placeholder="Search products...">
        <select id="filterCategory" class="form-select">
          <option value="">All Categories</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c['cat_id'] ?>"><?= htmlspecialchars($c['cat_name']) ?></option>
          <?php endforeach; ?>
        </select>
        <select id="filterBrand" class="form-select">
          <option value="">All Brands</option>
          <?php foreach ($brands as $b): ?>
            <option value="<?= $b['brand_id'] ?>"><?= htmlspecialchars($b['brand_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div id="productGrid" class="row">
      <!-- cards injected by js -->
    </div>

    <div id="productPagination" class="mt-4"></div>
  </div>

  <script>
    // ✅ Make base URL accessible to JS
    const BASE_URL = "<?= $baseUrl ?>";
  </script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../js/store_products.js"></script>
</body>
</html>
