/**
 * Cart drawer behaviour. Relies on WooCommerce's own wc-cart-fragments / wc-add-to-cart scripts
 * for add-to-cart and remove-from-cart AJAX (see template-parts/product-card.php and
 * woocommerce/cart/mini-cart.php, which use WooCommerce's standard classes/markup for those).
 * This file only adds: open/close of the drawer, auto-open on add-to-cart, and the qty +/- buttons
 * (WooCommerce's mini-cart has no built-in AJAX qty stepper, so we call our own endpoint for that
 * — see cbp_update_cart_qty() in functions.php).
 */
(function ($) {
  'use strict';

  function openCart() {
    document.getElementById('cart-overlay').classList.add('active');
    document.getElementById('cart-drawer').classList.add('active');
    document.body.classList.add('cart-open');
  }
  function closeCart() {
    document.getElementById('cart-overlay').classList.remove('active');
    document.getElementById('cart-drawer').classList.remove('active');
    document.body.classList.remove('cart-open');
  }
  window.cbpOpenCart = openCart;
  window.cbpCloseCart = closeCart;

  // Auto-open the drawer whenever WooCommerce successfully adds something to the cart.
  $(document.body).on('added_to_cart', function () {
    openCart();
  });

  // Quantity +/- buttons inside the drawer (event delegation so it survives fragment refreshes).
  $(document.body).on('click', '.cbp-qty-minus, .cbp-qty-plus', function (e) {
    e.preventDefault();
    var $btn = $(this);
    var key = $btn.data('cart-key');
    var $row = $btn.closest('.cart-item');
    var current = parseInt($row.find('.qty-num').text(), 10) || 0;
    var next = $btn.hasClass('cbp-qty-plus') ? current + 1 : current - 1;

    if (typeof wc_cart_fragments_params === 'undefined') return;

    $.ajax({
      url: wc_cart_fragments_params.ajax_url,
      type: 'POST',
      data: { action: 'cbp_update_cart_qty', key: key, qty: next },
      dataType: 'json',
      success: function (response) {
        if (!response || !response.fragments) return;
        $.each(response.fragments, function (selector, html) {
          $(selector).replaceWith(html);
        });
        $(document.body).trigger('wc_fragments_refreshed');
      },
    });
  });

  // Gift-wrap checkbox inside the cart drawer (for items added straight from a product grid).
  $(document.body).on('change', '.cbp-gift-wrap-toggle', function () {
    var $box = $(this);
    if (typeof wc_cart_fragments_params === 'undefined') return;

    $.ajax({
      url: wc_cart_fragments_params.ajax_url,
      type: 'POST',
      data: { action: 'cbp_toggle_gift_wrap', key: $box.data('cart-key'), checked: $box.is(':checked') ? 1 : 0 },
      dataType: 'json',
      success: function (response) {
        if (!response || !response.fragments) return;
        $.each(response.fragments, function (selector, html) {
          $(selector).replaceWith(html);
        });
        $(document.body).trigger('wc_fragments_refreshed');
      },
    });
  });

  // Make the whole product card clickable (not just the photo/name), while still letting
  // "Add to Cart" and any other real links/buttons on the card behave normally.
  $(document.body).on('click', '.product-card', function (e) {
    if ($(e.target).closest('a, button').length) return;
    var link = this.querySelector('.product-image');
    if (link) window.location.href = link.href;
  });
})(jQuery);
