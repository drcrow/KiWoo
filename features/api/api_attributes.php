<?php
/**
 * API Attributes
 */

/**
 * POST
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/attributes/(?P<attribute>\S*)', array(
        'methods'               => 'POST',
        'callback'              => 'kibooAPIAttributesAdd',
        'permission_callback'   => 'kibooAPIAuth',
    ) );
} );


function kibooAPIAttributesAdd( $data ) {

    $body = json_decode( $data->get_body(), true );
    $data = $data->get_params();
    $result = array();

    $brandAttTax = kibooGetProductAttributeSlug($data['attribute']);

    if($brandAttTax == false){
        return new WP_Error( 'attribute-name-not-found', __( "Attribute name not found", "kiboo" ), array( 'status' => 404 ) );
    }

    //die(print_r($body, true));

    foreach($body as $newTerm){
        $error = null;
        // UPDATE
        if((int)$newTerm['ec_id'] > 0){// if ec_id is pressent it's an update
            $term = get_term_by('ID', (int)$newTerm['ec_id'], $brandAttTax);
            //print_r($term);
            if($newTerm['ec_id'] == $term->term_id){
                if($newTerm['kiboo_id'] == get_term_meta($term->term_id, 'kiboo_id', true)){
                    $term_id = $term->term_id;
                }else{
                    $error = new WP_Error( 'attribute-ids-mismatch', __( "Attribute IDs mismatch", "kiboo" ), array( 'status' => 404 ) );
                    $term_id = 0;
                }
            }else{
                $error = new WP_Error( 'attribute-not-found', __( "Attribute not found", "kiboo" ), array( 'status' => 404 ) );
                $term_id = 0;
            }

        }else{// if ec_id == 0 it's an insert
            // CREATE
            $term_id = wp_insert_term( $newTerm['name'], $brandAttTax );
            if (is_wp_error($term_id)) {
                $error = $term_id;
                $term_id = 0;
            }else{
                $term_id = $term_id['term_id'];
            }
        }

        // UPDATE
        wp_update_term($term_id, $brandAttTax, array('name' => $newTerm['name']));

        // UPDATE META
        if ($error == null && $term_id != 0) {
            kibooCategoryMetaUpdate($term_id, $newTerm);
        }

        //result will return the new/updated user id or the WP error
        $result[] = array(
            'kiboo_id'          => $newTerm['kiboo_id'],
            'ec_id'            => $term_id,
            'error'             => $error
        );
    }

    return $result;
}

/**
 * DELETE
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/attributes/(?P<attribute>\S*)/(?P<kiboo_id>\S*)', array(
        'methods' => 'DELETE',
        'callback' => 'kibooAPIAttributesDelete',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );

function kibooAPIAttributesDelete( $data ) {

    $error = null;

    $brandAttTax = kibooGetProductAttributeSlug($data['attribute']);

    if($brandAttTax == false){
        $error = new WP_Error( 'attribute-name-not-found', __( "Attribute name not found", "kiboo" ), array( 'status' => 404 ) );
    }

    $term_id = kibooGetTermIDByKibooID($brandAttTax, $data['kiboo_id']);

    if($term_id > 0){
        wp_delete_term($term_id, $brandAttTax);
    }else{
        $error = new WP_Error( 'term-not-found', __( "Term not found", "kiboo" ), array( 'status' => 404 ) );
    }

    $result = array(
        'kiboo_id' => $data['kiboo_id'],
        'ec_id' => (int)$term_id,
        'error' => $error
    );

    return $result;
}


?>