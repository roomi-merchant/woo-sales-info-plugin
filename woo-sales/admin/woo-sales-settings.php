<?php
global $wpdb, $table_prefix;
$table_name = $table_prefix . 'woo_sales_info';

if (isset($_POST['submit'])) {
    global $wpdb, $table_prefix;
    $table_name = $table_prefix . 'woo_sales_info';

    $row_ids = isset($_POST['row_id']) ? array_map('sanitize_text_field', (array) $_POST['row_id']) : [];
    $woo_sales_texts = isset($_POST['woo_sales_text']) ? array_map('sanitize_text_field', (array) $_POST['woo_sales_text']) : [];
    $woo_product_ids = isset($_POST['woo_product_id']) ? array_map('sanitize_text_field', (array) $_POST['woo_product_id']) : [];

    foreach ($row_ids as $index => $row_id) {
        $data = array(
            'Woo_Sales_Text' => sanitize_text_field($woo_sales_texts[$index]),
            'Woo_Product_ID' => intval($woo_product_ids[$index]),
        );

        $where = array(
            'ID' => intval($row_id),  // <-- Use primary key, guaranteed unique
        );

        $updated = $wpdb->update(
            $table_name,
            $data,
            $where,
            array('%s', '%d'),
            array('%d')
        );

        // Debugging
        // echo "Row $row_id updated with "; print_r($data); echo "<br>";
    }
}


if (isset($_POST['add'])) {
    $row_ids = isset($_POST['row_id']) ? array_map('sanitize_text_field', (array) $_POST['row_id']) : [];
    $woo_sales_texts = isset($_POST['woo_sales_text']) ? array_map('sanitize_text_field', (array) $_POST['woo_sales_text']) : [];
    $woo_product_ids = isset($_POST['woo_product_id']) ? array_map('sanitize_text_field', (array) $_POST['woo_product_id']) : [];

    foreach ($row_ids as $index => $row_id) {
        $data = array(
            'Woo_Sales_Text' => sanitize_text_field($woo_sales_texts[$index]),
            'Woo_Product_ID' => intval($woo_product_ids[$index]),
        );

        $where = array(
            'ID' => intval($row_id),  // <-- Use primary key, guaranteed unique
        );

        $updated = $wpdb->update(
            $table_name,
            $data,
            $where,
            array('%s', '%d'),
            array('%d')
        );
    }
    $data = array(
        'Woo_Sales_Text' => '',
        'Woo_Product_ID' => ''
    );
    $wpdb->insert($table_name, $data);
}

if (isset($_GET['remove'])) {
    $woo_remove_id = $_GET['remove'];

    $where = array(
        'Woo_Product_ID' => $woo_remove_id
    );
    $wpdb->delete($table_name, $where);
    header('Location: ?page=product-labels');
}
?>
<div class="wrap">
    <h2>Add Labels to Products</h2>
    <?php
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    );
    $query = new WP_Query($args);

    $q = "SELECT * FROM $table_name ORDER BY ID ASC;";
    $result = $wpdb->get_results($q);
    ?>
    <form action="<?php echo get_the_permalink(); ?>" method="post">
        <?php foreach ($result as $index => $row) { ?>
            <div class="fields" style="display: flex;">
                <input type="hidden" name="row_id[]" value="<?php echo $row->ID; ?>">

                <input type="hidden" value="<?php echo $row->Woo_Product_ID; ?>" name="Woo_ID_Old[]">

                <p>
                    <label for="woo_sales_text_<?php echo $index; ?>">Sales Text</label>
                    <input type="text"
                        placeholder="Add sales text"
                        name="woo_sales_text[]"
                        id="woo_sales_text_<?php echo $index; ?>"
                        value="<?php echo $row->Woo_Sales_Text; ?>">
                </p>

                <p>
                    <label for="woo_product_id_<?php echo $index; ?>">Assign Product</label>
                    <select class="woo-product-select" name="woo_product_id[]" id="woo_product_id_<?php echo $index; ?>">
                        <?php
                        if ($query->have_posts()) {
                            while ($query->have_posts()) {
                                $query->the_post();
                                $product_id    = get_the_ID();
                                $product_title = get_the_title();
                                $product_sku   = get_post_meta($product_id, '_sku', true); ?>
                                <option value="<?php echo $product_id; ?>"
                                    <?php selected($product_id, $row->Woo_Product_ID); ?>>
                                    <?php echo $product_sku; ?> - <?php echo $product_title; ?>
                                </option>
                        <?php }
                        } ?>
                    </select>
                </p>

                <a href="?page=product-labels&remove=<?php echo $row->Woo_Product_ID; ?>">Remove</a>
            </div>
        <?php } ?>

        <div class="add-more-btn">
            <input type="submit" name="submit" value="SUBMIT" class="button button-primary">
            <input type="submit" name="add" value="ADD +" class="button button-primary">
        </div>
    </form>
    <?php wp_reset_postdata(); ?>

</div>