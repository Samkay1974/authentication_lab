// js/store_products.js
$(function () {
  const $grid = $('#productGrid');
  const $search = $('#productSearch');
  const $catFilter = $('#filterCategory');
  const $brandFilter = $('#filterBrand');
  const $pagination = $('#productPagination');
  const pageSize = 12;

  function renderProducts(products) {
    $grid.empty();
    if (!products || products.length === 0) {
      $grid.html('<div class="col-12"><div class="alert alert-info text-center">No products found.</div></div>');
      return;
    }

    products.forEach(p => {
      // ✅ Use full URL for image (BASE_URL comes from PHP)
      const img = p.product_image
  ? `${BASE_URL}${p.product_image}`
  : `${BASE_URL}uploads/default_pizza.png`;

      const card = `
      <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="store-card shadow-sm">
          <a href="../View/single_product.php?id=${p.product_id}" class="img-link">
            <img src="${img}" alt="${escapeHtml(p.product_title)}" class="store-img">
          </a>
          <div class="store-body">
            <div class="store-meta">
              <small class="text-muted">${escapeHtml(p.brand_name || '')} • ${escapeHtml(p.cat_name || '')}</small>
            </div>
            <h5 class="store-title">
              <a href="../View/single_product.php?id=${p.product_id}">${escapeHtml(p.product_title)}</a>
            </h5>
            <div class="store-price">₵ ${Number(p.product_price).toFixed(2)}</div>
            <div class="d-flex justify-content-between mt-2">
              <button data-id="${p.product_id}" class="btn btn-sm btn-outline-primary add-to-cart-btn">Add to Cart</button>
              <a href="../View/single_product.php?id=${p.product_id}" class="btn btn-sm btn-primary">View</a>
            </div>
          </div>
        </div>
      </div>`;
      $grid.append(card);
    });
  }

  function escapeHtml(str) {
    if (!str) return '';
    return $('<div>').text(str).html();
  }

  // ✅ Fetch products
  function fetchList(page = 1) {
    $.getJSON('../actions/product_actions.php', { action: 'list', limit: pageSize, page }, function (res) {
      if (res.status === 'success') {
        renderProducts(res.products);
      } else {
        console.error('Error loading products:', res.message);
      }
    });
  }

  // ✅ Search
  function doSearch(q) {
    $.getJSON('../actions/product_actions.php', { action: 'search', q, limit: pageSize }, function (res) {
      if (res.status === 'success') renderProducts(res.products);
    });
  }

  // ✅ Fixed file path (was `..actions` before)
  function filterByCategory(cat) {
    $.getJSON('../actions/product_actions.php', { action: 'filter_cat', cat_id: cat, limit: pageSize }, function (res) {
      if (res.status === 'success') renderProducts(res.products);
    });
  }

  // ✅ Fixed file path (was `..actions` before)
  function filterByBrand(brand) {
    $.getJSON('../actions/product_actions.php', { action: 'filter_brand', brand_id: brand, limit: pageSize }, function (res) {
      if (res.status === 'success') renderProducts(res.products);
    });
  }

  // ✅ Debounced search input
  let timer = null;
  $search.on('input', function () {
    clearTimeout(timer);
    const q = $(this).val().trim();
    timer = setTimeout(function () {
      if (q.length === 0) fetchList();
      else doSearch(q);
    }, 400);
  });

  // ✅ Category & brand filters
  $catFilter.on('change', function () {
    const v = $(this).val();
    if (!v) fetchList(); else filterByCategory(v);
  });

  $brandFilter.on('change', function () {
    const v = $(this).val();
    if (!v) fetchList(); else filterByBrand(v);
  });

  // ✅ Initial load
  fetchList();

  // Delegate Add to Cart clicks (works for dynamically injected cards)
  $(document).on('click', '.add-to-cart-btn', function (e) {
    e.preventDefault();
    const $btn = $(this);
    const pid = $btn.data('id');
    if (!pid) return;
    // Ask user for quantity using SweetAlert2 when available, else prompt()
    function doAdd(quantity) {
      quantity = parseInt(quantity) || 0;
      if (quantity <= 0) {
        if (window.Swal) Swal.fire({ icon: 'warning', title: 'Enter a valid quantity' });
        else alert('Enter a valid quantity');
        return;
      }
      // First check if product already exists in cart so we only increment distinct count when new
      $.getJSON('../actions/get_cart_action.php', function(cartResp){
        let isNew = true;
        if (cartResp && cartResp.status === 'success' && Array.isArray(cartResp.items)) {
          isNew = !cartResp.items.some(it => parseInt(it.product_id) === parseInt(pid));
        }
        $btn.prop('disabled', true);
        $.ajax({
          url: '../actions/add_to_cart_action.php',
          method: 'POST',
          data: { product_id: pid, quantity: quantity },
          dataType: 'json'
        }).done(function (resp) {
          if (resp && resp.status === 'success') {
            if (window.Swal) {
              Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: resp.message || 'Added to cart', showConfirmButton: false, timer: 1800 });
            } else alert(resp.message || 'Added to cart');
            // If the product was new to cart, increment distinct-item badge by 1; otherwise resync
            if (isNew && typeof window.animateCartBadge === 'function') window.animateCartBadge(1);
            else if (typeof window.updateCartCount === 'function') window.updateCartCount();
          } else {
            if (window.Swal) {
              Swal.fire({ icon: 'error', title: 'Error', text: resp.message || 'Failed to add to cart' });
            } else alert(resp.message || 'Failed to add to cart');
          }
        }).fail(function () {
          if (window.Swal) Swal.fire({ icon: 'error', title: 'Network error' }); else alert('Network error');
        }).always(function () {
          $btn.prop('disabled', false);
        });
      });
    }

    if (window.Swal) {
      Swal.fire({
        title: 'Quantity',
        input: 'number',
        inputLabel: 'How many would you like to add?',
        inputValue: 1,
        inputAttributes: { min: 1, step: 1 },
        showCancelButton: true,
        confirmButtonText: 'Add to cart'
      }).then(result => {
        if (result.isConfirmed) doAdd(result.value);
      });
    } else {
      const q = prompt('Enter quantity', '1');
      if (q !== null) doAdd(q);
    }
  });
});
