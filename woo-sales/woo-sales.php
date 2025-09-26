<?php
/*
    Plugin Name: Woo Sales
    Description: This plugin adds sales info in woocommerce product
    Author: Roomi Merchant
*/

if (!defined('ABSPATH')) {
    die();
}

add_action('admin_init', 'my_woo_addon_check_dependencies');

function my_woo_addon_check_dependencies()
{
    if (! class_exists('WooCommerce')) {
        // Deactivate the plugin if WooCommerce is not active
        deactivate_plugins(plugin_basename(__FILE__));

        // Show admin error notice
        add_action('admin_notices', 'my_woo_addon_admin_notice');
    }
}

/**
 * Show admin notice
 */
function my_woo_addon_admin_notice()
{
?>
    <div class="notice notice-error">
        <p><strong>Woo Sales</strong> requires WooCommerce to be installed and active.</p>
    </div>
<?php
}

function woo_sales_menu()
{
    add_menu_page('Woo Sales', 'Woo Sales', 'manage_options', 'woo-sales', 'wooSales', '', 25);
    add_submenu_page('woo-sales', 'Settings', 'Settings', 'manage_options', 'woo-sales', '');
}

add_action('admin_menu', 'woo_sales_menu');


register_activation_hook(__FILE__, 'woo_sales_activate');

function woo_sales_activate()
{
    global $wpdb, $table_prefix;
    $table_name = $table_prefix . 'woo_sales_info';

    $q = "CREATE TABLE IF NOT EXISTS $table_name (
        ID INT NOT NULL AUTO_INCREMENT,
        Woo_Sales_Text VARCHAR(255) NOT NULL,
        Woo_Product_ID int(255) NOT NULL,
        PRIMARY KEY (ID)
    );";
    $wpdb->query($q);

    $data = array(
        'Woo_Sales_Text' => '50% Off',
    );

    $wpdb->insert($table_name, $data);
}

function woo_sales_deactivate()
{
    global $wpdb, $table_prefix;

    $table_name = $table_prefix . 'woo_sales_info';

    $q = "TRUNCATE `$table_name`";
    $wpdb->query($q);
    // $wpdb->query("DROP TABLE IF EXISTS `$table_name`");
}

register_deactivation_hook(__FILE__, 'woo_sales_deactivate');

function wooSales()
{
    include 'admin/woo-sales-settings.php';
}

add_action('woocommerce_product_meta_end', 'add_sale_info');
function add_sale_info()
{
    include 'frontend/woo-sales-text.php';
}
