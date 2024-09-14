<?php

use WP_CLI\Utils;
use WP_CLI\Formatter;

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('import-products', 'import_products_from_csv');
}

function import_products_from_csv($args, $assoc_args) {
    if (!is_multisite()) {
        WP_CLI::error('This is not a multisite installation.');
    }

    $file = isset($assoc_args['file']) ? $assoc_args['file'] : '';
    $network = isset($assoc_args['network']) ? true : false;
    $url = isset($assoc_args['url']) ? $assoc_args['url'] : '';

    if (empty($file) || !file_exists($file)) {
        WP_CLI::error('Please provide a valid CSV file path.');
    }

    if ($network) {
        import_products_across_network($file);
    } elseif (!empty($url)) {
        import_products_for_single_site($file, $url);
    } else {
        WP_CLI::error('Please specify either --network or --url=<domain>.');
    }
}

function import_products_across_network($file) {
    $sites = get_sites(['number' => 0]); // Get all sites
    $total_sites = count($sites);
    $progress = Utils\make_progress_bar('Importing products across network', $total_sites);

    foreach ($sites as $site) {
        $blog_id = $site->blog_id;
        switch_to_blog($blog_id);

        WP_CLI::log("Importing products for site: " . get_bloginfo('url'));

        import_products_from_file($file);

        restore_current_blog();
        $progress->tick();
    }

    $progress->finish();
    WP_CLI::success('Products imported across all sites.');
}

function import_products_for_single_site($file, $url) {
    $sites = get_sites(['number' => 0]); // Get all sites
    $found = false;

    foreach ($sites as $site) {
        $blog_id = $site->blog_id;
        switch_to_blog($blog_id);

        if (get_bloginfo('url') === $url) {
            WP_CLI::log("Importing products for site: " . $url);

            import_products_from_file($file);

            restore_current_blog();
            WP_CLI::success('Products imported successfully for site: ' . $url);
            $found = true;
            break;
        }

        restore_current_blog();
    }

    if (!$found) {
        WP_CLI::error('No site found with the specified URL: ' . $url);
    }
}

function import_products_from_file($file) {
    if (($handle = fopen($file, 'r')) !== FALSE) {
        $header = fgetcsv($handle, 1000, ',');
        $rows = [];

        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (count($header) !== count($row)) {
                WP_CLI::warning('Skipping row due to column mismatch: ' . implode(',', $row));
                continue;
            }
            $rows[] = array_combine($header, $row);
        }

        fclose($handle);

        $total_rows = count($rows);
        $progress = Utils\make_progress_bar('Importing products', $total_rows);

        foreach ($rows as $data) {
            import_product($data);
            $progress->tick();
        }

        $progress->finish();
    } else {
        WP_CLI::warning('Failed to open the CSV file.');
    }
}

function import_product($data) {
    // Query for existing product with the SKU that is published
    $args = array(
        'post_type'   => 'product',
        'meta_key'    => '_sku',
        'meta_value'  => $data['SKU'],
        'post_status' => 'publish',
        'fields'      => 'ids'
    );

    $product_query = new WP_Query($args);
    $product_id = !empty($product_query->posts) ? $product_query->posts[0] : 0;

    if ($product_id) {
        // If product exists, get the product object
        $product = wc_get_product($product_id);
        WP_CLI::log("Updating existing product: " . $data['SKU']);
    } else {
        // If product does not exist, create a new product
        $product = new WC_Product_Subscription();
        WP_CLI::log("Creating new product: " . $data['SKU']);
    }

    try {
        // Set SKU
        $product->set_sku($data['SKU']);
    } catch (WC_Data_Exception $e) {
        WP_CLI::warning('Error setting SKU: ' . $e->getMessage());
        return;
    }

    // Set product as virtual
    $product->set_virtual(true);
    
    // Basic product fields
    $product->set_name($data['Name']);
    $product->set_status($data['Published'] ? 'publish' : 'draft');
    $product->set_featured($data['Is featured?'] ? true : false);
    $product->set_catalog_visibility($data['Visibility in catalog']);
    $product->set_tax_status($data['Tax status']);
    $product->set_stock_status($data['In stock?'] ? 'instock' : 'outofstock');
    $product->set_regular_price($data['Regular price']);

    // Subscription-specific fields (set as metadata)
    $product->update_meta_data('_subscription_period', $data['_subscription_period']);
    $product->update_meta_data('_subscription_period_interval', $data['_subscription_period_interval']);
    $product->update_meta_data('_subscription_length', $data['_subscription_length']);
    $product->update_meta_data('_subscription_trial_length', $data['_subscription_trial_length']);
    $product->update_meta_data('_subscription_trial_period', $data['_subscription_trial_period']);
    $product->update_meta_data('_subscription_sign_up_fee', $data['_subscription_sign_up_fee']);
    $product->update_meta_data('_subscription_price', $data['_subscription_price']);
    $product->update_meta_data('_price', $data['_subscription_price']);
    $product->update_meta_data('_subscription_limit', $data['_subscription_limit']);
    $product->update_meta_data('_subscription_one_time_shipping', $data['_subscription_one_time_shipping']);
    
    // Membership specific fields
    $product->update_meta_data('_wc_memberships_use_custom_product_viewing_restricted_message', $data['_wc_memberships_use_custom_product_viewing_restricted_message']);
    $product->update_meta_data('_wc_memberships_use_custom_product_purchasing_restricted_message', $data['_wc_memberships_use_custom_product_purchasing_restricted_message']);
    $product->update_meta_data('_wc_memberships_force_public', $data['_wc_memberships_force_public']);
    $product->update_meta_data('_wc_memberships_exclude_discounts', $data['_wc_memberships_exclude_discounts']);
    
// Set product category to "membership"
$category = get_term_by('slug', 'membership', 'product_cat');

    if ($category) {
        wp_set_object_terms($product->get_id(), $category->term_id, 'product_cat');
    } else {
        // If category doesn't exist, create it
        $new_category = wp_insert_term(
            'membership',  // The term
            'product_cat', // The taxonomy
            array(
                'slug' => 'membership',
            )
        );

        if (!is_wp_error($new_category)) {
            WP_CLI::success('Category "membership" created.');
            wp_set_object_terms($product->get_id(), $new_category['term_id'], 'product_cat');
        } else {
            WP_CLI::warning('Failed to create category "membership".');
        }
    }

    $product->save();
}
