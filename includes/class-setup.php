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
        add_action('admin_menu', array($this, 'taf_admin_menu'));
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
     * Admin menu page
     */
    public function taf_admin_menu() {
        add_menu_page(__('Indique um amigo', 'woocommerce-tell-a-friend'), __('Indique um amigo', 'woocommerce-tell-a-friend'), 'manage_options', 'tell-a-friend', array($this, 'taf_admin_template'));
    }

    /**
     * Admin template
     */
    public function taf_admin_template() {
        include plugin_dir_url(__DIR__) . '/includes/admin-templates/tell-a-friend.php';
    }

    /**
     * Incluir scripts no painel admin
     */
    public function taf_admin_enqueue_script() {

        // css
        wp_enqueue_style('datatables', plugin_dir_url(__DIR__) . 'assets/plugins/datatables/css/jquery.dataTables.min.css');

        // js 
        wp_enqueue_script('datatables', plugin_dir_url(__DIR__) . 'assets/plugins/datatables/js/jquery.dataTables.min.js', array('jquery'), 'v1.10.12', true);
    }

    /**
     * Incluir scripts no tema
     */
    public function taf_theme_enqueue_script() {

        $l10n = array(
            't_a_f_ajax_url' => admin_url('admin-ajax.php')
        );

        // js
        wp_enqueue_script('taf-functions', plugin_dir_url(__DIR__) . 'assets/js/functions.js', array('jquery'), '1.0.0', true);

        wp_enqueue_script('additional-methods', plugin_dir_url(__DIR__) . 'assets/plugins/jquery-validation/dist/additional-methods.min.js', array('jquery'), 'v1.15.0', true);
        wp_enqueue_script('jquery-validation', plugin_dir_url(__DIR__) . 'assets/plugins/jquery-validation/dist/jquery.validate.min.js', array('jquery'), 'v1.15.0', true);

        // ajax request
        wp_enqueue_script('taf-theme-script', plugin_dir_url(__DIR__) . 'assets/js/theme-script.js', array('jquery'), '1.0.0', true);
        wp_localize_script('taf-theme-script', 't_a_f_obj', $l10n);
    }

}

new Setup($upgrade);
