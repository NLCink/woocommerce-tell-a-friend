<?php

if (!defined('ABSPATH')):
    exit;
endif;

class Setup {

    private $upgrade;

    public function __construct($upgrade) {

        $this->upgrade = $upgrade;

        register_activation_hook(woo_file(), array($this, 'tell_a_friend_migration'));
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

}

new Setup($upgrade);
