<?php

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

add_action('init', 'wp_embeddable_register_post_type');

function wp_embeddable_register_post_type() {
    // Register the Embeddable post type
    register_post_type('embeddable', [
        'description' => 'Embeddable content that can be embedded in posts, pages, or other sites.',
        'public' => true,
        'exclude_from_search' => true,
        'has_archive' => false,
        'show_in_nav_menus' => false,
        'show_in_rest' => true,
        'labels' => [
            'name' => 'Embeddables',
            'singular_name' => 'Embeddable',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Embeddable',
            'edit' => 'Edit',
            'edit_item' => 'Edit Embeddable',
            'new_item' => 'New Embeddable',
            'view' => 'View',
            'view_item' => 'View Embeddable',
            'search_items' => 'Search Embeddables',
            'not_found' => 'No embeddable found',
            'not_found_in_trash' => 'No embeddable found in Trash'
        ],
        'menu_icon' => 'dashicons-feedback',
        'menu_position' => 20, // Below 'Pages'
        'supports' => ['title', 'editor', 'custom-fields', 'revisions'],
    ]);

    // Register the custom meta fields for an Embeddable
    register_post_meta('embeddable', 'wp_embeddable_disable_wp_head', [
        'show_in_rest' => true,
        'type' => 'boolean',
        'single' => true,
    ]);

    register_post_meta('embeddable', 'wp_embeddable_disable_wp_footer', [
        'show_in_rest' => true,
        'type' => 'boolean',
        'single' => true,
    ]);

    // Register the script to add the sidebar panel in the block editor
    $indexAsset = include( plugin_dir_path( __DIR__ ) . 'build/index.asset.php');
    wp_register_script(
        'wp-embeddable-sidebar.js',
        plugins_url( 'build/index.js', __DIR__ ),
        $indexAsset['dependencies'],
        $indexAsset['version']
    );

    // Register the script to auto size an embeddable's iframe
    $resizeFrameAsset = include( plugin_dir_path( __DIR__ ) . 'build/resize-frame.asset.php');
    wp_register_script(
        'wp-embeddable-resize-frame.js',
        plugins_url( 'build/resize-frame.js', __DIR__ ),
        $resizeFrameAsset['dependencies'],
        $resizeFrameAsset['version']
    );
}


// ================================================
// Shortcode
// ================================================

add_shortcode( 'embeddable', 'wp_embeddable_shortcode' );

function wp_embeddable_shortcode( $attrs ) {
    if (count($attrs) == 0) {
        return '';
    }

    $id = intval($attrs[0]);

    if ($id == 0) {
        return '';
    }

    $post = get_post($id);

    if ($post == null) {
        return '';
    }

    $width = array_key_exists('width', $attrs) ? $attrs['width'] : '';
    $height = array_key_exists('height', $attrs) ? $attrs['height'] : '';
    $autosize = in_array('autosize', $attrs, true) || (
        array_key_exists('autosize', $attrs) && filter_var($attrs['autosize'], FILTER_VALIDATE_BOOLEAN)
    );

    return wp_embeddable_generate_embed_code($post, $width, $height, $autosize);
}

function wp_embeddable_generate_embed_code($embeddablePost, $width = '', $height = '', $autosize = false) {
    $permalink = get_post_permalink($embeddablePost);

    $embedCode = "<iframe width=\"$width\" height=\"$height\" src=\"$permalink\" frameborder=\"0\" allowfullscreen " . ($autosize ? 'data-embeddable-autosize onload="window.wpEmbeddableResizeFrame ? wpEmbeddableResizeFrame(this) : this.setAttribute(\'data-loaded\', true)"' : '') . "></iframe>";

    if ($autosize) {
        wp_enqueue_script( 'wp-embeddable-resize-frame.js' );
    }

    return $embedCode;
}

// ================================================
// Block editor assets
// ================================================

add_action( 'enqueue_block_editor_assets', 'wp_embeddable_enqueue_editor_assets' );

function wp_embeddable_enqueue_editor_assets() {
    wp_enqueue_script( 'wp-embeddable-sidebar.js' );
}

// ================================================
// Embeddable renderer (with custom template)
// ================================================

add_filter('single_template', 'wp_embeddable_render_custom_template');

function wp_embeddable_render_custom_template($single) {
    global $post;

    if ( $post->post_type == 'embeddable' ) {
        $renderPath = plugin_dir_path(__FILE__) . 'render.php';
        if ( file_exists( $renderPath ) ) {
            return $renderPath;
        }
    }

    return $single;
}
