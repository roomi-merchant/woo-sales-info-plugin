<?php

if (! defined('ABSPATH')) exit; // Exit if accessed directly

global $wpdb, $table_prefix, $product, $post;
$table_name = $table_prefix . 'woo_sales_info';

$result = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM {$table_name} WHERE `Woo_Product_ID` = %d", $product->id)
);

foreach ($result as $row) {
    if ($row->Woo_Sales_Text) {
        echo '<div class="woo-product-label"><p>' . esc_attr($row->Woo_Sales_Text) . '</p></div>';
    }
}
