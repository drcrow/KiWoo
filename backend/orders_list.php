<?php
/**
 * Ads custom columns to Orders list
 */

add_filter( 'manage_shop_order_columns', 'kibooUsersListCustomColumn' );
function kibooUsersListCustomColumn($columns) {

    $columns = kibooArrayInsertAfterKey($columns, 'cb', 'kiboo_id', __( 'Kiboo ID', 'kiboo' ));

    //die(print_r($columns, true));

    return $columns;
}


add_action( 'manage_users_custom_column' , 'kibooUsersListCustomColumnValue', 10, 3 );
function kibooUsersListCustomColumnValue( $val, $column, $userID ) {
    switch ( $column ) {

        case 'kiboo_id' :
            $kibooID = get_user_meta( $userID , 'kiboo_id' , true );
            return $kibooID;
            break;


    }
}