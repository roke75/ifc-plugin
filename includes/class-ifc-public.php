<?php
// includes/class-ifc-public.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class IFC_Public {
    public function run() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
    }

    public function enqueue_styles_scripts() {
        // Bootstrap CSS
        wp_enqueue_style( 'bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' );

        // Plugin's CSS
        wp_enqueue_style( 'ifc-css', IFC_PLUGIN_URL . 'css/ifc.css' );

        // jQuery
        wp_enqueue_script( 'jquery' );

        // Bootstrap JS
        wp_enqueue_script( 'bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array( 'jquery' ), null, true );

        // jQCloud JS
        wp_enqueue_script( 'jqcloud-js', 'https://cdnjs.cloudflare.com/ajax/libs/jqcloud/1.0.4/jqcloud.min.js', array( 'jquery' ), '1.0.4', true );

        // Plugin's JS
        wp_enqueue_script( 'ifc-js', IFC_PLUGIN_URL . 'js/ifc.js', array( 'jquery', 'jqcloud-js' ), IFC_PLUGIN_VERSION, true );

        // Localize script for AJAX
        wp_localize_script( 'ifc-js', 'ifc_ajax_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'ifc_ajax_nonce' ),
        ) );
    }
}
