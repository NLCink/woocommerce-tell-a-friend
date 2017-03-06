<?php

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