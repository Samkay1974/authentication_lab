<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

// Authentication check
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"> Manage Pizza Products</h2>
        <!-- IMPORTANT: type="button" so it won't submit forms accidentally -->
        <button id="showAddFormBtn" type="button" class="btn btn-success">+ Add Product</button>
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

                        // IMPORTANT: add data-product-card so JS can remove the card on delete,
                        // and give the Edit link a class and data-id for JS to catch.
                        echo "
                        <div class='col-md-3' data-product-card='{$p['product_id']}'>
                            <div class='product-card shadow-sm'>
                                <img src='$image' class='product-img' alt='Product Image'>
                                <div class='product-info'>
                                    <h5 class='product-title'>" . htmlspecialchars($p['product_title']) . "</h5>
                                    <p class='text-muted mb-1'><strong>Brand:</strong> " . htmlspecialchars($p['brand_name']) . "</p>
                                    <p class='product-price'>₵" . number_format($p['product_price'], 2) . "</p>
                                    <div class='mt-2 d-flex justify-content-between'>
                    
                                        <a href='#' class='btn btn-sm btn-outline-primary editBtn' data-id='{$p['product_id']}'>Edit</a>
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

<script src="../js/product.js"></script>
</body>
</html>
