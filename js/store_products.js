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
      const img = p.product_image ? p.product_image : 'images/default_pizza.png';
      const card = `
      <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="store-card">
          <a href="../view/single_product.php?id=${p.product_id}" class="img-link"><img src="${img}" alt="${escapeHtml(p.product_title)}" class="store-img"></a>
          <div class="store-body">
            <div class="store-meta"><small class="text-muted">${escapeHtml(p.brand_name || '')} • ${escapeHtml(p.cat_name || '')}</small></div>
            <h5 class="store-title"><a href="../view/single_product.php?id=${p.product_id}">${escapeHtml(p.product_title)}</a></h5>
            <div class="store-price">₵ ${Number(p.product_price).toFixed(2)}</div>
            <div class="d-flex justify-content-between mt-2">
              <a href="#" class="btn btn-sm btn-outline-primary">Add to Cart</a>
              <a href="../view/single_product.php?id=${p.product_id}" class="btn btn-sm btn-primary">View</a>
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

  function fetchList(page = 1) {
    $.getJSON('actions/product_actions.php', { action: 'list', limit: pageSize, page }, function (res) {
      if (res.status === 'success') {
        renderProducts(res.products);
        // TODO: build pagination from res.page (server could supply total count in a later step)
      }
    });
  }

  function doSearch(q) {
    $.getJSON('actions/product_actions.php', { action: 'search', q, limit: pageSize }, function (res) {
      if (res.status === 'success') renderProducts(res.products);
    });
  }

  function filterByCategory(cat) {
    $.getJSON('actions/product_actions.php', { action: 'filter_cat', cat_id: cat, limit: pageSize }, function (res) {
      if (res.status === 'success') renderProducts(res.products);
    });
  }

  function filterByBrand(brand) {
    $.getJSON('actions/product_actions.php', { action: 'filter_brand', brand_id: brand, limit: pageSize }, function (res) {
      if (res.status === 'success') renderProducts(res.products);
    });
  }

  // Debounce search
  let timer = null;
  $search.on('input', function () {
    clearTimeout(timer);
    const q = $(this).val().trim();
    timer = setTimeout(function () {
      if (q.length === 0) fetchList();
      else doSearch(q);
    }, 400);
  });

  $catFilter.on('change', function () {
    const v = $(this).val();
    if (!v) fetchList(); else filterByCategory(v);
  });

  $brandFilter.on('change', function () {
    const v = $(this).val();
    if (!v) fetchList(); else filterByBrand(v);
  });

  // initial load
  fetchList();
});
