<?php
/**
 * PRODUCT'S STOCK
 */

/**
 * POST (UPDATE)
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/stock/', array(
        'methods'               => 'POST',
        'callback'              => 'kibooAPIStockUpdate',
        'permission_callback'   => 'kibooAPIAuth',
    ) );
} );

function kibooAPIStockUpdate( $data ) {
    $result = array();

    $body = json_decode( $data->get_body(), true );

    foreach($body as $p){
        $errro = null;
        $productID = kibooProductIDByKibooID($p['kiboo_id']);

        if($productID > 0){
            update_post_meta($productID, '_stock', (int)$p['stock']);
        }else{
            $error = new WP_Error( 'product-not-found', __( "Product not found", "kiboo" ), array( 'status' => 404 ) );
        }

        $resultItem = array(
            'kiboo_id' => $p['kiboo_id'],
            'ec_id' => $productID,
            'error' => $error
        );

        if(is_array($p['variations'])){
            foreach($p['variations'] as $pv){
                $error = null;
                $productVariationID = kibooProductVariationIDByKibooID($pv['kiboo_id']);

                if($productVariationID > 0){
                    update_post_meta($productVariationID, '_stock', (int)$pv['stock']);
                }else{
                    $error = new WP_Error( 'product-not-found', __( "Product not found", "kiboo" ), array( 'status' => 404 ) );
                }

                $resultItem['variations'][] = array(
                    'kiboo_id' => $pv['kiboo_id'],
                    'ec_id' => $productVariationID,
                    'error' => $error
                );

            }
        }

        $result[] = $resultItem;
    }

    return $result;
}