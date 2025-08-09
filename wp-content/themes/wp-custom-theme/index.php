<?php
/**
 * The main template file
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="content-area">
        <div class="main-content">
            <?php
            if (have_posts()) :
                if (is_home() && !is_front_page()) :
                    ?>
                    <header class="page-header">
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;
                
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php
                            if (is_singular()) :
                                the_title('<h1 class="entry-title">', '</h1>');
                            else :
                                the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                            endif;
                            
                            if ('post' === get_post_type()) :
                                ?>
                                <div class="entry-meta">
                                    <span class="posted-on">
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="byline">
                                        by <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <?php the_author(); ?>
                                        </a>
                                    </span>
                                </div>
                                <?php
                            endif;
                            ?>
                        </header>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <?php the_post_thumbnail(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="entry-content">
                            <?php
                            if (is_singular()) {
                                the_content();
                            } else {
                                the_excerpt();
                                ?>
                                <p><a href="<?php echo esc_url(get_permalink()); ?>" class="btn">
                                    <?php esc_html_e('Read More', 'wp-custom-theme'); ?>
                                </a></p>
                                <?php
                            }
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'wp-custom-theme'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>
                        
                        <?php if (get_edit_post_link()) : ?>
                            <footer class="entry-footer">
                                <a href="<?php echo esc_url(get_edit_post_link()); ?>">
                                    <?php esc_html_e('Edit', 'wp-custom-theme'); ?>
                                </a>
                            </footer>
                        <?php endif; ?>
                    </article>
                    <?php
                endwhile;
                
                the_posts_navigation();
                
            else :
                ?>
                <section class="no-results not-found">
                    <header class="page-header">
                        <h1 class="page-title"><?php esc_html_e('Nothing here', 'wp-custom-theme'); ?></h1>
                    </header>
                    
                    <div class="page-content">
                        <?php if (is_home() && current_user_can('publish_posts')) : ?>
                            <p><?php
                                printf(
                                    wp_kses(
                                        __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'wp-custom-theme'),
                                        array(
                                            'a' => array(
                                                'href' => array(),
                                            ),
                                        )
                                    ),
                                    esc_url(admin_url('post-new.php'))
                                );
                                ?></p>
                        <?php elseif (is_search()) : ?>
                            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'wp-custom-theme'); ?></p>
                            <?php get_search_form(); ?>
                        <?php else : ?>
                            <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'wp-custom-theme'); ?></p>
                            <?php get_search_form(); ?>
                        <?php endif; ?>
                    </div>
                </section>
                <?php
            endif;
            ?>
        </div>
        
        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();