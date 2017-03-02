<?php
/**
 * The template for displaying form Tell a friend
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-tell-a-friend.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if (!defined('ABSPATH')):
    exit;
endif;
?>

<div id="wrap-form-tell-a-friend">

    <form method="POST" name="form-tell-a-friend" id="form-tell-a-friend">

        <?php
        wp_nonce_field('action_tell_a_friend', 'nonce_tell_a_friend');
        ?>

        <div class="wrap-input-tell-a-friend">
            <input type="email" name="my_email" placeholder="Seu e-mail">
        </div>

        <div class="wrap-input-tell-a-friend">
            <input type="email" name="friend_email" placeholder="E-mail do seu amigo">
        </div>

        <div class="wrap-input-tell-a-friend">
            <button type="button" id="send-tell-a-friend">
                Indicar Amigos
            </button>
        </div>

    </form>

</div><!--/#wrap-form-tell-a-friend -->