<form name="post" action="" method="post">

    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Customize WordPress', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="show_admin_bar"><?=__( 'Show Admin Bar', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[show_admin_bar]" id="show_admin_bar" type="radio" value="1" <?=(kibooGetOption('show_admin_bar') == 1) ? 'checked' : ''; ?>> <?=__( 'Yes', 'kiboo' ); ?>&nbsp;
                        <input name="settings[show_admin_bar]" id="show_admin_bar" type="radio" value="0" <?=(kibooGetOption('show_admin_bar') == 0) ? 'checked' : ''; ?>> <?=__( 'No', 'kiboo' ); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="custom_login"><?=__( 'Custom Login', 'kiboo' ); ?></label></th>
                    <td>
                        <input name="settings[custom_login]" id="custom_login" type="radio" value="1" <?=(kibooGetOption('custom_login') == 1) ? 'checked' : ''; ?>> <?=__( 'Yes', 'kiboo' ); ?>&nbsp;
                        <input name="settings[custom_login]" id="custom_login" type="radio" value="0" <?=(kibooGetOption('custom_login') == 0) ? 'checked' : ''; ?>> <?=__( 'No', 'kiboo' ); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p class="submit">
        <input name="save" id="save" type="submit" class="button button-primary" value="<?=__( 'Save', 'kiboo' ); ?>">
        <br class="clear">
    </p>
</form>