<?php
/**
 * Shop page — full override of WooCommerce's default archive template so it matches
 * the original design 1:1. Any product Petra publishes shows up here automatically
 * through template-parts/product-card.php.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header( 'shop' );
?>

<!-- PAGE HEADER -->
<div class="page-header motif-lavender">
  <span class="section-label">The Collection</span>
  <h1>Our <em>Candles</em></h1>
  <p>Each scent is a story. Find the one that speaks to you.</p>
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
  <span class="section-label">Simple process</span>
  <h2>How to <em>order</em></h2>
  <div class="how-order-steps">
    <div class="how-order-step">
      <div class="how-order-num">1</div>
      <h4>Add to cart</h4>
      <p>Browse the collection and add your favourite candles to the cart.</p>
    </div>
    <div class="how-order-step">
      <div class="how-order-num">2</div>
      <h4>Receive payment email</h4>
      <p>After placing your order you'll receive an email with bank transfer details, the total amount, and a unique reference number.</p>
    </div>
    <div class="how-order-step">
      <div class="how-order-num">3</div>
      <h4>Pay by bank transfer</h4>
      <p>Transfer the amount within 3 business days using the reference number so we can match your payment.</p>
    </div>
    <div class="how-order-step">
      <div class="how-order-num">4</div>
      <h4>Your package is on its way</h4>
      <p>Once payment is confirmed we prepare your candles and send a shipping confirmation email.</p>
    </div>
  </div>
</section>

<!-- CANDLE CARE -->
<section class="how-order-section">
  <span class="section-label">Please read before burning</span>
  <h2>Candle <em>care &amp; safety</em></h2>
  <div style="max-width:700px;margin:0 auto;text-align:left;">
    <p style="color:var(--grey);margin-bottom:1.5rem;max-width:none;">To make sure your candle burns beautifully and safely, please follow these guidelines:</p>
    <ul style="color:var(--grey);margin:0 0 2rem;padding-left:1.25rem;line-height:1.9;">
      <li>Trim the wick to 3 to 5 mm before every burn.</li>
      <li>On the first burn, let the wax melt evenly out to the edges of the container.</li>
      <li>Never burn the candle for more than 3 to 4 hours at a time.</li>
      <li>Always place it on a stable, heat-resistant surface.</li>
      <li>Keep the wax pool clean and free of wick trimmings, matches, or other debris.</li>
      <li>Stop using the candle once about 1 cm of wax remains at the bottom of the container.</li>
    </ul>

    <h3 style="font-family:'Cormorant Garamond',serif;font-weight:400;font-size:1.3rem;margin-bottom:.75rem;">Candles with wax decorations</h3>
    <p style="color:var(--grey);margin-bottom:2rem;max-width:none;">Candles with wax decorations may behave slightly differently from classic candles, especially during the first few burns. The decorations may be made from wax with a different hardness, so they can melt at a different rate. If a larger pool of melted wax forms, the flame may shrink. Safely extinguish the candle and let the wax cool and set completely before relighting. If the flame is instead too high, safely extinguish the candle, let it cool, trim the wick to 3 to 5 mm, and relight.</p>

    <h3 style="font-family:'Cormorant Garamond',serif;font-weight:400;font-size:1.3rem;margin-bottom:.75rem;">Important safety warning</h3>
    <p style="color:var(--grey);margin-bottom:2rem;max-width:none;">This product is not intended for consumption. Do not eat the wax or any decorative parts of the candle. Never leave a burning candle unattended. Keep it out of reach of children and pets, and a safe distance from flammable objects, curtains, drafts, and other heat sources. Do not move the candle while it is burning or still hot. The container can become very hot during use, so always let it cool completely before handling. Do not use the candle if the container is cracked, chipped, or otherwise damaged. Do not place any objects into the melted wax. Never use water to extinguish the candle.</p>

    <p style="color:var(--grey);font-size:.85rem;font-style:italic;max-width:none;">Every candle is handmade and carefully tested with an emphasis on safe burning. Due to the handmade nature of the candles and the use of wax decorations, the appearance and burning behaviour of individual candles may vary slightly. Always follow the guidelines above. The maker is not responsible for damage resulting from misuse, improper handling, or failure to follow the safety instructions.</p>
  </div>
</section>

<?php get_footer( 'shop' ); ?>
