<?php
/**
 * PRODUCT'S PRICES
 */

/**
 * POST (UPDATE)
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/prices/', array(
        'methods'               => 'POST',
        'callback'              => 'kibooAPIPricesUpdate',
        'permission_callback'   => 'kibooAPIAuth',
    ) );
} );

function kibooAPIPricesUpdate( $data ) {

    $enabled = kibooIsAPIEnabled('prices');
    if(is_wp_error($enabled)){
        return $enabled;
    }

    $result = array();

    $body = json_decode( $data->get_body(), true );

    foreach($body as $p){
        $errro = null;
        $productID = kibooProductIDByKibooID($p['kiboo_id']);

        if($productID > 0){
            // PRICES
            if(@is_array($p['prices'])){
                kibooSetProductPrices($productID, $p['prices']);
            }
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
                    // PRICES
                    if(@is_array($p['prices'])){
                        kibooSetProductPrices($productVariationID, $pv['prices']);
                    }
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