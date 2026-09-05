<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<footer class="site-footer">
  <div class="footer-brand">
    <?php if ( has_custom_logo() ) : ?>
      <div class="footer-logo"><?php the_custom_logo(); ?></div>
    <?php else : ?>
      <a class="footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="Candles by Petra">
      </a>
    <?php endif; ?>
    <p><?php echo esc_html( get_theme_mod( 'cbp_footer_tagline', 'Handcrafted luxury candles made in Larnaca, Cyprus. Small batches, natural ingredients, pure intention.' ) ); ?></p>
  </div>
  <div class="footer-col">
    <h4><?php echo esc_html( get_theme_mod( 'cbp_footer_nav_heading', 'Navigate' ) ); ?></h4>
    <ul>
      <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
      <?php if ( function_exists( 'wc_get_page_id' ) ) : ?>
      <li><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">Shop</a></li>
      <?php endif; ?>
      <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
      <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
    </ul>
  </div>
  <div class="footer-col">
    <h4><?php echo esc_html( get_theme_mod( 'cbp_footer_contact_heading', 'Contact' ) ); ?></h4>
    <p><?php echo esc_html( get_theme_mod( 'cbp_footer_location', 'Cyprus, Larnaca' ) ); ?></p>
    <p><?php echo esc_html( get_theme_mod( 'cbp_footer_email', 'petra@candlesbyPetra.com' ) ); ?></p>
  </div>
</footer>
<div class="footer-bottom">
  <span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( get_theme_mod( 'cbp_footer_copyright', 'Candles by Petra. All rights reserved.' ) ); ?></span>
  <span><?php echo esc_html( get_theme_mod( 'cbp_footer_signoff', 'Made with love in Cyprus' ) ); ?></span>
</div>

<?php wp_footer(); ?>
</body>
</html>
