<?php
/**
 * Custom REST API Endpoints
 */

// Prevent direct access
if (! defined('ABSPATH')) {
    exit;
}

// Register custom REST API endpoints
function wp_custom_register_api_endpoints()
{
    // Register projects endpoint
    register_rest_route('wp-custom/v1', '/projects', [
        'methods'             => 'GET',
        'callback'            => 'wp_custom_get_projects_api',
        'permission_callback' => '__return_true',
        'args'                => [
            'per_page'   => [
                'default'           => 10,
                'sanitize_callback' => 'absint',
            ],
            'page'       => [
                'default'           => 1,
                'sanitize_callback' => 'absint',
            ],
            'start_date' => [
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'end_date'   => [
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'category'   => [
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ],
    ]);

    // Register single project endpoint
    register_rest_route('wp-custom/v1', '/project/(?P<slug>[a-zA-Z0-9-_]+)', [
        'methods'             => 'GET',
        'callback'            => 'wp_custom_get_single_project_api',
        'permission_callback' => '__return_true',
        'args'                => [
            'slug' => [
                'sanitize_callback' => 'sanitize_title',
            ],
        ],
    ]);
}
add_action('rest_api_init', 'wp_custom_register_api_endpoints');

// Get projects API callback
function wp_custom_get_projects_api($request)
{
    $per_page   = $request->get_param('per_page');
    $page       = $request->get_param('page');
    $start_date = $request->get_param('start_date');
    $end_date   = $request->get_param('end_date');
    $category   = $request->get_param('category');

    // Build query arguments
    $args = [
        'post_type'      => 'project',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'meta_query'     => ['relation' => 'AND'],
    ];

    // Add date filters
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

    // Add category filter
    if (! empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'project_category',
                'field'    => 'slug',
                'terms'    => $category,
            ],
        ];
    }

    $projects_query = new WP_Query($args);
    $projects       = [];

    if ($projects_query->have_posts()) {
        while ($projects_query->have_posts()) {
            $projects_query->the_post();
            $project_id = get_the_ID();

            // Get project meta
            $project_meta = wp_custom_get_project_meta($project_id);

            $projects[] = [
                'slug'               => get_post_field('post_name', $project_id),
                'title'              => get_the_title(),
                'project_url'        => $project_meta['project_url'],
                'project_start_date' => $project_meta['project_start_date'],
                'project_end_date'   => $project_meta['project_end_date'],
            ];
        }
    }

    wp_reset_postdata();

    // Prepare response with pagination info
    $response = [
        'projects'   => $projects,
        'pagination' => [
            'total_posts'  => (int) $projects_query->found_posts,
            'total_pages'  => (int) $projects_query->max_num_pages,
            'current_page' => $page,
            'per_page'     => $per_page,
            'has_more'     => $page < $projects_query->max_num_pages,
        ],
    ];

    return rest_ensure_response($response);
}

// Get single project API callback
function wp_custom_get_single_project_api($request)
{
    $slug    = $request->get_param('slug');
    $project = get_page_by_path($slug, OBJECT, 'project');

    if (! $project || $project->post_status !== 'publish') {
        return new WP_Error('project_not_found', 'Project not found', ['status' => 404]);
    }

    // Get project meta
    $project_id   = $project->ID;
    $project_meta = wp_custom_get_project_meta($project_id);

    // Get project categories
    $categories    = get_the_terms($project_id, 'project_category');
    $category_list = [];
    if ($categories && ! is_wp_error($categories)) {
        foreach ($categories as $category) {
            $category_list[] = [
                'id'          => $category->term_id,
                'name'        => $category->name,
                'slug'        => $category->slug,
                'description' => $category->description,
            ];
        }
    }
    // Prepare project data
    $project_data = [
        'title'               => $project->post_title,
        'slug'                => $project->post_name,
        'content'             => apply_filters('the_content', $project->post_content),
        'project_name'        => $project_meta['project_name'],
        'project_description' => $project_meta['project_description'],
        'project_start_date'  => $project_meta['project_start_date'],
        'project_end_date'    => $project_meta['project_end_date'],
        'project_url'         => $project_meta['project_url'],
        'categories'          => $category_list,
        'permalink'           => get_permalink($project_id),
        'date_created'        => get_the_date('c', $project_id),
        'date_modified'       => get_the_modified_date('c', $project_id),

    ];

    return rest_ensure_response($project_data);
}

// Add CORS headers for API requests
function wp_custom_add_cors_headers()
{
    if (strpos($_SERVER['REQUEST_URI'], '/wp-json/wp-custom/') !== false) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }
}
add_action('init', 'wp_custom_add_cors_headers');
