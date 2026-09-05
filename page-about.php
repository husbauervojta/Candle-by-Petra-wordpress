<?php
/**
 * Template Name: About Page
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$page_id = get_the_ID();
$story_paragraphs = preg_split( '/\n\s*\n/', trim( cbp_page_text( $page_id, 'cbp_about_story' ) ) );
?>

<!-- PAGE HEADER -->
<div class="page-header motif-olive">
  <span class="watermark-text"><?php echo esc_html( cbp_page_text( $page_id, 'cbp_about_page_title_em' ) ); ?></span>
  <span class="section-label"><?php echo esc_html( cbp_page_text( $page_id, 'cbp_about_page_eyebrow' ) ); ?></span>
  <h1><?php echo esc_html( cbp_page_text( $page_id, 'cbp_about_page_title' ) ); ?> <em><?php echo esc_html( cbp_page_text( $page_id, 'cbp_about_page_title_em' ) ); ?></em></h1>
  <p><?php echo esc_html( cbp_page_text( $page_id, 'cbp_about_subtitle' ) ); ?></p>
</div>

<!-- STORY SECTION -->
<section class="about-story">
  <div class="about-story-image">
    <img src="<?php echo esc_url( cbp_page_text( $page_id, 'cbp_about_photo' ) ); ?>" alt="Petra crafting candles">
  </div>
  <div class="about-story-content">
    <span class="section-label"><?php echo esc_html( cbp_page_text( $page_id, 'cbp_about_eyebrow' ) ); ?></span>
    <h2><?php echo esc_html( cbp_page_text( $page_id, 'cbp_about_heading' ) ); ?></h2>
    <?php foreach ( $story_paragraphs as $paragraph ) : ?>
      <p><?php echo esc_html( $paragraph ); ?></p>
    <?php endforeach; ?>
  </div>
</section>

<!-- TESTIMONIAL -->
<section class="testimonial-section is-dark motif-olive">
  <p class="testimonial-quote">&ldquo;<?php echo esc_html( cbp_page_text( $page_id, 'cbp_testimonial_quote' ) ); ?>&rdquo;</p>
  <p class="testimonial-author"><?php echo esc_html( cbp_page_text( $page_id, 'cbp_testimonial_author' ) ); ?></p>
</section>

<!-- VALUES -->
<section class="about-values">
  <span class="section-label"><?php echo esc_html( cbp_page_text( $page_id, 'cbp_values_eyebrow' ) ); ?></span>
  <h2><?php echo esc_html( cbp_page_text( $page_id, 'cbp_values_heading' ) ); ?></h2>

  <div class="values-grid">
    <div class="value-item card">
      <div class="origin-dot"></div>
      <h3><?php echo esc_html( cbp_page_text( $page_id, 'cbp_value1_title' ) ); ?></h3>
      <p><?php echo esc_html( cbp_page_text( $page_id, 'cbp_value1_desc' ) ); ?></p>
    </div>
    <div class="value-item card">
      <div class="origin-dot"></div>
      <h3><?php echo esc_html( cbp_page_text( $page_id, 'cbp_value2_title' ) ); ?></h3>
      <p><?php echo esc_html( cbp_page_text( $page_id, 'cbp_value2_desc' ) ); ?></p>
    </div>
    <div class="value-item card">
      <div class="origin-dot"></div>
      <h3><?php echo esc_html( cbp_page_text( $page_id, 'cbp_value3_title' ) ); ?></h3>
      <p><?php echo esc_html( cbp_page_text( $page_id, 'cbp_value3_desc' ) ); ?></p>
    </div>
  </div>
</section>

<?php get_footer(); ?>
