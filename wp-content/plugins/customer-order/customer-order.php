<?php
/**
 * Plugin Name: Customer order
 * Plugin URI: localhost.com/
 * Description: Плагин создания заказа для пользователя
 * Version: 1.0.0
 * Author: CodeDot
 * Author URI: https://codedot.by/
 * License: GPL2
 */

add_action('admin_menu', 'customer_order_register_admin_page');
function customer_order_register_admin_page() {
    add_menu_page(
//        'admin.php',
        'Add Customer Order',
        'Add Customer Order',
        'manage_categories',
        'customer-order',
        'customer_order_render_admin_page'
    );
}

function customer_order_render_admin_page() {
    include plugin_dir_path(__FILE__) . 'customer-order-page.php';
}