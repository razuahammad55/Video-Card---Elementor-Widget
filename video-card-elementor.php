<?php
/**
 * Plugin Name: Video Card Elementor Widget
 * Description: Displays Video Cards as an Elementor widget. Supports filtering by count, group, and specific post IDs. Requires ACF and a "videos" custom post type.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: video-card-elementor
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'VCE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'VCE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load helper functions.
 */
require_once VCE_PLUGIN_PATH . 'includes/helper-functions.php';

/**
 * Register the Elementor widget.
 */
function vce_register_widget( $widgets_manager ) {
    require_once VCE_PLUGIN_PATH . 'widgets/video-card-widget.php';
    $widgets_manager->register( new \VCE_Video_Card_Widget() );
}
add_action( 'elementor/widgets/register', 'vce_register_widget' );

/**
 * Enqueue frontend assets.
 */
function vce_enqueue_frontend_assets() {
    wp_enqueue_style(
        'video-card-style',
        VCE_PLUGIN_URL . 'assets/css/video-card.css',
        [],
        '1.0.0'
    );

    wp_enqueue_script(
        'video-card-script',
        VCE_PLUGIN_URL . 'assets/js/video-card.js',
        [],
        '1.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'vce_enqueue_frontend_assets' );

/**
 * Also load assets in Elementor editor preview.
 */
function vce_editor_preview_assets() {
    wp_enqueue_style(
        'video-card-style',
        VCE_PLUGIN_URL . 'assets/css/video-card.css',
        [],
        '1.0.0'
    );

    wp_enqueue_script(
        'video-card-script',
        VCE_PLUGIN_URL . 'assets/js/video-card.js',
        [],
        '1.0.0',
        true
    );
}
add_action( 'elementor/preview/enqueue_styles', 'vce_editor_preview_assets' );
add_action( 'elementor/preview/enqueue_scripts', 'vce_editor_preview_assets' );
