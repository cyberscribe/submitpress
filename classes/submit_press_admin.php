<?php
class SubmitPressAdmin {

    private $db;

    public function __construct() {
        $this->db = new SubmitPressDb();
    }

    public function admin_menu() {
        add_submenu_page( 'edit.php?post_type=sp_submission_item', 
                          __('SubmitPress Reports','submitpress'), 
                          __('Reports','submitpress'), 
                          'edit_others_sp_submission_items',
                          'submitpress-reports',
                          array($this,'submitpress_reports'));
        $this->register_menu_page();
    }

    public function register_menu_page(){
        add_options_page( __('SubmitPress Options','submitpress'), __('SubmitPress','submitpress'), 'manage_options', dirname(plugin_dir_path(  __FILE__ )).'/views/admin.php');
    }

    public function submitpress_reports() {
        echo '<div class="wrap">';
        echo '<h1>'.__('SubmitPress Reports','submitpress').'</h1>';
        echo '<h3>'.__('Submission Report','submitpress').'</h3>';
        $submissions = $this->db->get_submission_items_by_submission();
        echo '<table class="wp-list-table widefat fixed striped posts"><thead><tr><th role="col">'.__('Submission','submitpress').'</th>';
        echo '<th role="col">'.__('Accepted','submitpress').'</th>';
        echo '<th role="col">'.__('Rejected','submitpress').'</th>';
        echo '<th role="col">'.__('Total','submitpress').'</th>';
        echo '<th role="col">'.__('% Complete','submitpress').'</th></tr></thead>';
        foreach ($submissions as $submission) {
            echo '<tr>';
            echo sprintf('<td><a href="%s">%s</a></td>','edit.php?sp_submission='.$submission->slug.'&post_type=sp_submission_item',
                                                        $submission->name);
            echo sprintf('<td><a href="%s">%s</a></td>','edit.php?sp_submission='.$submission->slug.'&post_type=sp_submission_item&post_status=accepted',
                                                        $submission->accept_count);
            echo sprintf('<td><a href="%s">%s</a></td>','edit.php?sp_submission='.$submission->slug.'&post_type=sp_submission_item&post_status=rejected',
                                                        $submission->reject_count);
            echo sprintf('<td><a href="%s">%s</a></td>','edit.php?sp_submission='.$submission->slug.'&post_type=sp_submission_item',
                                                        $submission->total_count);
            echo sprintf('<td>%s</td>',round( 100 * ($submission->accept_count + $submission->reject_count ) / $submission->total_count) );
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }


}
