// js/checkout.js
$(function () {
  const confirmModal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'), {});

  $('#simulatePayBtn').on('click', function () {
    // update modal amount in case cart changed
    const amount = $('#checkoutTotal').text() || $('#modalAmount').text();
    $('#modalAmount').text(amount);
    confirmModal.show();
  });

  $('#confirmPaymentBtn').on('click', function () {
    const currency = $('#paymentCurrency').val() || 'GHS';
    // disable while processing
    $(this).prop('disabled', true);

    $.post('../actions/process_checkout_action.php', { currency: currency }, function (resp) {
      $('#confirmPaymentBtn').prop('disabled', false);
      if (resp.status === 'success') {
        confirmModal.hide();
        // go to success page
        window.location.href = 'payment_success.php?order_id=' + encodeURIComponent(resp.order_id || '');
      } else {
        confirmModal.hide();
        // go to failure page with message
        const m = encodeURIComponent(resp.message || 'Payment failed');
        window.location.href = 'payment_failed.php?msg=' + m;
      }
    }, 'json').fail(function (xhr) {
      $('#confirmPaymentBtn').prop('disabled', false);
      confirmModal.hide();
      window.location.href = 'payment_failed.php?msg=' + encodeURIComponent('Server error during checkout');
    });
  });

  $('#cancelPaymentBtn').on('click', function () {
    confirmModal.hide();
  });
});
