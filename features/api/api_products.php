<?php
/**
 * Products REST API Endpoints
 */

/**
 * POST (INSERT / UPDATE)
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/products/', array(
        'methods'               => 'POST',
        'callback'              => 'kibooAPIProductsAdd',
        'permission_callback'   => 'kibooAPIAuth',
    ) );
} );

function kibooAPIProductsAdd( $data ) {

    $enabled = kibooIsAPIEnabled('products');
    if(is_wp_error($enabled)){
        return $enabled;
    }

    $data = $data->get_params();
    $result = array();

    foreach($data as $p){
        $error = null;
      
        $kiboo_id   = trim($p['kiboo_id']);
        $ec_id      = kibooProductIDByKibooID($kiboo_id);//this will be 0 if product is new

        $product_info = array(
            'name'               => $p['name'],
            'featured'           => $p['featured'],
            'catalog_visibility' => 'visible',
            'description'        => $p['description'],
            'short_description'  => $p['short_description'],
            'sku'                => $p['sku'],
            'regular_price'      => $p['price'],
            'tax_status'         => 'taxable',
            'tax_class'          => kibooGetTaxClassByValue($p['tax_percentage']),
            'manage_stock'       => kibooGetOption('manage_stock'),
            'stock_quantity'     => $p['stock'],
            'stock_status'       => 'instock',
            'backorders'         => 'yes',
            'sold_individually'  => false,
            'weight'             => $p['weight'],
            'length'             => $p['length'],
            'width'              => $p['width'],
            'height'             => $p['height'],
            'parent_id'          => 0,
            'reviews_allowed'    => true,
            'purchase_note'      => '',
            'menu_order'         => 10,
            'virtual'            => false,
            'downloadable'       => false,
            //'category_ids'       => array(kibooGetTermIDByKibooID('product_cat', $p['category'])),
            'tag_ids'            => '',
            'shipping_class_id'  => 0
        );
       
        if(kibooIsAPIEnabled('products_categories', true)){
            $product_info['category_ids'] = array(kibooGetTermIDByKibooID('product_cat', $p['category']));
        }


        if($kiboo_id == ''){

            $error = new WP_Error( 'empty-kiboo-id', __( "Empty Kiboo ID", "kiboo" ) );
            $result[] = array(
                'kiboo_id'  => $kiboo_id,
                'ec_id'     => $ec_id,
                'error'     => $error
            );

            continue;//stops iteration with the current product

        }elseif($ec_id > 0){//UPDATE

            $product = wc_get_product($ec_id);
            $product->set_props($product_info);
            $newProductID = $product->save();

        }else{//NEW

            if(@is_array($p['variations'])){
                $product = new WC_Product_Variable();
            }else{
                $product = new WC_Product();
            }

            $product->set_props($product_info);
            $newProductID = $product->save();

        }


        if (is_wp_error($newProductID)) {
            $error = new WP_Error( 'error-creating-product', __( "Error Creating Product", "kiboo" ) );
            $result[] = array(
                'kiboo_id'  => $kiboo_id,
                'ec_id'     => $ec_id,
                'error'     => $error
            );

            continue;//stops iteration with the current product
        }

        

        //BRAND
        $brandAttTax = 'pa_'.kibooGetOption('woo_brand_attribute');
        $brandID = kibooGetTermIDByKibooID($brandAttTax, $p['brand']);

        //die('aaa - '.$p['brand'].' - '.$brandID);

        kibooSetProductAttribute($newProductID, $brandAttTax, $brandID);

        

        //MODEL
        $modelAttTax = 'pa_'.kibooGetOption('woo_model_attribute');
        $modelID = kibooGetTermIDByKibooID($modelAttTax, $p['model']);

        

        kibooSetProductAttribute($newProductID, $modelAttTax, $modelID);

        // KIBOO_ID
        update_post_meta( $newProductID, "kiboo_id", $kiboo_id );

        // ON_SALE
        update_post_meta( $newProductID, "kiboo_on_sale", $p['on_sale'] );

        // EXTRA_INFO
        update_post_meta( $newProductID, "kiboo_extra_info", $p['extra_info'] );

        // PRICES
        if(@is_array($p['prices'])){
            kibooSetProductPrices($newProductID, $p['prices']);
        }

        // IMAGES
        //kibooSetProductImages($newProductID, $p['images']);

        // VARIATIONS
        $variations = array();
        if(@is_array($p['variations'])){
            // delete old unused variations
            kibooDeleteOldVariations($newProductID, $p['variations']);
            // add new variations
            $variations = kibooAddVariations($newProductID, $p['variations']);
        }

        //result will return the new/updated user id or the WP error
        $result[] = array(
            'kiboo_id'      => $kiboo_id,
            'ec_id'         => (int)$newProductID,
            'variations'    => $variations,
            'error'         => $error
        );
    }

    return $result;
}


/**
 * DELETE
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/products/(?P<kiboo_id>\S*)', array(
        'methods' => 'DELETE',
        'callback' => 'kibooAPIProductsDelete',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );

function kibooAPIProductsDelete( $data ) {

    $error = null;
    $product_id = 0;


    $items = wc_get_products( array( 'kiboo_id' => $data['kiboo_id'] ) );


    if(!is_array($items)){
        $error = new WP_Error( 'product-not-found', __( "Product not found", "kiboo" ), array( 'status' => 404 ) );
    }else{
        foreach($items as $item){
            $product_id = $item->get_id();
            wp_delete_post($product_id);
            break;
        }
    }

    $result = array(
        'kiboo_id' => $data['kiboo_id'],
        'ec_id' => (int)$product_id,
        'error' => $error
    );

    return $result;
}

/**
 * VARIATIONS
 */

function kibooAddVariations($productID, $variations){
    $product = wc_get_product($productID);
    $result = array();

    // get array of attributes and IDs
    $attsArray = array();
    foreach($variations as $var){
        foreach($var['attributes'] as $att){
            $termSlug = kibooGetProductAttributeSlug($att['attribute']);
            $attsArray[$termSlug][] = kibooGetTermIDByKibooID($termSlug, $att['kiboo_attribute_id']);
        }
    }

    // set attributes to parent product
    foreach($attsArray as $termSlug => $attIDs){
        kibooSetProductAttribute($productID, $termSlug, $attIDs, 1);
    }

    //die(print_r($attsArray, true));

    // add variation products
    foreach($variations as $var){

        $error = null;
        if($var['stock'] == 0){
            $backorder_check = 'no';
        } else {
            $backorder_check = 'yes';
        }
        $product_info = array(
            'name'               => $var['name'],
            'sku'                => $var['sku'],
            'regular_price'      => $var['price'],
            'manage_stock'       => kibooGetOption('manage_stock'),
            'stock_quantity'     => $var['stock'],
            'stock_status'       => 'instock',
            'backorders'         => 'yes',
            'sold_individually'  => false,
            'parent_id'          => $productID
        );

        $ec_id = kibooProductIDByKibooID($var['kiboo_id']);

        //die('ID-'.$ec_id.'-'.$var['kiboo_id']);

        if($ec_id == 0){
            $productV = new WC_Product_Variation();
        }else{
            $productV = wc_get_product($ec_id);
        }

        if (is_wp_error($productV)) {
            $error = $productV;
            $newProductID = 0;
        }else{
            $productV->set_props($product_info);
            $newProductID = $productV->save();

            foreach($var['attributes'] as $att){
                $termSlug = kibooGetProductAttributeSlug($att['attribute']);
                $attSlug = kibooGetTermSlugByKibooID($termSlug, $att['kiboo_attribute_id']);

                update_post_meta( $newProductID, 'attribute_'.$termSlug, $attSlug );
            }

            // KIBOO ID
            update_post_meta( $newProductID, "kiboo_id", $var['kiboo_id'] );

            // PRICES
            if(@is_array($var['prices'])){
                kibooSetProductPrices($newProductID, $var['prices']);
            }
        }

        $result[] = array(
            'kiboo_id'  => $var['kiboo_id'],
            'ec_id'     => $newProductID,
            'error'     => $error
        );
    }

    return $result;
}

/**
 * Get Variations
 */

function kibooGetVariations($ecID){

    $product = wc_get_product($ecID);
    $variations = $product->get_children();

    $result = array();

    foreach($variations as $varID){
        $kibooID = get_post_meta( $varID, 'kiboo_id', true );
        $result[] = array(
            'ec_id'     => $varID,
            'kiboo_id'  => $kibooID
        );

    }

    return $result;
}

/**
 * Delete Old Variations
 * Get all the variations from the API and delete the ones from woo nos present on the API call
 */

function kibooDeleteOldVariations($productID, $new_variations){
    $current_variations = kibooGetVariations($productID);
    //check if all the current variations exists in the new variations array
    //and deletes the current variation if not exists
    foreach($current_variations as $current_var){
        $delete = true;

        foreach($new_variations as $new_var){
            if($current_var['kiboo_id'] == $new_var['kiboo_id']){
                $delete = false;
                break;
            }
        }

        if($delete){
            wp_delete_post($current_var['ec_id']);
        }
    }
}