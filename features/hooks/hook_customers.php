<?php
/**
 * Hook on New Customer
 */

if(kibooGetOption('new_customer_hook') == 1){
    add_action( 'woocommerce_created_customer', 'kibooHookNewCustomer',  10, 1  );
}

/**
 * Hook New Customer
 */

function kibooHookNewCustomer( $customer_id ) {

    //die('drcrow '.$customer_id);

    $customer       = kibooGetCustomer($customer_id);


    //die(print_r($customer, true));

    if($customer['birth_date'] != ''){
        $birth_date = $customer['birth_date'].'T17:25:43.511Z';
    }else{
        $birth_date = null;
    }

    $new_customer = array(
        //'ec_id'                 => $customer_id,
        'name'                  => mb_convert_encoding($customer['name'], "UTF-8", "auto"),
        'address'               => $customer['address'],
        'addressNumber'         => $customer['address_number'],
        'apartment'             => $customer['address_apt'],
        'phone'                 => $customer['phone'],
        'email'                 => $customer['email'],
        'zipCode'               => $customer['zip'],
        'city'                  => $customer['city'],
        'neighborhood'          => $customer['neighborhood'],
        'state'                 => $customer['state'],
        'vatClassificationCode' => (int)$customer['vat_type'],
        'documentTypeCode'      => (int)$customer['doc_type'],
        'documentNumber'        => $customer['doc_number'],
        'birthDate'             => $birth_date
    );

    //die(print_r($new_customer, true));

    kibooSendHook('new_customer', $customer_id, $new_customer);
}


?>