<?php
/**
 * Template Name: About Page
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>

<!-- PAGE HEADER -->
<div class="page-header motif-olive">
  <span class="watermark-text">Petra</span>
  <span class="section-label">My Story</span>
  <h1>About <em>Petra</em></h1>
  <p>From the Mediterranean coast to your home: a story of passion, craft, and scent.</p>
</div>

<!-- STORY SECTION -->
<section class="about-story">
  <div class="about-story-image">
    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/levander2.jpeg' ); ?>" alt="Petra crafting candles">
  </div>
  <div class="about-story-content">
    <span class="section-label">How it began</span>
    <h2>A passion born in Cyprus</h2>
    <p>I've always loved candles, and at the same time I was looking for a way to use my creativity and make something of my own. It was the combination of these two things that led me to candle making.</p>
    <p>What I love most is the whole process: starting with nothing but loose wax, a bottle of fragrance, and an idea in my head, and slowly shaping it with my own hands into a small piece of work. I love choosing the scents, colours, decorations, and all the small details that make every candle a little different.</p>
    <p>And it makes me even happier when my work finds a place in someone's home and brings them the same joy I feel while making it.</p>
  </div>
</section>

<!-- TESTIMONIAL -->
<section class="testimonial-section is-dark motif-olive">
  <p class="testimonial-quote">&ldquo;The candles from Candles by Petra? Even the craftsmanship alone amazed me. I bought several the very first time: a few for myself, since I love a nicely scented home, and the rest to share with family and friends. And the result? Everyone was thrilled. They're beautiful decorations, almost a shame to light them, and they really fill the room with scent, burn for a long time, and I never have to worry whether they're safe to have at home. For your own home? Absolutely. As a gift? Perfect.&rdquo;</p>
  <p class="testimonial-author">Sylvi, Czech expat in Cyprus</p>
</section>

<!-- VALUES -->
<section class="about-values">
  <span class="section-label">What I believe in</span>
  <h2>The principles behind every candle</h2>

  <div class="values-grid">
    <div class="value-item card">
      <div class="origin-dot"></div>
      <h3>Natural Ingredients</h3>
      <p>I use only soy wax and certified fragrances. Safety data sheets (SDS) are available on request.</p>
    </div>
    <div class="value-item card">
      <div class="origin-dot"></div>
      <h3>Small Batches</h3>
      <p>Every candle is an original, and I only ever make a small number of each.</p>
    </div>
    <div class="value-item card">
      <div class="origin-dot"></div>
      <h3>Slow &amp; Intentional</h3>
      <p>Every candle is carefully tested by me, because safe, clean burning is always my priority.</p>
    </div>
  </div>
</section>

<?php get_footer(); ?>
