<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

// authorization check
if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit;
}

$user_id = $_SESSION['customer_id'];
$products = get_products_by_user_ctr($user_id);
$categories = get_all_categories_ctr($user_id);
$brands = get_all_brands_ctr($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/product.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">
<div class="container py-5">

    <?php if (isLoggedIn()): ?>
        <h2 class="mb-4"><?= htmlspecialchars($_SESSION['customer_name']) ?>'s Product Manager</h2>
        <a style="position: absolute; top: 20px; right: 20px;" href="../index.php" class="btn btn-outline-secondary">Back</a>
				<?php endif; ?>
    <div>
    <button id="showAddFormBtn" type="button" class="btn btn-success">+ Add New Product</button>
    </div>

    <!-- ADD PRODUCT FORM -->
    <div id="addProductForm" class="card mb-5" style="display: <?php echo empty($products) ? 'block' : 'none'; ?>;">
        <div class="card-body">
            <h4 class="text-center mb-3">Add New Product</h4>
            <form id="productForm" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Product Title</label>
                        <input type="text" class="form-control" name="product_title" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Product Price (₵)</label>
                        <input type="number" step="0.01" class="form-control" name="product_price" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="product_cat" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Brand</label>
                        <select class="form-select" name="product_brand" required>
                            <option value="">Select Brand</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Description</label>
                    <textarea class="form-control" name="product_desc" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Keywords</label>
                    <input type="text" class="form-control" name="product_keywords" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input type="file" class="form-control" name="product_image" accept="image/*" required>
                </div>

                <button type="submit" id="productFormSubmit" class="btn btn-primary w-100">Add Product</button>
            </form>
        </div>
    </div>

    <!-- PRODUCT DISPLAY -->
    <div id="productDisplay">
        <?php
        if (empty($products)) {
            echo "<div class='alert alert-warning text-center'>No products added yet. Use the form above to add your first product!</div>";
        } else {
            foreach ($categories as $cat) {
                $cat_id = $cat['cat_id'];
                $cat_products = array_filter($products, fn($p) => $p['product_cat'] == $cat_id);

                if (!empty($cat_products)) {
                    echo "<h3 class='category-title mt-5'>" . htmlspecialchars($cat['cat_name']) . "</h3>";
                    echo "<div class='row g-4'>";

                    foreach ($cat_products as $p) {
                        $image = !empty($p['product_image']) ? $p['product_image'] : '../images/default_pizza.png';

                        echo "
                        <div class='col-md-3' data-product-card='{$p['product_id']}'>
                            <div class='product-card shadow-sm'>
                                <img src='$image' class='product-img' alt='Product Image'>
                                <div class='product-info'>
                                    <h5 class='product-title'>" . htmlspecialchars($p['product_title']) . "</h5>
                                    <p class='text-muted mb-1'><strong>Brand:</strong> " . htmlspecialchars($p['brand_name']) . "</p>
                                    <p class='product-price'>₵" . number_format($p['product_price'], 2) . "</p>
                                    <div class='mt-2 d-flex justify-content-between'>
                                  
                                            <button 
                                                class='btn btn-sm btn-outline-primary editBtn'
                                                data-id='{$p['product_id']}'
                                                data-title='" . htmlspecialchars($p['product_title'], ENT_QUOTES) . "'
                                                data-price='{$p['product_price']}'
                                                data-desc='" . htmlspecialchars($p['product_desc'], ENT_QUOTES) . "'
                                                data-keywords='" . htmlspecialchars($p['product_keywords'], ENT_QUOTES) . "'
                                                data-cat='{$p['product_cat']}'
                                                data-brand='{$p['product_brand']}'>Edit
                                            </button>
        
                                        <button class='btn btn-sm btn-outline-danger deleteBtn' data-id='{$p['product_id']}'>Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }

                    echo "</div>";
                }
            }
        }
        ?>
    </div>
</div>


<!-- EDIT PRODUCT MODAL -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editProductForm" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <input type="hidden" name="product_id" id="edit_product_id">

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Product Title</label>
            <input type="text" class="form-control" name="product_title" id="edit_product_title" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Product Price (₵)</label>
            <input type="number" step="0.01" class="form-control" name="product_price" id="edit_product_price" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Category</label>
            <select class="form-select" name="product_cat" id="edit_product_cat" required>
              <option value="">Select Category</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Brand</label>
            <select class="form-select" name="product_brand" id="edit_product_brand" required>
              <option value="">Select Brand</option>
              <?php foreach ($brands as $brand): ?>
                <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="product_desc" id="edit_product_desc" rows="3" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Keywords</label>
          <input type="text" class="form-control" name="product_keywords" id="edit_product_keywords" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Product Image</label>
          <input type="file" class="form-control" name="product_image" id="edit_product_image" accept="image/*">
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Confirm Changes</button>
      </div>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/product.js"></script>
</body>
</html>
