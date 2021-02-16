<?php
/**
 * Product Categories REST API Endpoints
 * Create Categories POST /xxxxxxx/categories
 */

/**
 * ENDPOINT (POST) Categories
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/categories/', array(
        'methods' => 'POST',
        'callback' => 'kibooAPICategoriesAdd',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );


function kibooAPICategoriesAdd( $data ) {

    $enabled = kibooIsAPIEnabled('categories');
    if(is_wp_error($enabled)){
        return $enabled;
    }

    $data = $data->get_params();
    $result = array();

    foreach($data as $cat){
        $error = null;
        $parent_tax_id = 0;


        //get ID if exists
        $tax_id = kibooGetTermIDByKibooID('product_cat', $cat['kiboo_id']);

        //get parent ID if exists
        if($cat['kiboo_parent_id'] != null && $cat['kiboo_parent_id'] > 0){
            $parent_tax_id = kibooGetTermIDByKibooID('product_cat', $cat['kiboo_parent_id']);
        }

        // CREATE IF NOT EXIST
        if($tax_id == 0){
            $tax_id = wp_insert_term( $cat['name'], 'product_cat' );
            $tax_id = $tax_id['term_id'];
            update_term_meta( $tax_id, 'kiboo_id', $cat['kiboo_id'] );
        }

        // UPDATE
        wp_update_term( $tax_id, 'product_cat', array(
            'name'      => $cat['name'],
            'parent'    => $parent_tax_id,
            'slug'      => kibooGenerateProductCategorySlug($cat['name'], $parent_tax_id)
        ) );

        $result[] = array(
            'kiboo_id'          => $cat['kiboo_id'],
            'ec_id'             => $tax_id,
            'kiboo_parent_id'   => $cat['kiboo_parent_id'],
            'ec_parent_id'      => $parent_tax_id,
            'error'             => $error
        );


    }

    return $result;
}

/**
 * DELETE Categories
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/categories/(?P<kiboo_id>\S*)', array(
        'methods'               => 'DELETE',
        'callback'              => 'kibooAPICategoriesDelete',
        'permission_callback'   => 'kibooAPIAuth',
    ) );
} );

function kibooAPICategoriesDelete( $data ) {

    $error = null;

    $tax_id = kibooGetTermIDByKibooID('product_cat', $data['kiboo_id']);

    if($tax_id > 0){
        wp_delete_term($tax_id, 'product_cat');
    }else{
        $error = new WP_Error( 'category-not-found', __( "Category not found", "kiboo" ) );
    }

    $result = array(
        'kiboo_id' => $data['kiboo_id'],
        'ec_id' => (int)$tax_id,
        'error' => $error
    );

    return $result;
}

//return new WP_Error( 'rest_product_invalid', esc_html__( 'The product does not exist.', 'my-text-domain' ), array( 'status' => 404 ) );



function kibooGenerateProductCategorySlug($cat_name, $parent_tax_id){
    $slug = sanitize_title($cat_name);

    if($parent_tax_id > 0){
        $parent = get_category($parent_tax_id);
        $slug = $parent->slug.'-'.$slug;
    }

    return $slug;
}