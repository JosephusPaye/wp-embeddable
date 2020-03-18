# WP Embeddable

💠 Embed any content from your WordPress site into posts, pages, or other sites. WP_PLUGIN_DIR_LINK

<!-- [![Promotional screenshot of WP Embeddable Plugin](./screenshot.png)](WP_PLUGIN_DIR_LINK) -->

This project is part of [#CreateWeekly](https://dev.to/josephuspaye/createweekly-create-something-new-publicly-every-week-in-2020-1nh9), my attempt to create something new publicly every week in 2020.

## Features

WP Embeddable is a WordPress plugin that allows you to embed any content from your WordPress site into posts, pages, or other sites.

For example, you can use the plugin to create embeddables for contact forms on your site that can be embedded in other sites.

For another example, you can create an embeddable with content from a plugin, like a calendar widget. This embeddable can then be used on page on the same site, serving as a way to isolate content from the plugin in an `<iframe>`. This can be useful for avoiding CSS style clashes when using page builders with 3rd-party plugins.

The plugin allows you to disable `wp_head()` and `wp_footer()` for an embeddable. This is useful for removing all WordPress and third-party scripts and styles from the page header and footer.

## To-Do

-   [x] Add shortcode: `[embeddable 121 autosize width="100%" height="200px"]`
-   [ ] Show shortcode on embeddable edit screen for copy & paste
-   [ ] Add Embeddable block
-   [ ] Add support for classic editor
-   [ ] Publish to WP Plugins Directory

## Contributing

See [contribution guide](CONTRIBUTING.md).

## Licence

[GPLv2](LICENCE)
