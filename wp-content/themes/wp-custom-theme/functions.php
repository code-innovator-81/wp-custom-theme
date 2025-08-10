<?php
/**
 * WP Custom Theme Functions
 */

// Prevent direct access
if (! defined('ABSPATH')) {
    exit;
}
// Theme setup
function wp_custom_theme_setup()
{
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ]);

    // Register navigation menus
    register_nav_menus([
        'primary' => esc_html__('Primary Menu', 'wp-custom-theme'),
        'footer'  => esc_html__('Footer Menu', 'wp-custom-theme'),
    ]);

    // Set content width
    global $content_width;
    if (! isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'wp_custom_theme_setup');

// Enqueue styles and scripts
function wp_custom_theme_scripts()
{
    // Main stylesheet
    wp_enqueue_style('wp-custom-theme-style', get_stylesheet_uri(), [], '1.0');

    // Custom CSS files
    wp_enqueue_style('wp-custom-theme-main', get_template_directory_uri() . '/css/main.css', [], '1.0');
    wp_enqueue_style('wp-custom-theme-responsive', get_template_directory_uri() . '/css/responsive.css', [], '1.0');

    // Scripts
    wp_enqueue_script('wp-custom-theme-main', get_template_directory_uri() . '/js/main.js', ['jquery'], '1.0', true);

    // Localize script for AJAX
    wp_localize_script('wp-custom-theme-main', 'wp_custom_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('wp_custom_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'wp_custom_theme_scripts');

// Include custom functionality
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/custom-fields.php';
require_once get_template_directory() . '/inc/api-endpoints.php';
