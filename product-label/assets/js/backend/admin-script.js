jQuery(document).ready(function ($) {
    $('select.woo-product-select').select2({
        placeholder: 'Select a product',
        allowClear: true,
        width: '300px'
    });
});