<?php
// uninstall.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

// Drop tables
$table_questions = $wpdb->prefix . 'ifc_questions';
$table_answers   = $wpdb->prefix . 'ifc_answers';

$wpdb->query( "DROP TABLE IF EXISTS $table_answers" );
$wpdb->query( "DROP TABLE IF EXISTS $table_questions" );

// Delete plugin options
delete_option( 'ifc_poll_interval' );
delete_option( 'ifc_word_cloud_width' );
delete_option( 'ifc_word_cloud_height' );
delete_option( 'ifc_stop_words' );
delete_option( 'ifc_min_word_length' );
