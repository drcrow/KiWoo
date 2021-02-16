<?php
/**
 * Customers REST API Endpoints
 */

/**
 * GET Customer
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/customers/(?P<kiboo_id>\S*)', array(
        'methods'               => 'GET',
        'callback'              => 'kibooAPICustomersGet',
        'permission_callback'   => 'kibooAPIAuth',
    ) );
} );

function kibooAPICustomersGet( $data ) {
    $error = null;

    $args = array(
        'role' => 'customer',
        'meta_query' => array(
            array(
                'key'   => 'kiboo_id',
                'value' => $data['kiboo_id']
            )
        )
    );

    $query = new WP_User_Query($args);
    $items = $query->get_results();

    if(count($items) == 0){
        $result = new WP_Error( 'customer-not-found', __( "Customer not found", "kiboo" ), array( 'status' => 404 ) );
    }else{
        foreach($items as $item){
            $result = kibooGetCustomer($item->ID);
            //wp_delete_user($user_id);
            break;
        }
    }

    return $result;
}

/**
 * POST Customers
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/customers/', array(
        'methods' => 'POST',
        'callback' => 'kibooAPICustomersAdd',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );

function kibooAPICustomersAdd( $data ) {
    //return print_r(get_class_methods($data), true);
    //return print_r($data->get_params(), true);

    $enabled = kibooIsAPIEnabled('customers');
    if(is_wp_error($enabled)){
        return $enabled;
    }

    $data = $data->get_params();
    $result = array();

    foreach($data as $u){
        $error = null;
        // UPDATE CUSTOMER
        if($u['ec_id'] > 0){// if ec_id is pressent it's an update
            $user = get_user_by('ID', (int)$u['ec_id']);
            if($u['ec_id'] == $user->ID){
                if($u['kiboo_id'] == get_user_meta($user->ID, 'kiboo_id', true)){
                    $user_id = $user->ID;
                }else{
                    $error = new WP_Error( 'customer-ids-mismatch', __( "Customer IDs mismatch", "kiboo" ) );
                }
            }else{
                $error = new WP_Error( 'customer-not-found', __( "Customer not found", "kiboo" ) );
            }

        }else{// if ec_id is NOT pressent it's an insert
            // CREATE CUSTOMER
            $_GET['disable_email'] = true;//this is catched by customer_registration_email-php
            $user_id = wc_create_new_customer( $u['email'], $u['email'], $u['doc_number'] );
            if (is_wp_error($user_id)) {
                $error = $user_id;
                $user_id = 0;
            }
        }

        // UPDATE CUSTOMER META
        if ($error == null) {
            kibooCustomerMetaUpdate($user_id, $u);
        }

        //result will return the new/updated user id or the WP error
        $result[] = array(
            'kiboo_id' => $u['kiboo_id'],
            'ec_id' => (int)$user_id,
            'error' => $error
        );
    }

    return $result;
}

function kibooCustomerMetaUpdate($user_id, $u){
    // STANDARD
    update_user_meta( $user_id, "nickname", $u['name'] );
    update_user_meta( $user_id, "display_name", $u['name'] );
    // KIBOO
    update_user_meta( $user_id, "kiboo_id", $u['kiboo_id'] );
    update_user_meta( $user_id, "wholesaler", $u['wholesaler'] );
    update_user_meta( $user_id, "billing_doc_number", $u['doc_number'] );
    update_user_meta( $user_id, "billing_doc_type", $u['doc_type'] );
    update_user_meta( $user_id, "billing_vat_type", $u['vat_type'] );
    //update_user_meta( $user_id, "billing_tax_number", $u['tax_number'] );
    update_user_meta( $user_id, "billing_price_type", $u['price_type'] );
    update_user_meta( $user_id, "billing_birth_date", $u['birth_date'] );
    // BILLING
    update_user_meta( $user_id, "billing_first_name", $u['name'] );
    update_user_meta( $user_id, "billing_last_name", "" );
    update_user_meta( $user_id, "billing_company", $u['fantasy_name'] );
    update_user_meta( $user_id, "billing_address_1", $u['address'] );
    update_user_meta( $user_id, "billing_address_2", $u['neighborhood'] );
    update_user_meta( $user_id, "billing_address_number", $u['number'] );
    update_user_meta( $user_id, "billing_address_apt", $u['apartment'] );
    update_user_meta( $user_id, "billing_city", kibooGetCityName($u['city']) );
    update_user_meta( $user_id, "billing_postcode", $u['zip'] );
    update_user_meta( $user_id, "billing_country", kibooGetOption('woo_api_country') );
    update_user_meta( $user_id, "billing_state", $u['state'] );
    update_user_meta( $user_id, "billing_email", $u['email'] );
    update_user_meta( $user_id, "billing_phone", $u['phone'] );
    // SHIPPING
    update_user_meta( $user_id, "shipping_first_name", $u['name'] );
    update_user_meta( $user_id, "shipping_last_name", "" );
    update_user_meta( $user_id, "shipping_company", $u['fantasy_name'] );
    update_user_meta( $user_id, "shipping_address_1", $u['address'] );
    update_user_meta( $user_id, "shipping_address_2", $u['neighborhood'] );
    update_user_meta( $user_id, "shipping_city", kibooGetCityName($u['city']) );
    update_user_meta( $user_id, "shipping_postcode", $u['zip'] );
    update_user_meta( $user_id, "shipping_country", kibooGetOption('woo_api_country') );
    update_user_meta( $user_id, "shipping_state", $u['state'] );
}

//return new WP_Error( 'rest_product_invalid', esc_html__( 'The product does not exist.', 'my-text-domain' ), array( 'status' => 404 ) );

/**
 * DELETE Customers
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/customers/(?P<kiboo_id>\S*)', array(
        'methods' => 'DELETE',
        'callback' => 'kibooAPICustomersDelete',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );

function kibooAPICustomersDelete( $data ) {

    $error = null;

    $args = array(
        'role' => 'customer',
        'meta_query' => array(
            array(
                'key'   => 'kiboo_id',
                'value' => $data['kiboo_id']
            )
        )
    );

    $query = new WP_User_Query($args);
    $items = $query->get_results();

    if(count($items) == 0){
        $error = new WP_Error( 'customer-not-found', __( "Customer not found", "kiboo" ) );
    }else{
        foreach($items as $item){
            $user_id = $item->ID;
            wp_delete_user($user_id);
            break;
        }
    }

    $result = array(
        'kiboo_id' => $data['kiboo_id'],
        'ec_id' => (int)$user_id,
        'error' => $error
    );

    return $result;
}
?>