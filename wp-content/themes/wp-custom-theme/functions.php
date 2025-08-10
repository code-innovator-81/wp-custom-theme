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

// Custom excerpt length
function wp_custom_theme_excerpt_length($length)
{
    return 30;
}
add_filter('excerpt_length', 'wp_custom_theme_excerpt_length');

// Custom excerpt more
function wp_custom_theme_excerpt_more($more)
{
    return '...';
}
add_filter('excerpt_more', 'wp_custom_theme_excerpt_more');

// Add custom image sizes
function wp_custom_theme_image_sizes()
{
    add_image_size('project-thumbnail', 400, 300, true);
    add_image_size('project-large', 800, 600, true);
}
add_action('after_setup_theme', 'wp_custom_theme_image_sizes');

// AJAX handler for project filtering
function wp_custom_filter_projects()
{
    // Verify nonce
    if (! wp_verify_nonce($_POST['nonce'], 'wp_custom_nonce')) {
        wp_die('Security check failed');
    }

    $start_date = sanitize_text_field($_POST['start_date']);
    $end_date   = sanitize_text_field($_POST['end_date']);

    $args = [
        'post_type'      => 'project',
        'posts_per_page' => -1,
        'meta_query'     => ['relation' => 'AND'],
    ];

    if (! empty($start_date)) {
        $args['meta_query'][] = [
            'key'     => 'project_start_date',
            'value'   => $start_date,
            'compare' => '>=',
            'type'    => 'DATE',
        ];
    }

    if (! empty($end_date)) {
        $args['meta_query'][] = [
            'key'     => 'project_end_date',
            'value'   => $end_date,
            'compare' => '<=',
            'type'    => 'DATE',
        ];
    }

    $projects = new WP_Query($args);

    if ($projects->have_posts()) {
        while ($projects->have_posts()) {
            $projects->the_post();
            get_template_part('templates/project', 'item');
        }
    } else {
        echo '<p>No project found matching your criteria.</p>';
    }

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_filter_projects', 'wp_custom_filter_projects');
add_action('wp_ajax_nopriv_filter_projects', 'wp_custom_filter_projects');
