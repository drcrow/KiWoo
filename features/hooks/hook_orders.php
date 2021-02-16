<?php
/**
 * Hook on New Orders
 */

if(kibooGetOption('new_order_hook') == 1){
    add_action( 'woocommerce_thankyou', 'kibooHookNewOrder',  10, 1  );
}

/**
 * Hook New Order
*/

function kibooHookNewOrder( $order_id ) {
    //$order_id = 5901;
    //TODO make this came from settings
    $payment_methods = array(
        'bacs' => array(
            'label'         => 'BANK_TRANSFER',
            'cardPlan'      => 0,
            'creditCard'    => 0
        ),
        'ctacte' => array(
            'label'         => 'CURRENT_ACCOUNT_CUSTOMER',
            'cardPlan'      => 0,
            'creditCard'    => 0
        ),
        'woo-mercado-pago-basic' => array(
            'label'         => 'CREDIT_CARD',
            'cardPlan'      => kibooGetOption('cc_card_plan'),
            'creditCard'    => kibooGetOption('cc_credit_card')
        )
    );

    $current_user = wp_get_current_user(); 

    $wholesaler = get_user_meta($current_user->ID, 'wholesaler', true);
    
    if ($wholesaler != 1) {
        if (kibooGetOption('send_orders_as_notwholesalers') == 1) {
            $send_as = "CUSTOMER_NOTE";
        } else {
            $send_as = "SALE_RECEIPT";
        }
        $post_number = kibooGetOption('pos_number_notwholesalers');
        $service_channel = kibooGetOption('service_channel_notwholesalers');
    } else {
        if (kibooGetOption('send_orders_as_wholesalers') == 1) {
            $send_as = "CUSTOMER_NOTE";
        } else {
            $send_as = "SALE_RECEIPT";
        }
        $post_number = kibooGetOption('pos_number_wholesalers');
        $service_channel = kibooGetOption('service_channel_wholesalers');
    }

    if (kibooGetOption('force_bank_transfer') == 1){
        $send_as = "CUSTOMER_NOTE";
    }

    $order          = wc_get_order( $order_id );
    $items          = $order->get_items();
    $customer       = kibooGetCustomer($order->get_customer_id());
    $date           = $order->get_date_created();
    $payment_method = $payment_methods[$order->get_payment_method()];
    //$total          = $order->get_total() - $order->get_total_tax();
    $total          = $order->get_total();


    //echo '<pre>'.print_r($items, true).'</pre><hr>';
    
    $new_order_products = array();
    foreach($items as $item_id => $item){

        //$prices = kibooGetProductPrices($item->get_product_id());
        //die('drcrow '.print_r(get_class_methods($item), true));
        //$price_type = 'NET_'.$customer['price_type'];
        //$price = $prices[$price_type];

        $price = $item->get_subtotal() / $item->get_quantity();
        //echo $item->get_subtotal();
        //print_r(get_class_methods($item));
        //die();

        $product_kiboo_id   = get_post_meta( $item->get_product_id(), "kiboo_id", true );
        $variation_kiboo_id = (int)get_post_meta( $item->get_variation_id(), "kiboo_id", true );

        if($customer['birth_date'] != ''){
            $birth_date = $customer['birth_date'].'T17:25:43.511Z';
        }else{
            $birth_date = null;
        }

        if($variation_kiboo_id == false){
            $variation_kiboo_id = null;
        }

        $new_order_products[] = array(
            "productId"                 => (int)$product_kiboo_id,
            "productVariantId"          => $variation_kiboo_id,
            "description"               => "",
            "quantity"                  => (int)$item->get_quantity(),
            "unitPrice"                 => number_format($price, 2),
            "discountPercentage"        => 15,
            "discountAmount"            => $price - (($price * 15) / 100),
            "notes"                     => "-"
        );
    }

    $new_order = array(
        "externalCode"                      => (int)$order_id,
        "posNumber"                         => (int)kibooGetOption('pos_number'),
        "issueDate"                         => $date->__toString(),
        "customer"                          => array(
            "id"                    => (int)$customer['kiboo_id'],
            "documentTypeCode"      => $customer['doc_type'],
            "documentNumber"        => $customer['doc_number'],
            "email"                 => $customer['email'],
            "name"                  => $customer['name'],
            "birthDate"             => $birth_date,
            "phone"                 => $customer['phone'],
            "address"               => $customer['address'],
            "number"                => $customer['address_number'],
            "apartment"             => $customer['address_apt'],
            "neighborhood"          => $customer['neighborhood'],
            "city"                  => $customer['city'],
            "state"                 => $customer['state'],
            "zipCode"               => $customer['zip'],
            "vatClassificationCode" => $customer['vat_type'],
        ),
        "currencyISOCode"                   => $order->get_currency(),
        "priceType"                         => $customer['price_type'],
        "grossIncomePerceptionAmount"       => 0,
        "grossIncomePerceptionPercentage"   => 0,
        "globalDiscountPercentage"          => 0,
        "globalDiscountAmount"              => 0,
        "netRechargeAmount"                 => 0,
        "notes"                             => $order->get_customer_note(),
        "serviceChannel"                    => kibooGetOption('service_channel'),
        "items"                             => $new_order_products,
        "shipping"                          => array(
            "name"                              => trim(get_post_meta( $order_id, "_shipping_first_name", true ).' '.get_post_meta( $order_id, "_shipping_last_name", true )),
            "address"                           => get_post_meta( $order_id, "_shipping_address_1", true ),
            "number"                            => get_post_meta( $order_id, "_shipping_address_number", true ),
            "apartment"                         => get_post_meta( $order_id, "_shipping_address_apt", true ),
            "city"                              => get_post_meta( $order_id, "_shipping_city", true ),
            "state"                             => kibooGetStateName(get_post_meta( $order_id, "_shipping_state", true )),
            "zipCode"                           => get_post_meta( $order_id, "_shipping_postcode", true ),
            "cost"                              => get_post_meta( $order_id, "_order_shipping", true ),
            //"provider"                          => get_post_meta( $order_id, "_chosen_shipping", true ),
            "provider"                          => "OCA",
        ),
        "payment"                           => array(
                "observations"                  => "-",
                "details"                       => array(array(
                    "currency"                      => $order->get_currency(),
                    "paymentMethod"                 => $payment_method['label'],
                    "price"                         => number_format($total, 2),
                    "paymentReference"              => (int)$_GET['collection_id'], //returned by mercadopago https://www.mercadopago.com.ar/developers/es/guides/online-payments/checkout-pro/advanced-integration/
                    "cardPlan"                      => $payment_method['cardPlan'],
                    "creditCard"                    => $payment_method['creditCard'],
                    "sendAs"                       => $send_as,
                    "postNumber"                   => $post_number,
                    "serviceChannel"               => $service_channel
                ))
        )
    );
    //die('<pre id="drcrow">'.print_r($new_order, true).'</pre>');
    kibooSendHook('new_order', $order_id, $new_order);
}
?>