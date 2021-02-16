<?php
/**
 * Product Brands REST API Endpoints
 * Create Brands POST /xxxxxxx/brands
 */

/**
 * ENDPOINT (POST) Categories
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/brands/', array(
        'methods' => 'POST',
        'callback' => 'kibooAPIBrandsAdd',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );


function kibooAPIBrandsAdd( $data ) {

    $data = $data->get_params();
    $result = array();

    //Attribute selected as BRAND
    $brandAttTax = 'pa_'.kibooGetOption('woo_brand_attribute');

    //LIST OF BRANDS
    //$terms = get_terms($brandAttTax, 'orderby=name&hide_empty=0');
    //die(var_dump($terms));


    foreach($data as $cat){
        $error = null;
        // UPDATE CATEGORY
        if((int)$cat['ec_id'] > 0){// if ec_id is pressent it's an update
            $category = get_term_by('ID', (int)$cat['ec_id'], $brandAttTax);
            //print_r($category);
            if($cat['ec_id'] == $category->term_id){
                if($cat['kiboo_id'] == get_term_meta($category->term_id, 'kiboo_id', true)){
                    $category_id = $category->term_id;
                }else{
                    $error = new WP_Error( 'category-ids-mismatch', __( "Category IDs mismatch", "kiboo" ) );
                    $category_id = 0;
                }
            }else{
                $error = new WP_Error( 'category-not-found', __( "Category not found", "kiboo" ) );
                $category_id = 0;
            }

        }else{// if ec_id is NOT pressent it's an insert
            // CREATE CATEGORY
            $category_id = wp_insert_term( $cat['name'], $brandAttTax );
            if (is_wp_error($category_id)) {
                $error = $category_id;
                $category_id = 0;
            }else{
                $category_id = $category_id['term_id'];
            }
        }

        // UPDATE
        wp_update_term($category_id, $brandAttTax, array('name' => $cat['name']));

        // UPDATE META
        if ($error == null && $category_id != 0) {
            kibooCategoryMetaUpdate($category_id, $cat);
        }

        //result will return the new/updated user id or the WP error
        $result[] = array(
            'kiboo_id'          => $cat['kiboo_id'],
            'ec_id'            => $category_id,
            'error'             => $error
        );
    }

    return $result;
}

/**
 * DELETE Brands
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/brands/(?P<kiboo_id>\S*)', array(
        'methods' => 'DELETE',
        'callback' => 'kibooAPIBrandsDelete',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );

function kibooAPIBrandsDelete( $data ) {

    $error = null;

    //Attribute selected as BRAND
    $brandAttTax = 'pa_'.kibooGetOption('woo_brand_attribute');

    $tax_id = kibooGetTermIDByKibooID($brandAttTax, $data['kiboo_id']);

    if($tax_id > 0){
        wp_delete_term($tax_id, $brandAttTax);
    }else{
        $error = new WP_Error( 'brand-not-found', __( "Brand not found", "kiboo" ) );
    }

    $result = array(
        'kiboo_id' => $data['kiboo_id'],
        'ec_id' => (int)$tax_id,
        'error' => $error
    );

    return $result;
}


?>