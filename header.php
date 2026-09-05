<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- NAV -->
<nav>
  <?php if ( has_custom_logo() ) : ?>
    <div class="nav-logo"><?php the_custom_logo(); ?></div>
  <?php else : ?>
    <a class="nav-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="Candles by Petra">
    </a>
  <?php endif; ?>
  <?php
  wp_nav_menu( array(
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'nav-links',
    'fallback_cb'    => function () {
      echo '<ul class="nav-links">';
      echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>';
      echo '<li><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">Shop</a></li>';
      echo '<li><a href="' . esc_url( home_url( '/about/' ) ) . '">About</a></li>';
      echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '">Contact</a></li>';
      echo '</ul>';
    },
  ) );
  ?>
  <button class="nav-hamburger" onclick="toggleMobileMenu()" aria-label="Menu">
    <svg width="22" height="16" viewBox="0 0 22 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <line x1="0" y1="1" x2="22" y2="1"/><line x1="0" y1="8" x2="22" y2="8"/><line x1="0" y1="15" x2="22" y2="15"/>
    </svg>
  </button>
  <?php if ( class_exists( 'WooCommerce' ) ) : ?>
  <button class="cart-icon-btn" onclick="cbpOpenCart()">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
    </svg>
    <span class="cart-badge<?php echo WC()->cart->get_cart_contents_count() ? ' show' : ''; ?>"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
  </button>
  <?php endif; ?>
</nav>

<div id="mobile-menu" class="nav-mobile-menu">
  <button class="nav-mobile-close" onclick="toggleMobileMenu()">&times;</button>
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
  <?php if ( function_exists( 'wc_get_page_id' ) ) : ?>
  <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">Shop</a>
  <?php endif; ?>
  <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
  <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
</div>

<?php if ( class_exists( 'WooCommerce' ) ) : ?>
<!-- CART DRAWER (fed live by WooCommerce cart fragments — see assets/js/cart-drawer.js) -->
<div id="cart-overlay" onclick="cbpCloseCart()"></div>
<div id="cart-drawer">
  <div class="cart-header">
    <h3>Your Cart</h3>
    <button class="cart-header-close" onclick="cbpCloseCart()">&times;</button>
  </div>
  <div class="widget_shopping_cart_content">
    <?php woocommerce_mini_cart(); ?>
  </div>
</div>
<?php endif; ?>

<!-- LIGHTBOX (product photo zoom) -->
<div id="lightbox">
  <button id="lightbox-close" onclick="cbpCloseLightbox()">&times;</button>
  <img id="lightbox-img" src="" alt="">
</div>

<script>
  let cbpMobileMenuScrollY = 0;
  function toggleMobileMenu(){
    const menu = document.getElementById('mobile-menu');
    const isOpen = menu.classList.toggle('open');
    if (isOpen) {
      cbpMobileMenuScrollY = window.scrollY;
      document.body.style.position = 'fixed';
      document.body.style.top = -cbpMobileMenuScrollY + 'px';
      document.body.style.width = '100%';
    } else {
      document.body.style.position = '';
      document.body.style.top = '';
      document.body.style.width = '';
      window.scrollTo(0, cbpMobileMenuScrollY);
    }
  }
  function cbpOpenLightbox(img) {
    document.getElementById('lightbox-img').src = img.src;
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function cbpCloseLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
  }
  document.addEventListener('DOMContentLoaded', function () {
    const lb = document.getElementById('lightbox');
    lb.addEventListener('click', function (e) { if (e.target === this) cbpCloseLightbox(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') cbpCloseLightbox(); });

    (function scrollReveal() {
      const items = document.querySelectorAll('.reveal');
      items.forEach(function (el, i) { el.style.transitionDelay = (i % 3) * 0.08 + 's'; });
      const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); }
        });
      }, { threshold: 0.15 });
      items.forEach(function (el) { observer.observe(el); });
    })();
  });
</script>
