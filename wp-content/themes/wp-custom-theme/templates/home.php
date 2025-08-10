<?php
/**
 * Template Name: Home Page
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
        
        <!-- Featured Projects Section -->
        <section class="featured-projects">
            <h2><?php esc_html_e('Featured Projects', 'wp-custom-theme'); ?></h2>
            
            <?php
            $featured_projects = new WP_Query(array(
                'post_type' => 'project',
                'posts_per_page' => 6,
                'meta_query' => array(
                    array(
                        'key' => '_featured_project',
                        'value' => '1',
                        'compare' => '='
                    )
                )
            ));
            
            if ($featured_projects->have_posts()) :
                echo '<div class="projects-grid">';
                while ($featured_projects->have_posts()) :
                    $featured_projects->the_post();
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
                                <p class="project-description">
                                    <?php echo wp_trim_words(esc_html($project_meta['project_description']), 20); ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="project-meta">
                                <?php if (!empty($project_meta['project_start_date'])) : ?>
                                    <div class="project-meta-item">
                                        <span class="project-meta-label"><?php esc_html_e('Start Date', 'wp-custom-theme'); ?></span>
                                        <span class="project-meta-value"><?php echo wp_custom_format_project_date($project_meta['project_start_date']); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($project_meta['project_url'])) : ?>
                                    <div class="project-meta-item">
                                        <a href="<?php echo esc_url($project_meta['project_url']); ?>" target="_blank" class="btn">
                                            <?php esc_html_e('View Project', 'wp-custom-theme'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                echo '</div>';
                
                wp_reset_postdata();
            else :
                // Show latest projects if no featured projects
                $latest_projects = new WP_Query(array(
                    'post_type' => 'project',
                    'posts_per_page' => 6
                ));
                
                if ($latest_projects->have_posts()) :
                    echo '<div class="projects-grid">';
                    while ($latest_projects->have_posts()) :
                        $latest_projects->the_post();
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
                                    <p class="project-description">
                                        <?php echo wp_trim_words(esc_html($project_meta['project_description']), 20); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    echo '</div>';
                    
                    wp_reset_postdata();
                endif;
            endif;
            ?>
            
            <div class="view-all-projects">
                <a href="<?php echo esc_url(get_post_type_archive_link('project')); ?>" class="btn">
                    <?php esc_html_e('View All Projects', 'wp-custom-theme'); ?>
                </a>
            </div>
        </section>
        <?php
    endwhile;
    ?>
</main>

<style>
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.project-item {
    background: #f9f9f9;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.project-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.project-thumbnail img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.project-content {
    padding: 1.5rem;
}

.project-title {
    margin-bottom: 0.5rem;
}

.project-title a {
    color: #333;
    text-decoration: none;
}

.project-title a:hover {
    color: #0073aa;
}

.project-description {
    color: #666;
    margin-bottom: 1rem;
}

.view-all-projects {
    text-align: center;
    margin-top: 2rem;
}
</style>

<?php
get_footer();