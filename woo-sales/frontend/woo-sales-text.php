<?php

global $wpdb, $table_prefix, $product;
$table_name = $table_prefix . 'woo_sales_info';

$q = "SELECT * FROM $table_name WHERE `Woo_Product_ID` = $product->id";
// echo '$q = ' . $q . '<br>';
$result = $wpdb->get_results($q);

foreach ($result as $row) {
    echo '<p>' . $row->Woo_Sales_Text . '</p>';
}
