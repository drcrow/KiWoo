<?php
/**
 * Here are all the features
 * Ideally separated in different files
 * to keep order
 */

require('admin_bar.php');
require('custom_backend_login.php');
require('customer_address.php');
require('customer_registration_email.php');
require('customer_registration.php');
require('stock_realtime.php');
require('dinamic_price.php');
require('geo_fields.php');
require('order_checkout.php');
require('vat_labels.php');
require('wholesalers_vat.php');
require('new_payment.php');

// API REST
require('api/index.php');

// HOOKS
require('hooks/index.php');

/**
 * Get Tax Class By Value
 */
function kibooGetTaxClassByValue($value){
    global $wpdb;

    $value = str_replace(',', '.', $value);
    $value = number_format($value, 4, '.', '');

    $sql = "SELECT tax_rate_class FROM {$wpdb->prefix}woocommerce_tax_rates WHERE tax_rate = '{$value}'";
    $class = $wpdb->get_var( $sql );
    return $class;
}

/**
 * Set Product Prices
 */
function kibooSetProductPrices($productID, $prices){
    update_post_meta($productID, 'kiboo_prices', $prices);
}

/**
 * Get Product Prices
 */
function kibooGetProductPrices($productID){
    return get_post_meta($productID, 'kiboo_prices', true);
}

/**
 * Get Price Type
 */
function kibooGetCurrentUserPriceType(){
    $priceType = '';
    $ID = get_current_user_id();
    if($ID > 0){
        $priceType = get_user_meta($ID, 'kiboo_price_type', true);
    }

    if($priceType == ''){
        $priceType = kibooGetOption('default_price');
    }

    $priceType = 'NET_'.$priceType;

    return $priceType;
}

/**
 * Product ID By Kiboo ID
 */
function kibooProductIDByKibooID($k_id){
    global $wpdb;
    // get the product or product_variation with this kiboo id
    // only for published posts
    $ID = $wpdb->get_var( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = 'kiboo_id' AND meta_value={$k_id} AND post_id IN (SELECT ID FROM {$wpdb->prefix}posts WHERE (post_type = 'product' OR post_type = 'product_variation') AND post_status = 'publish')" );
    return $ID+0;
}

/**
 * Product Variation ID By Kiboo ID
 */
function kibooProductVariationIDByKibooID($k_id){
    $ID = 0;

    $args = array(
        'numberposts' => 1,
        'post_type' => 'product_variation',
        'meta_query' => array(
            array(
                'key'       => 'kiboo_id',
                'value'     => $k_id
            )
        )
    );

    $items = get_posts( $args );

    foreach($items as $item){
        if($k_id == get_post_meta( $item->ID, 'kiboo_id', true )){
            $ID = $item->ID;
            break;
        }
    }

    return $ID;
}

/**
 * Customer ID By Kiboo ID
 */

function kibooCustomerIDByKibooID($k_id){
    $ID = 0;

    $args = array(
        'role' => 'customer',
        'meta_query' => array(
            array(
                'key' => 'kiboo_id',
                'value' => $k_id
            )
        )
    );

    $data = new WP_User_Query($args);
    foreach($data->get_results() as $item){
        if($k_id == get_user_meta( $item->ID , 'kiboo_id' , true )){
            $ID = $item->ID;
            break;
        }
    }

    return $ID;
}

/**
 * Get Customer
 */

function kibooGetCustomer($ID){
    $result = array();
    $user = get_user_by('ID', $ID);
    //print_r($user);
    if($user->ID == $ID){
        $result = array(
            'kiboo_id'      => get_user_meta($ID, 'kiboo_id', true),
            'ec_id'         => $ID,
            'email'         => $user->user_email,
            'name'          => trim(get_user_meta($ID, 'billing_first_name', true).' '.get_user_meta($ID, 'billing_last_name', true)),
            'doc_number'    => get_user_meta($ID, 'billing_doc_number', true),
            'birth_date'    => get_user_meta($ID, 'billing_birth_date', true),
            'doc_type'      => get_user_meta($ID, 'billing_doc_type', true),
            'vat_type'      => get_user_meta($ID, 'billing_vat_type', true),
            //'iibb'          => get_user_meta($ID, 'kiboo_iibb', true),
            'price_type'    => get_user_meta($ID, 'kiboo_price_type', true),
            'address'       => get_user_meta($ID, 'billing_address_1', true),
            'address_number'=> get_user_meta($ID, 'billing_address_number', true),
            'address_apt'   => get_user_meta($ID, 'billing_address_apt', true),
            'neighborhood'  => get_user_meta($ID, 'billing_address_2', true),
            'zip'           => get_user_meta($ID, 'billing_postcode', true),
            'state_id'      => get_user_meta($ID, 'billing_state', true),
            'state'         => kibooGetStateName(get_user_meta($ID, 'billing_state', true)),
            'city_id'       => get_user_meta($ID, 'billing_city', true),
            'city'          => get_user_meta($ID, 'billing_city', true),
            //'city'          => kibooGetCityName(get_user_meta($ID, 'billing_city', true)),
            'phone'         => get_user_meta($ID, 'billing_phone', true)
        );
    }

    return $result;
}

/**
 * Get City Name
 */
function kibooGetCityName($id){
    global $wpdb;
    $name = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}kiboo_cities WHERE ID = {$id}" );
    return $name;
}

/**
 * Get State Name
 */
function kibooGetStateName($id){
    global $wpdb;
    $name = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}kiboo_states WHERE ID = {$id}" );
    return $name;
}

/**
 * Set Product Attribute
 */

function kibooSetProductAttribute($productID, $attributeName, $attributeID, $setVariation = 0){

    $product = wc_get_product($productID);

    $attributes = $product->get_attributes();

    $attribute_object = new WC_Product_Attribute();
    $attribute_object->set_name( $attributeName );
    $attribute_object->set_options( $attributeID );
    $attribute_object->set_visible( 1 );
    $attribute_object->set_variation( $setVariation );
    $attribute_object->set_id( sizeof( $attributes) + 1 );
    $attributes[$attributeName] = $attribute_object;

    

    $res = $product->set_attributes( $attributes );
    $product->save();

    

    wp_set_object_terms($productID, $attributeID, $attributeName);
}

/**
 * Allows to query products by kiboo_id
 */

function handle_custom_query_var( $query, $query_vars ) {
	if ( ! empty( $query_vars['kiboo_id'] ) ) {
		$query['meta_query'][] = array(
			'key' => 'kiboo_id',
			'value' => esc_attr( $query_vars['kiboo_id'] ),
		);
	}

	return $query;
}
add_filter( 'woocommerce_product_data_store_cpt_get_products_query', 'handle_custom_query_var', 10, 2 );

/**
 * Get Product Attribute Slug
 */
function kibooGetProductAttributeSlug($attribute){

    switch($attribute){

        case 'brand':
        case 'brands':
            $brandAttTax = 'pa_'.kibooGetOption('woo_brand_attribute');
        break;

        case 'model':
        case 'models':
            $brandAttTax = 'pa_'.kibooGetOption('woo_model_attribute');
        break;

        case 'color':
        case 'colors':
            $brandAttTax = 'pa_'.kibooGetOption('woo_color_attribute');
        break;

        case 'size':
        case 'sizes':
            $brandAttTax = 'pa_'.kibooGetOption('woo_size_attribute');
        break;

        default:
            $brandAttTax = false;

    }

    return $brandAttTax;
}

/**
 * Set Product Images
 */
function kibooSetProductImages($productID, $images){
    if(is_array($images)){
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $mediaIDs = array();
        foreach($images as $img){
            $mediaID = media_sideload_image( $img['url'], $productID, 'Prod '.$productID, 'id' );
            if(!empty($mediaID) && !is_wp_error($mediaID)){
                $mediaIDs[] = $mediaID;
            }
        }

        //echo $productID.'-'.$mediaIDs[0];

        //First image is thumbnail
        update_post_meta($productID, '_thumbnail_id', $mediaIDs[0]);
        //All but first is gallery
        unset($mediaIDs[0]);
        update_post_meta($productID,'_product_image_gallery', implode(',', $mediaIDs));
    }
}
?>