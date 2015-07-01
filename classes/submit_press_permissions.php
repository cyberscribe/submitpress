<?php
class SubmitPressPermissions {

    public function add_custom_capabilities() {
        $admins = get_role( 'administrator' );
        $admins->add_cap( 'edit_sp_submission_item' ); 
        $admins->add_cap( 'edit_sp_submission_items' ); 
        $admins->add_cap( 'edit_others_sp_submission_items' ); 
        $admins->add_cap( 'publish_sp_submission_items' ); 
        $admins->add_cap( 'read_sp_submission_item' ); 
        $admins->add_cap( 'read_private_sp_submission_items' ); 
        $admins->add_cap( 'delete_sp_submission_item' ); 
        $admins->add_cap( 'manage_sp_issue' );
        $admins->add_cap( 'manage_sp_submission' );
        $admins->add_cap( 'manage_sp_genre' );
        $admins->add_cap( 'edit_sp_submission_items' );
    }

    public function add_new_roles() {
        $this->remove_new_roles();
        add_role( 'sp_submitter', __( 'Submitter' ),
                   array(
                        'edit_sp_submission_item' => true,
                        'edit_sp_submission_items' => true,
                        'create_sp_submission_items' => true,
                        'read' => true,
                   )
        );
    }

    public function remove_new_roles() {
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

    public function login_form() {
        // display Twitter, Fb, etc.
    }
}
