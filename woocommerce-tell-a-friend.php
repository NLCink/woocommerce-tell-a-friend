<?php

/*
  Plugin Name: WooCommerce Tell A Friend
  Plugin URI:  https://github.com/higorch/
  Description: Indique um amigo e ganhe cupom de desconto
  Version:     1.0.0
  Author:      Higor Christian
  Author URI:  https://github.com/higorch/
  Text Domain: woocommerce-tell-a-friend
  License:     GPL2
 */

if (!defined('ABSPATH')):
    exit;
endif;

/**
 * Check if WooCommerce is active
 * */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    if (!function_exists('woo_file')):

        function woo_file() {
            return __FILE__;
        }

    endif;

    $upgrade = ABSPATH . 'wp-admin/includes/upgrade.php';

    include plugin_dir_path(__FILE__) . 'vendor/autoload.php';
    include plugin_dir_path(__FILE__) . 'includes/functions.php';
    include plugin_dir_path(__FILE__) . 'includes/class-setup.php';
    include plugin_dir_path(__FILE__) . 'includes/class-templates.php';
    include plugin_dir_path(__FILE__) . 'includes/class-proccess.php';
}