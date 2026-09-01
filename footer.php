<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<footer class="site-footer">
  <div class="footer-brand">
    <a class="footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="Candles by Petra">
    </a>
    <p>Handcrafted luxury candles made in Larnaca, Cyprus. Small batches, natural ingredients, pure intention.</p>
  </div>
  <div class="footer-col">
    <h4>Navigate</h4>
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
    <h4>Contact</h4>
    <p>Cyprus, Larnaca</p>
    <p>petra@candlesbyPetra.com</p>
  </div>
</footer>
<div class="footer-bottom">
  <span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Candles by Petra. All rights reserved.</span>
  <span>Made with love in Cyprus</span>
</div>

<?php wp_footer(); ?>
</body>
</html>
