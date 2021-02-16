<form name="post" action="" method="post">

    <div class="welcome-panel">
        <h3 class="kiboo-block-title"><?=__( 'Import / Export Settings', 'kiboo' ); ?></h3>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="options"><?=__( 'Settings', 'kiboo' ); ?></label></th>
                    <td>
                        <textarea name="options" id="options" class="regular-text" rows="10"><?=get_option('kiboo_options') ?></textarea>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p class="submit">
        <input type="submit" name="save" id="save" class="button button-primary" value="<?=__( 'Overwrite Settings', 'kiboo' ); ?>">
        <br class="clear">
    </p>
</form>