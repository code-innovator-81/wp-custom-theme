<?php
/* Template Name: Blog */
get_header();
?>
<div class="blog-list">
    <h2>Blog Posts</h2>
    <?php
    $args = ['post_type' => 'post', 'posts_per_page' => 10];
    $query = new WP_Query($args);
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            ?>
            <article>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div><?php the_excerpt(); ?></div>
            </article>
            <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>No blogs found.</p>';
    endif;
    ?>
</div>
<?php get_footer(); ?>