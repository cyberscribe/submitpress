<?php
class SubmitPressContent {

    private $statuses;
    private $post_types;
    private $taxonomies;

    public function __construct() {
        $this->statuses = array(
                              'accepted' => array(__('accepted','submitpress'), _n_noop('Accepted (%s)','Accepted (%s','submitpress')),
                              'rejected' => array(__('rejected','submitpress'), _n_noop('Rejected (%s)','Rejected (%s','submitpress')),
                              'withdrawn' => array(__('withdrawn','submitpress'), _n_noop('Withdrawn (%s)','Withdrawn (%s','submitpress')),
                              'flagged' => array(__('flagged','submitpress'), _n_noop('Flagged (%s)','Flagged (%s','submitpress')),
                             );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'submitpress', false, dirname(dirname( plugin_dir_path( __FILE__ ) ) ) . '/languages/' );
    }

    public function register_enqueue_scripts_css() {
        wp_register_style('submitpress_css', dirname(plugin_dir_url( __FILE__ )) . '/css/style.css');
        wp_enqueue_style('submitpress_css');
        wp_register_script('submitpress_js', dirname(plugin_dir_url( __FILE__ )) . '/js/main.js');
        wp_enqueue_script('submitpress_js');
    }

    public function register_enqueue_admin_scripts_css() {
        wp_register_style('submitpress_css', dirname(plugin_dir_url( __FILE__ )) . '/css/admin.css');
        wp_enqueue_style('submitpress_css');
        wp_register_script('submitpress_js', dirname(plugin_dir_url( __FILE__ )) . '/js/admin.js');
        wp_enqueue_script('submitpress_js');
    }

    public function register_menu_page(){
        add_options_page( __('SubmitPress Options','submitpress'), __('SubmitPress','submitpress'), 'manage_options', dirname(plugin_dir_path(  __FILE__ )).'/views/admin.php');
    }

    public function register_custom_content() {
        $this->post_types[] = 'sp_submission_item';
        register_post_type( 'sp_submission_item',
            array(
                'labels' => array(
                  'name' => __( 'Submission Items', 'submitpress' ),
                  'singular_name' => __( 'Submission Item', 'submitpress' )
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'submission_items'),
                'menu_icon' => 'dashicons-format-aside',
                'menu_position' => 20,
                'capability_type' => array('sp_submission_item','sp_submissions_item'),
                'capabilities' => array(
                    'edit_post' => 'edit_sp_submission_item',
                    'edit_posts' => 'edit_sp_submission_items',
                    'edit_others_posts' => 'edit_others_sp_submission_items',
                    'publish_posts' => 'publish_sp_submission_items',
                    'read_post' => 'read_sp_submission_item',
                    'read_private_posts' => 'read_private_sp_submission_items',
                    'delete_post' => 'delete_sp_submission_item',
                    'create_posts' => 'create_sp_submission_items',
                    'read' => 'read_sp_submission_item',
                ),
                'map_meta_cap' => true,
                'supports' => array('title','editor'),
                'register_meta_box_cb' => array($this, 'register_submission_item_meta_box'),
            )
        );
        $this->post_types[] = 'sp_contribution';
        register_post_type( 'sp_contribution',
            array(
                'labels' => array(
                  'name' => __( 'Contributions', 'submitpress' ),
                  'singular_name' => __( 'Contribution', 'submitpress' )
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'contributions'),
                'menu_icon' => 'dashicons-book-alt',
                'menu_position' => 20,
                'capability_type' => '',
                'capabilities' => array(
                    'edit_post' => 'edit_sp_contribution',
                    'edit_posts' => 'edit_sp_contributions',
                    'edit_others_posts' => 'edit_others_sp_contributions',
                    'publish_posts' => 'publish_sp_contributions',
                    'read_post' => 'read_sp_contributions',
                    'read_private_posts' => 'read_private_sp_contributions',
                    'delete_post' => 'delete_sp_contributions',
                ),
                'map_meta_cap' => true,
                'supports' => array('title','editor','author','custom-fields','thumbnail','excerpt','trackbacks','comments','revisions','page-attributes','post-formats'),
                'register_meta_box_cb' => array($this, 'register_contribution_meta_box'),
            )
        );
        $this->taxonomies[] = 'sp_issue';
        register_taxonomy('sp_issue', 'sp_contribution',
            array(  'label' => __('Issue','submitpress'),
                    'labels' => array(
                        'name' => 'Issues',
                        'singular_name' => 'Issue',
                        'all_items' => __('All Issues','submitpress'),
                        'edit_item' => __('Edit Issue','submitpress'),
                        'view_item' => __('View Issue','submitpress'),
                        'update_item' => __('View Issue','submitpress'),
                        'add_new_item' => __('Add New Issue','submitpress'),
                        'new_item_name' => __('New Issue Name','submitpress'),
                        'search_items' => __('Search Issues','submitpress')
                    ),
                    'show_tagcloud' => false,
                    'register_meta_box_cb' => array($this, 'register_issue_meta_box'),
                    'show_admin_column' => true,
                    'hierarchical' => true,
                    'rewrite' => array('slug' => 'issue'),
                    'capabilities' => array(
                        'manage_terms' => 'manage_issues',
                        'edit_terms' => 'manage_issues',
                        'delete_terms' => 'manage_issues',
                        'assign_terms' => 'edit_sp_contributions'
                    ),
                    'sort' => true,
            )
        );
        $this->taxonomies[] = 'sp_submission';
        register_taxonomy('sp_submission', 'sp_submission_item',
            array(  'label' => __('Submission','submitpress'),
                    'labels' => array(
                        'name' => 'Submissions',
                        'singular_name' => 'Submission',
                        'all_items' => __('All Submission','submitpress'),
                        'edit_item' => __('Edit Submission','submitpress'),
                        'view_item' => __('View Submission','submitpress'),
                        'update_item' => __('View Submission','submitpress'),
                        'add_new_item' => __('Add New Submission','submitpress'),
                        'new_item_name' => __('New Submission Name','submitpress'),
                        'search_items' => __('Search Submission','submitpress')
                    ),
                    'show_tagcloud' => false,
                    'register_meta_box_cb' => array($this, 'register_submission_meta_box'),
                    'show_admin_column' => true,
                    'hierarchical' => true,
                    'rewrite' => array('slug' => 'submission'),
                    'capabilities' => array(
                        'manage_terms' => 'manage_submission',
                        'edit_terms' => 'manage_submission',
                        'delete_terms' => 'manage_submission',
                        'assign_terms' => 'edit_sp_submission_items'
                    ),
                    'sort' => true,
            )
        );
        foreach ($this->statuses as $status => $labels) {
            register_post_status($status, array(
                                     'label'                     => $labels[0],
                                     'label_count'               => $labels[1], 
                                     'public'                    => false,
                                     'show_in_admin_all_list'    => true,
                                     'show_in_admin_status_list' => true,
                                     'exclude_from_search'       => true
                                 )
            );
        }
    }

    public function inject_custom_submitbox_status() {
        global $post;
        if (!is_object($post)) {
            $post = get_post($post_id);
        }
        switch ($post->post_type) {
            case 'sp_submission_item':
                echo '<script>jQuery(document).ready(function($){';
                foreach( $this->statuses as $status => $labels) {
                    $selected = '';
                    if ($post->post_status == $status) {
                        $selected = 'selected="selected"';
                        echo '$(".misc-pub-section label").append("<span id=\"post-status-display\"> '.ucwords($labels[0]).'</span>");';
                    }
                    echo '$("select#post_status").append("<option value=\"'.$status.'\" '.$selected.'> '.ucwords($labels[0]).'</option>");';
                }
                echo '});</script>';
            break;
            default:
            break;
        }

    }

    public function register_contribution_meta_box( $post ) {

    }

    public function register_issue_meta_box( $post ) {

    }

    public function register_submission_meta_box( $post ) {

    }

    public function register_submission_item_meta_box( $post ) {
        add_meta_box('sp_actions', __('SubmitPress Actions','submitpress'), array($this, 'display_submission_item_meta_box'), 'sp_submission_item', 'side', 'high');
    }

    public function display_submission_item_meta_box( $post ) {
        echo sprintf(__('Post is currently %s','submitpress'), $post->post_status );
        echo sprintf('<p><button class="button green dashicons-before dashicons-yes" data-confirm="%s" id="accept_contribution">%s</button></p>',
                        __('Are you sure you want to accept this submisison item?','submitpress'),
                        __('Accept as Contribution','submitpress')
                    );
        echo sprintf('<p><button class="button green dashicons-before dashicons-yes" data-confirm="%s" id="accept_post">%s</button></p>',
                        __('Are you sure you want to accept this submisison item?','submitpress'),
                        __('Accept as Post','submitpress')
                    );
        echo sprintf('<p><button class="button button-primary dashicons-before dashicons-flag" id="flag">%s</button></p>',__('Flag','submitpress'));
        echo sprintf('<p><button class="button red dashicons-before dashicons-no" data-confirm="%s" id="reject">%s</button></p>',
                        __('Are you sure you want to reject this submisison item?','submitpress'),
                        __('Reject','submitpress')
                    );
        echo '<input type="hidden" name="sp_action" id="sp_action" value="" />';
        wp_nonce_field('sp_action-'.$post->ID, 'sp_action_nonce');
    }

    public function save_post($post_id) {
        global $post;
        if (!is_object($post)) {
            $post = get_post($post_id);
        }
        switch ($post->post_type) {
            case 'sp_submission_item':
                $this->save_sp_submission_item($post_id);
            break;
            case 'sp_contribution':
                $this->save_sp_contribution($post_id);
            break;
        }
    }

    private function save_sp_submission_item($post_id) {
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
        switch (get_post_status($post_id)) {
            case 'trash':
            return;
            case 'auto-draft':
            return;
            case 'draft':
            return;
            case 'pending':
            return;
            default:
        }
        if (check_admin_referer('sp_action-'.$post_id, 'sp_action_nonce')) {
            $post_new = array('ID' => $post_id);
            $post_new_id = false;
            switch($_POST['sp_action']) {
                case 'accept_contribution':
                    $post_new['post_status'] = 'accepted';
                    $contribution = get_post($post_id,ARRAY_A);
                    $contribution['post_type'] = 'sp_contribution';
                    $contribution['post_status'] = 'pending';
                    unset($contribution['ID']);
                    remove_action('save_post', array($this,'save_post') );
                    $post_new_id = wp_insert_post($contribution);
                    add_action('save_post', array($this,'save_post') );
                break;
                case 'accept_post':
                    $post_new['post_status'] = 'accepted';
                    $post_accepted = get_post($post_id,ARRAY_A);
                    $post_accepted['post_type'] = 'post';
                    $post_accepted['post_status'] = 'pending';
                    unset($post_accepted['ID']);
                    remove_action('save_post', array($this,'save_post') );
                    $post_new_id = wp_insert_post($post_accepted);
                    add_action('save_post', array($this,'save_post') );
                break;
                case 'flag':
                    $post_new['post_status'] = 'flagged';
                break;
                case 'reject':
                    $post_new['post_status'] = 'rejected';
                break;
            }
            if ( ! wp_is_post_revision( $post_id ) ){
                remove_action('save_post', array($this,'save_post') );
                wp_update_post($post_new);
                add_action('save_post', array($this,'save_post') );
                if ($post_new_id) {
                    $url = admin_url( sprintf('post.php?post=%s&action=edit', urlencode($post_new_id) ) );
                    wp_redirect($url);
                    exit;
                }
            }
        } //check nonce
    }

    private function save_sp_contribution($post_id) {

    }
    
}
