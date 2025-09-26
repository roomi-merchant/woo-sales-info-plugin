<?php
global $wpdb, $table_prefix;
$table_name = $table_prefix . 'woo_sales_info';

if (isset($_POST['submit'])) {
    $woo_sales_text = $_POST['woo_sales_text'];
    $Woo_ID_Old = $_POST['Woo_ID_Old'];
    $woo_product_id = $_POST['woo_product_id'];
    $data = array(
        'Woo_Sales_Text' => $woo_sales_text,
        'Woo_Product_ID' => $woo_product_id,
    );

    $where = array(
        'Woo_Product_ID' => $Woo_ID_Old,
    );

    $updated = $wpdb->update(
        $table_name,
        $data,
        $where,
        array('%s', '%d'),
        array('%d')
    );
}
?>
<div class="wrap">
    <?php
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    );
    $query = new WP_Query($args);

    $q = "SELECT * FROM $table_name";
    $result = $wpdb->get_results($q);
    ?>
    <form action="<?php echo get_the_permalink(); ?>" method="post">
        <?php
        foreach ($result as $row) { ?>
            <input type="hidden" value="<?php echo $row->Woo_Product_ID; ?>" name="Woo_ID_Old">
        <?php }
        ?>
        <p>
            <label for="woo_sales_text">Sales Text</label>
            <input type="text" placeholder="Add sales text" name="woo_sales_text" id="woo_sales_text">
        </p>
        <p>
            <label for="woo_product_id">Assign Product</label>
            <select name="woo_product_id" id="woo_product_id">
                <?php
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $product_id = get_the_ID();
                        $product_title = get_the_title();
                        $product_sku = get_post_meta($product_id, '_sku', true);
                        echo '<option value=' . $product_id . '>' . $product_sku . ' - ' . $product_title . '</option>';
                    }
                }
                ?>
            </select>
        </p>
        <input type="submit" name="submit" value="SUBMIT">
    </form>
    <?php wp_reset_postdata(); ?>
</div>