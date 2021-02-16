<?php
/**
 * Ads custom columns to Product Categories list
 */

add_filter( 'manage_edit-product_cat_columns', 'kibooCategoriesListCustomColumn' );
function kibooCategoriesListCustomColumn($columns) {

    $columns = kibooArrayInsertAfterKey($columns, 'cb', 'kiboo_id', __( 'Kiboo ID', 'kiboo' ));

    //die(print_r($columns, true));

    return $columns;
}


add_action( 'manage_product_cat_custom_column' , 'kibooCategoriesListCustomColumnValue', 10, 3 );
function kibooCategoriesListCustomColumnValue( $val, $column, $termID ) {
    switch ( $column ) {

        case 'kiboo_id' :
            $kibooID = get_term_meta( $termID , 'kiboo_id' , true );
            return $kibooID;
            break;


    }
}