<?php
/**
 * Current Account REST API
 * Current Account info is saved in custom tables (wp_kiboo_current_accounts and wp_kiboo_current_accounts_movements)
 * /current_accounts
 * /current_accounts_movements
 */

/**
 * INSERT / UPDATE (POST)
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/current_accounts/', array(
        'methods' => 'POST',
        'callback' => 'kibooAPICurrentAccountsAdd',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );

function kibooAPICurrentAccountsAdd( $data ) {
    $data = $data->get_params();
    $result = array();

    foreach($data as $cuAcc){
        $error = null;

        //Check Customer
        $customerID = kibooCustomerIDByKibooID($cuAcc['kiboo_customer_id']);
        if($customerID > 0){
            //Create Current Account
            $cuAccID = kibooCurrentAccountsAdd($cuAcc);
            if($cuAccID == 0){
                $error = new WP_Error( 'current-account-creation-error', __( "Current Account creation error", "kiboo" ), array( 'status' => 500 ) );
            }
        }else{
            $error = new WP_Error( 'customer-not-found', __( "Customer not found", "kiboo" ), array( 'status' => 404 ) );
        }

        $result[] = array(
            'kiboo_customer_id'     => $cuAcc['kiboo_customer_id'],
            'ec_customer_id'        => $customerID,
            'ec_id'                 => $cuAccID,
            'error'                 => $error
        );

    }//foreach $data

    return $result;
}

function kibooCurrentAccountsAdd($data){
    global $wpdb;
    $ID = 0;

    $table_name = $wpdb->prefix.'kiboo_current_accounts';
    $last_payment_date = date("Y-m-d", strtotime($data['last_payment_date']));

    $sql = 'SELECT ID FROM '.$table_name.' WHERE kiboo_customer_id = "'.$data['kiboo_customer_id'].'"';
    $originalID = $wpdb->get_var($sql);

    if($originalID > 0){
        $wpdb->update(
            $table_name,
            array(
                'kiboo_customer_id'     => $data['kiboo_customer_id'],
                'ca_limit'              => $data['limit'],
                'ca_available'          => $data['available'],
                'ca_last_payment_date'  => $last_payment_date,
                'ca_balance'            => $data['balance']
            ),
            array(
                'ID' => $originalID
            )
        );

        $ID = $originalID;
    }else{

        $wpdb->insert(
            $table_name,
            array(
                'kiboo_customer_id'     => $data['kiboo_customer_id'],
                'ca_limit'              => $data['limit'],
                'ca_available'          => $data['available'],
                'ca_last_payment_date'  => $last_payment_date,
                'ca_balance'            => $data['balance']
            )
        );

        $ID = $wpdb->insert_id;

    }

    return $ID;
}

/**
 * (DELETE)
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/current_accounts/(?P<kiboo_customer_id>\S*)', array(
        'methods'               => 'DELETE',
        'callback'              => 'kibooAPICurrentAccountsDelete',
        'permission_callback'   => 'kibooAPIAuth',
    ) );
} );

function kibooAPICurrentAccountsDelete( $data ) {
    global $wpdb;
    $error = null;
    $table_name = $wpdb->prefix.'kiboo_current_accounts';

    $sql = 'SELECT ID FROM '.$table_name.' WHERE kiboo_customer_id = "'.$data['kiboo_customer_id'].'"';
    $originalID = $wpdb->get_var($sql);

    if($originalID > 0){
        $customerID = kibooCustomerIDByKibooID($data['kiboo_customer_id']);
        if($customerID > 0){
            //Delete Current Account
            $wpdb->delete( $table_name, array( 'ID' => $originalID ) );
        }else{
            $error = new WP_Error( 'customer-not-found', __( "Customer not found", "kiboo" ), array( 'status' => 404 ) );
        }
    }else{
        $error = new WP_Error( 'current-account-not-found', __( "Current Account not found", "kiboo" ), array( 'status' => 404 ) );
    }

    $result = array(
        'kiboo_customer_id'     => $data['kiboo_customer_id'],
        'ec_customer_id'        => $customerID,
        'ec_id'                 => $originalID,
        'error'                 => $error
    );

    return $result;
}
?>