<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

function select_discount_type($type) {

    if (get_option('taf_discount_type') == $type):
        return 'selected';
    endif;
}

$settings_wp_editor = array();
?>

<div class="wrap">

    <h2>
        <?php echo esc_html(get_admin_page_title()); ?>
    </h2>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">

                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">

                        <h2>
                            <span>
                                <?php _e('Indique um amigo, configurações', 'woocommerce-tell-a-friend'); ?>
                            </span>
                        </h2>

                        <div class="inside">

                            <form id="formTellAFriend" name="formTellAFriend" method="post" action="">

                                <div class="input-wrap-taf">

                                    <label>
                                        <?php _e('Tipo de desconto', 'woocommerce-tell-a-friend'); ?>

                                        <select name="discount_type" id="discount_type">

                                            <option <?php echo select_discount_type('fixed_cart'); ?> value="fixed_cart">
                                                <?php _e('Desconto no carrinho', 'woocommerce-tell-a-friend'); ?>
                                            </option>

                                            <option <?php echo select_discount_type('percent'); ?> value="percent">
                                                <?php _e('% Desconto no carrinho', 'woocommerce-tell-a-friend'); ?>
                                            </option>

                                            <option <?php echo select_discount_type('fixed_product'); ?> value="fixed_product">
                                                <?php _e('Desconto no produto', 'woocommerce-tell-a-friend'); ?>
                                            </option>

                                            <option <?php echo select_discount_type('percent_product'); ?> value="percent_product">
                                                <?php _e('% Desconto no produto', 'woocommerce-tell-a-friend'); ?>
                                            </option>

                                        </select>
                                    </label>

                                </div>

                                <div class="input-wrap-taf">
                                    <label>
                                        <?php _e('Valor do cupom', 'woocommerce-tell-a-friend'); ?>
                                        <input class="large-text" type="number" min="0" name="coupon_amount" id="coupon_amount" placeholder="<?php _e('Valor do cupom', 'woocommerce-tell-a-friend'); ?>" value="<?php echo get_option('taf_coupon_amount'); ?>">
                                    </label>
                                </div>

                                <div class="input-wrap-taf">
                                    <label>
                                        <?php _e('Limite de uso por cupom', 'woocommerce-tell-a-friend'); ?>
                                        <input class="large-text" type="number" min="0" name="usage_limit" id="usage_limit" placeholder="<?php _e('Limite de uso por cupom', 'woocommerce-tell-a-friend'); ?>" value="<?php echo get_option('taf_usage_limit'); ?>">
                                    </label>
                                </div>

                                <div class="input-wrap-taf">
                                    <label>
                                        <?php _e('Dias de validade', 'woocommerce-tell-a-friend'); ?>
                                        <input class="large-text" type="number" min="0" name="days_valid" id="days_valid" placeholder="<?php _e('Dias de validade', 'woocommerce-tell-a-friend'); ?>" value="<?php echo get_option('taf_days_valid'); ?>">
                                    </label>
                                </div>

                                <div class="input-wrap-taf-12">
                                    <h3>
                                        <?php _e('E-mail enviado para quem indicou, dizer que seu amigo foi indicado com sucesso', 'woocommerce-tell-a-friend'); ?>
                                    </h3>
                                    <?php
                                    wp_editor(get_option('taf_my_email'), 'taf_my_email', $settings_wp_editor);
                                    ?>
                                </div>

                                <div class="input-wrap-taf-12">
                                    <h3>
                                        <?php _e('E-mail enviado para o amigo indicado, explicações de como deve proceder para seu amigo ganhar cupom de desconto', 'woocommerce-tell-a-friend'); ?>
                                    </h3>
                                    <?php
                                    wp_editor(get_option('taf_friend_email'), 'taf_friend_email', $settings_wp_editor);
                                    ?>
                                </div>

                                <div class="input-wrap-taf-12">
                                    <h3>
                                        <?php _e('E-mail enviado para o amigo com o cupom de desconto, explicações da válidade do cupom e como usar', 'woocommerce-tell-a-friend'); ?>
                                    </h3>
                                    <?php
                                    wp_editor(get_option('taf_cupom_email'), 'taf_cupom_email', $settings_wp_editor);
                                    ?>
                                </div>

                            </form>

                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->
                </div>
                <!-- .meta-box-sortables .ui-sortable -->

            </div>
            <!-- post-body-content -->

            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">

                <div class="meta-box-sortables">
                    <div class="postbox">

                        <div class="inside">
                            <p>
                                <input form="formTellAFriend" type="submit" id="send-option" name="send-option" class="button-primary" value="<?php _e('Salvar', 'woocommerce-tell-a-friend'); ?>">
                            </p>
                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->
                </div>
                <!-- .meta-box-sortables -->

                <div class="meta-box-sortables">
                    <div class="postbox">

                        <div class="inside">
                            <p>
                                <?php
                                if (get_option('taf_thumb_header', false) != false):
                                    echo "<img id='image-attachment' src='" . get_option('taf_thumb_header') . "'>";
                                else:
                                    echo '<div id="image"></div>';
                                endif;
                                ?>
                                <a href="#" id="taf-wp-midia">Selecionar imagem para o cabeçalho dos e-mails</a>
                                <input form="formTellAFriend" type="hidden" id="taf_thumb_header" name="taf_thumb_header" class="" value="<?php echo get_option('taf_thumb_header'); ?>">
                            </p>
                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->
                </div>
                <!-- .meta-box-sortables -->


                <div class="meta-box-sortables">
                    <div class="postbox">

                        <div class="inside">
                            <p>
                                <input form="formTellAFriend" class="large-text" type="text" name="taf_remetente_title" value="<?php echo get_option('taf_remetente_title'); ?>" placeholder="<?php _e('Titulo e-mail remetente', 'woocommerce-tell-a-friend'); ?>">
                            </p>
                            <p>
                                <input form="formTellAFriend" class="large-text" type="text" name="taf_remetente_email" value="<?php echo get_option('taf_remetente_email'); ?>" placeholder="<?php _e('E-mail remetente', 'woocommerce-tell-a-friend'); ?>">
                            </p>
                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->
                </div>
                <!-- .meta-box-sortables -->



            </div>
            <!-- #postbox-container-1 .postbox-container -->

        </div>
        <!-- #post-body .metabox-holder .columns-2 -->

        <br class="clear">
    </div>
    <!-- #poststuff -->

</div> <!-- .wrap -->