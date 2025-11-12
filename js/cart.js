// js/cart.js
$(function(){
  // compute actions base in a root-aware way so this file works when included from / or /View/
  const ACTIONS_BASE = (typeof window.SITE_ROOT !== 'undefined' && window.SITE_ROOT) ? (window.SITE_ROOT + '/actions/') : 'actions/';

  function refreshCart() {
    $.getJSON(ACTIONS_BASE + 'get_cart_action.php', function(resp){
      if (resp.status === 'success') renderCart(resp.items);
      else $('#cartBody').html('<tr><td colspan="5">Empty cart</td></tr>');
    });
  }

  function renderCart(items) {
    let tbody = $('#cartBody');
    tbody.empty();
    if (!items.length) tbody.append('<tr><td colspan="5">Cart is empty</td></tr>');
    items.forEach(it => {
      // normalize image path: if relative (no http and not starting with ../), prefix with ../ because cart page lives in View/
      let img = it.product_image ? it.product_image : 'uploads/default_pizza.png';
      if (img && !img.startsWith('http') && !img.startsWith('/')) {
        if (!img.startsWith('../')) img = '../' + img;
      }
      tbody.append(`
        <tr>
          <td><img src="${img}" style="width:60px"></td>
          <td>${it.product_title}</td>
          <td>₵ ${Number(it.product_price).toFixed(2)}</td>
          <td><input class="form-control cart-qty-input" data-pid="${it.product_id}" value="${it.qty}" style="width:90px"></td>
          <td><button class="btn btn-sm btn-danger remove-cart-btn" data-pid="${it.product_id}">Remove</button></td>
        </tr>
      `);
    });
  }

  // refresh on load if #cartBody exists
  if ($('#cartBody').length) refreshCart();

  // Expose cart count updater
  function updateCartCount() {
    $.getJSON(ACTIONS_BASE + 'get_cart_action.php', function(resp){
      if (resp && resp.status === 'success') {
        // show distinct item count (number of rows)
        const count = Array.isArray(resp.items) ? resp.items.length : 0;
        // update any elements with id 'cartCount' or class 'cart-count'
        $('#cartCount').text(count);
        $('.cart-count').each(function(){ $(this).text(count); });
      }
    });
  }

  // Run once on load to populate any count badges
  updateCartCount();

  // Make function available globally
  window.updateCartCount = updateCartCount;

  // Animate badge incrementally and then re-sync with server
  function animateCartBadge(delta) {
    const $el = $('#cartCount');
    if (!$el.length) return;
    const cur = parseInt($el.text()) || 0;
    const next = Math.max(0, cur + (parseInt(delta) || 0));
    $el.text(next);
    $el.addClass('badge-pulse');
    // remove pulse after short duration
    setTimeout(() => $el.removeClass('badge-pulse'), 300);
    // resync with server after 800ms to correct any drift
    setTimeout(() => { if (typeof window.updateCartCount === 'function') window.updateCartCount(); }, 800);
  }
  window.animateCartBadge = animateCartBadge;

  // update quantity
  $(document).on('change', '.cart-qty-input', function(){
    const pid = $(this).data('pid');
    const qty = parseInt($(this).val()) || 0;
    $.post(ACTIONS_BASE + 'update_quantity_action.php', { product_id: pid, quantity: qty }, function(resp){
      if (resp.status === 'success') refreshCart(); else Swal.fire('Error', resp.message || 'Update failed','error');
    }, 'json');
  });

  // remove
  $(document).on('click', '.remove-cart-btn', function(){
    const pid = $(this).data('pid');
    $.post(ACTIONS_BASE + 'remove_from_cart_action.php', { product_id: pid }, function(resp){
      if (resp.status === 'success') refreshCart(); else Swal.fire('Error', resp.message || 'Remove failed','error');
    }, 'json');
  });

  // empty cart
  $(document).on('click', '#emptyCartBtn', function(){
    $.post(ACTIONS_BASE + 'empty_cart_action.php', function(resp){
      if (resp.status === 'success') refreshCart(); else Swal.fire('Error', resp.message || 'Failed','error');
    }, 'json');
  });
});
