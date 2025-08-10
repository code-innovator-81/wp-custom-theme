<?php
    /**
     * Template for displaying single project posts.
     */
    get_header();
?>

<main id="primary" class="site-main">
    <?php
        while (have_posts()) {
            the_post();
            $project_meta = wp_custom_get_project_meta(get_the_ID());
        ?>
		        <article id="post-<?php the_ID(); ?>"<?php post_class('single-project'); ?>>
		            <header class="entry-header">
		                <h1 class="entry-title"><?php the_title(); ?></h1>

		                <?php if (! empty($project_meta['project_name']) && $project_meta['project_name'] !== get_the_title()) {?>
		                    <p class="project-subtitle"><?php echo esc_html($project_meta['project_name']); ?></p>
		                <?php }?>

                <div class="project-navigation">
                    <?php
                        $prev_project = get_previous_post(false, '', 'project_category');
                            $next_project = get_next_post(false, '', 'project_category');

                            if ($prev_project) {
                            ?>
                        <a href="<?php echo get_permalink($prev_project->ID); ?>" class="prev-project">
                            ←                                                                                                                             <?php echo get_the_title($prev_project->ID); ?>
                        </a>
                    <?php }?>

                    <a href="<?php echo get_post_type_archive_link('project'); ?>" class="all-projects">
                        <?php esc_html_e('All Projects', 'wp-custom-theme'); ?>
                    </a>

                    <?php if ($next_project) {?>
                        <a href="<?php echo get_permalink($next_project->ID); ?>" class="next-project">
                            <?php echo get_the_title($next_project->ID); ?> →
                        </a>
                    <?php }?>
                </div>
            </header>

            <?php if (has_post_thumbnail()) {?>
                <div class="project-featured-image">
                    <?php the_post_thumbnail('project-large'); ?>
                </div>
            <?php }?>

            <div class="project-meta-details">
                <div class="project-meta">
                    <?php if (! empty($project_meta['project_start_date'])) {?>
                        <div class="project-meta-item">
                            <span class="project-meta-label"><?php esc_html_e('Start Date', 'wp-custom-theme'); ?></span>
                            <span class="project-meta-value"><?php echo wp_custom_format_project_date($project_meta['project_start_date']); ?></span>
                        </div>
                    <?php }?>

                    <?php if (! empty($project_meta['project_end_date'])) {?>
                        <div class="project-meta-item">
                            <span class="project-meta-label"><?php esc_html_e('End Date', 'wp-custom-theme'); ?></span>
                            <span class="project-meta-value"><?php echo wp_custom_format_project_date($project_meta['project_end_date']); ?></span>
                        </div>
                    <?php }?>

                    <?php if (! empty($project_meta['project_url'])) {?>
                        <div class="project-meta-item">
                            <span class="project-meta-label"><?php esc_html_e('Project URL', 'wp-custom-theme'); ?></span>
                            <span class="project-meta-value">
                                <a href="<?php echo esc_url($project_meta['project_url']); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html($project_meta['project_url']); ?>
                                </a>
                            </span>
                        </div>
                    <?php }?>

                    <?php
                        $categories = get_the_terms(get_the_ID(), 'project_category');
                            if ($categories && ! is_wp_error($categories)) {
                            ?>
                        <div class="project-meta-item">
                            <span class="project-meta-label"><?php esc_html_e('Categories', 'wp-custom-theme'); ?></span>
                            <span class="project-meta-value">
                                <?php
                                    $category_links = [];
                                            foreach ($categories as $category) {
                                                $category_links[] = '<a href="' . get_term_link($category) . '">' . $category->name . '</a>';
                                            }
                                            echo implode(', ', $category_links);
                                        ?>
                            </span>
                        </div>
                    <?php }?>
                </div>
            </div>

            <?php if (! empty($project_meta['project_description'])) {?>
                <div class="project-description">
                    <h2><?php esc_html_e('Project Description', 'wp-custom-theme'); ?></h2>
                    <p><?php echo esc_html($project_meta['project_description']); ?></p>
                </div>
            <?php }?>

            <div class="entry-content">
                <?php
                    the_content();

                        wp_link_pages([
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'wp-custom-theme'),
                            'after'  => '</div>',
                        ]);
                    ?>
            </div>

            <?php if (! empty($project_meta['project_url'])) {?>
                <div class="project-cta">
                    <a href="<?php echo esc_url($project_meta['project_url']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                        <?php esc_html_e('View Live Project', 'wp-custom-theme'); ?> →
                    </a>
                </div>
            <?php }?>

            <footer class="entry-footer">
                <div class="project-sharing">
                    <h3><?php esc_html_e('Share this project', 'wp-custom-theme'); ?></h3>
                    <div class="sharing-buttons">
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share-twitter">
                            <?php esc_html_e('Share on Twitter', 'wp-custom-theme'); ?>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="share-linkedin">
                            <?php esc_html_e('Share on LinkedIn', 'wp-custom-theme'); ?>
                        </a>
                    </div>
                </div>
            </footer>
        </article>

        <?php
            // Related Projects
                if ($categories) {
                    $category_ids = [];
                    foreach ($categories as $category) {
                        $category_ids[] = $category->term_id;
                    }

                    $related_projects = new WP_Query([
                        'post_type'      => 'project',
                        'posts_per_page' => 3,
                        'post__not_in'   => [get_the_ID()],
                        'tax_query'      => [
                            [
                                'taxonomy' => 'project_category',
                                'field'    => 'term_id',
                                'terms'    => $category_ids,
                            ],
                        ],
                    ]);

                    if ($related_projects->have_posts()) {
                    ?>
                <section class="related-projects">
                    <h2><?php esc_html_e('Related Projects', 'wp-custom-theme'); ?></h2>
                    <div class="related-projects-grid">
                        <?php
                            while ($related_projects->have_posts()) {
                                            $related_projects->the_post();
                                            $related_meta = wp_custom_get_project_meta(get_the_ID());
                                        ?>
		                            <div class="related-project-item">
		                                <?php if (has_post_thumbnail()) {?>
		                                    <div class="related-project-thumbnail">
		                                        <a href="<?php the_permalink(); ?>">
		                                            <?php the_post_thumbnail('project-thumbnail'); ?>
		                                        </a>
		                                    </div>
		                                <?php }?>

                                <div class="related-project-content">
                                    <h3 class="related-project-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>

                                    <?php if (! empty($related_meta['project_description'])) {?>
                                        <p class="related-project-excerpt">
                                            <?php echo wp_trim_words(wp_custom_escape($related_meta['project_description']), 15); ?>
                                        </p>
                                    <?php }?>
                                </div>
                            </div>
                            <?php
                                }
                                            wp_reset_postdata();
                                        ?>
                    </div>
                </section>
                <?php
                    }
                        }
                    ?>

    <?php }?>
</main>

<style>
.single-project {
    margin: 0 auto;
}

.project-subtitle {
    font-size: 1.2rem;
    color: #666;
    margin: 0.5rem 0 1.5rem 0;
}

.project-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 1rem 0 2rem 0;
    padding: 1rem;
    background: #f9f9f9;
    border-radius: 5px;
}

.project-navigation a {
    text-decoration: none;
    color: #333;
    padding: 0.5rem 1rem;
    border-radius: 3px;
    transition: background 0.3s ease;
}

.project-navigation a:hover {
    background: #333;
    color: #fff;
}

.all-projects {
    font-weight: bold;
}

.project-featured-image {
    margin: 2rem 0;
    text-align: center;
}

.project-featured-image img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.project-meta-details {
    margin: 2rem 0;
}

.project-meta {
    background: #f9f9f9;
    padding: 1.5rem;
    border-radius: 8px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.project-meta-item {
    display: flex;
    flex-direction: column;
}

.project-meta-label {
    font-weight: bold;
    color: #666;
    font-size: 0.85rem;
    text-transform: uppercase;
    margin-bottom: 0.25rem;
}

.project-meta-value {
    color: #333;
    font-size: 1rem;
}

.project-meta-value a {
    color: #0073aa;
    text-decoration: none;
}

.project-meta-value a:hover {
    text-decoration: underline;
}

.project-description {
    margin: 2rem 0;
    padding: 1.5rem;
    background: #fff;
    border-left: 4px solid #333;
}

.project-description h2 {
    margin-bottom: 1rem;
    color: #333;
}

.project-cta {
    text-align: center;
    margin: 2rem 0;
}

.btn-primary {
    background: #0073aa;
    color: #fff;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: #005a87;
}

.project-sharing {
    margin: 2rem 0;
    text-align: center;
    padding: 1.5rem;
    background: #f9f9f9;
    border-radius: 8px;
}

.sharing-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
}

.sharing-buttons a {
    padding: 0.5rem 1rem;
    background: #333;
    color: #fff;
    text-decoration: none;
    border-radius: 3px;
    transition: background 0.3s ease;
}

.share-twitter:hover {
    background: #1da1f2;
}

.share-linkedin:hover {
    background: #0077b5;
}

.related-projects {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid #eee;
}

.related-projects h2 {
    text-align: center;
    margin-bottom: 2rem;
}

.related-projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.related-project-item {
    background: #f9f9f9;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.related-project-item:hover {
    transform: translateY(-5px);
}

.related-project-thumbnail img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.related-project-content {
    padding: 1rem;
}

.related-project-title {
    margin-bottom: 0.5rem;
}

.related-project-title a {
    color: #333;
    text-decoration: none;
}

.related-project-title a:hover {
    color: #0073aa;
}

.related-project-excerpt {
    color: #666;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .project-navigation {
        flex-direction: column;
        gap: 1rem;
    }

    .project-meta {
        grid-template-columns: 1fr;
    }

    .sharing-buttons {
        flex-direction: column;
    }

    .related-projects-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
get_footer();
