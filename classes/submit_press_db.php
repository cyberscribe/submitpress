<?php
class SubmitPressDb {
    private $wpdb;
    private $db_version;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->db_version = 1; //increment with subsequent releases that require db changes
    }

    public function create_tables() {
        $charset_collate = $this->wpdb->get_charset_collate();
        $sql = "create table if not exists ".$this->wpdb->prefix."sp_submissions (
                `submission_id` int not null,
                `correspondence_id` int null,
                `accept_count` int not null default 0,
                `reject_count` int not null default 0,
                `total_count` int not null default 0,
                UNIQUE KEY `submission_id` (`submission_id`),
                UNIQUE KEY `correspondence_id` (`correspondence_id`),
                KEY `accept_count` (`accept_count`),
                KEY `reject_count` (`reject_count`),
                KEY `total_count` (`total_count`)
                ) ".$charset_collate.";";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $this->wpdb->query( $sql );

        if (!get_option('sp_db_version')) {
            add_option('sp_db_version',$this->db_version);
        } else {
            update_option('sp_db_version',$this->db_version);
        }
    }

    public function update_submission( $submission_id, $array) {
        if ($submission_id == 0) {
            return;
        }
        $table = $this->wpdb->prefix."sp_submissions";
        $format = array_fill(0, sizeof($array) + 1, '%d');
        $array['submission_id'] = $submission_id;
        return $this->wpdb->replace($table, $array, $format);
    }

    public function get_submission_items_by_submission() {
        $s_table = $this->wpdb->prefix."sp_submissions";
        $t_table = $this->wpdb->prefix."terms";
        $query = "select name, slug, term_id, accept_count, reject_count, total_count
                  from {$s_table} s left join {$t_table} t
                  on s.`submission_id` = t.`term_id`";
        $result = $this->wpdb->get_results( $query );
        return $result;
    }

}
