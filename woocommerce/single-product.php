<?php
/**
 * Single product page — full override so an individual candle's page matches the rest of
 * the site (fonts, colours, motifs) instead of WooCommerce's generic default template.
 * Everything shown here (name, price, description, photos, stock) still comes straight from
 * the normal Products screen in wp-admin — this file only changes how that same data is displayed.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

global $product;
while ( have_posts() ) : the_post();
$product = wc_get_product( get_the_ID() );
if ( ! $product ) continue;

$origin_label = get_post_meta( $product->get_id(), '_cbp_origin_label', true );
$scent_tag    = get_post_meta( $product->get_id(), '_cbp_scent_tag', true );
$gallery_ids  = $product->get_gallery_image_ids();
?>

<section class="product-detail">
  <div class="product-detail-gallery">
    <div class="product-detail-main-image" id="product-main-image">
      <?php echo $product->get_image( 'large' ); ?>
    </div>
    <?php if ( $gallery_ids ) : ?>
    <div class="product-detail-thumbs">
      <?php
      $main_thumb_html = wp_get_attachment_image( $product->get_image_id(), 'thumbnail' );
      echo '<button type="button" class="product-thumb active" data-full="' . esc_url( wp_get_attachment_image_url( $product->get_image_id(), 'large' ) ) . '">' . $main_thumb_html . '</button>';
      foreach ( $gallery_ids as $attachment_id ) {
        echo '<button type="button" class="product-thumb" data-full="' . esc_url( wp_get_attachment_image_url( $attachment_id, 'large' ) ) . '">' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '</button>';
      }
      ?>
    </div>
    <?php endif; ?>
  </div>

  <div class="product-detail-content">
    <a class="product-detail-back" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">&larr; Back to Shop</a>

    <?php if ( $origin_label ) : ?>
      <div class="origin-badge">
        <div class="origin-dot"></div>
        <span class="origin-label"><?php echo esc_html( $origin_label ); ?></span>
      </div>
    <?php endif; ?>

    <h1 class="product-detail-title"><?php the_title(); ?></h1>
    <?php if ( $scent_tag ) : ?><p class="product-detail-scent"><?php echo esc_html( $scent_tag ); ?></p><?php endif; ?>

    <p class="product-detail-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>

    <?php if ( $short_desc = $product->get_short_description() ) : ?>
      <div class="product-detail-excerpt"><?php echo wp_kses_post( wpautop( $short_desc ) ); ?></div>
    <?php endif; ?>

    <div class="product-detail-cart">
      <?php woocommerce_template_single_add_to_cart(); ?>
    </div>

    <?php if ( ! $product->is_in_stock() ) : ?>
      <p class="product-detail-stock out">Sold out for now</p>
    <?php else : ?>
      <p class="product-detail-stock">In stock &middot; ships within Cyprus</p>
    <?php endif; ?>
  </div>
</section>

<?php if ( $product->get_description() ) : ?>
<section class="product-detail-description">
  <span class="section-label">The Details</span>
  <h2>About this candle</h2>
  <div class="product-detail-description-body">
    <?php echo wp_kses_post( wpautop( $product->get_description() ) ); ?>
  </div>
</section>
<?php endif; ?>

<section class="product-detail-reviews">
  <span class="section-label">Customer Love</span>
  <?php comments_template(); ?>
</section>

<?php
$related_ids = wc_get_related_products( $product->get_id(), 3 );
if ( $related_ids ) : ?>
<section class="featured">
  <div class="featured-header">
    <span class="section-label">You May Also Like</span>
    <h2>More <em>Candles</em></h2>
  </div>
  <div class="products-grid">
    <?php foreach ( $related_ids as $related_id ) {
      $product = wc_get_product( $related_id );
      $GLOBALS['product'] = $product;
      get_template_part( 'template-parts/product-card' );
    } ?>
  </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<script>
(function () {
  // Fill this star and every star before it (WooCommerce's own JS only highlights the single clicked star).
  function paintStars(container, upToIndex, className) {
    container.querySelectorAll('a').forEach(function (a, i) {
      a.classList.toggle(className, i <= upToIndex);
    });
  }
  document.querySelectorAll('p.stars').forEach(function (container) {
    var links = container.querySelectorAll('a');
    links.forEach(function (a, i) {
      a.addEventListener('mouseenter', function () { paintStars(container, i, 'hover-fill'); });
      a.addEventListener('click', function () { paintStars(container, i, 'filled'); });
    });
    container.addEventListener('mouseleave', function () { paintStars(container, -1, 'hover-fill'); });
  });

  // Newer WooCommerce versions render a plain <select> instead of clickable star links.
  // Build our own 5-star widget on top of it so the rating field looks the same either way.
  var ratingSelect = document.querySelector('.comment-form-rating select#rating');
  if (ratingSelect) {
    var wrap = document.createElement('div');
    wrap.className = 'cbp-star-picker';
    for (var i = 1; i <= 5; i++) {
      var star = document.createElement('button');
      star.type = 'button';
      star.className = 'cbp-star';
      star.dataset.value = i;
      wrap.appendChild(star);
    }
    ratingSelect.insertAdjacentElement('afterend', wrap);
    ratingSelect.classList.add('cbp-visually-hidden');

    var stars = wrap.querySelectorAll('.cbp-star');
    function paintPicker(upToIndex, className) {
      stars.forEach(function (s, i) { s.classList.toggle(className, i <= upToIndex); });
    }
    stars.forEach(function (star, i) {
      star.addEventListener('mouseenter', function () { paintPicker(i, 'hover-fill'); });
      star.addEventListener('click', function () {
        ratingSelect.value = star.dataset.value;
        ratingSelect.dispatchEvent(new Event('change'));
        paintPicker(i, 'filled');
      });
    });
    wrap.addEventListener('mouseleave', function () { paintPicker(-1, 'hover-fill'); });
  }

  // Add +/- buttons around the quantity input (WooCommerce only outputs the plain number field).
  document.querySelectorAll('.product-detail-cart .quantity').forEach(function (qtyWrap) {
    var input = qtyWrap.querySelector('input.qty');
    if (!input || qtyWrap.dataset.cbpEnhanced) return;
    qtyWrap.dataset.cbpEnhanced = '1';

    var minus = document.createElement('button');
    minus.type = 'button';
    minus.className = 'cbp-qty-step cbp-qty-step-minus';
    minus.textContent = '−';

    var plus = document.createElement('button');
    plus.type = 'button';
    plus.className = 'cbp-qty-step cbp-qty-step-plus';
    plus.textContent = '+';

    qtyWrap.classList.add('cbp-qty-wrap');
    qtyWrap.insertBefore(minus, input);
    qtyWrap.appendChild(plus);

    function step(dir) {
      var min = parseInt(input.min, 10) || 1;
      var max = parseInt(input.max, 10) || Infinity;
      var val = (parseInt(input.value, 10) || min) + dir;
      input.value = Math.min(max, Math.max(min, val));
      input.dispatchEvent(new Event('change'));
    }
    minus.addEventListener('click', function () { step(-1); });
    plus.addEventListener('click', function () { step(1); });
  });

  document.querySelectorAll('.product-thumb').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var full = btn.getAttribute('data-full');
      var mainImg = document.querySelector('#product-main-image img');
      if (mainImg && full) mainImg.src = full;
      document.querySelectorAll('.product-thumb').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
    });
  });
})();
</script>

<?php get_footer(); ?>
