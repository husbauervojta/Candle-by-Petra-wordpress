<?php
/**
 * Shop page — full override of WooCommerce's default archive template so it matches
 * the original design 1:1. Any product Petra publishes shows up here automatically
 * through template-parts/product-card.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header( 'shop' );

$shop_id = wc_get_page_id( 'shop' );
?>

<!-- PAGE HEADER -->
<div class="page-header motif-lavender">
  <span class="section-label"><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_collection_eyebrow' ) ); ?></span>
  <h1><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_collection_heading' ) ); ?> <em><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_collection_heading_em' ) ); ?></em></h1>
  <p><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_shop_subtitle' ) ); ?></p>
</div>

<!-- SHOP GRID -->
<section class="shop-section">
  <?php wc_print_notices(); ?>
  <div class="shop-grid">
    <?php
    $products = wc_get_products( array( 'status' => 'publish', 'limit' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
    if ( $products ) {
      foreach ( $products as $product ) {
        $GLOBALS['product'] = $product;
        get_template_part( 'template-parts/product-card' );
      }
    } else {
      echo '<p style="color:var(--grey);text-align:center;grid-column:1/-1;">No candles published yet — add one in WooCommerce &rarr; Products.</p>';
    }
    ?>
  </div>
</section>

<!-- HOW TO ORDER -->
<section class="how-order-section">
  <span class="section-label"><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_order_eyebrow' ) ); ?></span>
  <h2><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_order_heading' ) ); ?> <em><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_order_heading_em' ) ); ?></em></h2>
  <div class="how-order-steps">
    <div class="how-order-step">
      <div class="how-order-num">1</div>
      <h4><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_step1_title' ) ); ?></h4>
      <p><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_step1_desc' ) ); ?></p>
    </div>
    <div class="how-order-step">
      <div class="how-order-num">2</div>
      <h4><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_step2_title' ) ); ?></h4>
      <p><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_step2_desc' ) ); ?></p>
    </div>
    <div class="how-order-step">
      <div class="how-order-num">3</div>
      <h4><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_step3_title' ) ); ?></h4>
      <p><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_step3_desc' ) ); ?></p>
    </div>
    <div class="how-order-step">
      <div class="how-order-num">4</div>
      <h4><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_step4_title' ) ); ?></h4>
      <p><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_step4_desc' ) ); ?></p>
    </div>
  </div>
</section>

<!-- CANDLE CARE -->
<section class="how-order-section">
  <span class="section-label"><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_care_eyebrow' ) ); ?></span>
  <h2><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_care_heading' ) ); ?> <em><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_care_heading_em' ) ); ?></em></h2>
  <div style="max-width:700px;margin:0 auto;text-align:left;">
    <p style="color:var(--grey);margin-bottom:1.5rem;max-width:none;"><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_care_intro' ) ); ?></p>
    <ul style="color:var(--grey);margin:0 0 2rem;padding-left:1.25rem;line-height:1.9;">
      <?php foreach ( preg_split( '/\r\n|\r|\n/', trim( cbp_page_text( $shop_id, 'cbp_care_bullets' ) ) ) as $bullet ) : if ( '' === trim( $bullet ) ) continue; ?>
      <li><?php echo esc_html( $bullet ); ?></li>
      <?php endforeach; ?>
    </ul>

    <h3 style="font-family:'Cormorant Garamond',serif;font-weight:400;font-size:1.3rem;margin-bottom:.75rem;"><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_care_deco_heading' ) ); ?></h3>
    <p style="color:var(--grey);margin-bottom:2rem;max-width:none;"><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_care_deco_text' ) ); ?></p>

    <h3 style="font-family:'Cormorant Garamond',serif;font-weight:400;font-size:1.3rem;margin-bottom:.75rem;"><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_care_safety_heading' ) ); ?></h3>
    <p style="color:var(--grey);margin-bottom:2rem;max-width:none;"><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_care_safety_text' ) ); ?></p>

    <p style="color:var(--grey);font-size:.85rem;font-style:italic;max-width:none;"><?php echo esc_html( cbp_page_text( $shop_id, 'cbp_care_closing' ) ); ?></p>
  </div>
</section>

<?php get_footer( 'shop' ); ?>
