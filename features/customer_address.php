<?php
/**
 * Change format of Customer Address to show custom fields in Order Confirmation (post checkuout)
 */

//https://stackoverflow.com/questions/18859237/woocommerce-how-to-customize-the-addresses-output
/*
add_filter( 'woocommerce_formatted_address_replacements', function( $replacements, $args ){
    //print_r($args);
    // we want to replace {phone} in the format with the data we populated
    //$replacements['{phone}'] = $args['phone'];

    $replacements['{city}'] = kibooGetCityName($replacements['{city}']);
    $replacements['{state}'] = kibooGetStateName($replacements['{state}']);

    return $replacements;
}, 10, 2 );
*/


add_filter( 'woocommerce_order_formatted_billing_address', function( $address, $order ){

    $address_number = get_post_meta( $order->ID, "_billing_address_number", true );
    $address_apt = get_post_meta( $order->ID, "_billing_address_apt", true );

    $address['address_1'] = $address['address_1'] . ' ' . $address_number . ' ' . $address_apt;
    $address['city'] = kibooGetCityName($address['city']);
    $address['state'] = kibooGetStateName($address['state']);

    return $address;

}, 10, 2 );

add_filter( 'woocommerce_order_formatted_shipping_address', function( $address, $order ){

    $address_number = get_post_meta( $order->ID, "_shipping_address_number", true );
    $address_apt = get_post_meta( $order->ID, "_shipping_address_apt", true );

    $address['address_1'] = $address['address_1'] . ' ' . $address_number . ' ' . $address_apt;
    $address['city'] = kibooGetCityName($address['city']);
    $address['state'] = kibooGetStateName($address['state']);

    return $address;

}, 10, 2 );
?>