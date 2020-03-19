<?php

add_shortcode('embeddable', function ($attrs) {
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
    $autosize = in_array('autosize', $attrs, true) || (array_key_exists('autosize', $attrs) && filter_var($attrs['autosize'], FILTER_VALIDATE_BOOLEAN));

    return wp_embeddable_generate_embed_code($post, $width, $height, $autosize);
});

function wp_embeddable_generate_embed_code($embeddablePost, $width = '', $height = '', $autosize = false)
{
    $permalink = get_post_permalink($embeddablePost);

    $embedCode = "<iframe "
        . (empty($width) ? '' : " width=\"$width\"")
        . (empty($height) ? '' : " height=\"$height\"")
        . " src=\"$permalink\""
        . " frameborder=\"0\""
        . " allowfullscreen "
        . ($autosize
            ? 'data-embeddable-autosize onload="window.wpEmbeddableResizeFrame ? wpEmbeddableResizeFrame(this) : this.setAttribute(\'data-loaded\', true)"'
            : '')
        . "></iframe>";

    if ($autosize) {
        wp_enqueue_script('wp-embeddable-resize-frame.js');
    }

    return $embedCode;
}
