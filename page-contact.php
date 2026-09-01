<?php
/**
 * Template Name: Contact Page
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<section class="contact-section">

  <!-- LEFT: FORM -->
  <div class="contact-form-wrap">
    <h2>Have a question?<br><em>Ask away</em></h2>

    <form action="mailto:petra@candlesbyPetra.com" method="post" enctype="text/plain">
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
        <textarea id="message" name="message" placeholder="Ask about scents, ingredients, custom orders, or anything else..."></textarea>
      </div>
      <button type="submit" class="btn-pill btn-pill-dark">
        Send Message
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </form>
  </div>

  <!-- RIGHT: INFO -->
  <div class="contact-info motif-olive">
    <span class="section-label">Get in touch</span>
    <h2>Let's talk<br><em>candles</em></h2>
    <p>Whether you'd like to ask about a scent, or simply say hello, I'd love to hear from you. I reply to all messages within 24 hours.</p>

    <div class="contact-detail">
      <p class="contact-detail-label">Location</p>
      <p class="contact-detail-value">Larnaca, Cyprus</p>
    </div>
    <div class="contact-detail">
      <p class="contact-detail-label">Email</p>
      <p class="contact-detail-value">petra@candlesbyPetra.com</p>
    </div>
    <div class="contact-detail">
      <p class="contact-detail-label">Instagram</p>
      <p class="contact-detail-value">@candlesbyPetra</p>
    </div>
    <div class="contact-detail">
      <p class="contact-detail-label">Delivery</p>
      <p class="contact-detail-value">Cyprus only &middot; Shipping &amp; handling: &euro;6</p>
    </div>
  </div>

</section>

<?php get_footer(); ?>
