// js/cart.js
$(function(){
  function refreshCart() {
    $.getJSON('../actions/get_cart_action.php', function(resp){
      if (resp.status === 'success') renderCart(resp.items);
      else $('#cartBody').html('<tr><td colspan="5">Empty cart</td></tr>');
    });
  }

  function renderCart(items) {
    let tbody = $('#cartBody');
    tbody.empty();
    if (!items.length) tbody.append('<tr><td colspan="5">Cart is empty</td></tr>');
    items.forEach(it => {
      const img = it.product_image ? it.product_image : 'uploads/default_pizza.png';
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
    $.getJSON('../actions/get_cart_action.php', function(resp){
      if (resp && resp.status === 'success') {
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

  // update quantity
  $(document).on('change', '.cart-qty-input', function(){
    const pid = $(this).data('pid');
    const qty = parseInt($(this).val()) || 0;
    $.post('../actions/update_quantity_action.php', { product_id: pid, quantity: qty }, function(resp){
      if (resp.status === 'success') refreshCart(); else Swal.fire('Error', resp.message || 'Update failed','error');
    }, 'json');
  });

  // remove
  $(document).on('click', '.remove-cart-btn', function(){
    const pid = $(this).data('pid');
    $.post('../actions/remove_from_cart_action.php', { product_id: pid }, function(resp){
      if (resp.status === 'success') refreshCart(); else Swal.fire('Error', resp.message || 'Remove failed','error');
    }, 'json');
  });

  // empty cart
  $(document).on('click', '#emptyCartBtn', function(){
    $.post('../actions/empty_cart_action.php', function(resp){
      if (resp.status === 'success') refreshCart(); else Swal.fire('Error', resp.message || 'Failed','error');
    }, 'json');
  });
});
