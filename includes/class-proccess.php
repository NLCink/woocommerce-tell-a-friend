<?php

if (!defined('ABSPATH')):
    exit;
endif;

class Proccess {

    public function __construct() {
        add_action('wp_ajax_save_tell_a_friend', array($this, 'action_save_tell_a_friend'));
        add_action('wp_ajax_nopriv_save_tell_a_friend', array($this, 'action_save_tell_a_friend'));

        $this->get_template_email_tell_a_friend_cupom();
    }

    public function action_save_tell_a_friend() {

        global $wpdb;

        date_default_timezone_set('America/Sao_Paulo');

        $table_tell_a_friend = $wpdb->prefix . 'tell_a_friend';

        $my_email = filter_input(INPUT_POST, 'my_email', FILTER_VALIDATE_EMAIL);
        $friend_email = filter_input(INPUT_POST, 'friend_email', FILTER_VALIDATE_EMAIL);
        $nonce_tell_a_friend = filter_input(INPUT_POST, 'nonce_tell_a_friend');

        if (wp_verify_nonce($nonce_tell_a_friend, 'action_tell_a_friend')):

            if (!$my_email):
                echo 'Seu e-mail é inválido.';
            elseif (!$friend_email):
                echo 'O e-mail do seu amigo é inválido.';
            elseif ($this->data_exist($table_tell_a_friend, 'friend_email', $friend_email)):
                echo 'O e-mail desse amigo já recebeu indicação, obrigado por indicar o BEERS4CHEERS para seus amigos';
            else:

                $data = array(
                    'my_email' => $my_email,
                    'friend_email' => $friend_email,
                    'cupom' => 'Sem cupom',
                    'status' => 0,
                    'create_at' => date('Y-m-d H:i:s')
                );

                $format = array('%s', '%s', '%s', '%s', '%s');
                $result = $wpdb->insert($table_tell_a_friend, $data, $format);

                if ($result):
                    echo 'OK';
                else:
                    echo 'Fail';
                endif;

            endif;

        endif;

        die;
    }

    /**
     * Verifica se o dado existe
     * 
     * @global object $wpdb
     * @param string $table
     * @param string $col
     * @param string $input
     * @return int
     */
    public function data_exist($table, $col, $input) {

        global $wpdb;

        $result = $wpdb->get_row($wpdb->prepare("SELECT {$col} FROM {$table} WHERE {$col}=%s", $input));

        if (!empty($result)):
            return 1;
        endif;

        return 0;
    }

    /**
     * Template e-email quando o amigo indicar
     * 
     * @param string $my_email
     * @param string $email_friend
     * @param string $cupom
     * @return mixed
     */
    public function get_template_email_tell_a_friend($my_email = 'hhh', $email_friend = 'vvv') {
        include woo_plugin_dir_path(__DIR__) . 'templates/emails/email-tell-a-friend.php';
    }

    /**
     * Template e-email quando o amigo indicado comprar, envia o cupom com de 
     * desconto
     * 
     * @param string $my_email
     * @param string $email_friend
     * @param string $cupom
     * @return mixed
     */
    public function get_template_email_tell_a_friend_cupom($my_email = 'hhh', $email_friend = 'vvv', $cupom = '') {
        include woo_plugin_dir_path(__DIR__) . 'templates/emails/email-tell-a-friend-cupom.php';
    }

    /**
     * Criar cupom de desconto 
     * 
     * @param string $codigo codigo do cupom de desconto
     */
    public function taf_generate_cupom($codigo) {

        // Code
        $coupon_code = $codigo;

        // Amount
        $amount = '10';

        // Type: fixed_cart, percent, fixed_product, percent_product
        $discount_type = 'percent';

        // Expiry date
        $expiry_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + ' . get_option('taf_days_valid') . ' days'));

        $coupon = array(
            'post_title' => $coupon_code,
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon'
        );

        $new_coupon_id = wp_insert_post($coupon);

        // Add meta
        update_post_meta($new_coupon_id, 'discount_type', get_option('taf_discount_type', 'percent'));
        update_post_meta($new_coupon_id, 'coupon_amount', get_option('taf_coupon_amount', '10'));
        update_post_meta($new_coupon_id, 'individual_use', 'yes');
        update_post_meta($new_coupon_id, 'product_ids', '');
        update_post_meta($new_coupon_id, 'exclude_product_ids', '');
        update_post_meta($new_coupon_id, 'usage_limit', get_option('taf_usage_limit', '1'));
        update_post_meta($new_coupon_id, 'expiry_date', $expiry_date);
        update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
        update_post_meta($new_coupon_id, 'free_shipping', 'no');
    }

}

new Proccess();
