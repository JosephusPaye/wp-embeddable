<?php

/* Exit if accessed directly */
if (!defined('ABSPATH')) {
    return;
}

// Disable the Admin Bar
show_admin_bar(false);
add_filter('show_admin_bar', '__return_false');
remove_action('init', 'wp_admin_bar_init');
remove_action('wp_head', '_admin_bar_bump_cb');

// Disable unnecessary stuff from wp_head()
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'wp_generator');

$disableWpHead = boolval(get_post_meta(get_the_ID(), '_wp_embeddable_disable_wp_head', $single = true));
$disableWpFooter = boolval(get_post_meta(get_the_ID(), '_wp_embeddable_disable_wp_footer', $single = true));

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    if ($disableWpHead) {
        echo '<title>' . get_the_title() . '</title>';
    } else {
        wp_head();
    }
    ?>
</head>

<body>
    <?php
    while (have_posts()) : the_post();
        the_content();
    endwhile;

    if (!$disableWpFooter) {
        wp_footer();
    }
    ?>
</body>

</html>
