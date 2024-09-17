<?php
// Prevent direct access
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

// Plugin tables
global $wpdb;
$table_questions = $wpdb->prefix . 'ifc_questions';
$table_answers = $wpdb->prefix . 'ifc_answers';

// Delete plugin tables
$wpdb->query( "DROP TABLE IF EXISTS $table_questions" );
$wpdb->query( "DROP TABLE IF EXISTS $table_answers" );
