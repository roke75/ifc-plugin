<?php
// includes/class-ifc-admin.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IFC_Admin {
    public function run() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );

        add_action( 'admin_post_ifc_add_question', array( $this, 'handle_add_question' ) );
        add_action( 'admin_post_ifc_edit_question', array( $this, 'handle_edit_question' ) );
        add_action( 'admin_post_ifc_delete_question', array( $this, 'handle_delete_question' ) );
        add_action( 'admin_post_ifc_delete_answers', array( $this, 'handle_delete_answers' ) );
    }

    public function add_admin_menu() {
        add_menu_page(
            __( 'Instant Feedback Collector Plugin', 'ifc-plugin' ),
            __( 'Instant Feedback Collector', 'ifc-plugin' ),
            'manage_options',
            'ifc-plugin',
            array( $this, 'admin_page' ),
            'dashicons-editor-help',
            6
        );

        add_submenu_page(
            'ifc-plugin',
            __( 'Settings', 'ifc-plugin' ),
            __( 'Settings', 'ifc-plugin' ),
            'manage_options',
            'ifc-plugin-settings',
            array( $this, 'settings_page' )
        );
    }

    // Register settings
    public function register_settings() {
        register_setting( 'ifc_plugin_settings', 'ifc_poll_interval', array(
            'type'              => 'integer',
            'default'           => 5000,
            'sanitize_callback' => array( $this, 'sanitize_poll_interval' ),
        ) );

        register_setting( 'ifc_plugin_settings', 'ifc_word_cloud_width', array(
            'type'              => 'integer',
            'default'           => 600,
            'sanitize_callback' => 'absint',
        ) );

        register_setting( 'ifc_plugin_settings', 'ifc_word_cloud_height', array(
            'type'              => 'integer',
            'default'           => 400,
            'sanitize_callback' => 'absint',
        ) );

        register_setting( 'ifc_plugin_settings', 'ifc_stop_words', array(
            'type'              => 'string',
            'default'           => 'and, or, the, a, an, is, was, as, in, of, to, for, on, at, by, with, from, ja, on, että, tämä, se, mutta, niin, tai, jos, kuten, kuitenkin, koska, jotta, vaan, kun, mikä, missä, mitä, milloin, jopa, sillä',
            'sanitize_callback' => 'sanitize_textarea_field',
        ) );

        register_setting( 'ifc_plugin_settings', 'ifc_min_word_length', array(
            'type'              => 'integer',
            'default'           => 2,
            'sanitize_callback' => 'absint',
        ) );
    }

    // Sanitize poll interval to ensure it's within reasonable bounds
    public function sanitize_poll_interval( $value ) {
        $value = absint( $value );
        if ( $value < 1000 ) {
            $value = 1000; // Minimum 1 second
        } elseif ( $value > 60000 ) {
            $value = 60000; // Maximum 60 seconds
        }
        return $value;
    }

    // Settings page
    public function settings_page() {
        include IFC_PLUGIN_DIR . 'includes/settings-page.php';
    }

    // Load translations
    public function load_textdomain() {
        load_plugin_textdomain( 'ifc-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/../languages' );
    }

    // Show admin page
    public function admin_page() {
        include IFC_PLUGIN_DIR . 'includes/admin-page.php';
    }

    // Handle add question
    public function handle_add_question() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sinulla ei ole riittäviä oikeuksia tähän sivuun.', 'ifc-plugin' ) );
        }

        check_admin_referer( 'ifc_admin_action', 'ifc_admin_nonce' );

        global $wpdb;
        $table_questions = $wpdb->prefix . 'ifc_questions';

        if ( isset( $_POST['ifc_action'] ) && $_POST['ifc_action'] === 'add_question' && ! empty( $_POST['question_text'] ) ) {
            $question_text = sanitize_text_field( $_POST['question_text'] );
            $inserted = $wpdb->insert(
                $table_questions,
                array( 'question' => $question_text ),
                array( '%s' )
            );

            if ( false !== $inserted ) {
                wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=add' ) );
                exit;
            }

            // Log database error
            error_log( sprintf(
                'IFC Plugin: Failed to insert question. DB Error: %s, Last Query: %s',
                $wpdb->last_error,
                $wpdb->last_query
            ) );

            $error_detail = $wpdb->last_error ? urlencode( $wpdb->last_error ) : 'database_insert_failed';
            wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error&error_detail=' . $error_detail ) );
            exit;
        }

        // Log invalid request
        error_log( 'IFC Plugin: Invalid add question request - missing action or question text' );
        wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error&error_detail=invalid_request' ) );
        exit;
    }

    // Handle edit question
    public function handle_edit_question() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sinulla ei ole riittäviä oikeuksia tähän sivuun.', 'ifc-plugin' ) );
        }

        check_admin_referer( 'ifc_admin_action', 'ifc_admin_nonce' );

        global $wpdb;
        $table_questions = $wpdb->prefix . 'ifc_questions';

        if ( isset( $_POST['ifc_action'] ) && $_POST['ifc_action'] === 'edit_question' && ! empty( $_POST['question_text'] ) && isset( $_POST['question_id'] ) ) {
            $question_text = sanitize_text_field( $_POST['question_text'] );
            $question_id   = intval( $_POST['question_id'] );

            $updated = $wpdb->update(
                $table_questions,
                array( 'question' => $question_text ),
                array( 'id' => $question_id ),
                array( '%s' ),
                array( '%d' )
            );

            if ( false !== $updated ) {
                wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=edit' ) );
                exit;
            }

            // Log database error
            error_log( sprintf(
                'IFC Plugin: Failed to update question ID %d. DB Error: %s, Last Query: %s',
                $question_id,
                $wpdb->last_error,
                $wpdb->last_query
            ) );

            $error_detail = $wpdb->last_error ? urlencode( $wpdb->last_error ) : 'database_update_failed';
            wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error&error_detail=' . $error_detail ) );
            exit;
        }

        // Log invalid request
        error_log( 'IFC Plugin: Invalid edit question request - missing required fields' );
        wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error&error_detail=invalid_request' ) );
        exit;
    }

    // Handle delete question
    public function handle_delete_question() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sinulla ei ole riittäviä oikeuksia tähän sivuun.', 'ifc-plugin' ) );
        }

        check_admin_referer( 'ifc_admin_action', 'ifc_admin_nonce' );

        global $wpdb;
        $table_questions = $wpdb->prefix . 'ifc_questions';
        $table_answers   = $wpdb->prefix . 'ifc_answers';

        if ( isset( $_POST['ifc_action'] ) && $_POST['ifc_action'] === 'delete_question' && isset( $_POST['question_id'] ) ) {
            $question_id = intval( $_POST['question_id'] );

            // Manually delete answers first as a fallback in case foreign key doesn't work
            $wpdb->delete(
                $table_answers,
                array( 'question_id' => $question_id ),
                array( '%d' )
            );

            $deleted = $wpdb->delete(
                $table_questions,
                array( 'id' => $question_id ),
                array( '%d' )
            );

            if ( false !== $deleted ) {
                wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=delete' ) );
                exit;
            }

            // Log database error
            error_log( sprintf(
                'IFC Plugin: Failed to delete question ID %d. DB Error: %s, Last Query: %s',
                $question_id,
                $wpdb->last_error,
                $wpdb->last_query
            ) );

            $error_detail = $wpdb->last_error ? urlencode( $wpdb->last_error ) : 'database_delete_failed';
            wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error&error_detail=' . $error_detail ) );
            exit;
        }

        // Log invalid request
        error_log( 'IFC Plugin: Invalid delete question request - missing question ID' );
        wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error&error_detail=invalid_request' ) );
        exit;
    }

    // Handle delete answers
    public function handle_delete_answers() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sinulla ei ole riittäviä oikeuksia tähän sivuun.', 'ifc-plugin' ) );
        }

        check_admin_referer( 'ifc_admin_action', 'ifc_admin_nonce' );

        global $wpdb;
        $table_answers = $wpdb->prefix . 'ifc_answers';

        if ( isset( $_POST['ifc_action'] ) && $_POST['ifc_action'] === 'delete_answers' && isset( $_POST['question_id'] ) ) {
            $question_id = intval( $_POST['question_id'] );

            $deleted = $wpdb->delete(
                $table_answers,
                array( 'question_id' => $question_id ),
                array( '%d' )
            );

            if ( false !== $deleted ) {
                wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=delete_answers' ) );
                exit;
            }

            // Log database error
            error_log( sprintf(
                'IFC Plugin: Failed to delete answers for question ID %d. DB Error: %s, Last Query: %s',
                $question_id,
                $wpdb->last_error,
                $wpdb->last_query
            ) );

            $error_detail = $wpdb->last_error ? urlencode( $wpdb->last_error ) : 'database_delete_failed';
            wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error&error_detail=' . $error_detail ) );
            exit;
        }

        // Log invalid request
        error_log( 'IFC Plugin: Invalid delete answers request - missing question ID' );
        wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error&error_detail=invalid_request' ) );
        exit;
    }
}
