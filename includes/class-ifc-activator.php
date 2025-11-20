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
            start_date datetime DEFAULT NULL,
            end_date datetime DEFAULT NULL,
            max_answers int DEFAULT NULL,
            status varchar(20) DEFAULT 'active',
            PRIMARY KEY  (id)
        ) ENGINE=InnoDB $charset_collate;";

        // Table for answers
        $table_answers = $wpdb->prefix . 'ifc_answers';
        $sql_answers = "CREATE TABLE $table_answers (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            question_id mediumint(9) NOT NULL,
            answer text NOT NULL,
            time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            FOREIGN KEY (question_id) REFERENCES $table_questions(id) ON DELETE CASCADE
        ) ENGINE=InnoDB $charset_collate;";

        dbDelta( $sql_questions );
        dbDelta( $sql_answers );

        // Set default options if they don't exist
        if ( get_option( 'ifc_poll_interval' ) === false ) {
            add_option( 'ifc_poll_interval', 5000 );
        }
        if ( get_option( 'ifc_word_cloud_width' ) === false ) {
            add_option( 'ifc_word_cloud_width', 600 );
        }
        if ( get_option( 'ifc_word_cloud_height' ) === false ) {
            add_option( 'ifc_word_cloud_height', 400 );
        }
        if ( get_option( 'ifc_stop_words' ) === false ) {
            add_option( 'ifc_stop_words', 'and, or, the, a, an, is, was, as, in, of, to, for, on, at, by, with, from, ja, on, että, tämä, se, mutta, niin, tai, jos, kuten, kuitenkin, koska, jotta, vaan, kun, mikä, missä, mitä, milloin, jopa, sillä' );
        }
        if ( get_option( 'ifc_min_word_length' ) === false ) {
            add_option( 'ifc_min_word_length', 2 );
        }

        // Run database migration if needed
        self::maybe_upgrade_database();
    }

    /**
     * Check and upgrade database schema if needed
     */
    public static function maybe_upgrade_database() {
        global $wpdb;
        $table_questions = $wpdb->prefix . 'ifc_questions';

        // Check if the new columns exist
        $columns = $wpdb->get_col( "DESCRIBE $table_questions", 0 );

        // Add scheduling columns if they don't exist
        if ( ! in_array( 'start_date', $columns ) ) {
            $wpdb->query( "ALTER TABLE $table_questions ADD COLUMN start_date datetime DEFAULT NULL AFTER created_at" );
            error_log( 'IFC Plugin: Added start_date column to questions table' );
        }

        if ( ! in_array( 'end_date', $columns ) ) {
            $wpdb->query( "ALTER TABLE $table_questions ADD COLUMN end_date datetime DEFAULT NULL AFTER start_date" );
            error_log( 'IFC Plugin: Added end_date column to questions table' );
        }

        if ( ! in_array( 'max_answers', $columns ) ) {
            $wpdb->query( "ALTER TABLE $table_questions ADD COLUMN max_answers int DEFAULT NULL AFTER end_date" );
            error_log( 'IFC Plugin: Added max_answers column to questions table' );
        }

        if ( ! in_array( 'status', $columns ) ) {
            $wpdb->query( "ALTER TABLE $table_questions ADD COLUMN status varchar(20) DEFAULT 'active' AFTER max_answers" );
            error_log( 'IFC Plugin: Added status column to questions table' );
        }
    }
}
