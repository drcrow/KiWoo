<?php
/**
 * PRODUCT'S IMAGES
 */

/**
 * POST (UPDATE)
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/images/', array(
        'methods'               => 'POST',
        'callback'              => 'kibooAPIImages',
        'permission_callback'   => 'kibooAPIAuth',
    ) );
} );

function kibooAPIImages( $data ) {

    $enabled = kibooIsAPIEnabled('images');
    if(is_wp_error($enabled)){
        return $enabled;
    }

    $result = array();

    $body = json_decode( $data->get_body(), true );

    foreach($body as $p){
        $errro = null;
        $productID = kibooProductIDByKibooID($p['kiboo_id']);

        if($productID > 0){
            kibooSetProductImages($productID, $p['images']);
        }else{
            $error = new WP_Error( 'product-not-found', __( "Product not found", "kiboo" ), array( 'status' => 404 ) );
        }

        $resultItem = array(
            'kiboo_id' => $p['kiboo_id'],
            'ec_id' => $productID,
            'error' => $error
        );

        $result[] = $resultItem;
    }

    return $result;
}