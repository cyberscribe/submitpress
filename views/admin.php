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
            <th scope="row"><label for="id_submitpress_option_1"><?php _e('SubmitPress Option 1','submitpress'); ?>: </span>
            </label></th>
        <td><input type="text" id="id_submitpress_option_1" name="submitpress_option_1" value="<?php echo get_option('submitpress_option_1'); ?>" size="40" /></td>
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
