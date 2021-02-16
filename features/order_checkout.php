<?php
/**
 * Custom Fields for Order Checkout
 */

//https://docs.woocommerce.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
//https://www.businessbloomer.com/woocommerce-add-custom-checkout-field-php/

add_filter( 'woocommerce_checkout_fields' , 'kibooCheckoutFields', 999, 1 );

// Our hooked in function - $fields is passed via the filter!
function kibooCheckoutFields( $fields ) {

    global $wpdb;

    $doc_types = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_doc_types", OBJECT );
    $doc_types_options = array();
    foreach($doc_types as $doc_type){
        $doc_types_options[$doc_type->ID] = $doc_type->name;
    }

    $vat_types = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_vat_types", OBJECT );
    $vat_types_options = array();
    foreach($vat_types as $vat_type){
        $vat_types_options[$vat_type->ID] = $vat_type->name;
    }



    //die('<pre>'.print_r($fields, true).'</pre>');

    $fields['billing']['billing_address_2']['validate'] = false;

    $fields['billing']['billing_email']['priority'] = 21;

    $fields['billing']['billing_invoice_type'] = array(
        'label'         => __( 'Invoice Type', 'kiboo' ),
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-wide'),
        'priority'      => 23,
        'type'          => 'select',
        'options'       => array(1 => 'A', 2 => 'B')
    );

    $fields['billing']['billing_vat_type'] = array(
        'label'         => __( 'VAT Type', 'kiboo' ),
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-wide'),
        'priority'      => 24,
        'type'          => 'select',
        'options'       => $vat_types_options
    );

    $fields['billing']['billing_company'] = array(
        'label'         => __( 'Company Name', 'kiboo' ),
        'placeholder'   => '',
        'required'      => false,
        'class'         => array('form-row-wide'),
        'priority'      => 25
    );

    $fields['billing']['billing_doc_type'] = array(
        'label'         => __( 'Doc Type', 'kiboo' ),
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-first'),
        'priority'      => 26,
        'type'          => 'select',
        'options'       => $doc_types_options
    );

    $fields['billing']['billing_doc_number'] = array(
        'label'         => __( 'Doc Number', 'kiboo' ),
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-last'),
        'priority'      => 27
    );

    $fields['billing']['billing_phone'] = array(
        'label'         => __( 'Phone', 'kiboo' ),
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-wide'),
        'priority'      => 29
    );

    $fields['billing']['billing_birth_date'] = array(
        'label'         => __( 'Birth Date', 'kiboo' ),
        'placeholder'   => '',
        'required'      => false,
        'class'         => array('form-row-wide'),
        'priority'      => 31,
        'type'          => 'date'
    );

    return $fields;
}



add_filter( 'woocommerce_default_address_fields' , 'kibooCheckoutAddressFields', 999, 1 );

function kibooCheckoutAddressFields( $address_fields_array ) {

    //die('<pre>'.print_r($address_fields_array, true).'</pre>');

    global $wpdb;

    $user_id = get_current_user_id();
    if($user_id > 0){
        $state_id = get_user_meta($user_id, 'billing_state', true);
        if((int)$state_id == 0){
            $state_id = kibooGetOption('default_state');
        }
    }else{
        $state_id = kibooGetOption('default_state');
    }

    $states = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_states ORDER BY name", OBJECT );
    $states_options = array();
    foreach($states as $state){
        $states_options[$state->ID] = $state->name;
    }

    $cities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_cities WHERE state_id = {$state_id} ORDER BY name", OBJECT );
    $cities_options = array();
    foreach($cities as $city){
        $cities_options[$city->ID] = $city->name;
    }

    //die((int)$state_id.' '.print_r($cities_options, true));

    //$cities_options = array(0 => 'aaaa');

    //die('<pre>'.print_r($address_fields_array, true).'</pre>');

    //unset($address_fields_array['company']);
    //unset($address_fields_array['country']);

    $address_fields_array['city']['priority'] = 82;

    $address_fields_array['address_1'] = array(
        'label'         => __( 'Address', 'kiboo' ),
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-wide'),
        'priority'      => 33
    );

    $address_fields_array['address_number'] = array(
        'label'         => __( 'Number', 'kiboo' ),
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-first'),
        'priority'      => 34
    );

    $address_fields_array['address_apt'] = array(
        'label'         => __( 'Apartment', 'kiboo' ),
        'placeholder'   => '',
        'required'      => false,
        'class'         => array('form-row-last'),
        'priority'      => 35
    );

    $address_fields_array['address_2'] = array(
        'label'         => __( 'Neighborhood', 'kiboo' ),
        'placeholder'   => '',
        'required'      => false,
        'class'         => array('form-row-first'),
        'priority'      => 36
    );

    $address_fields_array['postcode'] = array(
        'label'         => __( 'ZIP Code', 'kiboo' ),
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-last'),
        'priority'      => 37
    );

    $address_fields_array['country']['label'] = __( 'Country', 'kiboo' );
    $address_fields_array['state']['label'] = __( 'State', 'kiboo' );
    $address_fields_array['city']['label'] = __( 'City', 'kiboo' );

    /*
    $address_fields_array['state'] = array(
        'label'         => __( 'State', 'kiboo' ),
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-first'),
        'priority'      => 33,
        'type'          => 'select',
        'options'       => $states_options
    );

    $address_fields_array['city'] = array(
        'label'         => __( 'City', 'kiboo' ),
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-last'),
        'priority'      => 34,
        'type'          => 'select',
        'options'       => $cities_options
    );
*/

	//unset( $address_fields_array['state']['validate']);
	//unset( $address_fields_array['postcode']['validate']);
	// you can also hook first_name and last_name, company, country, city, address_1 and address_2

	return $address_fields_array;

}


/**
 * This adds the JS for the Invoice Type
 */

add_action('woocommerce_after_order_notes', 'kibooInvoiceTypeJS', 999, 1);

function kibooInvoiceTypeJS($checkout){
    ?>

    <script>

    jQuery('#billing_invoice_type').change(function() {
        invoice_type = jQuery('#billing_invoice_type').val();
        if(invoice_type == 1){//A
            jQuery('#billing_vat_type').val(1);
            jQuery('#billing_doc_type').val(80);
            jQuery('#billing_company_field').show();
        }

        if(invoice_type == 2){//B
            jQuery('#billing_vat_type').val(5);
            jQuery('#billing_doc_type').val(96);
            jQuery('#billing_company_field').hide();
        }
    });
    </script>
    <?php

    return $checkout;
}



/**
 * DEPRECATED This adds the JS for the Cities
 */

//https://wedevs.com/blog/105722/add-extra-field-to-woocommerce-checkout

//add_action('woocommerce_after_order_notes', 'kibooCheckoutFieldsJS', 999, 1);

function kibooCheckoutFieldsJS($checkout){
    global $wpdb;
    $cities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_cities ORDER BY name", OBJECT );
    $cities = json_encode($cities);
    ?>

    <script>
    cities = <?=$cities; ?>;
    //console.log(cities);


    jQuery('#billing_state').change(function() {
        state_id = jQuery('#billing_state').val();
        jQuery('#billing_city').find('option').remove();
        for(var k in cities) {
            if(cities[k].state_id == state_id){
                jQuery('#billing_city').append('<option value="'+cities[k].ID+'">'+cities[k].name+'</option>');
            }
        }
    });

    jQuery('#shipping_state').change(function() {
        state_id = jQuery('#shipping_state').val();
        jQuery('#shipping_city').find('option').remove();
        for(var k in cities) {
            if(cities[k].state_id == state_id){
                jQuery('#shipping_city').append('<option value="'+cities[k].ID+'">'+cities[k].name+'</option>');
            }
        }
    });
    </script>
    <?php

    return $checkout;
}
?>