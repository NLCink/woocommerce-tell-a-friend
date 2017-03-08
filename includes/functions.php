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

if (!function_exists('tell_a_frind_replace_string_template')):

    /**
     * Replace especial tags templates
     * 
     * $str_array = array(
     *      '{friend_cupom}' => 'cupom_123',
     *      '{validade_cupom}' => '05/04/2017',
     *      '{valor_desconto}' => '10%',
     *      '{email_friend}' => 'higor@gmail.com',
     *      '{email_my}' => 'maira@gmail.com'
     * );
     * 
     * @param array $str_array
     * @param mixed $template
     * @return mixed
     */
    function replace_especial_tags_templates(array $str_array, $template) {

        ob_start();
        include $template;
        $output_prev = ob_get_clean();

        return str_replace(array_keys($str_array), array_values($str_array), $output_prev);
 
    }

endif;