<?php

/**
 * Plugin Name:     WP Embeddable
 * Plugin URI:      https://github.com/JosephusPaye/wp-embeddable
 * Description:     Create and embed any content from your WordPress site into posts, pages, or other sites.
 * Author:          Josephus Paye II
 * Author URI:      https://twitter.com/JosephusPaye
 * Text Domain:     wp-embeddable
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wp_Embeddable
 */

/* Exit if accessed directly */
if (!defined('ABSPATH')) {
    return;
}

function wp_embeddable_log(...$values)
{
    foreach ($values as $value) {
        if (is_array($value) || is_object($value)) {
            error_log(print_r($value, true));
        } else {
            error_log($value);
        }
    }
}

require(plugin_dir_path(__FILE__) . 'plugin/setup.php');
