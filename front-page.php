<?php
/**
 * Homepage.
 * Hero content comes from the Customizer (Appearance > Customize > Homepage Hero) so Petra
 * can swap the photo/text for a seasonal promo without touching a product or any code.
 * The "Our Candles" grid pulls whichever products are marked "Featured" in WooCommerce.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$hero_image    = get_theme_mod( 'cbp_hero_image', get_template_directory_uri() . '/assets/images/cherry-hero.jpg' );
$hero_badge    = get_theme_mod( 'cbp_hero_badge', 'Handcrafted · Larnaca, Cyprus' );
$hero_title    = get_theme_mod( 'cbp_hero_title', 'Light that feels like' );
$hero_title_em = get_theme_mod( 'cbp_hero_title_em', 'home' );
$hero_subtitle = get_theme_mod( 'cbp_hero_subtitle', 'Small-batch soy candles poured by hand, inspired by the coastline, mountains and citrus groves of Cyprus.' );
$hero_button   = get_theme_mod( 'cbp_hero_button_url', '' );
$hero_button_label  = get_theme_mod( 'cbp_hero_button_label', 'Shop the Collection' );
$hero_button2_label = get_theme_mod( 'cbp_hero_button2_label', 'Our Story' );
$shop_url      = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
if ( ! $hero_button ) $hero_button = $shop_url;

$home_id  = cbp_get_home_texts_page_id();
$about_id = cbp_get_about_page_id();
?>

<!-- HERO -->
<section class="hero">
  <div class="hero-content motif-lavender">
    <div class="origin-badge">
      <div class="origin-dot"></div>
      <span class="origin-label"><?php echo esc_html( $hero_badge ); ?></span>
    </div>
    <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?><br><em><?php echo esc_html( $hero_title_em ); ?></em></h1>
    <p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
    <div class="hero-actions">
      <a class="btn-pill btn-pill-dark" href="<?php echo esc_url( $hero_button ); ?>">
        <?php echo esc_html( $hero_button_label ); ?>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
      <a class="btn-pill btn-pill-ghost" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php echo esc_html( $hero_button2_label ); ?></a>
    </div>
  </div>
  <div class="hero-image">
    <img src="<?php echo esc_url( $hero_image ); ?>" alt="Candles by Petra">
  </div>
</section>

<!-- FEATURED -->
<section class="featured">
  <div class="featured-header">
    <span class="section-label"><?php echo esc_html( cbp_page_text( $home_id, 'cbp_featured_eyebrow' ) ); ?></span>
    <h2><?php echo esc_html( cbp_page_text( $home_id, 'cbp_featured_heading' ) ); ?> <em><?php echo esc_html( cbp_page_text( $home_id, 'cbp_featured_heading_em' ) ); ?></em></h2>
    <p><?php echo esc_html( cbp_page_text( $home_id, 'cbp_featured_intro' ) ); ?></p>
  </div>

  <div class="products-grid">
    <?php
    if ( class_exists( 'WooCommerce' ) ) {
      $featured = cbp_get_featured_products( 3 );
      if ( $featured ) {
        foreach ( $featured as $product ) {
          $GLOBALS['product'] = $product;
          get_template_part( 'template-parts/product-card' );
        }
      } else {
        echo '<p style="color:var(--grey);text-align:center;grid-column:1/-1;">Mark a product as "Featured" in WooCommerce to show it here.</p>';
      }
    }
    ?>
  </div>

  <div class="featured-cta">
    <a class="btn-pill btn-pill-dark" href="<?php echo esc_url( $shop_url ); ?>">
      <?php echo esc_html( cbp_page_text( $home_id, 'cbp_featured_button' ) ); ?>
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
  </div>
</section>

<!-- ABOUT TEASER -->
<section class="about-teaser">
  <div class="about-teaser-image">
    <img src="<?php echo esc_url( cbp_page_text( $home_id, 'cbp_teaser_photo' ) ); ?>" alt="A lit Candles by Petra candle surrounded by flowers">
  </div>
  <div class="about-teaser-content">
    <span class="section-label"><?php echo esc_html( cbp_page_text( $home_id, 'cbp_teaser_eyebrow' ) ); ?></span>
    <h2><?php echo esc_html( cbp_page_text( $home_id, 'cbp_teaser_heading' ) ); ?> <em><?php echo esc_html( cbp_page_text( $home_id, 'cbp_teaser_heading_em' ) ); ?></em></h2>
    <p><?php echo esc_html( cbp_page_text( $home_id, 'cbp_teaser_text' ) ); ?></p>
    <div>
      <a class="btn-pill btn-pill-ghost-light" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php echo esc_html( cbp_page_text( $home_id, 'cbp_teaser_button' ) ); ?></a>
    </div>
  </div>
</section>

<!-- TESTIMONIAL (same one edited on the About page, so Petra only updates it once) -->
<section class="testimonial-section motif-olive">
  <p class="testimonial-quote">&ldquo;<?php echo esc_html( cbp_page_text( $about_id, 'cbp_testimonial_quote' ) ); ?>&rdquo;</p>
  <p class="testimonial-author"><?php echo esc_html( cbp_page_text( $about_id, 'cbp_testimonial_author' ) ); ?></p>
</section>

<?php get_footer(); ?>
