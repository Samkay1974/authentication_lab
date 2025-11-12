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
        <img src="../<?= htmlspecialchars($product['product_image']) ?>" alt="Product Image" class="single-img">
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
          <form id="add-to-cart-form" class="d-flex align-items-center gap-2" action="../actions/add_to_cart_action.php" method="post">
            <input type="hidden" name="product_id" value="<?= intval($product['product_id']) ?>">
            <input type="number" name="quantity" value="1" min="1" class="form-control" style="width:100px">
            <button type="submit" class="btn btn-primary btn-lg">Add to cart</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../js/cart.js"></script>
  <script>
    (function(){
      const form = document.getElementById('add-to-cart-form');
      form.addEventListener('submit', function(e){
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        fetch(form.action, { method: 'POST', body: new FormData(form) })
          .then(r => r.json())
          .then(j => {
            btn.disabled = false;
            if (j.status === 'success') {
              if (window.Swal) Swal.fire({ toast:true, position:'top-end', icon:'success', title: j.message || 'Added to cart', showConfirmButton:false, timer:1500 });
              else alert(j.message || 'Added to cart');
              if (typeof window.updateCartCount === 'function') window.updateCartCount();
            } else {
              if (window.Swal) Swal.fire({ icon:'error', title:'Error', text: j.message || 'Failed to add to cart' });
              else alert(j.message || 'Failed to add to cart');
            }
          }).catch(()=>{
            btn.disabled = false;
            if (window.Swal) Swal.fire({ icon:'error', title:'Network error' }); else alert('Network or server error');
          });
      });
    })();
  </script>
</html>
