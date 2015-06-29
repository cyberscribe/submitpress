<?php
/*
Plugin Name: SubmitPress
Plugin URI: https://wordpress.org/plugins/submitpress/
Description: Manage Your User-Contributed Online Magazine with Wordpress
Author: Robert Peake
Version: 0.1
Author URI: http://www.robertpeake.com/
Text Domain: submitpress
Domain Path: /languages/
*/

if ( ! defined( 'WPINC' ) ) {
    die();
}

class SubmitPress {

    private static $instance;
    private $actions;
    private $permissions;
    private $content;
    private $admin;
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
            $file = plugin_dir_path( __FILE__ ) . 'classes/' . SubmitPress::cc2_($ClassName) . '.php';
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
        $this->admin = new SubmitPressAdmin();
        $this->db = new SubmitPressDb();

        /* general setup */
        $this->add_action( 'init', array($this->content, 'register_custom_content' ));
        $this->add_action( 'plugins_loaded', array($this->content, 'load_textdomain'));

        /* login */
        $this->add_action('login_form',array($this->permissions, 'login_form'));

        /* public */
        $this->add_action('enqueue_scripts', array($this->content, 'register_enqueue_scripts_css'));

        /* admin */
        $this->add_action( 'admin_menu', array($this->admin, 'admin_menu' ));
        $this->add_action( 'admin_init', array($this->settings, 'register_settings' ));
        $this->add_action( 'admin_init', array($this->permissions, 'add_custom_capabilities'));
        $this->add_action('admin_enqueue_scripts', array($this->content, 'register_enqueue_admin_scripts_css'));
        $this->add_action( 'pre_get_posts', array($this->permissions,'filter_submissions_by_author') );
        $this->add_action('post_submitbox_misc_actions', array($this->content,'inject_custom_submitbox_status'));
        $this->add_action('save_post', array($this->content, 'save_post'));
    }

    public function activate() {
        $this->content->register_custom_content();
        $this->permissions->add_new_roles();
        $this->db->create_tables();
        flush_rewrite_rules();
    }

    public function deactivate() {
        $this->permissions->remove_new_roles();
        flush_rewrite_rules();
    }

    public function run() {
        $this->add_all_actions();
    }

    private function add_action( $hook, $array, $priority = 10, $accepted_args = 1) {
        $this->actions[] = array(
                                'hook' => $hook,
                                'class' => $array[0],
                                'method' => $array[1],
                                'priority' => $priority,
                                'accepted_args' => $accepted_args
                          );
        add_action($hook, $array, $priority, $accepted_args);
    }

    private function add_all_actions() {
        $actions = apply_filters('sp_actions',$this->actions);
        foreach( $actions as $action) {
            $hook = $action['hook'];
            $array = array($action['class'],$action['method']);
            $priority = $action['priority'];
            $args = $action['accepted_args'];
            add_action($hook, $array, $priority, $args);
        }
    }
}
$submitpress = SubmitPress::init();
$submitpress->run();
