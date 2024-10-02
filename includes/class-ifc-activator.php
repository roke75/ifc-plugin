<?php
// includes/class-ifc-activator.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IFC_Activator {
    public static function activate() {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Table for questions
        $table_questions = $wpdb->prefix . 'ifc_questions';
        $sql_questions = "CREATE TABLE $table_questions (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            question text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        // Table for answers
        $table_answers = $wpdb->prefix . 'ifc_answers';
        $sql_answers = "CREATE TABLE $table_answers (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            question_id mediumint(9) NOT NULL,
            answer text NOT NULL,
            time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            FOREIGN KEY (question_id) REFERENCES $table_questions(id) ON DELETE CASCADE
        ) $charset_collate;";

        dbDelta( $sql_questions );
        dbDelta( $sql_answers );
    }
}
