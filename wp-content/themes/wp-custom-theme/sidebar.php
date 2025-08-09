<?php
/**
 * The sidebar containing the main widget area
 */

if (!is_active_sidebar('primary-sidebar')) {
    return;
}
?>

<aside id="secondary" class="widget-area">
    <?php dynamic_sidebar('primary-sidebar'); ?>
</aside>