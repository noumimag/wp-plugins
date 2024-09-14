<?php
/*
Plugin Name: WC Subscriptions Importer with WP-CLI
Description: Easily import WC subscriptions across your WordPress multisite network using WP-CLI. This plugin simplifies the management of WC subscription data, supporting seamless migration and bulk import of subscriptions with just a few CLI commands. Perfect for large eCommerce stores and networks, this tool is optimized for performance and multisite compatibility, helping you efficiently manage multiple stores from one central dashboard.
Version: 1.1.3
Author: noumanii08@gmail.com
Requires at least: 6.0
Requires PHP: 7.4
*/

if (defined('WP_CLI') && WP_CLI) {
    include_once 'wc-subscriptions-import-cli-command.php';
}