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
    // Put your plugin code here

    add_action('woocommerce_loaded', function () {

        $upgrade = ABSPATH . 'wp-admin/includes/upgrade.php';

        include plugin_dir_path(__FILE__) . 'includes/functions.php';
        include plugin_dir_path(__FILE__) . 'includes/class-setup.php';
        include plugin_dir_path(__FILE__) . 'includes/class-templates.php';
        include plugin_dir_path(__FILE__) . 'includes/class-proccess.php';

    });
}