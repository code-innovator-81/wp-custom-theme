<?php
/**
 * Custom Post Types
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register Projects Custom Post Type
function wp_custom_register_project_post_type() {
    $labels = array(
        'name'                  => _x('Projects', 'Post Type General Name', 'wp-custom-theme'),
        'singular_name'         => _x('Project', 'Post Type Singular Name', 'wp-custom-theme'),
        'menu_name'             => __('Projects', 'wp-custom-theme'),
        'name_admin_bar'        => __('Project', 'wp-custom-theme'),
        'archives'              => __('Project Archives', 'wp-custom-theme'),
        'attributes'            => __('Project Attributes', 'wp-custom-theme'),
        'parent_item_colon'     => __('Parent Project:', 'wp-custom-theme'),
        'all_items'             => __('All Projects', 'wp-custom-theme'),
        'add_new_item'          => __('Add New Project', 'wp-custom-theme'),
        'add_new'               => __('Add New', 'wp-custom-theme'),
        'new_item'              => __('New Project', 'wp-custom-theme'),
        'edit_item'             => __('Edit Project', 'wp-custom-theme'),
        'update_item'           => __('Update Project', 'wp-custom-theme'),
        'view_item'             => __('View Project', 'wp-custom-theme'),
        'view_items'            => __('View Projects', 'wp-custom-theme'),
        'search_items'          => __('Search Project', 'wp-custom-theme'),
        'not_found'             => __('Not found', 'wp-custom-theme'),
        'not_found_in_trash'    => __('Not found in Trash', 'wp-custom-theme'),
        'featured_image'        => __('Featured Image', 'wp-custom-theme'),
        'set_featured_image'    => __('Set featured image', 'wp-custom-theme'),
        'remove_featured_image' => __('Remove featured image', 'wp-custom-theme'),
        'use_featured_image'    => __('Use as featured image', 'wp-custom-theme'),
        'insert_into_item'      => __('Insert into project', 'wp-custom-theme'),
        'uploaded_to_this_item' => __('Uploaded to this project', 'wp-custom-theme'),
        'items_list'            => __('Projects list', 'wp-custom-theme'),
        'items_list_navigation' => __('Projects list navigation', 'wp-custom-theme'),
        'filter_items_list'     => __('Filter projects list', 'wp-custom-theme'),
    );

    $args = array(
        'label'                 => __('Project', 'wp-custom-theme'),
        'description'           => __('Custom post type for projects', 'wp-custom-theme'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies'            => array('project_category'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'show_in_rest'          => true,
        'rest_base'             => 'projects',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'capability_type'       => 'post',
        'rewrite'               => array(
            'slug'                  => 'projects',
            'with_front'            => false,
            'pages'                 => true,
            'feeds'                 => true,
        ),
    );

    register_post_type('project', $args);
}
add_action('init', 'wp_custom_register_project_post_type', 0);

// Register Project Category Taxonomy
function wp_custom_register_project_category_taxonomy() {
    $labels = array(
        'name'                       => _x('Project Categories', 'Taxonomy General Name', 'wp-custom-theme'),
        'singular_name'              => _x('Project Category', 'Taxonomy Singular Name', 'wp-custom-theme'),
        'menu_name'                  => __('Categories', 'wp-custom-theme'),
        'all_items'                  => __('All Categories', 'wp-custom-theme'),
        'parent_item'                => __('Parent Category', 'wp-custom-theme'),
        'parent_item_colon'          => __('Parent Category:', 'wp-custom-theme'),
        'new_item_name'              => __('New Category Name', 'wp-custom-theme'),
        'add_new_item'               => __('Add New Category', 'wp-custom-theme'),
        'edit_item'                  => __('Edit Category', 'wp-custom-theme'),
        'update_item'                => __('Update Category', 'wp-custom-theme'),
        'view_item'                  => __('View Category', 'wp-custom-theme'),
        'separate_items_with_commas' => __('Separate categories with commas', 'wp-custom-theme'),
        'add_or_remove_items'        => __('Add or remove categories', 'wp-custom-theme'),
        'choose_from_most_used'      => __('Choose from the most used', 'wp-custom-theme'),
        'popular_items'              => __('Popular Categories', 'wp-custom-theme'),
        'search_items'               => __('Search Categories', 'wp-custom-theme'),
        'not_found'                  => __('Not Found', 'wp-custom-theme'),
        'no_terms'                   => __('No categories', 'wp-custom-theme'),
        'items_list'                 => __('Categories list', 'wp-custom-theme'),
        'items_list_navigation'      => __('Categories list navigation', 'wp-custom-theme'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rest_base'                  => 'project_categories',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
        'rewrite'                    => array(
            'slug'                       => 'project-category',
            'with_front'                 => false,
            'hierarchical'               => true,
        ),
    );

    register_taxonomy('project_category', array('project'), $args);
}
add_action('init', 'wp_custom_register_project_category_taxonomy', 0);

// Flush rewrite rules on theme activation
function wp_custom_flush_rewrite_rules() {
    wp_custom_register_project_post_type();
    wp_custom_register_project_category_taxonomy();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'wp_custom_flush_rewrite_rules');

// Add custom columns to projects admin list
function wp_custom_project_admin_columns($columns) {
    $columns['project_start_date'] = __('Start Date', 'wp-custom-theme');
    $columns['project_end_date'] = __('End Date', 'wp-custom-theme');
    $columns['project_url'] = __('Project URL', 'wp-custom-theme');
    return $columns;
}
add_filter('manage_project_posts_columns', 'wp_custom_project_admin_columns');

// Display custom column content
function wp_custom_project_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'project_start_date':
            $start_date = get_post_meta($post_id, 'project_start_date', true);
            echo $start_date ? esc_html(date('M j, Y', strtotime($start_date))) : '—';
            break;
        case 'project_end_date':
            $end_date = get_post_meta($post_id, 'project_end_date', true);
            echo $end_date ? esc_html(date('M j, Y', strtotime($end_date))) : '—';
            break;
        case 'project_url':
            $url = get_post_meta($post_id, 'project_url', true);
            if ($url) {
                echo '<a href="' . esc_url($url) . '" target="_blank">' . esc_html($url) . '</a>';
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_project_posts_custom_column', 'wp_custom_project_admin_column_content', 10, 2);