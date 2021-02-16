<?php
/**
 * Wholesaler VAT can be set to 0
 */

if (kibooGetOption('wholesaler_vat0') == 1){
    add_filter( 'woocommerce_product_get_tax_class', 'kibooWholesalerVAT0', 1, 2 );
    add_filter( 'woocommerce_product_variation_get_tax_class', 'kibooWholesalerVAT0', 1, 2 );
} 

add_action( 'wp_head', 'only_net_price' );
function only_net_price() { 
    echo '<style type="text/css">
    .only-net-price tr.cart-subtotal, .only-net-price tr.tax-total, .only-net-price small.tax_label, .tax_label{
        display:none!important;
    }
    </style>';
} 
//die(kibooGetOption('wholesaler_vat0') );
add_filter( 'body_class', 'webroom_add_body_class' );
function webroom_add_body_class( $classes ) {
    $current_user = wp_get_current_user();  
    $wholesaler = get_user_meta($current_user->ID, 'wholesaler', true);
    if ($wholesaler != 1) {
        $classes[] = "only-net-price";
    }
    if (($wholesaler == 1) && (kibooGetOption('wholesaler_vat0') == 1)) {
        $classes[] = "wholesaler";
    } 
    if (($wholesaler == 1) &&  (kibooGetOption('wholesaler_vat0') == 0)) {
        $classes[] = "only-net-price";
    }
    return $classes;
}
function kibooWholesalerVAT0( $tax_class, $product ) {

    //die($tax_class);

    $current_user = wp_get_current_user();  
    $wholesaler = get_user_meta($current_user->ID, 'wholesaler', true);
    if($wholesaler == 1){
        $tax_class = 'kiboo_iva_standard';
    } 
    //die($tax_class);
    return $tax_class;
}   
