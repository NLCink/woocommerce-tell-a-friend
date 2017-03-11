<?php

if (!defined('ABSPATH')):
    exit;
endif;

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\MySQL;

class Proccess {

    public function __construct() {

        add_action('wp_ajax_save_tell_a_friend', array($this, 'action_save_tell_a_friend'));
        add_action('wp_ajax_nopriv_save_tell_a_friend', array($this, 'action_save_tell_a_friend'));

        add_action('wp_ajax_list_tell_a_friend', array($this, 'action_list_tell_a_friend'));
        add_action('wp_ajax_nopriv_list_tell_a_friend', array($this, 'action_list_tell_a_friend'));

        add_action('woocommerce_order_status_changed', array($this, 'taf_order_status_processing'), 10);
    }

    /**
     * Gerar cupom de desconto quando o amigo indicado concluir uma compra
     * e enviar o email para o amigo que indicou com o cupom de desconto
     * 
     * @param int $order_id
     */
    public function taf_order_status_processing($order_id) {

        $order = wc_get_order($order_id);

        // E-mail do pedido que esta com o status processando...
        // comparar para ver se existe ele indicado por um amigo
        $get_address = $order->get_address();
        $email_order = $get_address['email'];
        $friends_data = $this->my_friend_completed_order($email_order);

        if (!empty($friends_data) && $order->get_status() == 'processing'):

            $email_explode = explode('@', $email_order);
            $codigo = strtoupper($email_explode[0]) . '_AMIGO';

            $this->taf_generate_cupom($codigo);
            $expiry_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + ' . get_option('taf_days_valid') . ' days'));

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: ' . get_option('taf_remetente_title') . ' <' . get_option('taf_remetente_email') . '>'
            );

            $attachments = "";

            $str_array = array(
                '{friend_cupom}' => $codigo,
                '{validade_cupom}' => date('d-m-Y H:i:s', strtotime($expiry_date)),
                '{valor_desconto}' => get_option('taf_coupon_amount', '10'),
                '{email_friend}' => $email_order
            );

            $message = replace_especial_tags_templates($str_array, $this->get_template_email_tell_a_friend_cupom());
            wp_mail($friends_data['my_email'], 'BEERS4CHEERS - VOCÊ GANHOU UM CUPOM!', $message, $headers, $attachments);

            $this->update_status_cupom($codigo, $email_order);

        endif;
    }

    /**
     * Atualiza os dados do amigo indicado quando ele conclui o pedido
     * 
     * @global object $wpdb
     * @param string $cupom
     * @param string $friend_email
     */
    public function update_status_cupom($cupom, $friend_email) {

        global $wpdb;

        $table = $wpdb->prefix . 'tell_a_friend';

        $data = array(
            'cupom' => $cupom,
            'status' => 1
        );

        $where = array('friend_email' => $friend_email);
        $format = array('%s', '%d');
        $where_format = array('%s');

        $wpdb->update($table, $data, $where, $format, $where_format);
    }

    /**
     * Verifica se o amigo indicado realizou a compra
     * 
     * @global object $wpdb
     * @param string $email_order
     * @return boolean false se o amigo indicado não realizou compra
     */
    public function my_friend_completed_order($email_order) {

        global $wpdb;

        $table = $wpdb->prefix . 'tell_a_friend';

        $result = $wpdb->get_row("SELECT * FROM {$table} WHERE friend_email = '{$email_order}' AND status = 0", ARRAY_A);

        if (is_null($result)):
            return false;
        else:
            return $result;
        endif;
    }

    /**
     * Listar amigos indicado
     * 
     * @global object $wpdb
     */
    public function action_list_tell_a_friend() {

        global $wpdb;

        date_default_timezone_set('America/Sao_Paulo');

        $table = $wpdb->prefix . 'tell_a_friend';

        $config = [
            'host' => DB_HOST,
            'port' => '',
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'database' => DB_NAME
        ];

        $dt = new Datatables(new MySQL($config));
        $dt->query("SELECT my_email, friend_email, cupom, status, create_at FROM {$table}");

        $dt->edit('create_at', function($data) {
            // return an edit link.
            return date('d-m-Y H:i:s', strtotime($data['create_at']));
        });

        $dt->edit('status', function($data) {
            // return an edit link.
            return ($data['status'] == 1 ? 'Cupom Liberado' : 'Cupom Pendente');
        });

        echo $dt->generate();

        die;
    }

    /**
     * Salvar amigo indicado
     * 
     * @global object $wpdb
     */
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

                    $headers = array(
                        'Content-Type: text/html; charset=UTF-8',
                        'From: ' . get_option('taf_remetente_title') . ' <' . get_option('taf_remetente_email') . '>'
                    );
                    $attachments = "";

                    $str_array = array(
                        '{email_my}' => $my_email
                    );

                    $message_01 = replace_especial_tags_templates($str_array, $this->get_template_email_tell_a_friend());
                    wp_mail($friend_email, 'BEERS4CHEERS - VOCÊ FOI INDICADO!', $message_01, $headers, $attachments);

                    $str_array = array(
                        '{email_friend}' => $friend_email
                    );

                    $message_02 = replace_especial_tags_templates($str_array, $this->get_template_email_tell_a_friend_success());
                    wp_mail($my_email, 'BEERS4CHEERS - OBRIGADO POR NÓS INDICAR!', $message_02, $headers, $attachments);

                    echo '1';

                else:
                    echo 'Não foi possivel indicar, por favor entre em contato com a gente.';
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
     * @return mixed
     */
    public function get_template_email_tell_a_friend() {
        return woo_plugin_dir_path(__DIR__) . 'templates/emails/email-tell-a-friend.php';
    }

    /**
     * Template e-email quando o amigo indicado comprar, envia o cupom com de 
     * desconto
     * 
     * @return mixed
     */
    public function get_template_email_tell_a_friend_cupom() {
        return woo_plugin_dir_path(__DIR__) . 'templates/emails/email-tell-a-friend-cupom.php';
    }

    /**
     * Template e-email quando o amigo indicado comprar, envia o cupom com de 
     * desconto
     * @return mixed
     */
    public function get_template_email_tell_a_friend_success() {
        return woo_plugin_dir_path(__DIR__) . 'templates/emails/email-tell-a-friend-success.php';
    }

    /**
     * Criar cupom de desconto 
     * 
     * @param string $codigo codigo do cupom de desconto
     */
    public function taf_generate_cupom($codigo) {

        // Code
        $coupon_code = $codigo;

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
