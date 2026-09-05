<?php
/**
 * Product card — used on both the homepage featured grid and the Shop archive.
 * Expects the global $product (WC_Product) to already be set, as WooCommerce does in its loops.
 *
 * This is the ONE place that defines what a candle "card" looks like. Any product Petra
 * publishes in wp-admin renders through this same markup automatically — no per-candle coding.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

global $product;
if ( empty( $product ) || ! $product->is_visible() ) return;

$origin_label = get_post_meta( $product->get_id(), '_cbp_origin_label', true );
$scent_tag    = get_post_meta( $product->get_id(), '_cbp_scent_tag', true );
$in_stock     = $product->is_in_stock();
?>
<div class="product-card card reveal<?php echo $in_stock ? '' : ' outofstock'; ?>" data-name="<?php echo esc_attr( $product->get_name() ); ?>">
  <a class="product-image" href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
    <?php echo $product->get_image( 'large' ); ?>
    <div class="flame"></div>
  </a>
  <div class="product-card-body">
    <?php if ( $origin_label ) : ?>
      <div class="origin-badge">
        <div class="origin-dot"></div>
        <span class="origin-label"><?php echo esc_html( $origin_label ); ?></span>
      </div>
    <?php endif; ?>
    <p class="product-name"><a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></p>
    <?php if ( $scent_tag ) : ?>
      <p class="product-scent"><?php echo esc_html( $scent_tag ); ?></p>
    <?php endif; ?>
    <p class="product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
    <a class="product-view-details" href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">View Details</a>

    <?php if ( $in_stock ) : ?>
      <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
         data-quantity="1"
         data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
         data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
         class="btn-pill btn-pill-dark ajax_add_to_cart add_to_cart_button">
        Add to Cart
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    <?php else : ?>
      <button class="btn-pill btn-pill-dark disabled" disabled>Sold out for now</button>
    <?php endif; ?>
  </div>
</div>
