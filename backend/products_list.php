<?php
/**
 * Ads custom columns to Products
 */

add_filter( 'manage_edit-product_columns', 'kibooProductsListCustomColumn' );
function kibooProductsListCustomColumn($columns) {

    $columns = kibooArrayInsertAfterKey($columns, 'cb', 'kiboo_id', __( 'Kiboo ID', 'kiboo' ));

    //die(print_r($columns, true));

    return $columns;
}


add_action( 'manage_product_posts_custom_column' , 'kibooProductsListCustomColumnValue', 10, 3 );
function kibooProductsListCustomColumnValue( $column, $prodID ) {
    //echo 'drcrow '.$column;
    switch ( $column ) {

        case 'kiboo_id' :
            $kibooID = get_post_meta( $prodID , 'kiboo_id' , true );
            echo $kibooID;
            break;


    }
}

//SORTABLE
add_filter( 'manage_product_sortable_columns', 'my_sortable_cake_column' );
function my_sortable_cake_column( $columns ) {
    $columns['kiboo_id'] = 'kiboo_id';
    return $columns;
}