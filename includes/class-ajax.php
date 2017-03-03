<?php

if (!defined('ABSPATH')):
    exit;
endif;

class Ajax {

    public function __construct() {
        add_action('wp_ajax_save_tell_a_friend', array($this, 'action_save_tell_a_friend'));
        add_action('wp_ajax_nopriv_save_tell_a_friend', array($this, 'action_save_tell_a_friend'));
    }

    public function action_save_tell_a_friend() {
        
    }

}

new Ajax();
