<?php
/**
 * Ads custom columns to Users list
 */

add_filter( 'manage_users_columns', 'kibooUsersListCustomColumn' );
function kibooUsersListCustomColumn($columns) {

    $columns = kibooArrayInsertAfterKey($columns, 'cb', 'kiboo_id', __( 'Kiboo ID', 'kiboo' ));
    $columns = kibooArrayInsertAfterKey($columns, 'kiboo_id', 'kiboo_price_type', __( 'Type', 'kiboo' ));

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
        case 'kiboo_price_type' :
            $txt = get_user_meta( $userID , 'kiboo_price_type' , true );
            if(get_user_meta( $userID , 'wholesaler' , true ) == 1){
                $txt.='<br>'.__( 'Wholesaler', 'kiboo' );
            }
            return $txt;
            break;


    }
}