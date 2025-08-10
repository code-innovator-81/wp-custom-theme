<?php
/**
 * Template part for displaying project items
 */

$project_meta = wp_custom_get_project_meta(get_the_ID());
?>

<div class="project-item">
    <?php if (has_post_thumbnail()) : ?>
        <div class="project-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('project-thumbnail'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="project-content">
        <h3 class="project-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <?php if (!empty($project_meta['project_description'])) : ?>
            <div class="project-excerpt">
                <?php echo wp_trim_words(esc_html($project_meta['project_description']), 25); ?>
            </div>
        <?php else : ?>
            <div class="project-excerpt">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>
        
        <div class="project-meta-summary">
            <div class="project-date-range">
                <?php
                if (!empty($project_meta['project_start_date']) && !empty($project_meta['project_end_date'])) {
                    echo wp_custom_format_project_date($project_meta['project_start_date'], 'M Y') . ' - ' . wp_custom_format_project_date($project_meta['project_end_date'], 'M Y');
                } elseif (!empty($project_meta['project_start_date'])) {
                    echo esc_html__('Started', 'wp-custom-theme') . ' ' . wp_custom_format_project_date($project_meta['project_start_date'], 'M Y');
                } else {
                    echo get_the_date('M Y');
                }
                ?>
            </div>
            
            <div class="project-actions">
                <a href="<?php the_permalink(); ?>" class="project-view-link">
                    <?php esc_html_e('View Details', 'wp-custom-theme'); ?>
                </a>
            </div>
        </div>
        
        <?php
        $categories = get_the_terms(get_the_ID(), 'project_category');
        if ($categories && !is_wp_error($categories)) :
            ?>
            <div class="project-categories">
                <?php
                foreach ($categories as $category) {
                    echo '<a href="' . get_term_link($category) . '" class="project-category-tag">' . $category->name . '</a>';
                }
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($project_meta['project_url'])) : ?>
            <div class="project-external-link">
                <a href="<?php echo esc_url($project_meta['project_url']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-small">
                    <?php esc_html_e('View Live', 'wp-custom-theme'); ?> ↗
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>