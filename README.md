# WP Embeddable

💠 Embed any content from your WordPress site into posts, pages, or other sites. https://wordpress.org/plugins/wp-embeddable

<!-- [![Promotional screenshot of WP Embeddable Plugin](./screenshot.png)](https://wordpress.org/plugins/wp-embeddable) -->

This project is part of [#CreateWeekly](https://dev.to/josephuspaye/createweekly-create-something-new-publicly-every-week-in-2020-1nh9), my attempt to create something new publicly every week in 2020.

## Features

WP Embeddable is a WordPress plugin that allows you to create "embeddables" (kinda like embed codes) for any content on your WordPress site, to embed into posts, pages, or other sites.

You can use the plugin to:

-   Create embeddables for contact forms on your site that can be embedded in other sites.
-   Create an embeddable with content from a plugin, like a calendar widget. This embeddable can then be used on a page on the same site, providing a way to isolate content from the plugin in an `<iframe>`. This can be useful for avoiding CSS style clashes when using different 3rd-party plugins and themes that have conflicting styles.

The plugin allows you to disable `wp_head()` and `wp_footer()` for an embeddable. This is useful for removing all WordPress and third-party scripts and styles from the page header and footer.

## Installation

Upload the WP Embeddable plugin to your site and activate it.

## Usage

-   Install and activate the plugin.
-   Go to the **Embeddables** section in the WordPress dashboard and add a new embeddable.
-   Use the editor to create the content you want in the embeddable, and publish when done.
-   In the sidebar, under the **Embeddable Usage** section, you can copy the shortcode or embed code:
    -   Copy the shortcode for use in a page or post on the same site.
    -   Copy the embed code for use on another site.

## What's next

-   [x] Add shortcode: `[embeddable 121 autosize width="100%" height="200px"]`
-   [x] Show shortcode and embed code on embeddable edit screen for copy & paste

### Extension

-   [x] Add support for classic editor
    -   [x] Options metabox
    -   [x] Shortcode and embed code metabox
-   [ ] Add Embeddable block
-   [ ] Figure out how to opt-out of Gutenberg automatically changing the width & height of every iframe to be responsive while keeping aspect ratio

## Contributing

See [contribution guide](CONTRIBUTING.md).

## Licence

[GPLv2](LICENCE) or later
