<?php
/**
 * Product Price relative to User's "kiboo_price_type" meta
 * 
*/
/* 
    ** wholesaler_vat0 = 0 -> MOSTRAR PRECIOS CON IVA
    ** wholesaler_vat0 = 1 -> MOSTRAR PRECIOS SIN IVA
*/
// PRODUCT SIMPLE
add_filter('woocommerce_product_get_price', 'kibooGetCustomPrice', 10, 2);
add_filter('woocommerce_product_get_regular_price', 'kibooGetCustomRegularPrice', 10, 2);
// PRODUCT VARIATION
add_filter('woocommerce_product_variation_get_price', 'kibooGetCustomPrice', 10, 2);
add_filter('woocommerce_product_variation_get_regular_price', 'kibooGetCustomRegularPrice', 10, 2);
// HTML PRICE
add_filter('woocommerce_get_price_html', 'kibooGetCustomHTMLPrice', 20, 2);


if(kibooGetOption('wholesaler_vat0') == 1){
    add_action('wp_head', 'removeTextImpuesto', 100);
}
function kibooGetCustomPrice($price, $product)
{
    //ON SALE
    if ($product->is_on_sale())
    {
        $price = $product->get_sale_price();
    }
    else
    {
        $price = kibooGetCustomRegularPrice($price, $product) ;
    }
    if (did_action('woocommerce_before_calculate_totals') >= 2)
    {
        //die($price);
        return $price;
    }
    //print_r($product);
    $tax = new WC_Tax();
    $product_tax_class = $product->get_tax_class();
    $current_tax = $tax->get_rates($product_tax_class);
    // get_rates() returns an array with one sub_array with a variable index
    $current_tax = $current_tax[array_key_first($current_tax) ];
    //die($price.' - '.$product_tax_class.' - '.print_r($current_tax, true));
    // the magic formula
    $current_user = wp_get_current_user();
    $wholesaler = get_user_meta($current_user->ID, 'wholesaler', true);
    //$price + ($price * $current_tax['rate'] / 100);
    // Condicional dependiendo el tipo de cliente (Consumidor final/Wholesaler)
    //$price = $price - ($price * $current_tax['rate'] / 100);
    if (($wholesaler == 1) && (kibooGetOption('wholesaler_vat0') == 1))
    {
        $price = $price / (1 + $current_tax['rate'] / 100);
    }
    if (($wholesaler == 1) && (kibooGetOption('wholesaler_vat0') == 0))
    {
        $price = $price - ($price * $current_tax['rate'] / 100);
    }
    return $price;
}
function kibooGetCustomRegularPrice($price, $product)
{
    $priceType = kibooGetCurrentUserPriceType();
    $prices = kibooGetProductPrices($product->get_id());
    $price = @$prices[$priceType];
    $tax = new WC_Tax();
    $product_tax_class = $product->get_tax_class();
    $current_tax = $tax->get_rates($product_tax_class);
    $current_tax = $current_tax[array_key_first($current_tax) ];
    $current_user = wp_get_current_user();
    $wholesaler = get_user_meta($current_user->ID, 'wholesaler', true);
    $sale_price = get_post_meta( $product->get_id(), '_sale_price', true);
    // Si es wholesaler, el precio tachado debe ser sin IVA.
    if ( ($sale_price > 0) && ($wholesaler == 1) && (kibooGetOption('wholesaler_vat0') == 1)) {
        $price = $price - ($price * $current_tax['rate'] / 100);
    }
    return $price;
}
function kibooGetCustomHTMLPrice($price, $product)
{
    $productType = $product->get_type();
   // print_r($productType);
    if ($productType == 'simple')
    {       
    }
    if ($productType == 'variable')
    {
        $priceType = kibooGetCurrentUserPriceType();
        $prices = kibooGetProductPrices($product->get_id());
        $price = $prices[$priceType];
        $tax = new WC_Tax();
        $product_tax_class = $product->get_tax_class();
        $current_tax = $tax->get_rates($product_tax_class);
        $current_tax = $current_tax[array_key_first($current_tax) ];
        $current_user = wp_get_current_user();
        $wholesaler = get_user_meta($current_user->ID, 'wholesaler', true);
        $sale_price = get_post_meta( $product->get_id(), '_sale_price', true);
        // Si es wholesaler, el precio tachado debe ser sin IVA.
        if (($wholesaler == 1) && (kibooGetOption('wholesaler_vat0') == 0)) {
            $price = $price + ($price * $current_tax['rate'] / 100);
        }
        if ($wholesaler != 1)  {
            $price = $price + ($price * $current_tax['rate'] / 100);
        }
       // $price = number_format($price, 2);
        $price = apply_filters('woocommerce_variable_price_html', wc_price( $price ) . $product->get_price_suffix(), $product );   
        return $price;
    }
    return $price;
}
function kibooGetChildrenPrices($parentID)
{

}
//https://stackoverflow.com/questions/45806249/change-product-prices-via-a-hook-in-woocommerce-3/45807054#45807054
/*
add_filter('woocommerce_get_variation_prices_hash', 'variationPricesHash');
function variationPricesHash($hash) {
    $hash[] = get_current_user_id();
    return $hash;
}*/

add_filter('woocommerce_widget_cart_item_quantity', 'custom_wc_widget_cart_item_quantity', 50, 3);
function custom_wc_widget_cart_item_quantity($output, $cart_item, $cart_item_key)
{
    //return print_r($cart_item, true);
    $priceType = kibooGetCurrentUserPriceType();
    $prices = kibooGetProductPrices($cart_item['product_id']);
    $price = $prices[$priceType];
    //$price = number_format($price, 2);
    $product = wc_get_product($cart_item['product_id']);
    $tax = new WC_Tax();
    $product_tax_class = $product->get_tax_class();
    $current_tax = $tax->get_rates($product_tax_class);
    // get_rates() returns an array with one sub_array with a variable index
    $current_tax = $current_tax[array_key_first($current_tax) ];
    if ($product->is_on_sale())
    {
        $price = $product->get_sale_price();
    }
    else
    {
        $price = kibooGetCustomRegularPrice($price, $product); 
    }
    // ICON CART
    $current_user = wp_get_current_user();
    $wholesaler = get_user_meta($current_user->ID, 'wholesaler', true);
    if($wholesaler != 1){
        $price = $price + ($price * $current_tax['rate'] / 100);
    }
    if (($wholesaler == 1) && (kibooGetOption('wholesaler_vat0') == 0)) {
        $price = $price + ($price * $current_tax['rate'] / 100);
    }
    if (($wholesaler == 1) && (kibooGetOption('wholesaler_vat0') == 1)) {
        //$price = $price + ($price * $current_tax['rate'] / 100);
    }
    $output = '<span class="quantity hello">' . sprintf('%s &times; $%s', $cart_item['quantity'], $price) . '</span>';
    return $output;
}

// CHANGE PRICE FINAL
add_filter('woocommerce_calculated_total', 'custom_cart_grand_total', 20, 2);
function custom_cart_grand_total($total, $cart )
{   


    $current_user = wp_get_current_user();
    $wholesaler = get_user_meta($current_user->ID, 'wholesaler', true);
    if (($wholesaler == 1)  && (kibooGetOption('wholesaler_vat0') == 1)) 
    {   
        $cart->taxes = ($cart->subtotal * 21) / 100;
        $taxes = ($cart->subtotal * 21) / 100;
        $new_total = $cart->subtotal + $cart->shipping_total;
        $total = $new_total;
        // if ($wholesaler == 1){
        //     // if ( WC()->cart->has_discount( 'freeweek' ) )  {
        //     //     $total = $new_total - (($new_total * 15) / 100);
        //     // }
        // }
        echo "<style>.tax-total {display: none;}.order-total td bdi::after {content: '+ Impuestos';display: inline-flex;color: grey;}</style>";
    }
    return $total;
}

add_action( 'woocommerce_calculate_totals', 'action_cart_calculate_totals', 10, 1 );
function action_cart_calculate_totals( $cart_object ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
    if ( !WC()->cart->is_empty() ):
    endif;
}

function removeTextImpuesto(){
    $current_user = wp_get_current_user();
    $wholesaler = get_user_meta($current_user->ID, 'wholesaler', true);
    if($wholesaler === 1){
        echo "<style>.fee th {font-size: 0px !important;}.fee td span bdi::after {content: '+ Impuestos';display: table-header-group;color:grey;}</style>";
        if (kibooGetOption('display_wholesalers') == 0) {  
            //echo "<style>.onbackorder{display:none}</style>";
        }
    }
    if (kibooGetOption('display_notwholesalers') == 0) {     
        //echo "<style>.onbackorder{display:none}</style>";
    }
}