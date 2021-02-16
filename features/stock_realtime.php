<?php
/**
 * Real-Time Stock
 */

//https://rudrastyh.com/woocommerce/custom-checkout-validation.html
if(kibooGetOption('manage_stock') == 1){
	add_action( 'woocommerce_after_checkout_validation', 'kibooRealtimeStock', 9999, 2);
}


function kibooRealtimeStock( $fields, $errors ){

	//die(print_r($fields, true));

	if ( WC()->cart->is_empty() ) {
        return;
	}

	$products_to_check = array();
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		//die(print_r($cart_item, true));
		//The endpoint only gets de kiboo_id the other info is just for the error message when is out of stock
		$products_to_check[] = array(
			'id' 					=> $cart_item['product_id'],
			'kiboo_id' 				=> (int)get_post_meta($cart_item['product_id'], 'kiboo_id', true),
			'variation_id' 			=> $cart_item['variation_id'],
			'variation_kiboo_id' 	=> (int)get_post_meta($cart_item['variation_id'], 'kiboo_id', true),
			'qty'					=> $cart_item['quantity'],
			'name'					=> $cart_item['data']->get_title()
		);
	}

	//die(print_r($products_to_check, true));

	$resp = kibooSendHook('stock', null, $products_to_check);
	//s $resp = json_decode($resp, true);
	//die(print_r($resp));

	foreach($resp['data'] as $resp_item){
		foreach($products_to_check as $products_item){
			if($products_item['variation_kiboo_id']!=0){ // variation product
				if(
				$resp_item['productId'] == $products_item['kiboo_id'] &&
				$resp_item['productVariantId'] == $products_item['variation_kiboo_id'] &&
				($resp_item['currentStock'] - $products_item['qty']) <= 0){
					$errors->add( 'stock', sprintf( __( "Product '%s' is out of stock", "kiboo" ), $products_item['name'] ) );
				}
			}else{ // single product
				if(
					$resp_item['productId'] == $products_item['kiboo_id'] &&
					($resp_item['currentStock'] - $products_item['qty']) <= 0){
						$errors->add( 'stock', sprintf( __( "Product '%s' is out of stock", "kiboo" ), $products_item['name'] ) );
					}
			}
		}
	}
}
?>