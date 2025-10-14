<?php

woocommerce_wp_text_input(
    array(
        'id'          => '_product_admin_option',
        'label'       => __('Product Label', 'product-admin-label'),
        'placeholder' => __('Add Product Label Here', 'product-admin-label'),
        'desc_tip'    => true,
        'description' => __('This is a custom text field for product options.', 'product-admin-label'),
    )
);
