<?php
/**
 * Candles by Petra theme setup.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ── THEME SETUP ── */
function cbp_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'custom-logo', array(
		'height'      => 90,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'candles-by-petra' ),
	) );
}
add_action( 'after_setup_theme', 'cbp_setup' );

/* ── ASSETS ── */
function cbp_enqueue_assets() {
	wp_enqueue_style( 'cbp-style', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
	wp_enqueue_script( 'cbp-cart-drawer', get_template_directory_uri() . '/assets/js/cart-drawer.js', array( 'jquery', 'wc-cart-fragments' ), filemtime( get_template_directory() . '/assets/js/cart-drawer.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'cbp_enqueue_assets' );

/* Every page needs the WooCommerce AJAX add-to-cart + fragments scripts, not just the Shop page,
 * because "Add to Cart" buttons also appear on the homepage featured-products grid. */
function cbp_ensure_cart_fragments() {
	if ( ! is_admin() && class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'wc-add-to-cart' );
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}
add_action( 'wp_enqueue_scripts', 'cbp_ensure_cart_fragments' );

/* Keep the little number badge on the cart icon live-updated after AJAX add-to-cart, same
 * mechanism WooCommerce uses for the mini-cart content itself. */
function cbp_cart_count_fragment( $fragments ) {
	$count = WC()->cart->get_cart_contents_count();
	ob_start();
	?>
	<span class="cart-badge<?php echo $count ? ' show' : ''; ?>"><?php echo esc_html( $count ); ?></span>
	<?php
	$fragments['span.cart-badge'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'cbp_cart_count_fragment' );

/* ── WOOCOMMERCE TWEAKS ── */
// We build our own cart drawer / product grid markup, so drop WooCommerce's default stylesheet.
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Shop page: 2 columns of related "Featured" products isn't used; we control the grid ourselves in archive-product.php.
add_filter( 'loop_shop_columns', function () { return 3; } );

/* ── CUSTOM PRODUCT FIELDS: Origin badge + Scent tag ──
 * These show up as plain text boxes on the Product edit screen (Product data > General tab),
 * no extra plugin needed. Petra fills them in exactly like price/description.
 */
function cbp_add_product_fields() {
	global $woocommerce, $post;
	echo '<div class="options_group">';

	woocommerce_wp_text_input( array(
		'id'          => '_cbp_origin_label',
		'label'       => 'Text v odznaku (origin badge)',
		'placeholder' => 'např. Lavender fields · Platres',
		'desc_tip'    => true,
		'description' => 'Zobrazí se jako malá pilulka nad názvem produktu.',
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_cbp_scent_tag',
		'label'       => 'Vůně / materiál (popisek)',
		'placeholder' => 'např. Handmade Floral Candle',
		'desc_tip'    => true,
		'description' => 'Zobrazí se pod názvem produktu, nad cenou.',
	) );

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'cbp_add_product_fields' );

function cbp_save_product_fields( $post_id ) {
	if ( isset( $_POST['_cbp_origin_label'] ) ) {
		update_post_meta( $post_id, '_cbp_origin_label', sanitize_text_field( $_POST['_cbp_origin_label'] ) );
	}
	if ( isset( $_POST['_cbp_scent_tag'] ) ) {
		update_post_meta( $post_id, '_cbp_scent_tag', sanitize_text_field( $_POST['_cbp_scent_tag'] ) );
	}
}
add_action( 'woocommerce_process_product_meta', 'cbp_save_product_fields' );

/* ── HOMEPAGE HERO: editable via Customizer (photo + text), independent of any product ──
 * Petra edits this under Appearance > Customize > Homepage Hero. No code, no product tie-in,
 * so she can promote a seasonal candle (or anything else) without it having to be a real product photo.
 */
function cbp_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'cbp_hero_section', array(
		'title'    => 'Hlavní fotka na homepage (Hero)',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'cbp_hero_image', array( 'default' => get_template_directory_uri() . '/assets/images/cherry-hero.jpg' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'cbp_hero_image', array(
		'label'   => 'Hlavní fotka',
		'section' => 'cbp_hero_section',
	) ) );

	$wp_customize->add_setting( 'cbp_hero_badge', array( 'default' => 'Handcrafted · Larnaca, Cyprus' ) );
	$wp_customize->add_control( 'cbp_hero_badge', array(
		'label' => 'Malý text v odznaku', 'section' => 'cbp_hero_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_hero_title', array( 'default' => 'Light that feels like' ) );
	$wp_customize->add_control( 'cbp_hero_title', array(
		'label' => 'Nadpis (běžná část)', 'section' => 'cbp_hero_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_hero_title_em', array( 'default' => 'home' ) );
	$wp_customize->add_control( 'cbp_hero_title_em', array(
		'label' => 'Nadpis (zlaté kurzívní slovo)', 'section' => 'cbp_hero_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_hero_subtitle', array( 'default' => 'Small-batch soy candles poured by hand, inspired by the coastline, mountains and citrus groves of Cyprus.' ) );
	$wp_customize->add_control( 'cbp_hero_subtitle', array(
		'label' => 'Podnadpis', 'section' => 'cbp_hero_section', 'type' => 'textarea',
	) );

	$wp_customize->add_setting( 'cbp_hero_button_url', array( 'default' => '' ) );
	$wp_customize->add_control( 'cbp_hero_button_url', array(
		'label'       => 'Odkaz tlačítka "Shop the Collection"',
		'description' => 'Nech prázdné pro odkaz na hlavní stránku obchodu. Nebo vlož adresu konkrétního produktu pro sezónní svíčku.',
		'section'     => 'cbp_hero_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_hero_button_label', array( 'default' => 'Shop the Collection' ) );
	$wp_customize->add_control( 'cbp_hero_button_label', array(
		'label' => 'Text tlačítka (velké, tmavé)', 'section' => 'cbp_hero_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_hero_button2_label', array( 'default' => 'Our Story' ) );
	$wp_customize->add_control( 'cbp_hero_button2_label', array(
		'label' => 'Text tlačítka (obrys, vede na About)', 'section' => 'cbp_hero_section', 'type' => 'text',
	) );

	/* ── Patička webu (celý web, nezávisle na jednotlivých stránkách) ── */
	$wp_customize->add_section( 'cbp_footer_section', array(
		'title'    => 'Patička webu',
		'priority' => 31,
	) );

	$wp_customize->add_setting( 'cbp_footer_tagline', array( 'default' => 'Handcrafted luxury candles made in Larnaca, Cyprus. Small batches, natural ingredients, pure intention.' ) );
	$wp_customize->add_control( 'cbp_footer_tagline', array(
		'label' => 'Text pod logem', 'section' => 'cbp_footer_section', 'type' => 'textarea',
	) );

	$wp_customize->add_setting( 'cbp_footer_location', array( 'default' => 'Cyprus, Larnaca' ) );
	$wp_customize->add_control( 'cbp_footer_location', array(
		'label' => 'Lokalita', 'section' => 'cbp_footer_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_footer_email', array( 'default' => 'petra@candlesbyPetra.com' ) );
	$wp_customize->add_control( 'cbp_footer_email', array(
		'label' => 'E-mail', 'section' => 'cbp_footer_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_footer_nav_heading', array( 'default' => 'Navigate' ) );
	$wp_customize->add_control( 'cbp_footer_nav_heading', array(
		'label' => 'Nadpis sloupce "Navigate"', 'section' => 'cbp_footer_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_footer_contact_heading', array( 'default' => 'Contact' ) );
	$wp_customize->add_control( 'cbp_footer_contact_heading', array(
		'label' => 'Nadpis sloupce "Contact"', 'section' => 'cbp_footer_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_footer_copyright', array( 'default' => 'Candles by Petra. All rights reserved.' ) );
	$wp_customize->add_control( 'cbp_footer_copyright', array(
		'label'       => 'Text vedle roku (© 2026 ...)',
		'description' => 'Rok se doplňuje automaticky, piš jen text za ním.',
		'section'     => 'cbp_footer_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_footer_signoff', array( 'default' => 'Made with love in Cyprus' ) );
	$wp_customize->add_control( 'cbp_footer_signoff', array(
		'label' => 'Text vpravo dole', 'section' => 'cbp_footer_section', 'type' => 'text',
	) );
}
add_action( 'customize_register', 'cbp_customize_register' );

/* ── Mark WooCommerce products "featured" to show them in the homepage grid.
 * Petra ticks "Featured product" (built into WooCommerce, Product data > Advanced or the Publish box)
 * on any product and it appears on the homepage automatically — no code changes needed per candle.
 */
function cbp_get_featured_products( $limit = 2 ) {
	return wc_get_products( array(
		'featured' => true,
		'limit'    => $limit,
		'status'   => 'publish',
	) );
}

/* ── Live quantity +/- in the cart drawer (mirrors the old cart.js UX) ──
 * WooCommerce's mini-cart has no built-in AJAX qty stepper (only a page-reload "Update cart"
 * on the full cart page), so we add a small endpoint and reuse WC_AJAX's fragment response —
 * the same JSON shape wc-cart-fragments.js already knows how to apply.
 */
function cbp_update_cart_qty() {
	if ( ! isset( $_POST['key'], $_POST['qty'] ) ) wp_die();
	$key = sanitize_text_field( wp_unslash( $_POST['key'] ) );
	$qty = max( 0, intval( $_POST['qty'] ) );

	if ( $qty === 0 ) {
		WC()->cart->remove_cart_item( $key );
	} else {
		WC()->cart->set_quantity( $key, $qty );
	}
	WC_AJAX::get_refreshed_fragments();
}
add_action( 'wp_ajax_cbp_update_cart_qty', 'cbp_update_cart_qty' );
add_action( 'wp_ajax_nopriv_cbp_update_cart_qty', 'cbp_update_cart_qty' );

/* Lets the gift-wrap box be toggled straight from the cart drawer too, not just the product page
 * (needed for candles added via the quick "Add to Cart" button on the Home/Shop grid). */
function cbp_toggle_gift_wrap() {
	if ( ! isset( $_POST['key'] ) ) wp_die();
	$key     = sanitize_text_field( wp_unslash( $_POST['key'] ) );
	$checked = ! empty( $_POST['checked'] );

	if ( isset( WC()->cart->cart_contents[ $key ] ) ) {
		WC()->cart->cart_contents[ $key ]['cbp_gift_wrap'] = $checked;
		WC()->cart->calculate_totals();
	}
	WC_AJAX::get_refreshed_fragments();
}
add_action( 'wp_ajax_cbp_toggle_gift_wrap', 'cbp_toggle_gift_wrap' );
add_action( 'wp_ajax_nopriv_cbp_toggle_gift_wrap', 'cbp_toggle_gift_wrap' );

/* ── Bank-transfer order email: put the order number in the subject line
 * and repeat it clearly right above the order summary, so it's impossible
 * to miss when the customer is looking for their payment reference. ── */
function cbp_bacs_email_subject( $subject, $order ) {
	if ( ! $order ) return $subject;
	return sprintf( 'Your Candles by Petra order #%s — payment details', $order->get_order_number() );
}
add_filter( 'woocommerce_email_subject_customer_on_hold_order', 'cbp_bacs_email_subject', 10, 2 );

function cbp_bacs_email_order_number_callout( $order, $sent_to_admin, $plain_text, $email ) {
	if ( $sent_to_admin ) return;
	if ( ! $email || 'customer_on_hold_order' !== $email->id ) return;
	if ( 'bacs' !== $order->get_payment_method() ) return;

	if ( $plain_text ) {
		echo "\nYOUR ORDER NUMBER: #" . $order->get_order_number() . "\nPlease use this as the payment reference for your bank transfer.\n\n";
	} else {
		echo '<p style="font-size:1.1em;"><strong>Your order number: #' . esc_html( $order->get_order_number() ) . '</strong><br>Please use this as the payment reference for your bank transfer.</p>';
	}
}
add_action( 'woocommerce_email_before_order_table', 'cbp_bacs_email_order_number_callout', 5, 4 );

/* Note: the order items + total (order summary) are already included automatically
 * in every WooCommerce order email — no extra code needed, that's core behaviour. */

/* ── Add "Payment reference" (the order number) as its own row inside the bank
 * details box itself — shown both on the order-received page and in the BACS
 * email, since WooCommerce renders that box from the same place in both. */
function cbp_bacs_add_reference_field( $fields, $order_id ) {
	$order = wc_get_order( $order_id );
	if ( $order ) {
		$fields['payment_reference'] = array(
			'label' => 'Payment reference',
			'value' => '#' . $order->get_order_number(),
		);
	}
	return $fields;
}
add_filter( 'woocommerce_bacs_account_fields', 'cbp_bacs_add_reference_field', 10, 2 );

/* ── "Return to Homepage" button at the very end of the order-received page ── */
function cbp_thankyou_home_button() {
	echo '<div style="text-align:center;margin-top:2rem;">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="btn-pill btn-pill-dark">Return to Homepage</a>';
	echo '</div>';
}
add_action( 'woocommerce_thankyou', 'cbp_thankyou_home_button', 100 );

/* ── "Payment received" email (order status: Processing) ──
 * This is the second email in the flow: order placed -> On-hold (payment instructions)
 * -> Petra sees the bank transfer arrive and marks the order "Processing" in wp-admin
 * -> WooCommerce automatically sends this email, with the order recap included by default.
 */
function cbp_processing_email_subject( $subject, $order ) {
	if ( ! $order ) return $subject;
	return sprintf( 'Payment received — your Candles by Petra order #%s', $order->get_order_number() );
}
add_filter( 'woocommerce_email_subject_customer_processing_order', 'cbp_processing_email_subject', 10, 2 );

function cbp_processing_email_intro( $order, $sent_to_admin, $plain_text, $email ) {
	if ( $sent_to_admin ) return;
	if ( ! $email || 'customer_processing_order' !== $email->id ) return;

	if ( $plain_text ) {
		echo "\nGood news, we've received your payment! Your candles are being prepared. Here is your order recap:\n\n";
	} else {
		echo '<p>Good news, we&rsquo;ve received your payment! Your candles are being prepared. Here is your order recap:</p>';
	}
}
add_action( 'woocommerce_email_before_order_table', 'cbp_processing_email_intro', 5, 4 );

/* ── Editable page text (About / Contact) ──
 * These pages use fixed PHP templates for layout, but every piece of copy on them is
 * stored as a normal custom field on the page — visible in a "Page Text" box on the
 * Page edit screen — so Petra can update wording herself without touching any code.
 */
function cbp_page_text_fields( $template ) {
	if ( 'page-about.php' === $template ) {
		return array(
			'cbp_about_page_eyebrow'=> array( 'label' => 'Hlavička stránky — malý nadpis', 'type' => 'text', 'default' => 'My Story' ),
			'cbp_about_page_title'  => array( 'label' => 'Hlavička stránky — titulek (běžná část)', 'type' => 'text', 'default' => 'About' ),
			'cbp_about_page_title_em'=> array( 'label' => 'Hlavička stránky — titulek (zlatá kurzíva)', 'type' => 'text', 'default' => 'Petra' ),
			'cbp_about_subtitle'    => array( 'label' => 'Podnadpis v hlavičce stránky', 'type' => 'textarea', 'default' => 'From the Mediterranean coast to your home: a story of passion, craft, and scent.' ),
			'cbp_about_photo'       => array( 'label' => 'Fotka u příběhu', 'type' => 'image', 'default' => get_template_directory_uri() . '/assets/images/levander2.jpeg' ),
			'cbp_about_eyebrow'     => array( 'label' => 'Příběh — malý nadpis nad titulkem', 'type' => 'text', 'default' => 'How it began' ),
			'cbp_about_heading'     => array( 'label' => 'Příběh — titulek', 'type' => 'text', 'default' => 'A passion born in Cyprus' ),
			'cbp_about_story'       => array( 'label' => 'Příběh (jeden nebo více odstavců — mezi odstavci nech prázdný řádek)', 'type' => 'textarea', 'default' => "I've always loved candles, and at the same time I was looking for a way to use my creativity and make something of my own. It was the combination of these two things that led me to candle making.\n\nWhat I love most is the whole process: starting with nothing but loose wax, a bottle of fragrance, and an idea in my head, and slowly shaping it with my own hands into a small piece of work. I love choosing the scents, colours, decorations, and all the small details that make every candle a little different.\n\nAnd it makes me even happier when my work finds a place in someone's home and brings them the same joy I feel while making it." ),
			'cbp_testimonial_quote' => array( 'label' => 'Recenze — text', 'type' => 'textarea', 'default' => "The candles from Candles by Petra? Even the craftsmanship alone amazed me. I bought several the very first time: a few for myself, since I love a nicely scented home, and the rest to share with family and friends. And the result? Everyone was thrilled. They're beautiful decorations, almost a shame to light them, and they really fill the room with scent, burn for a long time, and I never have to worry whether they're safe to have at home. For your own home? Absolutely. As a gift? Perfect." ),
			'cbp_testimonial_author'=> array( 'label' => 'Recenze — podpis autora', 'type' => 'text', 'default' => 'Sylvi, Czech expat in Cyprus' ),
			'cbp_values_eyebrow'    => array( 'label' => 'Principy — malý nadpis nad titulkem', 'type' => 'text', 'default' => 'What I believe in' ),
			'cbp_values_heading'    => array( 'label' => 'Principy — titulek', 'type' => 'text', 'default' => 'The principles behind every candle' ),
			'cbp_value1_title'      => array( 'label' => 'Princip 1 — nadpis', 'type' => 'text', 'default' => 'Natural Ingredients' ),
			'cbp_value1_desc'       => array( 'label' => 'Princip 1 — popis', 'type' => 'textarea', 'default' => 'I use only soy wax and certified fragrances. Safety data sheets (SDS) are available on request.' ),
			'cbp_value2_title'      => array( 'label' => 'Princip 2 — nadpis', 'type' => 'text', 'default' => 'Small Batches' ),
			'cbp_value2_desc'       => array( 'label' => 'Princip 2 — popis', 'type' => 'textarea', 'default' => 'Every candle is an original, and I only ever make a small number of each.' ),
			'cbp_value3_title'      => array( 'label' => 'Princip 3 — nadpis', 'type' => 'text', 'default' => 'Slow & Intentional' ),
			'cbp_value3_desc'       => array( 'label' => 'Princip 3 — popis', 'type' => 'textarea', 'default' => 'Every candle is carefully tested by me, because safe, clean burning is always my priority.' ),
		);
	}
	if ( 'page-contact.php' === $template ) {
		return array(
			'cbp_contact_form_title'   => array( 'label' => 'Formulář — nadpis (běžná část)', 'type' => 'text', 'default' => 'Have a question?' ),
			'cbp_contact_form_title_em'=> array( 'label' => 'Formulář — nadpis (kurzíva)', 'type' => 'text', 'default' => 'Ask away' ),
			'cbp_contact_form_button'  => array( 'label' => 'Formulář — text tlačítka', 'type' => 'text', 'default' => 'Send Message' ),
			'cbp_contact_eyebrow'      => array( 'label' => 'Kontaktní panel — malý nadpis', 'type' => 'text', 'default' => 'Get in touch' ),
			'cbp_contact_title'        => array( 'label' => 'Kontaktní panel — titulek (běžná část)', 'type' => 'text', 'default' => "Let's talk" ),
			'cbp_contact_title_em'     => array( 'label' => 'Kontaktní panel — titulek (kurzíva)', 'type' => 'text', 'default' => 'candles' ),
			'cbp_contact_intro'        => array( 'label' => 'Úvodní text', 'type' => 'textarea', 'default' => "Whether you'd like to ask about a scent, or simply say hello, I'd love to hear from you. I reply to all messages within 24 hours." ),
			'cbp_contact_location'     => array( 'label' => 'Lokalita', 'type' => 'text', 'default' => 'Larnaca, Cyprus' ),
			'cbp_contact_email'        => array( 'label' => 'E-mail (zobrazený návštěvníkům)', 'type' => 'text', 'default' => 'petra@candlesbyPetra.com' ),
			'cbp_contact_instagram'    => array( 'label' => 'Instagram (název účtu)', 'type' => 'text', 'default' => '@candlesbyPetra' ),
			'cbp_contact_delivery'     => array( 'label' => 'Poznámka k doručení', 'type' => 'text', 'default' => 'Cyprus only · Shipping & handling: €6' ),
		);
	}
	if ( 'virtual-home-texts' === $template ) {
		return array(
			'cbp_featured_eyebrow'     => array( 'label' => 'Nabídka svíček — malý nadpis', 'type' => 'text', 'default' => 'The Collection' ),
			'cbp_featured_heading'     => array( 'label' => 'Nabídka svíček — titulek (běžná část)', 'type' => 'text', 'default' => 'Our' ),
			'cbp_featured_heading_em'  => array( 'label' => 'Nabídka svíček — titulek (kurzíva)', 'type' => 'text', 'default' => 'Candles' ),
			'cbp_featured_intro'       => array( 'label' => 'Nabídka svíček — podnadpis', 'type' => 'textarea', 'default' => 'Each scent is a story. Find the one that speaks to you.' ),
			'cbp_featured_button'      => array( 'label' => 'Nabídka svíček — text tlačítka', 'type' => 'text', 'default' => 'View Full Shop' ),
			'cbp_teaser_photo'         => array( 'label' => 'Sekce o Petře — fotka', 'type' => 'image', 'default' => get_template_directory_uri() . '/assets/images/lavender-cozy.jpg' ),
			'cbp_teaser_eyebrow'       => array( 'label' => 'Sekce o Petře — malý nadpis', 'type' => 'text', 'default' => 'My Story' ),
			'cbp_teaser_heading'       => array( 'label' => 'Sekce o Petře — titulek (běžná část)', 'type' => 'text', 'default' => 'Made by hand in' ),
			'cbp_teaser_heading_em'    => array( 'label' => 'Sekce o Petře — titulek (kurzíva)', 'type' => 'text', 'default' => 'Larnaca' ),
			'cbp_teaser_text'          => array( 'label' => 'Sekce o Petře — text', 'type' => 'textarea', 'default' => 'Every candle is poured in small batches using natural soy wax and premium fragrance oils, with the same care as the very first one I ever made.' ),
			'cbp_teaser_button'        => array( 'label' => 'Sekce o Petře — text tlačítka', 'type' => 'text', 'default' => 'Read My Story' ),
		);
	}
	return array();
}

/* ── Shop page (WooCommerce) — nemá vlastní PHP šablonu jako About/Contact, je to
 * speciální WooCommerce stránka, tak se pozná podle ID, ne podle _wp_page_template. */
function cbp_shop_text_fields() {
	return array(
		'cbp_collection_eyebrow' => array( 'label' => 'Hlavička obchodu — malý nadpis', 'type' => 'text', 'default' => 'The Collection' ),
		'cbp_collection_heading' => array( 'label' => 'Hlavička obchodu — titulek (běžná část)', 'type' => 'text', 'default' => 'Our' ),
		'cbp_collection_heading_em'=> array( 'label' => 'Hlavička obchodu — titulek (kurzíva)', 'type' => 'text', 'default' => 'Candles' ),
		'cbp_shop_subtitle'  => array( 'label' => 'Podnadpis v hlavičce obchodu', 'type' => 'textarea', 'default' => 'Each scent is a story. Find the one that speaks to you.' ),
		'cbp_order_eyebrow'  => array( 'label' => '"Jak nakoupit" — malý nadpis', 'type' => 'text', 'default' => 'Simple process' ),
		'cbp_order_heading'  => array( 'label' => '"Jak nakoupit" — titulek (běžná část)', 'type' => 'text', 'default' => 'How to' ),
		'cbp_order_heading_em'=> array( 'label' => '"Jak nakoupit" — titulek (kurzíva)', 'type' => 'text', 'default' => 'order' ),
		'cbp_step1_title'    => array( 'label' => 'Krok 1 — nadpis', 'type' => 'text', 'default' => 'Add to cart' ),
		'cbp_step1_desc'     => array( 'label' => 'Krok 1 — popis', 'type' => 'textarea', 'default' => 'Browse the collection and add your favourite candles to the cart.' ),
		'cbp_step2_title'    => array( 'label' => 'Krok 2 — nadpis', 'type' => 'text', 'default' => 'Receive payment email' ),
		'cbp_step2_desc'     => array( 'label' => 'Krok 2 — popis', 'type' => 'textarea', 'default' => "After placing your order you'll receive an email with bank transfer details, the total amount, and a unique reference number." ),
		'cbp_step3_title'    => array( 'label' => 'Krok 3 — nadpis', 'type' => 'text', 'default' => 'Pay by bank transfer' ),
		'cbp_step3_desc'     => array( 'label' => 'Krok 3 — popis', 'type' => 'textarea', 'default' => 'Transfer the amount within 3 business days using the reference number so we can match your payment.' ),
		'cbp_step4_title'    => array( 'label' => 'Krok 4 — nadpis', 'type' => 'text', 'default' => 'Your package is on its way' ),
		'cbp_step4_desc'     => array( 'label' => 'Krok 4 — popis', 'type' => 'textarea', 'default' => 'Once payment is confirmed we prepare your candles and send a shipping confirmation email.' ),
		'cbp_care_eyebrow'   => array( 'label' => 'Péče o svíčku — malý nadpis', 'type' => 'text', 'default' => 'Please read before burning' ),
		'cbp_care_heading'   => array( 'label' => 'Péče o svíčku — titulek (běžná část)', 'type' => 'text', 'default' => 'Candle' ),
		'cbp_care_heading_em'=> array( 'label' => 'Péče o svíčku — titulek (kurzíva)', 'type' => 'text', 'default' => 'care & safety' ),
		'cbp_care_intro'     => array( 'label' => 'Péče o svíčku — úvod', 'type' => 'textarea', 'default' => 'To make sure your candle burns beautifully and safely, please follow these guidelines:' ),
		'cbp_care_bullets'   => array( 'label' => 'Péče o svíčku — seznam pravidel (jedno na řádek)', 'type' => 'textarea', 'default' => "Trim the wick to 3 to 5 mm before every burn.\nOn the first burn, let the wax melt evenly out to the edges of the container.\nNever burn the candle for more than 3 to 4 hours at a time.\nAlways place it on a stable, heat-resistant surface.\nKeep the wax pool clean and free of wick trimmings, matches, or other debris.\nStop using the candle once about 1 cm of wax remains at the bottom of the container." ),
		'cbp_care_deco_heading' => array( 'label' => 'Péče o svíčku — nadpis "S voskovými dekoracemi"', 'type' => 'text', 'default' => 'Candles with wax decorations' ),
		'cbp_care_deco_text' => array( 'label' => 'Péče o svíčku — text o voskových dekoracích', 'type' => 'textarea', 'default' => 'Candles with wax decorations may behave slightly differently from classic candles, especially during the first few burns. The decorations may be made from wax with a different hardness, so they can melt at a different rate. If a larger pool of melted wax forms, the flame may shrink. Safely extinguish the candle and let the wax cool and set completely before relighting. If the flame is instead too high, safely extinguish the candle, let it cool, trim the wick to 3 to 5 mm, and relight.' ),
		'cbp_care_safety_heading' => array( 'label' => 'Péče o svíčku — nadpis "Bezpečnostní upozornění"', 'type' => 'text', 'default' => 'Important safety warning' ),
		'cbp_care_safety_text' => array( 'label' => 'Péče o svíčku — bezpečnostní text', 'type' => 'textarea', 'default' => 'This product is not intended for consumption. Do not eat the wax or any decorative parts of the candle. Never leave a burning candle unattended. Keep it out of reach of children and pets, and a safe distance from flammable objects, curtains, drafts, and other heat sources. Do not move the candle while it is burning or still hot. The container can become very hot during use, so always let it cool completely before handling. Do not use the candle if the container is cracked, chipped, or otherwise damaged. Do not place any objects into the melted wax. Never use water to extinguish the candle.' ),
		'cbp_care_closing'   => array( 'label' => 'Péče o svíčku — závěrečná poznámka (kurzívou)', 'type' => 'textarea', 'default' => 'Every candle is handmade and carefully tested with an emphasis on safe burning. Due to the handmade nature of the candles and the use of wax decorations, the appearance and burning behaviour of individual candles may vary slightly. Always follow the guidelines above. The maker is not responsible for damage resulting from misuse, improper handling, or failure to follow the safety instructions.' ),
	);
}

/** Works out which set of editable fields (if any) apply to a given page,
 * whether it's a normal template page (About/Contact) or the special
 * WooCommerce Shop page (which has no _wp_page_template of its own). */
function cbp_fields_for_post( $post_id ) {
	if ( function_exists( 'wc_get_page_id' ) && (int) $post_id === (int) wc_get_page_id( 'shop' ) ) {
		return cbp_shop_text_fields();
	}
	return cbp_page_text_fields( get_post_meta( $post_id, '_wp_page_template', true ) );
}

function cbp_page_text_meta_box() {
	global $post;
	if ( ! $post ) return;
	$fields = cbp_fields_for_post( $post->ID );
	if ( empty( $fields ) ) return;

	add_meta_box( 'cbp_page_text', 'Texty stránky', function () use ( $post, $fields ) {
		wp_nonce_field( 'cbp_page_text_save', 'cbp_page_text_nonce' );
		foreach ( $fields as $key => $field ) {
			$value = get_post_meta( $post->ID, $key, true );
			if ( '' === $value ) $value = $field['default'];
			echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><br>';
			if ( 'textarea' === $field['type'] ) {
				echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="4" style="width:100%;">' . esc_textarea( $value ) . '</textarea>';
			} elseif ( 'image' === $field['type'] ) {
				echo '<div class="cbp-image-field">';
				echo '<img src="' . esc_url( $value ) . '" style="max-width:220px;height:auto;display:block;margin-bottom:8px;border:1px solid #dcdcde;border-radius:4px;">';
				echo '<input type="hidden" class="cbp-image-value" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
				echo '<button type="button" class="button cbp-image-select">Vybrat / změnit fotku</button>';
				echo '</div>';
			} else {
				echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%;">';
			}
			echo '</p>';
		}
		?>
		<script>
		jQuery(function($){
			$('.cbp-image-select').on('click', function(e){
				e.preventDefault();
				var $btn = $(this), $wrap = $btn.closest('.cbp-image-field');
				var frame = wp.media({ title: 'Vybrat fotku', multiple: false, library: { type: 'image' } });
				frame.on('select', function(){
					var att = frame.state().get('selection').first().toJSON();
					$wrap.find('img').attr('src', att.url);
					$wrap.find('.cbp-image-value').val(att.url);
				});
				frame.open();
			});
		});
		</script>
		<?php
	}, 'page', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'cbp_page_text_meta_box' );

function cbp_enqueue_media_for_page_editor( $hook ) {
	if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) return;
	global $post;
	if ( ! $post || 'page' !== $post->post_type ) return;
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'cbp_enqueue_media_for_page_editor' );

/* Prominent notice right under the title, so it's impossible to miss even before
 * scrolling past the (deliberately unused) main content editor. */
function cbp_page_text_notice( $post ) {
	$fields = cbp_fields_for_post( $post->ID );
	if ( empty( $fields ) ) return;
	echo '<div class="notice notice-info" style="margin:16px 0;padding:12px 16px;"><p style="margin:0;font-size:14px;"><strong>👋 Texty téhle stránky se needitují tady nahoře.</strong> Sjeď úplně dolů pod stránku, najdeš tam box <strong>„Texty stránky“</strong> se všemi poli.</p></div>';
}
add_action( 'edit_form_after_title', 'cbp_page_text_notice' );

function cbp_save_page_text( $post_id ) {
	if ( ! isset( $_POST['cbp_page_text_nonce'] ) || ! wp_verify_nonce( $_POST['cbp_page_text_nonce'], 'cbp_page_text_save' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	$fields = cbp_fields_for_post( $post_id );
	foreach ( $fields as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) ) continue;
		if ( 'textarea' === $field['type'] ) {
			$value = sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) );
		} elseif ( 'image' === $field['type'] ) {
			$value = esc_url_raw( wp_unslash( $_POST[ $key ] ) );
		} else {
			$value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
		}
		update_post_meta( $post_id, $key, $value );
	}
}
add_action( 'save_post_page', 'cbp_save_page_text' );

/** The homepage testimonial is the same one shown on the About page, so Petra only
 * has to update it in one place. Cached in a static var to avoid repeat lookups. */
function cbp_get_about_page_id() {
	static $id = null;
	if ( null === $id ) {
		$about = get_page_by_path( 'about' );
		$id    = $about ? $about->ID : 0;
	}
	return $id;
}

/** The virtual "Domů — texty" page holds the homepage's Featured/About-teaser copy. */
function cbp_get_home_texts_page_id() {
	static $id = null;
	if ( null === $id ) {
		$page = get_page_by_path( 'domu-texty' );
		$id   = $page ? $page->ID : 0;
	}
	return $id;
}

/** Small helper the templates use to read a text field with its default. */
function cbp_page_text( $post_id, $key ) {
	$fields = cbp_fields_for_post( $post_id );
	$value  = get_post_meta( $post_id, $key, true );
	if ( '' !== $value ) return $value;
	return isset( $fields[ $key ] ) ? $fields[ $key ]['default'] : '';
}

/* ── Contact form: real delivery via wp_mail(), not a mailto: link ──
 * mailto: only opens the VISITOR's own email client and relies on them hitting send —
 * unreliable, especially on mobile. This posts to admin-post.php and emails Petra directly.
 */
function cbp_handle_contact_form() {
	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/contact/' );

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) );
		exit;
	}

	$to      = get_theme_mod( 'cbp_footer_email', get_option( 'admin_email' ) );
	$subject = 'New message from ' . $name . ' via Candles by Petra';
	$body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'contact', 'sent', $redirect ) );
	exit;
}
add_action( 'admin_post_cbp_contact_form', 'cbp_handle_contact_form' );
add_action( 'admin_post_nopriv_cbp_contact_form', 'cbp_handle_contact_form' );

/* ── Auto-cancel unpaid bank-transfer orders after 3 days ──
 * Matches "pay within 3 business days" from the order-flow copy.
 */
function cbp_cancel_unpaid_orders() {
	$orders = wc_get_orders( array(
		'status'       => 'on-hold',
		'date_created' => '<' . ( time() - 3 * DAY_IN_SECONDS ),
		'limit'        => -1,
	) );
	foreach ( $orders as $order ) {
		$order->update_status( 'cancelled', __( 'Auto-cancelled: payment not received within 3 business days.', 'candles-by-petra' ) );
	}
}
if ( ! wp_next_scheduled( 'cbp_cancel_unpaid_orders_event' ) ) {
	wp_schedule_event( time(), 'daily', 'cbp_cancel_unpaid_orders_event' );
}
add_action( 'cbp_cancel_unpaid_orders_event', 'cbp_cancel_unpaid_orders' );

/* ── Gift wrapping option (+€2), offered on every product's page ── */
define( 'CBP_GIFT_WRAP_FEE', 2 );

add_action( 'woocommerce_before_add_to_cart_button', 'cbp_gift_wrap_field' );
function cbp_gift_wrap_field() {
	printf(
		'<label class="cbp-gift-wrap"><input type="checkbox" name="cbp_gift_wrap" value="1" /> Add gift wrapping (+%s)</label>',
		wc_price( CBP_GIFT_WRAP_FEE )
	);
}

add_filter( 'woocommerce_add_cart_item_data', 'cbp_save_gift_wrap_choice', 10, 2 );
function cbp_save_gift_wrap_choice( $cart_item_data, $product_id ) {
	if ( ! empty( $_POST['cbp_gift_wrap'] ) ) {
		$cart_item_data['cbp_gift_wrap'] = true;
	}
	return $cart_item_data;
}

add_action( 'woocommerce_before_calculate_totals', 'cbp_apply_gift_wrap_fee' );
function cbp_apply_gift_wrap_fee( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
	foreach ( $cart->get_cart() as $cart_item ) {
		if ( ! empty( $cart_item['cbp_gift_wrap'] ) ) {
			$cart_item['data']->set_price( $cart_item['data']->get_price() + CBP_GIFT_WRAP_FEE );
		}
	}
}

add_filter( 'woocommerce_get_item_data', 'cbp_show_gift_wrap_in_cart', 10, 2 );
function cbp_show_gift_wrap_in_cart( $item_data, $cart_item ) {
	if ( ! empty( $cart_item['cbp_gift_wrap'] ) ) {
		$item_data[] = array(
			'name'  => 'Gift wrapping',
			'value' => '+' . wc_price( CBP_GIFT_WRAP_FEE ),
		);
	}
	return $item_data;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'cbp_save_gift_wrap_on_order', 10, 3 );
function cbp_save_gift_wrap_on_order( $item, $cart_item_key, $values ) {
	if ( ! empty( $values['cbp_gift_wrap'] ) ) {
		$item->add_meta_data( 'Gift wrapping', '+' . wc_price( CBP_GIFT_WRAP_FEE ) );
	}
}
