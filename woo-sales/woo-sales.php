<?php
/*
    Plugin Name: Woo Sales
    Description: This plugin adds sales info in woocommerce product
    Author: Roomi Merchant
*/

namespace WooSales;

if (!defined('ABSPATH')) {
    die();
}

add_action('admin_init', __NAMESPACE__ . '\\my_woo_addon_check_dependencies');

function my_woo_addon_check_dependencies()
{
    if (! class_exists('WooCommerce')) {
        // Deactivate the plugin if WooCommerce is not active
        deactivate_plugins(plugin_basename(__FILE__));

        // Show admin error notice
        add_action('admin_notices', __NAMESPACE__ . '\\my_woo_addon_admin_notice');
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
    add_menu_page('Product Labels', 'Product Labels', 'manage_options', 'product-labels', __NAMESPACE__ . '\\wooSales', '', 25);
    add_submenu_page('product-labels', 'Settings', 'Settings', 'manage_options', 'product-labels', '');
}

add_action('admin_menu', __NAMESPACE__ . '\\woo_sales_menu');


register_activation_hook(__FILE__, __NAMESPACE__ . '\\woo_sales_activate');

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

register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\woo_sales_deactivate');

function wooSales()
{
    include 'admin/woo-sales-settings.php';
}

add_action('woocommerce_before_single_product', __NAMESPACE__ . '\\add_sale_info');
function add_sale_info()
{
    include 'frontend/woo-sales-text.php';
}

function add_frontend_scripts()
{
    $script_path = plugin_dir_path(__FILE__) . 'woo-sales/assets/js/woo-sales.js';
    $script_version = file_exists($script_path) ? filemtime($script_path) : '1.0.0';

    wp_enqueue_script(
        'woo-sales-js',
        plugins_url('assets/js/woo-sales.js', __FILE__),
        array('jquery'),
        $script_version,
        true
    );

    wp_enqueue_style('woo-style', plugins_url('assets/css/woo-sales-style.css', __FILE__), array());
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\add_frontend_scripts');


function add_backend_scripts()
{
    $script_path = plugin_dir_path(__FILE__) . 'woo-sales/assets/js/backend/admin-script.js';
    $script_version = file_exists($script_path) ? filemtime($script_path) : '1.0.0';

    wp_enqueue_script(
        'woo-sales-js',
        plugins_url('assets/js/backend/admin-script.js', __FILE__),
        array('jquery'),
        $script_version,
        true
    );

    wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');

    // Select2 JS
    wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['jquery'], null, true);
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\add_backend_scripts');

