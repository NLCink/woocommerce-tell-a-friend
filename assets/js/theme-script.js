(function ($) {

    var $my_email = 'input[name="my_email"]';
    var $friend_email = 'input[name="friend_email"]';
    var $form = 'form[name="form-tell-a-friend"]';
    var $btnSend = '#send-tell-a-friend';

    $($btnSend).on('click', function (e) {

        e.preventDefault();

        $($form).validate();

        $($my_email).rules("add", {
            required: true,
            email: true,
            messages: {
                required: "Por favor, informe o seu e-mail.",
                email: "Por favor, informe um e-mail válido."
            }
        });

        $($friend_email).rules("add", {
            required: true,
            email: true,
            messages: {
                required: "Por favor, informe o e-mail do seu amigo.",
                email: "Por favor, informe um e-mail válido."
            }
        });

        if ($($form).valid()) {

            var $formData = new FormData();
            $formData.append('my_email', $($my_email).val());
            $formData.append('friend_email', $($friend_email).val());
            $formData.append('nonce_tell_a_friend', $('input[name="nonce_tell_a_friend"]').val());
            $formData.append('action', 'save_tell_a_friend');

            $.ajax({
                type: 'POST',
                url: t_a_f_obj.t_a_f_ajax_url,
                processData: false,
                contentType: false,
                cache: false,
                data: $formData,
                beforeSend: function () {
                    console.log('proccess...');
                },
                success: function (data) {
                    console.log(data);
                }

            });


        }


    });


})(jQuery);