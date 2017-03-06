<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
        <title><?php echo get_bloginfo('name', 'display'); ?></title>
    </head>
    <body style="background-color: #eee;">

        <div style="width: 600px; display: table; margin: 0 auto;">

            <?php
            if (get_option('taf_thumb_header', false) != false):
                ?>
                <div style="width: 100%; padding: 10px; background-color:#5e1e14; ">
                    <a target="_blank" href="<?php echo get_site_url(); ?>">
                        <img src="<?php echo get_option('taf_thumb_header'); ?>">
                    </a>
                </div>
                <?php
            endif;
            ?>

            <div style="width: 100%; background-color: #fff; padding: 10px;">
                <?php
                echo get_option('taf_cupom_email');
                ?>
            </div>

            <div style="width: 100%; background-color: #f8f8f8; padding: 10px; text-align: center; border-top: dotted 1px #ccc;">
                <a target="_blank" style="color: #5e1e14; font-size: 0.750em;" href="<?php echo get_site_url(); ?>">
                    <?php echo bloginfo('site-name'); ?>
                </a>
            </div>

        </div>

    </body>
</html>