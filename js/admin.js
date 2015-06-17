jQuery(document).ready(function() {
    $ = jQuery;
    $('#sp_actions button').on('click',function(e) {
        if ( jQuery('#submitpress_confirm_accept_reject').val() == '1' && $(this).attr('data-confirm') ) {
            var resp = window.confirm( $(this).attr('data-confirm') );
            if (resp) {
                $('#sp_action').val( $(this).attr('id') );
                return true;
            } else {
                $('#sp_action').val('');
                return false;
            }
        } else {
            $('#sp_action').val( $(this).attr('id') );
            return true;
        }
    });
});
