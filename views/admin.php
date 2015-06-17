<?php
if (!is_admin()) {
    die();
}
?><div class="wrap">
<h2><?php _e('SubmitPress Options','submitpress'); ?></h2>
<form method="post" action="options.php">
<?php
echo settings_fields( 'submitpress' );
?>
<table class="form-table">
    <tr valign="top">
        <th scope="row"><label><?php _e('Accept as','submitpress'); ?>:</label></th>
        <td>
            <input type="radio" id="id_submitpress_accept_as_post" name="submitpress_accept_as" value="sp_contribution"
                <?php if ( get_option('submitpress_accept_as') === 'sp_contribution' ) echo 'checked="checked"'; ?> />
            <label for="id_submitpress_accept_as_post"><?php _e('New Contribution','submitpress'); ?></label>

            <input type="radio" id="id_submitpress_accept_as_sp_contribution" name="submitpress_accept_as" value="post"
                <?php if ( get_option('submitpress_accept_as') === 'post' ) echo 'checked="checked"'; ?> />
            <label for="id_submitpress_accept_as_sp_contribution"><?php _e('New Post','submitpress'); ?></label>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="id_submitpress_confirm_accept_reject"><?php _e('Prompt to confirm before accepting or rejecting', 'submitpress'); ?></label></th>
        <td>
            <input type="checkbox" id="id_submitpress_confirm_accept_reject" name="submitpress_confirm_accept_reject" value="1" <?php if(get_option('submitpress_confirm_accept_reject')) echo 'checked="checked"'; ?> />
        </td>
    </tr>
</table>
<p>
    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
</p>
</form>
<script>
(function($) {
})(jQuery);
</script>
