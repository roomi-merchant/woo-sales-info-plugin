<?php
// Prevent direct access
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

// Table name
$table_name = $wpdb->prefix . 'woo_sales_info';

// Drop custom table
$wpdb->query($wpdb->prepare("DROP TABLE IF EXISTS {$table_name}"));

// Delete options
delete_option('woo_sales_info_settings');

// Delete multisite option if applicable
if (is_multisite()) {
    delete_site_option('woo_sales_info_settings');
}
