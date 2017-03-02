<?php

if (!defined('ABSPATH')):
    exit;
endif;

class Setup {

    private $upgrade;

    public function __construct($upgrade) {

        $this->upgrade = $upgrade;

        register_activation_hook(woo_file(), array($this, 'tell_a_friend_migration'));

        add_action('wp_enqueue_scripts', array($this, 'taf_theme_enqueue_script'));
    }

    /**
     * Migration (table) tell_a_friend
     * 
     * @global object $wpdb
     */
    public function tell_a_friend_migration() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'tell_a_friend';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		my_email varchar(255) NOT NULL,
		friend_email varchar(255) NOT NULL,
		cupom varchar(255) NOT NULL,
		status int(1) NOT NULL,
		create_at datetime NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

        require_once($this->upgrade);
        dbDelta($sql);
    }

    /**
     * Incluir scripts no tema
     */
    public function taf_theme_enqueue_script() {
        wp_enqueue_script('functions', plugin_dir_url(__DIR__) . 'assets/js/functions.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('theme-script', plugin_dir_url(__DIR__) . 'assets/js/theme-script.js', array('jquery'), '1.0.0', true);
    }

}

new Setup($upgrade);
