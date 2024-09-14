# WooCommerce Subscriptions Importer with WP-CLI (Multisite Support)

## Description
The WooCommerce Subscriptions Importer plugin allows you to import WooCommerce subscriptions across a WordPress multisite network using WP-CLI commands. It's designed to streamline bulk imports and subscription management for large eCommerce stores, simplifying the migration and setup process across multiple sites in your network.

### Features:
- Import WooCommerce subscriptions using a CSV file via WP-CLI.
- Fully compatible with WordPress multisite networks.
- Easy to use and highly efficient for large-scale stores.
- Supports the latest versions of WordPress and WooCommerce.

## Installation

1. Upload the plugin or copy folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. (Optional) If you are using a multisite network, ensure the plugin is activated network-wide.

The plugin includes a sample subscriptions.csv file in the root directory. It serves as a template for importing subscription products.

## WP-CLI Command

To import subscriptions, run the following command:

```bash
wp import-products --file="[FILE_PATH_CSV]" --network