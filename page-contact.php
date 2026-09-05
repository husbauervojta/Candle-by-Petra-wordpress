<?php
/**
 * Template Name: Contact Page
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

$page_id = get_the_ID();
$sent    = isset( $_GET['contact'] ) ? sanitize_text_field( $_GET['contact'] ) : '';
?>

<section class="contact-section">

  <!-- LEFT: FORM -->
  <div class="contact-form-wrap">
    <h2><?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_form_title' ) ); ?><br><em><?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_form_title_em' ) ); ?></em></h2>

    <?php if ( 'sent' === $sent ) : ?>
      <p style="color:var(--gold);margin-bottom:1.5rem;">Thanks! Your message has been sent — Petra will reply within 24 hours.</p>
    <?php elseif ( 'error' === $sent ) : ?>
      <p style="color:#b3452c;margin-bottom:1.5rem;">Please fill in your name, a valid email, and a message.</p>
    <?php endif; ?>

    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
      <input type="hidden" name="action" value="cbp_contact_form">
      <div class="checkout-field">
        <label for="name">Your name</label>
        <input type="text" id="name" name="name" placeholder="Jane Smith" required>
      </div>
      <div class="checkout-field">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" placeholder="jane@example.com" required>
      </div>
      <div class="checkout-field">
        <label for="message">Your message</label>
        <textarea id="message" name="message" placeholder="Ask about scents, ingredients, custom orders, or anything else..." required></textarea>
      </div>
      <button type="submit" class="btn-pill btn-pill-dark">
        <?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_form_button' ) ); ?>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </form>
  </div>

  <!-- RIGHT: INFO -->
  <div class="contact-info motif-olive">
    <span class="section-label"><?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_eyebrow' ) ); ?></span>
    <h2><?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_title' ) ); ?><br><em><?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_title_em' ) ); ?></em></h2>
    <p><?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_intro' ) ); ?></p>

    <div class="contact-detail">
      <p class="contact-detail-label">Location</p>
      <p class="contact-detail-value"><?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_location' ) ); ?></p>
    </div>
    <div class="contact-detail">
      <p class="contact-detail-label">Email</p>
      <p class="contact-detail-value"><?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_email' ) ); ?></p>
    </div>
    <div class="contact-detail">
      <p class="contact-detail-label">Instagram</p>
      <p class="contact-detail-value"><?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_instagram' ) ); ?></p>
    </div>
    <div class="contact-detail">
      <p class="contact-detail-label">Delivery</p>
      <p class="contact-detail-value"><?php echo esc_html( cbp_page_text( $page_id, 'cbp_contact_delivery' ) ); ?></p>
    </div>
  </div>

</section>

<?php get_footer(); ?>
