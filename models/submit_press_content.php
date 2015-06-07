<?php
class SubmitPressContent {

    public function create_custom_post_types() {
        register_post_type( 'sp_submission',
            array(
                'labels' => array(
                  'name' => __( 'Submissions', 'submitpress' ),
                  'singular_name' => __( 'Submission', 'submitpress' )
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'submissions'),
                'menu_icon' => 'dashicons-format-aside',
                'menu_position' => 20,
                'capability_type' => array('sp_submission','sp_submissions'),
                'capabilities' => array(
                    'edit_post' => 'edit_sp_submission',
                    'edit_posts' => 'edit_sp_submissions',
                    'edit_others_posts' => 'edit_others_sp_submissions',
                    'publish_posts' => 'publish_sp_submissions',
                    'read_post' => 'read_sp_submission',
                    'read_private_posts' => 'read_private_sp_submissions',
                    'delete_post' => 'delete_sp_submission',
                    'create_posts' => 'create_sp_submissions',
                    'read' => 'read_sp_submission',
                ),
                'map_meta_cap' => true,
                'supports' => array('title','editor'),
            )
        );
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
        register_taxonomy('sp_issue', 'sp_contribution',
            array(  'label' => __('Issue','submitpress'),
                    'labels' => array(
                        'name' => 'issues',
                        'singular_name' => 'issue',
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
    }

    public function register_contribution_meta_box( $post ) {

    }

    public function register_issue_meta_box( $post ) {

    }
    
}
