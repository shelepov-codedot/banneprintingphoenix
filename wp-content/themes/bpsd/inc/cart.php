<?php



add_action( 'wp_ajax_product_add', 'product_to_cart' );
add_action( 'wp_ajax_nopriv_product_add', 'product_to_cart' );
if ( ! function_exists( 'product_to_cart' ) ):
    function product_to_cart() {

        $WC_Cart = new WC_Cart();
        $variation=array();
        parse_str($_POST['data'],  $variation);

        $cart_item_data = array(
            'attribute_pa_grommets'=>'2-corners'
        );

        $WC_Cart->add_to_cart( $variation['product_id'], $_POST['quantity'], $variation['variation_id'], array() , $cart_item_data );


            echo json_encode(true);
        die();
    }

endif;