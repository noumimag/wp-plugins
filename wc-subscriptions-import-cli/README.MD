# WC Subscriptions Importer with WP-CLI (Multisite Support)

## Description
The WC Subscriptions Importer plugin allows you to import WC subscriptions across a WordPress multisite network using WP-CLI commands. It's designed to streamline bulk imports and subscription management for large eCommerce stores, simplifying the migration and setup process across multiple sites in your network.

### Features:
- Import WC subscriptions using a CSV file via WP-CLI.
- Fully compatible with WordPress multisite networks.
- Easy to use and highly efficient for large-scale stores.
- Supports the latest versions of WordPress and WC.

## Installation

1. Upload the plugin or copy folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. (Optional) If you are using a multisite network, ensure the plugin is activated network-wide.


Important Note:
The plugin includes a sample subscriptions.csv file in the root directory. It serves as a template for importing subscription products.

This plugin requires WC subscriptions plugin to installed and enabled.

## WP-CLI Command

To import subscriptions, run the following command:

[FILE_PATH_CSV] is the path to the CSV template, you can use the basic tempalte that includes all the supported fields for importing/updating WC subscriptions.

```bash
wp import-products --file="[FILE_PATH_CSV]" --network