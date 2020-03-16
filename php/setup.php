<?php

add_action('init', 'wp_embeddables_register_post_type');

function wp_embeddables_register_post_type() {
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

    // Register our script to add the sidebar panel in the block editor
    $asset_file = include( plugin_dir_path( __DIR__ ) . 'build/index.asset.php');
    wp_register_script(
        'wp-embeddable-sidebar.js',
        plugins_url( 'build/index.js', __DIR__ ),
        $asset_file['dependencies'],
        $asset_file['version']
    );
}

// ================================================
// Block editor assets
// ================================================

add_action( 'enqueue_block_editor_assets', 'wp_embeddables_enqueue_editor_assets' );

function wp_embeddables_enqueue_editor_assets() {
    wp_enqueue_script( 'wp-embeddable-sidebar.js' );
}

// ================================================
// Embeddable renderer (with custom template)
// ================================================

add_filter('single_template', 'wp_embeddables_render_custom_template');

function wp_embeddables_render_custom_template($single) {
    global $post;

    if ( $post->post_type == 'embeddable' ) {
        $renderPath = plugin_dir_path(__FILE__) . 'render.php';
        if ( file_exists( $renderPath ) ) {
            return $renderPath;
        }
    }

    return $single;
}
