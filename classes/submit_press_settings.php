<?php
class SubmitPressSettings {

    public function register_settings() {
        add_option('submitpress_option_1', '');
        register_setting( 'submitpress', 'submitpress_option_1', 'SubmitPressSettings::filter_string' );

    }

    public static function filter_string( $string ) {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }

}
