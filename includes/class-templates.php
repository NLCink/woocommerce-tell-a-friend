<?php

if (!defined('ABSPATH')):
    exit;
endif;

class Templates {

    public function __construct() {
        add_shortcode('form_tell_a_friend', array($this, 'callback_form_tell_a_friend'));
    }

    /**
     * Template do formulário de indicar amigos
     */
    public function callback_form_tell_a_friend() {
        wc_get_template('global/form-tell-a-friend.php', array(), '', plugin_dir_path(__DIR__) . 'templates/');
    }

}

new Templates();
