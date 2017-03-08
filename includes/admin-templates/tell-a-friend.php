<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">

    <h2>
        <?php echo esc_html(get_admin_page_title()); ?>
    </h2>

    <div style="background-color: #fff; padding: 10px; margin-top: 20px;">

        <table id="table-tell-a-friend">

            <thead>
                <tr>
                    <th width="25%" align="left">E-mail Indicador</th>
                    <th width="25%" align="left">E-mail Indicado</th>
                    <th width="15%">Cupom</th>
                    <th width="10%">Status</th>
                    <th width="20%">Cadastrado</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th width="25%" align="left">E-mail Indicador</th>
                    <th width="25%" align="left">E-mail Indicado</th>
                    <th width="15%">Cupom</th>
                    <th width="10%">Status</th>
                    <th width="20%">Cadastrado</th>
                </tr>
            </tfoot>

        </table>

    </div><!-- /.table-list -->

</div><!-- /.wrap -->