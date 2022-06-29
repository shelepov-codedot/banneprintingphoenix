<?php
/**
 * BPSD functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package BPSD
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '2.1' );
}

if ( ! function_exists( 'bpsd_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function bpsd_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on BPSD, use a find and replace
		 * to change 'bpsd' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'bpsd', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'bpsd' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'bpsd_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'bpsd_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bpsd_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'bpsd_content_width', 640 );
}
add_action( 'after_setup_theme', 'bpsd_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bpsd_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'bpsd' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'bpsd' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'bpsd_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function bpsd_scripts() {
    wp_enqueue_script( 'jquery' );

    wp_enqueue_style( 'bpsd-main-css', get_template_directory_uri() . '/assets/css/main.css', array(), _S_VERSION );
    wp_enqueue_style( 'bpsd-slick-css', get_template_directory_uri() . '/assets/lib/slick/slick.css', array(), _S_VERSION );

    wp_enqueue_script('validator-js', get_template_directory_uri() . '/assets/lib/validator-js/jquery.validate.min.js', array(), _S_VERSION, true);
    wp_enqueue_script( 'bpsd-slick-js', get_template_directory_uri() . '/assets/lib/slick/slick.min.js', array('jquery'), _S_VERSION, true );
    wp_enqueue_script( 'bpsd-main-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), _S_VERSION, true );

    wp_enqueue_script( 'bpsd-cart-js', get_template_directory_uri() . '/assets/js/cart.js', array('jquery'), _S_VERSION, true );

    wp_enqueue_script( 'stripe', 'https://js.stripe.com/v3/', '', '3.0', true );
    wp_enqueue_script( 'woocommerce_stripe', '/wp-content/plugins/woocommerce-gateway-stripe/assets/js/stripe.js', array('jquery-payment', 'stripe'), WC_STRIPE_VERSION, true );


    if(is_product()){
        wp_enqueue_script( 'bpsd-product-js', get_template_directory_uri() . '/assets/js/product.js', array('jquery','validator-js'), _S_VERSION, true );

        if(wc_get_product(get_the_ID())->is_type( 'variable' ) ):
//            wp_localize_script( 'bpsd-product-js', 'ajax_url',
//                array(
//                    'url' => admin_url('admin-ajax.php'),
//                    'variations'=> json_encode(wc_get_product(get_the_ID())->get_available_variations()),
//                    'default_price'=>wc_get_product(get_the_ID())->get_variation_price( 'min' )
//                )
//
//            );
        else:
            wp_localize_script( 'bpsd-product-js', 'ajax_url',
                array(
                    'url' => admin_url('admin-ajax.php')
                )
            );
        endif;

        if (wc_get_product(get_the_ID())->get_category_ids()):
            $table = get_field('discount_table', 'product_cat_' . wc_get_product(get_the_ID())->get_category_ids()[0]);
            if ($table):
                wp_localize_script('bpsd-product-js', 'discount',
                    array(
                        'data' =>  $table
                    )
                );
            endif;
            $table = get_field('add_dop_price', 'product_cat_' . wc_get_product(get_the_ID())->get_category_ids()[0]);
            if ($table):
                wp_localize_script('bpsd-product-js', 'add_dop_price',
                    array(
                        'data' =>  array_map('array_mb_replace', $table)
                    )
                );
            endif;
        endif;

    }

    add_action( 'wp_enqueue_scripts', 'myajax_data', 99 );
    function myajax_data(){

        wp_localize_script( 'bpsd-main-js', 'ajax_data',
            array(
                'url' => admin_url('admin-ajax.php')
            )
        );

    }

}
add_action( 'wp_enqueue_scripts', 'bpsd_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


require get_template_directory() . '/inc/custom-post-type.php';
require get_template_directory() . '/inc/subscription.php';
require get_template_directory() . '/inc/category-functions.php';
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/pagination.php';
require get_template_directory() . '/inc/product-admin.php';
require get_template_directory() . '/inc/product-review.php';
//require get_template_directory() . '/inc/cart.php';


// ACF Option fields
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title' 	=> 'General options',
        'menu_title'	=> 'General options',
        'menu_slug' 	=> 'general_options',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));

}

function mytheme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );


//add_filter( 'woocommerce_enqueue_styles', '__return_false' );
add_filter( 'wp_sitemaps_enabled', '__return_false' );

function array_mb_replace($value) {
    return [
        'option' => str_replace(' ', '-', mb_strtolower($value['option'])),
        'price' => $value['price']
    ];
}

add_filter( 'woocommerce_checkout_redirect_empty_cart', '__return_false' );
add_filter( 'woocommerce_checkout_update_order_review_expired', '__return_false' );

// Utility function that give the discount percentage based on quantity argument
function get_discount_percent( $quantity, $product_id, $variations){
    $product = wc_get_product($product_id);
    $percent = 0;
    $discountTable = get_field('discount_table', 'product_cat_'.$product->get_category_ids()[0]);
    $optionsTable = array_map('array_mb_replace', get_field('add_dop_price', 'product_cat_'.$product->get_category_ids()[0]));

    $price = 0;
    foreach ($variations as $variation) {
        $min_variant = str_replace(' ', '-', mb_strtolower($variation));
        $column_id = array_search($min_variant, array_column($optionsTable, 'option'));
        if ($column_id !== false) {
            $price += $optionsTable[$column_id]['price'];
        }
    }

    foreach ($discountTable as $discount) {
        if ($quantity >= $discount['from'] && $quantity < $discount['to'])
            $percent = $discount['discount'];
    }

    return [$percent, $price];
}

function get_discount_table($product_id, $variations){
    $product = wc_get_product($product_id);
    $percent = 0;
    $discountTable = get_field('discount_table', 'product_cat_'.$product->get_category_ids()[0]);
    $optionsTable = array_map('array_mb_replace', get_field('add_dop_price', 'product_cat_'.$product->get_category_ids()[0]));

    $price = 0;
    foreach ($variations as $variation) {
        $min_variant = str_replace(' ', '-', mb_strtolower($variation));
        $column_id = array_search($min_variant, array_column($optionsTable, 'option'));
        if ($column_id !== false) {
            $price += $optionsTable[$column_id]['price'];
        }
    }

    return [
        'table' => $discountTable,
        'dop_price' => $price
    ];
}

add_filter('woocommerce_add_cart_item_data', 'add_items_default_price_as_custom_data', 20, 3 );
function add_items_default_price_as_custom_data( $cart_item_data, $product_id, $variation_id ){
    $productID = $product_id;
    $product_id = $variation_id > 0 ? $variation_id : $product_id;

    // The WC_Product Object
    $product = wc_get_product($product_id);

    // Set the Product default base price as custom cart item data
    $cart_item_data['discounts'] = get_discount_table($productID, $product->get_attributes());

    if (isset($_POST['custom_variant'])) {

        list($width, $height) = explode('-x-', $_POST['variant']);

        $cart_item_data['custom_size'] = $_POST['variant'];
        if ($_POST['new_price']) {
            $cart_item_data['custom_price'] = $_POST['new_price'];
        } else {
            $cart_item_data['custom_price'] = $product->get_price()*$width*$height;
        }

        if ($_POST['new_name']) {
            $cart_item_data['custom_name'] = $_POST['new_name'];
        }
    }

    if (isset($_POST['custom_weight'])) {
        $cart_item_data['custom_weight'] = $_POST['custom_weight'];
    }

    return $cart_item_data;
}

// Display the product original price
add_filter('woocommerce_cart_item_price', 'display_cart_items_default_price', 20, 3 );
function display_cart_items_default_price( $product_price, $cart_item, $cart_item_key ){
    if( isset($cart_item['discount'][0]) ) {
        $product        = $cart_item['data'];
        $product_price  = wc_price( wc_get_price_to_display( $product, array( 'price' => $cart_item['discount'][0] ) ) );
    }
    return $product_price;
}

// Display the product name with the discount percentage
add_filter( 'woocommerce_cart_item_name', 'append_percetage_to_item_name', 20, 3 );
function append_percetage_to_item_name( $product_name, $cart_item, $cart_item_key ){
//    if (isset($cart_item['custom_size'])) {
//
//        $attributes = $cart_item['data']->get_attributes();
//
//        $attributes['pa_set-size'] = $cart_item['custom_size'];
//
//        $cart_item['data']->set_attributes($attributes);
//    }

    // get the percent based on quantity
    $percent = get_discount_percent($cart_item['quantity'], $cart_item['product_id'], $cart_item['variation']);

    if($percent[0] != 0) {
        if( $cart_item['data']->get_price() != $cart_item['discount'][0] )
            $product_name .= ' <em>(' . $percent[0] . '% discounted)</em>';
    }
    return $product_name;
}

add_action( 'woocommerce_before_calculate_totals', 'set_custom_discount_cart_item_price', 25, 1 );
function set_custom_discount_cart_item_price( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    foreach( $cart->get_cart() as $cart_item ){
        // get the percent based on quantity
        $percentage = get_discount_percent($cart_item['quantity'], $cart_item['product_id'], $cart_item['variation']);

        $price = $cart_item['data']->get_price();
        if (isset($cart_item['custom_price'])) {
            $price = $cart_item['custom_price'];
        }

        if (isset($cart_item['custom_name'])) {
            $cart_item['data']->set_name($cart_item['custom_name']);
        }

        if (isset($cart_item['custom_weight'])) {
            $cart_item['data']->set_weight($cart_item['custom_weight']);
        }

        // For items non on sale set a discount based on quantity as defined in
        if( $percentage != 0 && ! $cart_item['data']->is_on_sale() ) {
            $cart_item['data']->set_price(($price-$percentage[1]) * ((100-$percentage[0])/100) + $percentage[1]);
        } else {
            $cart_item['data']->set_price($price);
        }

        file_put_contents('../.log-product_data', date('[Y-m-d H:i:s] ') . "\n" . print_r([
                'variation' => $cart_item['variation'],
                'discount' => $percentage[0],
                'quantity' => $cart_item['quantity'],
                'product_id' => $cart_item['product_id'],
                'old_price' => $price,
                'dop_price' => $percentage[1],
                'no_dop_price' =>$price-$percentage[1],
                'new_price' => ($price-$percentage[1]) * ((100-$percentage[0])/100) + $percentage[1],
                'cart_item_data' => $cart_item
            ], true) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}


function register_pending_artwork_order_status() {
    register_post_status( 'wc-pending-artwork', array(
        'label'                     => 'Pending Artwork Approval',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Pending Artwork Approval (%s)', 'Pending Artwork Approval (%s)' )
    ) );
}
add_action('init', 'register_pending_artwork_order_status');
function register_artwork_approved_order_status() {
    register_post_status( 'wc-artwork-approved', array(
        'label'                     => 'Artwork Approved',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Artwork Approved (%s)', 'Artwork Approved (%s)' )
    ) );
}
add_action('init', 'register_artwork_approved_order_status');
function register_in_production_order_status() {
    register_post_status( 'wc-in-production', array(
        'label'                     => 'In production',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'In production (%s)', 'In production (%s)' )
    ) );
}
add_action('init', 'register_in_production_order_status');
function register_ready_for_quality_check_order_status() {
    register_post_status( 'wc-ready-check', array(
        'label'                     => 'Ready for Quality Check',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Ready for Quality Check (%s)', 'Ready for Quality Check (%s)' )
    ) );
}
add_action('init', 'register_ready_for_quality_check_order_status');
function register_ready_for_pickup_order_status() {
    register_post_status( 'wc-ready-pickup', array(
        'label'                     => 'Ready for Pickup',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Ready for Pickup (%s)', 'Ready for Pickup (%s)' )
    ) );
}
add_action('init', 'register_ready_for_pickup_order_status');
function register_picked_up_order_status() {
    register_post_status( 'wc-picked-up', array(
        'label'                     => 'Picked Up',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Picked Up (%s)', 'Picked Up (%s)' )
    ) );
}
add_action('init', 'register_picked_up_order_status');


function add_pending_artwork_approval_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-pending-artwork'] = 'Pending Artwork Approval';
        }
    }
    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_pending_artwork_approval_to_order_statuses');
function add_artwork_approved_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-artwork-approved'] = 'Artwork Approved';
        }
    }
    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_artwork_approved_to_order_statuses');
function add_in_production_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-in-production'] = 'In production';
        }
    }
    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_in_production_to_order_statuses');
function add_ready_for_quality_check_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-ready-check'] = 'Ready for Quality Check';
        }
    }
    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_ready_for_quality_check_to_order_statuses');
function add_ready_for_pickup_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-ready-pickup'] = 'Ready for Pickup';
        }
    }
    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_ready_for_pickup_to_order_statuses');
function add_picked_up_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-picked-up'] = 'Picked Up';
        }
    }
    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_picked_up_to_order_statuses');


function change_custom_to_order_notification( $order_id, $from_status, $to_status, $order ) {
    global $woocommerce;
    $order = new WC_Order( $order_id );

    if($order->status === 'artwork-approved') {
        $email_notifications = WC()->mailer()->get_emails();
        $email_notifications['WC_Email_Customer_In_Production_Order']->trigger($order_id);
    }
    if($order->status === 'ready-pickup') {
        $email_notifications = WC()->mailer()->get_emails();
        $email_notifications['WC_Email_Customer_Ready_For_Pickup_Order']->trigger($order_id);
    }
    if($order->status === 'picked-up') {
        $email_notifications = WC()->mailer()->get_emails();
        $email_notifications['WC_Email_Customer_Picked_Up_Order']->trigger($order_id);
    }
}
add_action('woocommerce_order_status_changed', 'change_custom_to_order_notification', 10, 4);

add_filter('nav_menu_css_class', 'filter_nav_menu_css_classes', 10, 4);
function filter_nav_menu_css_classes($classes, $item, $args, $depth) {
    if ($args->menu === 'Category Menu') {
        if ($depth == 0) {
            $classes = [
                'main-menu__category-list-item is_mobile',
            ];
        } else {
            $classes = [];
        }
    }

    return $classes;
}

add_filter('nav_menu_submenu_css_class', 'filter_nav_menu_submenu_css_class', 10, 3);
function filter_nav_menu_submenu_css_class($classes, $args, $depth) {
    if ($args->menu == 'Category Menu') {
        if ($depth == 0) {
            $classes = [
                'sub-menu__category'
            ];
        } else {
            $classes = [];
        }
    }

    return $classes;
}

add_filter( 'wp_mail_content_type', 'true_content_type' );

function true_content_type( $content_type ) {
    return 'text/html';
}

add_filter( 'wp_mail_charset', 'true_mail_charset' );

function true_mail_charset( $content_type ) {
    return 'utf-8';
}

function custom_remove_post_locked() {
    $current_post_type = get_current_screen()->post_type;

    // Disable locking for page, post and some custom post type
    $post_types_arr = array(
        'page',
        'post',
        'custom_post_type',
        'shop_order'
    );

    if(in_array($current_post_type, $post_types_arr)) {
        add_filter( 'show_post_locked_dialog', '__return_false' );
        add_filter( 'wp_check_post_lock_window', '__return_false' );
        wp_deregister_script('heartbeat');
    }
}

add_action('load-edit.php', 'custom_remove_post_locked');
add_action('load-post.php', 'custom_remove_post_locked');

add_filter( 'wpo_wcpdf_paper_format', 'wcpdf_a6_packing_slips', 10, 2 );
function wcpdf_a6_packing_slips($paper_format, $template_type) {
    if ($template_type == 'packing-slip') {
        $paper_format = 'a6';
    }

    return $paper_format;
}
//add_action( 'woocommerce_before_calculate_totals', 'add_custom_price', 1000, 1);
//function add_custom_price( $cart ) {
//    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
//        return;
//
//    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
//        return;
//
//    echo '<pre>';
//    print_r($cart->get_cart());
//    echo '</pre>';
//    die;
//
//    foreach ( $cart->get_cart() as $cart_item ) {
//        if (isset($cart_item['custom_height']) && isset($cart_item['custom_width'])) {
//            $new_price = $cart_item['data']->get_price() * $cart_item['custom_height'] * $cart_item['custom_width'];
//            $cart_item['data']->set_price($new_price);
//        }
//    }
//}

require_once __DIR__ . '/class-Kama_SEO_Tags.php';
Kama_SEO_Tags::init();
