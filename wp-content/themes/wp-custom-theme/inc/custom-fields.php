<?php
/**
 * Custom Fields for Projects
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add meta boxes for project custom fields
function wp_custom_add_project_meta_boxes() {
    add_meta_box(
        'project_details',
        __('Project Details', 'wp-custom-theme'),
        'wp_custom_project_meta_box_callback',
        'project',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wp_custom_add_project_meta_boxes');

// Meta box callback function
function wp_custom_project_meta_box_callback($post) {
    // Add nonce field for security
    wp_nonce_field('wp_custom_project_meta_box', 'wp_custom_project_meta_box_nonce');
    
    // Get existing values
    $project_name = get_post_meta($post->ID, 'project_name', true);
    $project_description = get_post_meta($post->ID, 'project_description', true);
    $project_start_date = get_post_meta($post->ID, 'project_start_date', true);
    $project_end_date = get_post_meta($post->ID, 'project_end_date', true);
    $project_url = get_post_meta($post->ID, 'project_url', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="project_name"><?php _e('Project Name', 'wp-custom-theme'); ?></label>
            </th>
            <td>
                <input type="text" id="project_name" name="project_name" value="<?php echo esc_attr($project_name); ?>" class="regular-text" />
                <p class="description"><?php _e('Enter the project name.', 'wp-custom-theme'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="project_description"><?php _e('Project Description', 'wp-custom-theme'); ?></label>
            </th>
            <td>
                <textarea id="project_description" name="project_description" rows="4" cols="50" class="large-text"><?php echo esc_textarea($project_description); ?></textarea>
                <p class="description"><?php _e('Enter a detailed description of the project.', 'wp-custom-theme'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="project_start_date"><?php _e('Project Start Date', 'wp-custom-theme'); ?></label>
            </th>
            <td>
                <input type="date" id="project_start_date" name="project_start_date" value="<?php echo esc_attr($project_start_date); ?>" class="regular-text" />
                <p class="description"><?php _e('Select the project start date.', 'wp-custom-theme'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="project_end_date"><?php _e('Project End Date', 'wp-custom-theme'); ?></label>
            </th>
            <td>
                <input type="date" id="project_end_date" name="project_end_date" value="<?php echo esc_attr($project_end_date); ?>" class="regular-text" />
                <p class="description"><?php _e('Select the project end date.', 'wp-custom-theme'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="project_url"><?php _e('Project URL', 'wp-custom-theme'); ?></label>
            </th>
            <td>
                <input type="url" id="project_url" name="project_url" value="<?php echo esc_url($project_url); ?>" class="regular-text" />
                <p class="description"><?php _e('Enter the project URL (include http:// or https://).', 'wp-custom-theme'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

// Save meta box data
function wp_custom_save_project_meta_box($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['wp_custom_project_meta_box_nonce']) || !wp_verify_nonce($_POST['wp_custom_project_meta_box_nonce'], 'wp_custom_project_meta_box')) {
        return;
    }
    
    // Check if user has permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Check if not an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check if it's a project post type
    if (get_post_type($post_id) != 'project') {
        return;
    }
    
    // Sanitize and save custom fields
    $fields = array(
        'project_name' => 'sanitize_text_field',
        'project_description' => 'sanitize_textarea_field',
        'project_start_date' => 'sanitize_text_field',
        'project_end_date' => 'sanitize_text_field',
        'project_url' => 'esc_url_raw'
    );
    
    foreach ($fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            $value = call_user_func($sanitize_callback, $_POST[$field]);
            update_post_meta($post_id, $field, $value);
        }
    }
}
add_action('save_post', 'wp_custom_save_project_meta_box');

// Helper function to get project meta
function wp_custom_get_project_meta($post_id, $key = '') {
    if (empty($key)) {
        return array(
            'project_name' => get_post_meta($post_id, 'project_name', true),
            'project_description' => get_post_meta($post_id, 'project_description', true),
            'project_start_date' => get_post_meta($post_id, 'project_start_date', true),
            'project_end_date' => get_post_meta($post_id, 'project_end_date', true),
            'project_url' => get_post_meta($post_id, 'project_url', true)
        );
    }
    
    return get_post_meta($post_id, $key, true);
}

// Helper function to format project dates
function wp_custom_format_project_date($date, $format = 'F j, Y') {
    if (empty($date)) {
        return '';
    }
    
    return date($format, strtotime($date));
}