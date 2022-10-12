<?php
/**
 * WordPress Ajax Process Execution
 *
 * @package WordPress
 * @subpackage Administration
 *
 * @link https://codex.wordpress.org/AJAX_in_Plugins
 */

/**
 * Executing Ajax process.
 *
 * @since 2.1.0
 */
define( 'DOING_AJAX', true );
if ( ! defined( 'WP_ADMIN' ) ) {
	define( 'WP_ADMIN', true );
}

/** Load WordPress Bootstrap */
require_once dirname( __DIR__ ) . '/wp-load.php';

/** Allow for cross-domain requests (from the front end). */
send_origin_headers();

header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
header( 'X-Robots-Tag: noindex' );

// Require an action parameter.
if ( empty( $_REQUEST['action'] ) ) {
	wp_die( '0', 400 );
}

/** Load WordPress Administration APIs */
require_once ABSPATH . 'wp-admin/includes/admin.php';

/** Load Ajax Handlers for WordPress Core */
require_once ABSPATH . 'wp-admin/includes/ajax-actions.php';

send_nosniff_header();
nocache_headers();

/** This action is documented in wp-admin/admin.php */
do_action( 'admin_init' );

$core_actions_get = array(
	'fetch-list',
	'ajax-tag-search',
	'wp-compression-test',
	'imgedit-preview',
	'oembed-cache',
	'autocomplete-user',
	'dashboard-widgets',
	'logged-in',
	'rest-nonce',
);

$core_actions_post = array(
	'oembed-cache',
	'image-editor',
	'delete-comment',
	'delete-tag',
	'delete-link',
	'delete-meta',
	'delete-post',
	'trash-post',
	'untrash-post',
	'delete-page',
	'dim-comment',
	'add-link-category',
	'add-tag',
	'get-tagcloud',
	'get-comments',
	'replyto-comment',
	'edit-comment',
	'add-menu-item',
	'add-meta',
	'add-user',
	'closed-postboxes',
	'hidden-columns',
	'update-welcome-panel',
	'menu-get-metabox',
	'wp-link-ajax',
	'menu-locations-save',
	'menu-quick-search',
	'meta-box-order',
	'get-permalink',
	'sample-permalink',
	'inline-save',
	'inline-save-tax',
	'find_posts',
	'widgets-order',
	'save-widget',
	'delete-inactive-widgets',
	'set-post-thumbnail',
	'date_format',
	'time_format',
	'wp-remove-post-lock',
	'dismiss-wp-pointer',
	'upload-attachment',
	'get-attachment',
	'query-attachments',
	'save-attachment',
	'save-attachment-compat',
	'send-link-to-editor',
	'send-attachment-to-editor',
	'save-attachment-order',
	'media-create-image-subsizes',
	'heartbeat',
	'get-revision-diffs',
	'save-user-color-scheme',
	'update-widget',
	'query-themes',
	'parse-embed',
	'set-attachment-thumbnail',
	'parse-media-shortcode',
	'destroy-sessions',
	'install-plugin',
	'update-plugin',
	'crop-image',
	'generate-password',
	'save-wporg-username',
	'delete-plugin',
	'search-plugins',
	'search-install-plugins',
	'activate-plugin',
	'update-theme',
	'delete-theme',
	'install-theme',
	'get-post-thumbnail-html',
	'get-community-events',
	'edit-theme-plugin-file',
	'wp-privacy-export-personal-data',
	'wp-privacy-erase-personal-data',
	'health-check-site-status-result',
	'health-check-dotorg-communication',
	'health-check-is-in-debug-mode',
	'health-check-background-updates',
	'health-check-loopback-requests',
	'health-check-get-sizes',
	'toggle-auto-updates',
);

// Deprecated.
$core_actions_post_deprecated = array(
	'wp-fullscreen-save-post',
	'press-this-save-post',
	'press-this-add-category',
	'health-check-dotorg-communication',
	'health-check-is-in-debug-mode',
	'health-check-background-updates',
	'health-check-loopback-requests',
);
$core_actions_post            = array_merge( $core_actions_post, $core_actions_post_deprecated );

// Register core Ajax calls.
if ( ! empty( $_GET['action'] ) && in_array( $_GET['action'], $core_actions_get, true ) ) {
	add_action( 'wp_ajax_' . $_GET['action'], 'wp_ajax_' . str_replace( '-', '_', $_GET['action'] ), 1 );
}

if ( ! empty( $_POST['action'] ) && in_array( $_POST['action'], $core_actions_post, true ) ) {
	add_action( 'wp_ajax_' . $_POST['action'], 'wp_ajax_' . str_replace( '-', '_', $_POST['action'] ), 1 );
}

add_action( 'wp_ajax_nopriv_heartbeat', 'wp_ajax_nopriv_heartbeat', 1 );

add_action('wp_ajax_post_add_to_cart'       , 'post_add_to_cart');
add_action('wp_ajax_nopriv_post_add_to_cart', 'post_add_to_cart');
add_action('wp_ajax_post_update_quantity_product_to_cart'       , 'post_update_quantity_product_to_cart');
add_action('wp_ajax_nopriv_post_update_quantity_product_to_cart', 'post_update_quantity_product_to_cart');
add_action('wp_ajax_get_cart_total'       , 'get_cart_total');
add_action('wp_ajax_nopriv_get_cart_total', 'get_cart_total');
add_action('wp_ajax_post_checkout_user'       , 'post_checkout_user');
add_action('wp_ajax_nopriv_post_checkout_user', 'post_checkout_user');
add_action('wp_ajax_post_upload_file', 'post_upload_file');
add_action('wp_ajax_nopriv_post_upload_file', 'post_upload_file');
add_action('wp_ajax_post_upload_custom_file', 'post_upload_custom_file');
add_action('wp_ajax_nopriv_post_upload_custom_file', 'post_upload_custom_file');
add_action('wp_ajax_get_states', 'get_states');
add_action('wp_ajax_nopriv_get_states', 'get_states');

add_action('wp_ajax_post_delete_file', 'post_delete_file');
add_action('wp_ajax_nopriv_post_delete_file', 'post_delete_file');

add_action('wp_ajax_get_variants_product', 'get_variants_product');
add_action('wp_ajax_nopriv_get_variants_product', 'get_variants_product');

add_action('wp_ajax_get_variants_of_product', 'get_variants_of_product');
add_action('wp_ajax_nopriv_get_variants_of_product', 'get_variants_of_product');

add_action('wp_ajax_admin_product_get', 'admin_product_get');
add_action('wp_ajax_nopriv_admin_product_get', 'admin_product_get');
add_action('wp_ajax_admin_generation_order', 'admin_generation_order');
add_action('wp_ajax_nopriv_admin_generation_order', 'admin_generation_order');
add_action('wp_ajax_form_send', 'form_send');
add_action('wp_ajax_nopriv_form_send', 'form_send');

function form_send()
{
    $to = 'contact@bannerprintingphoenix.com';
    $subject = 'Completed contact form with https://bannerprintingphoenix.com/';
    $name = $_POST['name'];
    $type = $_POST['type'];
    $email_message = $_POST['message'];
    $contact_email = $_POST['email'];
    $message = "<div>Name: $name</div><div>Email: $contact_email</div><div>Type: $type</div><div>Message: $email_message</div>";
    $id = WC()->session->get('customer_folder');
    $uploaddir = '../user_print/contact/' . $id . '/';

    $scanned_directory = array_diff(scandir($uploaddir), array('..', '.'));

    foreach ($scanned_directory as $file) {
        $img_url = 'https://bannerprintingphoenix.com/user_print/contact/'.$id.'/'.$file;
        $message .= "<img height='320px' src='$img_url'>";
    }

    wp_mail($to, $subject, $message, array());
    echo 'ok';

    wp_die();
}

function get_variants_product() {
    $data = [];

    $data['variations'] = json_encode(wc_get_product($_GET['key'])->get_available_variations());

    echo json_encode($data);

    wp_die();
}

function get_variants_of_product() {
    $product = wc_get_product($_GET['key']);
    $variations_json = wp_json_encode($product->get_available_variations());

    $variations = json_encode(json_decode($variations_json));

    echo json_encode($variations);

    wp_die();
}

function post_add_to_cart(){
    $result = WC()->cart->add_to_cart($_POST['product_id'], $_POST['quantity'], $_POST['variation_id']);

    $product_new_add_cart = [];
    $products_array = [];
    foreach (WC()->cart->get_cart() as $product_key => $product):

        $_product = apply_filters( 'woocommerce_cart_item_product', $product['data'], $product, $product_key );

        $arrtibutes_array = [];
        $attributes = $_product->get_attributes();
        foreach ($attributes as $attribute=>$value):
            $value = !is_object($value) ? $value : $_product->get_default_attributes()[$attribute];
            if ($attribute == 'pa_set-size' && isset($product['custom_size'])) {
                $value = $product['custom_size'];
            }

            $arrtibutes_array[] = [
                'name' => implode(' ', array_map('ucfirst', explode('-', substr($attribute, 3)))),
                'value' => $value,
            ];
        endforeach;

        if ($product_key == $result) {
            $product_new_add_cart = [
                'id' => $result,
                'img' => wp_get_attachment_image_url( $_product->get_image_id(), 'full', '$icon' ),
                'name' => $_product->get_name(),
                'quantity' => $product['quantity'],
                'price' => WC()->cart->get_product_subtotal( $_product, $product['quantity'] ),
                'attributes' => $arrtibutes_array,
                'action' => [
                    'edit' => apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $product ) : '', $product, $product_key ),
                    'delete' => wc_get_cart_remove_url($product_key),
                ],
            ];
        }

        $products_array[$product_key] = [
            'img' => wp_get_attachment_image_url( $_product->get_image_id(), 'full', '$icon' ),
            'name' => $_product->get_name(),
            'quantity' => $product['quantity'],
            'price' => WC()->cart->get_product_subtotal( $_product, $product['quantity'] ),
            'attributes' => $arrtibutes_array,
            'action' => [
                'edit' => apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $product ) : '', $product, $product_key ),
                'delete' => wc_get_cart_remove_url($product_key),
            ],
        ];
    endforeach;

    echo(json_encode([
        'status'=>'ok',
        'post_vars'=>$_POST,
        'wc' => WC()->cart->get_cart(),
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'products' => $products_array,
        'totals' => '$'.WC()->cart->get_subtotal(),
        'product_new_add_cart' => $product_new_add_cart,
    ]));
    wp_die();
}

function post_update_quantity_product_to_cart() {
    WC()->cart->set_quantity($_POST['product_id'], $_POST['quantity']);

    $cart_products = [];
    foreach (WC()->cart->get_cart() as $product_key => $product):

        $_product = apply_filters( 'woocommerce_cart_item_product', $product['data'], $product, $product_key );

        $arrtibutes_array = [];
        $attributes = $_product->get_attributes();
        foreach ($attributes as $attribute=>$value):
            $value = !is_object($value) ? $value : $_product->get_default_attributes()[$attribute];
            if ($attribute == 'pa_set-size' && isset($product['custom_size'])) {
                $value = $product['custom_size'];
            }

            $arrtibutes_array[] = [
                'name' => implode(' ', array_map('ucfirst', explode('-', substr($attribute, 3)))),
                'value' => $value,
            ];
        endforeach;

        $cart_products[$product_key] = [
            'img' => wp_get_attachment_image_url( $_product->get_image_id(), 'full', '$icon' ),
            'name' => $_product->get_name(),
            'quantity' => $product['quantity'],
            'price' => WC()->cart->get_product_subtotal( $_product, $product['quantity'] ),
            'attributes' => $arrtibutes_array,
            'action' => [
                'edit' => apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $product ) : '', $product, $product_key ),
                'delete' => wc_get_cart_remove_url($product_key),
            ],
        ];
    endforeach;

    echo(json_encode([
        'status'=>'ok',
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_products' => $cart_products,
        'totals' => '$'.WC()->cart->get_subtotal(),
    ]));
    wp_die();
}

function post_upload_file(){
    if (!WC()->session->has_session()) {
        WC()->session->set_customer_session_cookie(true);
    }

    if (WC()->session->get( 'customer_folder' ) == true) {
        $id = WC()->session->get('customer_folder');
    } else {
        $id = randHash();
        WC()->session->set('customer_folder', $id);
    }

    $uploaddir = '../user_print/' . $id . '/';
    $files = [];

    if (!is_dir($uploaddir)) mkdir($uploaddir, 0777);

    foreach ($_FILES as $file) {
        if  ( isset($_GET['key']) && isset($_GET['variation']) ) {
            $name = $id . '_' . $_GET['key'] . '_' . $_GET['variation'] . '_' . $file['name'];
        } elseif( isset($_GET['key']) ) {
            $name = $id . '_' . $_GET['key'] . '_' . $file['name'];
        } else {
            $name = $id . '_' . $file['name'];
        }

        if (move_uploaded_file($file['tmp_name'], $uploaddir . $name)) {
            $files[] = $file;
        } else {
            $error = true;
        }
    }

    $data = $error ? array('error' => 'File upload error.') : array('files' => $files);

    echo json_encode($data);

    wp_die();
}

function randHash($len=32) {
    return substr(md5(openssl_random_pseudo_bytes(20)),-$len);
}

function post_upload_custom_file() {
    if (!WC()->session->has_session()) {
        WC()->session->set_customer_session_cookie(true);
    }

    if (WC()->session->get( 'customer_folder' ) == true) {
        $id = WC()->session->get('customer_folder');
    } else {
        $id = randHash();
        WC()->session->set('customer_folder', $id);
    }
    $uploaddir = '../user_print/contact/' . $id . '/';
    $files = [];

    if (!is_dir($uploaddir)) mkdir($uploaddir, 0777);

    foreach ($_FILES as $file) {
        $name = WC()->session->get_customer_id() . '_' . $file['name'];

        if (move_uploaded_file($file['tmp_name'], $uploaddir . $name)) {
            $files[] = $file;
        } else {
            $error = true;
        }
    }

    $data = $error ? array('error' => 'Ошибка загрузки файлов.') : array('files' => $files);

    echo json_encode($data);

    wp_die();
}

function post_delete_file() {
    $delete_item = unlink('../user_print/' . $_POST['file']);
    if ( $delete_item == true ) {
        $status = 'deleted';
    }

    wp_die(json_encode($status));
}

function get_cart_total(){
    preg_match('/([0-9.]+)<\/bdi>/m', WC()->cart->get_total(), $total);

    echo $total[1];
    wp_die();
}

function post_checkout_user() {
    echo json_encode(['test'=>'text']);
    wp_die();
}

function get_states() {
    $states = WC()->countries->get_states($_GET['country']);
    echo json_encode($states);
    wp_die();
}

function admin_product_get() {
    $product_name = $_POST['productName'];

    $include_ids = !empty($_POST['include']) ? array_map( 'absint', (array)wp_unslash($_POST['include'])) : array();
    $exclude_ids = !empty($_POST['exclude']) ? array_map( 'absint', (array)wp_unslash($_POST['exclude'])) : array();

    $exclude_types = array();
    if (!empty($_POST['exclude_type']) ) {
        // Support both comma-delimited and array format inputs.
        $exclude_types = wp_unslash($_POST['exclude_type'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        if (!is_array( $exclude_types)) {
            $exclude_types = explode(',', $exclude_types);
        }

        // Sanitize the excluded types against valid product types.
        foreach ($exclude_types as &$exclude_type) {
            $exclude_type = strtolower( trim( $exclude_type));
        }
        $exclude_types = array_intersect(
            array_merge(array( 'variation' ), array_keys(wc_get_product_types())),
            $exclude_types
        );
    }

    $data_store = WC_Data_Store::load('product');
    $ids = $data_store->search_products($product_name, '', true, false, 30, $include_ids, $exclude_ids);

    $products = array();
    foreach ($ids as $id) {
        $product_object = wc_get_product($id);

        if (!wc_products_array_filter_readable($product_object)) {
            continue;
        }

        $formatted_name = $product_object->get_formatted_name();
        $managing_stock = $product_object->managing_stock();

        if (in_array($product_object->get_type(), $exclude_types, true)) {
            continue;
        }

        if ($managing_stock && !empty($_POST['display_stock'])) {
            $stock_amount = $product_object->get_stock_quantity();
            /* Translators: %d stock amount */
            $formatted_name .= ' &ndash; '.sprintf(__('Stock: %d', 'woocommerce'), wc_format_stock_quantity_for_display($stock_amount, $product_object));
        }

        $products[$product_object->get_id()] = rawurldecode($formatted_name);
    }

    $status = [
        'status' => 'statusCheck',
        'products' => $products
    ];

    echo json_encode($status);
    wp_die();
}

function admin_generation_order() {
    global $wpdb;
    $data = $_POST['data'];
    $user_email = $_POST['mail'];

    $href_order = wp_generate_password(20, false);
    $dataJson = json_encode($data);

    $wpdb->query("INSERT INTO `wp_custom_order` (`href`, `data`) VALUES ('{$href_order}', '{$dataJson}');");

    $url = 'https://bannerprintingphoenix.com/checkout/?order='.$href_order;
    admin_send_order_on_mail($user_email, $data, $url);

    echo json_encode([
        'href' => $href_order
    ]);
    wp_die();
}

function admin_send_order_on_mail($user_email, $data, $order_href) {
    if (isset($user_email)) {
        $new_order = WC()->mailer()->get_emails();
        //Send user notification
        $new_order['WC_Email_Customer_Custom_Invoice']->trigger($user_email, $data, $order_href);
        //Send admin notification
        $new_order['WC_Email_Custom_Customer_Invoice']->trigger($data, $order_href);
    }
}

$action = ( isset( $_REQUEST['action'] ) ) ? $_REQUEST['action'] : '';

if ( is_user_logged_in() ) {
	// If no action is registered, return a Bad Request response.
	if ( ! has_action( "wp_ajax_{$action}" ) ) {
		wp_die( '0', 400 );
	}

	/**
	 * Fires authenticated Ajax actions for logged-in users.
	 *
	 * The dynamic portion of the hook name, `$action`, refers
	 * to the name of the Ajax action callback being fired.
	 *
	 * @since 2.1.0
	 */
	do_action( "wp_ajax_{$action}" );
} else {
	// If no action is registered, return a Bad Request response.
	if ( ! has_action( "wp_ajax_nopriv_{$action}" ) ) {
		wp_die( '0', 400 );
	}

	/**
	 * Fires non-authenticated Ajax actions for logged-out users.
	 *
	 * The dynamic portion of the hook name, `$action`, refers
	 * to the name of the Ajax action callback being fired.
	 *
	 * @since 2.8.0
	 */
	do_action( "wp_ajax_nopriv_{$action}" );
}
// Default status.
wp_die( '0' );
