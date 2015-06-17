<?php
class SubmitPressSettings {

    public function register_settings() {
        add_option('submitpress_accept_as', 'sp_contribution');
        add_option('submitpress_confirm_accept_reject', '1');
        register_setting( 'submitpress', 'submitpress_accept_as', 'SubmitPressSettings::filter_post_types' );
        register_setting( 'submitpress', 'submitpress_confirm_accept_reject', 'intval' );
    }

    public static function filter_post_types( $string ) {
        $string = SubmitPressSettings::filter_string($string);
        if (in_array($string, get_post_types() )) {
            return $string;
        } else {
            return 'sp_contribution';
        }
    }

    public static function filter_string( $string ) {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }

}
