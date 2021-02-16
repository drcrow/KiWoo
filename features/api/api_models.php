<?php
/**
 * Product Models REST API Endpoints
 * Create Models POST /xxxxxxx/models
 */

/**
 * ENDPOINT (POST) Models
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/models/', array(
        'methods' => 'POST',
        'callback' => 'kibooAPIModelsAdd',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );


function kibooAPIModelsAdd( $data ) {

    $data = $data->get_params();
    $result = array();

    //Attribute selected as MODEL
    $modelAttTax = 'pa_'.kibooGetOption('woo_model_attribute');

    //LIST OF MODELS
    //$terms = get_terms($modelAttTax, 'orderby=name&hide_empty=0');
    //die(var_dump($terms));


    foreach($data as $cat){
        $error = null;
        // UPDATE TERM
        if((int)$cat['ec_id'] > 0){// if ec_id is pressent it's an update
            $term = get_term_by('ID', (int)$cat['ec_id'], $modelAttTax);
            //print_r($term);
            if($cat['ec_id'] == $term->term_id){
                if($cat['kiboo_id'] == get_term_meta($term->term_id, 'kiboo_id', true)){
                    $term_id = $term->term_id;
                }else{
                    $error = new WP_Error( 'model-ids-mismatch', __( "Model IDs mismatch", "kiboo" ) );
                    $term_id = 0;
                }
            }else{
                $error = new WP_Error( 'model-not-found', __( "Model not found", "kiboo" ) );
                $term_id = 0;
            }

        }else{// if ec_id is NOT pressent it's an insert
            // CREATE CATEGORY
            $term_id = wp_insert_term( $cat['name'], $modelAttTax );
            if (is_wp_error($term_id)) {
                $error = $term_id;
                $term_id = 0;
            }else{
                $term_id = $term_id['term_id'];
            }
        }

        // UPDATE
        wp_update_term($term_id, $modelAttTax, array('name' => $cat['name']));

        // UPDATE META
        if ($error == null && $term_id != 0) {
            kibooCategoryMetaUpdate($term_id, $cat);
        }

        //result will return the new/updated user id or the WP error
        $result[] = array(
            'kiboo_id'          => $cat['kiboo_id'],
            'ec_id'            => $term_id,
            'error'             => $error
        );
    }

    return $result;
}

/**
 * DELETE Models
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/models/(?P<kiboo_id>\S*)', array(
        'methods' => 'DELETE',
        'callback' => 'kibooAPIModelsDelete',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );

function kibooAPIModelsDelete( $data ) {

    $error = null;

    //Attribute selected as MODEL
    $modelAttTax = 'pa_'.kibooGetOption('woo_model_attribute');

    $tax_id = kibooGetTermIDByKibooID($modelAttTax, $data['kiboo_id']);

    if($tax_id > 0){
        wp_delete_term($tax_id, $modelAttTax);
    }else{
        $error = new WP_Error( 'model-not-found', __( "Model not found", "kiboo" ) );
    }

    $result = array(
        'kiboo_id' => $data['kiboo_id'],
        'ec_id' => (int)$tax_id,
        'error' => $error
    );

    return $result;
}


?>