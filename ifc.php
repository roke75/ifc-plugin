<?php
/*
Plugin Name: Instant Feedback Collector Plugin
Plugin URI: https://github.com/roke75/ifc-plugin
Description: Ask question and get instant feedback
Version: 1.0
Author: Jarkko Roininen
Text Domain: ifc-plugin
Domain Path: /languages
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants.
define( 'IFC_PLUGIN_VERSION', '1.0' );
define( 'IFC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'IFC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files.
require_once IFC_PLUGIN_DIR . 'includes/class-ifc-activator.php';
require_once IFC_PLUGIN_DIR . 'includes/class-ifc-deactivator.php';
require_once IFC_PLUGIN_DIR . 'includes/class-ifc-admin.php';
require_once IFC_PLUGIN_DIR . 'includes/class-ifc-public.php';
require_once IFC_PLUGIN_DIR . 'includes/class-ifc-ajax.php';
require_once IFC_PLUGIN_DIR . 'includes/class-ifc-shortcodes.php';

// Activation and deactivation hooks.
register_activation_hook( __FILE__, array( 'IFC_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'IFC_Deactivator', 'deactivate' ) );

// Initialize the plugin.
function run_ifc_plugin() {
    // Initialize Admin functionalities.
    if ( is_admin() ) {
        $admin = new IFC_Admin();
        $admin->run();
    }

    // Initialize Public functionalities.
    $public = new IFC_Public();
    $public->run();

    // Initialize AJAX functionalities.
    $ajax = new IFC_AJAX();
    $ajax->run();

    // Initialize Shortcodes.
    $shortcodes = new IFC_Shortcodes();
    $shortcodes->run();
}

run_ifc_plugin();
