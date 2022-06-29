<?php


// SET new color features for products
add_filter( 'woocommerce_product_data_tabs', 'add_custom_product_data_tab' );
function add_custom_product_data_tab( $tabs ) {
    $tabs['my-custom-tab'] = array(
        'label' => 'Product files',
        'target' => 'product_files',
    );
    return $tabs;
}

add_action( 'woocommerce_product_data_panels', 'add_custom_product_data_fields' );
function add_custom_product_data_fields() {
    echo '<div id="product_files" class="panel woocommerce_options_panel"></div>
<script>
jQuery(document).ready(function($) {
    $("#acf-group_60af497d83397").appendTo("#product_files");
});
</script>';
}
