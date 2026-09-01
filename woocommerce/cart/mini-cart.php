<?php
/**
 * Cart drawer content override (replaces WooCommerce's default mini-cart template).
 * Rendered by woocommerce_mini_cart() in header.php, and re-rendered live via WooCommerce's
 * own cart-fragments AJAX system (the wrapping div.widget_shopping_cart_content in header.php
 * is exactly the selector WooCommerce refreshes by default after every add/remove/qty change).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$cart_items_data = WC()->cart->get_cart();
?>
<div id="cart-items">
  <?php if ( empty( $cart_items_data ) ) : ?>
    <p class="cart-empty">Your cart is empty.</p>
  <?php else : ?>
    <?php foreach ( $cart_items_data as $cart_item_key => $cart_item ) :
      $product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
      if ( ! $product || ! $product->exists() || $cart_item['quantity'] <= 0 ) continue;
      ?>
      <div class="cart-item" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
        <div class="cart-item-info">
          <p class="cart-item-name"><?php echo wp_kses_post( $product->get_name() ); ?></p>
          <p class="cart-item-price"><?php echo wp_kses_post( WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ) ); ?></p>
        </div>
        <div class="cart-item-controls">
          <button type="button" class="qty-btn cbp-qty-minus" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">&minus;</button>
          <span class="qty-num"><?php echo esc_html( $cart_item['quantity'] ); ?></span>
          <button type="button" class="qty-btn cbp-qty-plus" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">&#43;</button>
          <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>"
             class="remove_from_cart_button cart-remove"
             aria-label="Remove item"
             data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
             data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>"
             data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>">&times;</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<div class="cart-footer">
  <div class="cart-total-row">
    <span>Total</span>
    <span><?php wc_cart_totals_order_total_html(); ?></span>
  </div>
  <a class="btn-checkout" href="<?php echo esc_url( wc_get_checkout_url() ); ?>">Proceed to Checkout</a>
  <p class="cart-note">Cyprus only &middot; Shipping &amp; handling: &euro;6</p>
</div>
