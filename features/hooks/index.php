<?php
require('hook_orders.php');
require('hook_customers.php');

/**
 * Send Hook
 */

function kibooSendHook($hook, $id, $data){
    $client_id      = kibooGetOption('kiboo_client_id');
    $client_secret  = kibooGetOption('kiboo_client_secret');
    $token_url      = kibooGetOption('kiboo_url').'idp/connect/token';
    //Customer Note or Sale Receipt
    if(kibooGetOption('send_orders_as') == 1){
        $order_url  = kibooGetOption('kiboo_url').'api/e-customer-notes';
    }else{
        $order_url  = kibooGetOption('kiboo_url').'api/sales';
    }
    $customer_url   = kibooGetOption('kiboo_url').'api/e-customers';
    $stock_url      = kibooGetOption('kiboo_url').'api/stock/current-stock-products';

    $token = kibooAPIGetToken($client_id, $client_secret, $token_url);

    switch($hook){
        case 'new_order':
            $body       = json_encode($data, JSON_UNESCAPED_UNICODE);
            $subject    = 'New Order - '.$id;

            $response = kibooAPISendHookRequest($data, $order_url, $token['data']['access_token']);
            kibooLogHook($subject, $body, json_encode($response), $order_url);
            //$response = json_decode($response, true);
            //die(print_r($response));
            $order_kiboo_id = (int)$response['data']['id'];
            if($order_kiboo_id > 0){
                update_post_meta($id, 'kiboo_id', $order_kiboo_id);
            }
        break;
        case 'new_customer':
            $body       = json_encode($data, JSON_UNESCAPED_UNICODE);

            $subject    = 'New Customer - '.$id;

            $response = kibooAPISendHookRequest($data, $customer_url, $token['data']['access_token']);
            kibooLogHook($subject, $body, json_encode($response), $customer_url);
            //$response = json_decode($response, true);
            //die(print_r($response));
            $customer_kiboo_id = (int)$response['data']['id'];
            if($customer_kiboo_id > 0){
                update_user_meta($id, 'kiboo_id', $customer_kiboo_id);
            }
        break;
        case 'stock':
            $params = '?';
            foreach($data as $prod_info){
                $params.= '&productChainId='.$prod_info['kiboo_id'];
            }
            $body       = json_encode($data, JSON_UNESCAPED_UNICODE);
            $response = kibooAPISendHookRequest(null, $stock_url.$params, $token['data']['access_token'], false);
            kibooLogHook('Stock', $body, json_encode($response), $stock_url);
        break;
    }

    return $response;
}

/**
 * Log Hook
 */

function kibooLogHook($details, $body, $response, $url=''){
    if(kibooGetOption('log_hooks') == 1){
        global $wpdb;
        $table_name = $wpdb->prefix.'kiboo_log';
        $wpdb->insert(
            $table_name,
            array(
                'date_add'  => current_time('mysql', 1),
                'details'   => $details.' '.$url,
                'body'      => $body,
                'response'  => $response,
            )
        );
    }
}

function kibooAPIGetToken($client_id, $client_secret, $token_url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'grant_type'    => 'client_credentials'
    ));

    $data = curl_exec($ch);
    $error = curl_error($ch);

    $data = json_decode($data, true);

    curl_close($ch);

    return array(
        'data' => $data,
        'error' => $error
    );
}

function kibooAPISendHookRequest($order_info, $url, $access_token, $method = 'post'){
    $ch = curl_init();

    $headr = array();
    //$headr[] = 'Content-length: 0';
    $headr[] = 'Content-type: application/json;charset=utf-8';
    $headr[] = 'Authorization: Bearer '.$access_token;
    $payload = json_encode($order_info, JSON_UNESCAPED_UNICODE);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);

    if($method == 'post'){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }else{
        curl_setopt($ch, CURLOPT_POST, false);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $data = curl_exec($ch);
    $error = curl_error($ch);

    $data = json_decode($data, true);

    curl_close($ch);

    return array(
        'data' => $data,
        'error' => $error
    );
}
?>