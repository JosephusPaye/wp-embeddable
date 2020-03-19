<?php

$wpEmbeddableMetaBox = [
    'key' => 'wp_embeddable_update_post_metabox',
    'nonce' => 'wp_embeddable_update_post_nonce',
    'label' => 'Embeddable Options',
    'location' => 'side',
];

$wpEmbeddableOptionsMetaboxHtml = function ($post) use ($wpEmbeddableMetaFields, $wpEmbeddableMetaBox) {
    wp_nonce_field($wpEmbeddableMetaBox['key'], $wpEmbeddableMetaBox['nonce']);

    foreach ($wpEmbeddableMetaFields as $fieldName => $fieldOptions) {
        $fieldValue = get_post_meta($post->ID, $fieldName, $single = true);
        $fieldValue = $fieldValue == null ? $fieldOptions['default'] : $fieldValue;
?>
        <div>
            <div class="post-attributes-label" style="margin-bottom: 4px;"><?php echo $fieldOptions['label'] ?></div>
            <label class="selectit">
                <input value="1" type="checkbox" name="<?php echo $fieldName ?>" <?php echo boolval($fieldValue) ? 'checked="checked"' : '' ?>> <?php echo $fieldOptions['help'] ?>
            </label>
        </div>
<?php
    }
};

add_action('add_meta_boxes', function () use ($wpEmbeddablePostTypeKey, $wpEmbeddableMetaBox, $wpEmbeddableOptionsMetaboxHtml) {
    add_meta_box(
        $wpEmbeddableMetaBox['key'],
        $wpEmbeddableMetaBox['label'],
        $wpEmbeddableOptionsMetaboxHtml,
        $wpEmbeddablePostTypeKey,
        $wpEmbeddableMetaBox['location']
    );
});

add_action('save_post', function ($postId) use ($wpEmbeddablePostTypeKey, $wpEmbeddableMetaBox, $wpEmbeddableMetaFields) {
    global $post;
    if ($post->post_type != $wpEmbeddablePostTypeKey) {
        return;
    }

    if (!current_user_can('edit_posts', $postId)) {
        return;
    }

    if (
        !isset($_POST[$wpEmbeddableMetaBox['nonce']]) ||
        !wp_verify_nonce($_POST[$wpEmbeddableMetaBox['nonce']], $wpEmbeddableMetaBox['key'])
    ) {
        return;
    }

    foreach ($wpEmbeddableMetaFields as $fieldName => $fieldOptions) {
        update_post_meta(
            $postId,
            $fieldName,
            array_key_exists($fieldName, $_POST) ? $_POST[$fieldName] : $fieldOptions['default']
        );
    }
}, null, 1);
