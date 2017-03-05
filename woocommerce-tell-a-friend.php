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

$upgrade = ABSPATH . 'wp-admin/includes/upgrade.php';

if (!function_exists('woo_plugin_dir_path')):

    function woo_plugin_dir_path($file) {
        return plugin_dir_path($file);
    }

endif;

if (!function_exists('woo_plugin_dir_url')):

    function woo_plugin_dir_url($file) {
        return plugin_dir_url($file);
    }

endif;

if (!function_exists('woo_file')):

    function woo_file() {
        return __FILE__;
    }

endif;

include plugin_dir_path(__FILE__) . 'includes/class-setup.php';
include plugin_dir_path(__FILE__) . 'includes/class-templates.php';
include plugin_dir_path(__FILE__) . 'includes/class-proccess.php';
