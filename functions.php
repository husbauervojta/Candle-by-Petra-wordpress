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

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'candles-by-petra' ),
	) );
}
add_action( 'after_setup_theme', 'cbp_setup' );

/* ── ASSETS ── */
function cbp_enqueue_assets() {
	wp_enqueue_style( 'cbp-style', get_stylesheet_uri(), array(), '1.0' );
	wp_enqueue_script( 'cbp-cart-drawer', get_template_directory_uri() . '/assets/js/cart-drawer.js', array( 'jquery', 'wc-cart-fragments' ), '1.0', true );
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
		'label'       => __( 'Origin badge text', 'candles-by-petra' ),
		'placeholder' => 'e.g. Lavender fields · Platres',
		'desc_tip'    => true,
		'description' => __( 'Shown as the small pill above the product name.', 'candles-by-petra' ),
	) );

	woocommerce_wp_text_input( array(
		'id'          => '_cbp_scent_tag',
		'label'       => __( 'Scent / material tag', 'candles-by-petra' ),
		'placeholder' => 'e.g. Handmade Floral Candle',
		'desc_tip'    => true,
		'description' => __( 'Shown under the product name, above the price.', 'candles-by-petra' ),
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
		'title'    => __( 'Homepage Hero', 'candles-by-petra' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'cbp_hero_image', array( 'default' => get_template_directory_uri() . '/assets/images/cherry-hero.jpg' ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'cbp_hero_image', array(
		'label'   => __( 'Hero photo', 'candles-by-petra' ),
		'section' => 'cbp_hero_section',
	) ) );

	$wp_customize->add_setting( 'cbp_hero_badge', array( 'default' => 'Handcrafted · Larnaca, Cyprus' ) );
	$wp_customize->add_control( 'cbp_hero_badge', array(
		'label' => __( 'Small badge text', 'candles-by-petra' ), 'section' => 'cbp_hero_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_hero_title', array( 'default' => 'Light that feels like' ) );
	$wp_customize->add_control( 'cbp_hero_title', array(
		'label' => __( 'Headline (plain part)', 'candles-by-petra' ), 'section' => 'cbp_hero_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_hero_title_em', array( 'default' => 'home' ) );
	$wp_customize->add_control( 'cbp_hero_title_em', array(
		'label' => __( 'Headline (gold italic word)', 'candles-by-petra' ), 'section' => 'cbp_hero_section', 'type' => 'text',
	) );

	$wp_customize->add_setting( 'cbp_hero_subtitle', array( 'default' => 'Small-batch soy candles poured by hand, inspired by the coastline, mountains and citrus groves of Cyprus.' ) );
	$wp_customize->add_control( 'cbp_hero_subtitle', array(
		'label' => __( 'Subtitle', 'candles-by-petra' ), 'section' => 'cbp_hero_section', 'type' => 'textarea',
	) );

	$wp_customize->add_setting( 'cbp_hero_button_url', array( 'default' => '' ) );
	$wp_customize->add_control( 'cbp_hero_button_url', array(
		'label'       => __( '"Shop the Collection" button link', 'candles-by-petra' ),
		'description' => __( 'Leave empty to link to the main Shop page. Paste a product URL to point straight at a seasonal candle.', 'candles-by-petra' ),
		'section'     => 'cbp_hero_section', 'type' => 'text',
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
