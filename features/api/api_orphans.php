<?php
/**
 * ENDPOINT (GET) Orphan Customers
 */
add_action( 'rest_api_init', function () {
    global $API_NAMESPACE;

    register_rest_route( $API_NAMESPACE, '/orphans/(?P<entity>\S*)', array(
        'methods' => 'GET',
        'callback' => 'kibooAPIOrphanCustomersGet',
        'permission_callback' => 'kibooAPIAuth',
    ) );
} );

function kibooAPIOrphanCustomersGet( $data ) {
    $result = array();

    switch($data['entity']){
        case 'customers':

            $args = array(
                'role' => 'customer',
                'meta_query' => array(
                    'relation' => 'OR', //default AND
                    array(
                        'key' => 'kiboo_id',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key' => 'kiboo_id',
                        'value' => '_wp_zero_value',
                        'compare' => '='
                    )
                )
            );

            $data = new WP_User_Query($args);
            foreach($data->get_results() as $item){
                //$result[] = array('ec_id' => $item->ID);
                $result[] = kibooGetCustomer($item->ID);
            }

        break;

        case 'categories':

            $args = array(
                'taxonomy'      => 'product_cat',
                'hide_empty'    => false,
                'meta_query' => array(
                    'relation' => 'OR', //default AND
                    array(
                        'key' => 'kiboo_id',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key' => 'kiboo_id',
                        'value' => '_wp_zero_value',
                        'compare' => '='
                    )
                )
            );

            $data = new WP_Term_Query($args);
            foreach($data->get_terms() as $item){
                $result[] = array('ec_id' => $item->term_id);
            }

        break;
    }

    return $result;
}
?>