<?php
/**
 * Fallback template (required by WordPress). The site uses front-page.php, page-about.php,
 * page-contact.php and the woocommerce/ overrides for everything real; this only catches
 * anything else (e.g. blog posts, if ever added).
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<div class="page-header">
  <h1><?php is_home() ? esc_html_e( 'Latest posts', 'candles-by-petra' ) : the_title(); ?></h1>
</div>
<div style="max-width:760px;margin:0 auto;padding:4rem 1.75rem;">
  <?php
  if ( have_posts() ) :
    while ( have_posts() ) : the_post();
      the_content();
    endwhile;
  else :
    echo '<p>Nothing here yet.</p>';
  endif;
  ?>
</div>
<?php get_footer(); ?>
