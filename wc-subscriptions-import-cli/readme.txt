=== WC Subscriptions Importer with WP-CLI ===
Contributors: noumik
Tags: WC, WP-CLI, Subscriptions, Multisite
Requires at least: 5.6
Tested up to: 6.6.2
Stable tag: 1.1.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Simplifies the process of importing WooCommerce subscription products using a CSV file and WP-CLI commands, with multisite support.

== Description ==
This plugin enables easy importing of WooCommerce subscriptions across a WordPress multisite network using WP-CLI. It's designed to simplify bulk imports and ensure smooth migration of subscription data, making it ideal for large WooCommerce stores or multisite setups. With a simple command, you can handle subscription imports across your entire network.

= Features =
* Bulk import WooCommerce subscriptions using a CSV file.
* Bulk update wc subscription if the same SKU is already imported
* Supports multisite networks.
* Easily integrates into WP-CLI for efficient management.
* Reduces manual data entry and speeds up migrations.
* Ideal for large eCommerce networks running WooCommerce.

= WP-CLI Command =
WP-CLI command is available to import subscriptions and supports multisite environments.

= Support =
* If you have more complex requirements, I will be happy to help you. If you have any questions please feel free to get in touch.

== Installation ==
1. Upload the plugin or copy folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. (Optional) If you are using a multisite network, ensure the plugin is activated network-wide.

== Important Note ==
* The plugin includes a sample `subscriptions.csv` file in the root directory. It serves as a template for importing subscription products.
* This plugin requires WooCommerce Subscriptions plugin to be installed and enabled.

== Changelog ==
= 1.1.3 =
**Added**