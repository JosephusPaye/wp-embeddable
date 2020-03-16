<?php
    // Disable unnecessary stuff from wp_head()
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
    remove_action( 'wp_head', 'wp_generator' );

	$disable_wp_head = boolval(get_post_meta(get_the_ID(), 'wp_embeddable_disable_wp_head', $single = true));
	$disable_wp_footer = boolval(get_post_meta(get_the_ID(), 'wp_embeddable_disable_wp_footer', $single = true));
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		if ($disable_wp_head) {
	?>
		<title><?php echo get_the_title(); ?></title>
	<?php
		} else {
			wp_head();
		}
	?>
</head>
<body>
<?php
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
?>
<?php
	if (!$disable_wp_footer) {
		wp_footer();
	}
?>
</body>
</html>
