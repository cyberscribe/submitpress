<?php
/*
Plugin Name: SubmitPress
Plugin URI: https://wordpress.org/plugins/submitpress/
Description: 
Author: Robert Peake
Version: 0.1a
Author URI: http://www.robertpeake.com/
Text Domain: submitpress
Domain Path: /languages/
*/

if ( ! defined( 'WPINC' ) ) {
    die();
}

class SubmitPress {

    private static $instance;
    private $permissions;
    private $content;
    private $settings;

    public static function init() {
        if( !is_object(self::$instance) ) {
            spl_autoload_register('SubmitPress::__autoload');
            self::$instance = new SubmitPress;
            register_activation_hook( __FILE__, array(self::$instance, 'activate' ) );
            register_deactivation_hook( __FILE__, array(self::$instance, 'deactivate') );
        }
        return self::$instance;
    }

    public static function __autoload( $ClassName ) {
        if (!class_exists($ClassName)) {
            $file = plugin_dir_path( __FILE__ ) . 'models/' . SubmitPress::cc2_($ClassName) . '.php';
            if (file_exists($file)) {
                require_once($file);
            }
        }
    }

    public static function cc2_($input) { //camel case to underscore conversion
          preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
          $parts = $matches[0];
          array_map('strtolower',$parts);
          return implode('_', $parts);
    }

    public function __construct() {
        /* create model instances */
        $this->permissions = new SubmitPressPermissions();
        $this->content = new SubmitPressContent();
        $this->settings = new SubmitPressSettings();

        /* general setup */
        add_action( 'init', array($this->content, 'create_custom_post_types' ));
        add_action( 'plugins_loaded', array($this, 'load_textdomain'));

        /* login */
        add_action('login_form',array($this, 'login_form'));

        /* public */
        add_action('enqueue_scripts', array($this, 'register_enqueue_scripts_css'));

        /* admin */
        add_action( 'admin_menu', array($this, 'register_menu_page' ));
        add_action( 'admin_init', array($this, 'register_settings' ));
        add_action( 'admin_init', array($this, 'create_custom_admin_permissions'));
        add_action('admin_enqueue_scripts', array($this, 'register_enqueue_admin_scripts_css'));
        add_action( 'pre_get_posts', array($this,'filter_submissions_by_author') );
    }

    public function activate() {
        $this->content->create_custom_post_types();
        $this->create_new_roles();
        flush_rewrite_rules();
    }

    public function deactivate() {
        $this->delete_new_roles();
        flush_rewrite_rules();
    }


    public function create_custom_admin_permissions() {
        $admins = get_role( 'administrator' );
        $admins->add_cap( 'edit_sp_submission' ); 
        $admins->add_cap( 'edit_sp_submissions' ); 
        $admins->add_cap( 'edit_others_sp_submissions' ); 
        $admins->add_cap( 'publish_sp_submissions' ); 
        $admins->add_cap( 'read_sp_submission' ); 
        $admins->add_cap( 'read_private_sp_submissions' ); 
        $admins->add_cap( 'delete_sp_submission' ); 
        $admins->add_cap('manage_sp_issues' );
        $admins->add_cap('edit_sp_submissions' );
    }

    private function create_new_roles() {
        $this->delete_new_roles();
        add_role( 'sp_submitter', __( 'Submitter' ),
                   array(
                        'edit_sp_submission' => true,
                        'edit_sp_submissions' => true,
                        'create_sp_submissions' => true,
                        'read' => true,
                   )
        );
    }

    private function delete_new_roles() {
        remove_role('sp_submitter');
    }

    function filter_submissions_by_author( $wp_query_obj ) {
        global $current_user, $pagenow;
        get_currentuserinfo();

        if(is_admin() ) {
            if( is_a( $current_user, 'WP_User') && 
                'edit.php' == $pagenow &&  
                'sp_submission' == $wp_query_obj->query['post_type'] ) {
                if( !current_user_can( 'edit_others_sp_submissions' ) ) {
                    $wp_query_obj->set('author', $current_user->ID );
                }
            }
        }
    }


    public function load_textdomain() {
        load_plugin_textdomain( 'submitpress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    public function register_menu_page(){
        add_options_page( __('SubmitPress Options','submitpress'), __('SubmitPress','submitpress'), 'manage_options', plugin_dir_path(  __FILE__ ).'views/admin.php');
    }

    public function register_settings() {
        add_option('submitpress_option_1', '');
        register_setting( 'submitpress', 'submitpress_option_1', 'SubmitPress::filter_string' );

    }

    public static function filter_string( $string ) {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }

    public function register_enqueue_scripts_css() {
        wp_register_style('submitpress_css', plugin_dir_url( __FILE__ ) . 'css/style.css');
        wp_enqueue_style('submitpress_css');
        wp_register_script('submitpress_js', plugin_dir_url( __FILE__ ) . 'js/main.js');
        wp_enqueue_script('submitpress_js');
    }

    public function register_enqueue_admin_scripts_css() {
    }

    public function login_form() {
        // display Twitter, Fb, etc.
    }

}
SubmitPress::init();
