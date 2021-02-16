<?php
/**
 * Current Account Movements REST API
 * Current Account Movements info is saved in custom tables (wp_kiboo_current_accounts and wp_kiboo_current_accounts_movements)
 * /current_accounts
 * /current_accounts_movements
 */

/**
 * INSERT / UPDATE (POST)
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/current_accounts_movements/', array(
        'methods'               => 'POST',
        'callback'              => 'kibooAPICurrentAccountsMovementsAdd',
        'permission_callback'   => 'kibooAPIAuth',
    ) );
} );

function kibooAPICurrentAccountsMovementsAdd( $data ) {
    $data = $data->get_params();
    $result = array();

    foreach($data as $cuAcc){
        $error = null;

        //Check Customer
        $customerID = kibooCustomerIDByKibooID($cuAcc['kiboo_customer_id']);
        if($customerID > 0){
            //Create Current Account
            $cuAccMovID = kibooCurrentAccountsMovementsAdd($cuAcc);
            if($cuAccMovID == 0){
                $error = new WP_Error( 'current-account-movement-creation-error', __( "Current Account Movement creation error", "kiboo" ), array( 'status' => 500 ) );
            }
        }else{
            $error = new WP_Error( 'customer-not-found', __( "Customer not found", "kiboo" ), array( 'status' => 404 ) );
        }

        $result[] = array(
            'ec_id'                 => $cuAccMovID,
            'kiboo_id'              => $cuAcc['kiboo_id'],
            'kiboo_customer_id'     => $cuAcc['kiboo_customer_id'],
            'ec_customer_id'        => $customerID,
            'error'                 => $error
        );

    }//foreach $data

    return $result;
}

function kibooCurrentAccountsMovementsAdd($data){
    global $wpdb;
    $ID = 0;

    $table_name = $wpdb->prefix.'kiboo_current_accounts_movements';
    $operation_date = date("Y-m-d", strtotime($data['operation_date']));

    $sql = 'SELECT ID FROM '.$table_name.' WHERE kiboo_id = "'.$data['kiboo_id'].'"';
    $originalID = $wpdb->get_var($sql);

    if($originalID > 0){
        $wpdb->update(
            $table_name,
            array(
                'kiboo_id'              => $data['kiboo_id'],
                'kiboo_customer_id'     => $data['kiboo_customer_id'],
                'cam_code'              => $data['code'],
                'cam_operation_date'    => $operation_date,
                'cam_debit'             => $data['debit'],
                'cam_credit'            => $data['credit'],
                'cam_balance'           => $data['balance']
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
                'kiboo_id'              => $data['kiboo_id'],
                'kiboo_customer_id'     => $data['kiboo_customer_id'],
                'cam_code'              => $data['code'],
                'cam_operation_date'    => $operation_date,
                'cam_debit'             => $data['debit'],
                'cam_credit'            => $data['credit'],
                'cam_balance'           => $data['balance']
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

    register_rest_route( $API_NAMESPACE, '/current_accounts_movements/(?P<kiboo_id>\S*)', array(
        'methods'               => 'DELETE',
        'callback'              => 'kibooAPICurrentAccountsMovementsDelete',
        'permission_callback'   => 'kibooAPIAuth',
    ) );
} );

function kibooAPICurrentAccountsMovementsDelete( $data ) {
    global $wpdb;
    $error = null;
    $customerID = 0;
    $table_name = $wpdb->prefix.'kiboo_current_accounts_movements';

    $sql = 'SELECT ID FROM '.$table_name.' WHERE kiboo_id = "'.$data['kiboo_id'].'"';
    $originalID = $wpdb->get_var($sql);

    $sql = 'SELECT kiboo_customer_id FROM '.$table_name.' WHERE kiboo_id = "'.$data['kiboo_id'].'"';
    $customerKibooID = $wpdb->get_var($sql);

    if($originalID > 0){
        $customerID = kibooCustomerIDByKibooID($customerKibooID);
        if($customerID > 0){
            //Delete Current Account
            $wpdb->delete( $table_name, array( 'ID' => $originalID ) );
        }else{
            $error = new WP_Error( 'customer-not-found', __( "Customer not found", "kiboo" ), array( 'status' => 404 ) );
        }
    }else{
        $error = new WP_Error( 'current-account-movement-not-found', __( "Current Account Movement not found", "kiboo" ), array( 'status' => 404 ) );
    }

    $result = array(
        'kiboo_id'              => $data['kiboo_id'],
        'kiboo_customer_id'     => $customerKibooID,
        'ec_customer_id'        => $customerID,
        'ec_id'                 => $originalID,
        'error'                 => $error
    );

    return $result;
}
?>