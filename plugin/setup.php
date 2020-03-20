<?php

/* Exit if accessed directly */
if (!defined('ABSPATH')) {
    return;
}

$wpEmbeddablePostTypeKey = 'embeddable';

$wpEmbeddableMetaFields = [
    '_wp_embeddable_disable_wp_head' => [
        'type' => 'boolean',
        'label' => 'Disable wp_head()',
        'help' => 'Disable scripts and styles from the page header',
        'default' => '0',
    ],
    '_wp_embeddable_disable_wp_footer' => [
        'type' => 'boolean',
        'label' => 'Disable wp_footer()',
        'help' => 'Disable scripts and styles from the page footer',
        'default' => '0',
    ],
];

$wpEmbeddableAssets = [
    'wp-embeddable-sidebar.js' => [
        'basename' => 'index',
        'enqueue_for_editor' => true,
    ],
    'wp-embeddable-resize-frame.js' => [
        'basename' => 'resize-frame',
        'enqueue_for_editor' => false,
    ],
];

add_action('init', function () use ($wpEmbeddablePostTypeKey, $wpEmbeddableMetaFields, $wpEmbeddableAssets) {
    // Register the custom post type
    register_post_type($wpEmbeddablePostTypeKey, [
        'description' => 'Embeddable content for use in posts, pages, or other sites.',
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
        'supports' => ['title', 'editor', 'custom-fields'],
    ]);

    // Register the custom meta fields for an embeddable
    foreach ($wpEmbeddableMetaFields as $fieldName => $fieldOptions) {
        register_post_meta($wpEmbeddablePostTypeKey, $fieldName, [
            'show_in_rest' => true,
            'type' => $fieldOptions['type'],
            'single' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            }
        ]);
    }

    // Register the frontend assets
    foreach ($wpEmbeddableAssets as $assetKey => $assetOptions) {
        $assetBasename = $assetOptions['basename'];
        $meta = include(plugin_dir_path(__DIR__) . "build/$assetBasename.asset.php");
        wp_register_script(
            $assetKey,
            plugins_url("build/$assetBasename.js", __DIR__),
            $meta['dependencies'],
            $meta['version']
        );
    }
});

// ================================================
// Block editor assets
// ================================================

add_action('enqueue_block_editor_assets', function () use ($wpEmbeddableAssets) {
    foreach ($wpEmbeddableAssets as $assetKey => $assetOptions) {
        if ($assetOptions['enqueue_for_editor']) {
            wp_enqueue_script($assetKey);
        }
    }
});

// ================================================
// Embeddable renderer (with custom template)
// ================================================

add_filter('single_template', function ($single) use ($wpEmbeddablePostTypeKey) {
    global $post;

    if ($post->post_type == $wpEmbeddablePostTypeKey) {
        $renderPath = plugin_dir_path(__FILE__) . 'render.php';
        if (file_exists($renderPath)) {
            return $renderPath;
        }
    }

    return $single;
});

// ================================================
// Shortcode and metaboxes
// ================================================

require(__DIR__ . '/shortcode.php');
require(__DIR__ . '/metaboxes.php');
