(function ($) {

    $.fn.taf_image_header_manager = function () {

        var $btn_open_wp_midia = '#taf-wp-midia';

        /**
         * Imagens geral, default
         */
        var $upload_images_main;

        $($btn_open_wp_midia).click(function (e) {

            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if ($upload_images_main) {
                $upload_images_main.open();
                return;
            }

            //Extend the wp.media object
            $upload_images_main = wp.media.frames.file_frame = wp.media({
                title: 'Selecione a imagem para o cabeçalho do e-mail',
                button: {
                    text: 'Definir imagem do cabeçalho do e-amil'
                },
                multiple: false
            });

            //When a file is selected, grab the URL and set it as the text field's value
            $upload_images_main.on('select', function () {

                var $attachment = $upload_images_main.state().get('selection').first().toJSON();
                var $image = '#image';
                var $taf_thumb_header = '#taf_thumb_header';
                var $image_attachment = '#image-attachment';

                if (jQuery($image_attachment).length) {
                    $($taf_thumb_header).val($attachment.url);
                    $($image_attachment).attr('src', $attachment.url);
                } else {
                    $($image).html('<img src="' + $attachment.url + '">');
                }

            });

            //Open the uploader dialog
            $upload_images_main.open();

        });

    };

})(jQuery);