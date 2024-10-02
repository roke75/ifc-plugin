<?php
// includes/class-ifc-admin.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IFC_Admin {
    public function run() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

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
        }

        wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error' ) );
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
        }

        wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error' ) );
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

            $deleted = $wpdb->delete(
                $table_questions,
                array( 'id' => $question_id ),
                array( '%d' )
            );

            if ( false !== $deleted ) {
                wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=delete' ) );
                exit;
            }
        }

        wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error' ) );
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
        }

        wp_redirect( admin_url( 'admin.php?page=ifc-plugin&updated=error' ) );
        exit;
    }
}
