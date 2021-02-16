<?php
/**
 * Ads extra fields to Customers Registration Form
 */

function kibooRegistrationExtraFields() {
    //global $woocommerce;
    global $wpdb;
    $doc_types = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_doc_types", OBJECT );
    $vat_types = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_vat_types", OBJECT );
    $states = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_states", OBJECT );
    $cities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_cities ORDER BY name", OBJECT );
    $cities_json = json_encode($cities);

    //$countries_obj   = new WC_Countries();
    //$default_county_states = $countries_obj->get_states( kibooGetOption('woo_api_country') );
    //print_r($default_county_states);
    ?>

    <script>
    cities = <?=$cities_json; ?>;
    default_city = "<?=kibooGetOption('default_city'); ?>";
    //console.log(cities);

    function kiboo_get_cities(){
        state_id = jQuery('#reg_billing_state').val();
        jQuery('#reg_billing_city').find('option').remove();
        for(var k in cities) {
            if(cities[k].state_id == state_id){
                //jQuery('#reg_billing_city').append('<option value="'+cities[k].ID+'">'+cities[k].name+'</option>');
                if(default_city == cities[k].name){
                    jQuery('#reg_billing_city').append('<option value="'+cities[k].name+'" selected>'+cities[k].name+'</option>');
                }else{
                    jQuery('#reg_billing_city').append('<option value="'+cities[k].name+'">'+cities[k].name+'</option>');
                }
            }
        }
    }
    </script>
<?php
/**
 * In some woocommerse settings "first name" and "last name" are automatically added showing duplicated fields
 */
if(kibooGetOption('registration_add_name') == 1){
?>
    <!--FIRST NAME-->
    <p class="form-row form-row-first">
        <label for="reg_billing_first_name"><?php _e( 'Name', 'kiboo' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php esc_attr_e( $_POST['billing_first_name'] ); ?>" />
    </p>

    <!--LAST NAME-->
    <p class="form-row form-row-last">
        <label for="reg_billing_last_name"><?php _e( 'Last Name', 'kiboo' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php esc_attr_e( $_POST['billing_last_name'] ); ?>" />
    </p>
<?php
}//if
?>
    <!--DOC TYPE-->
    <p class="form-row form-row-wide">
        <label for="reg_billing_doc_type"><?php _e( 'Doc Type', 'kiboo' ); ?> <span class="required">*</span></label>
        <select name="billing_doc_type" id="reg_billing_doc_type">
        <?php
        foreach($doc_types as $doc_type){
            if($_POST['doc_type'] == $doc_type->ID || (!isset($_POST['doc_type']) && kibooGetOption('default_doc_type') == $doc_type->ID)){
                echo '<option value="'.$doc_type->ID.'" selected>'.$doc_type->name.'</option>';
            }else{
                echo '<option value="'.$doc_type->ID.'">'.$doc_type->name.'</option>';
            }
        }
        ?>
        </select>
    </p>
    <!--DOC NUMBER-->
    <p class="form-row form-row-wide">
        <label for="reg_billing_doc_number"><?php _e( 'Doc Number', 'kiboo' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_doc_number" id="reg_billing_doc_number" value="<?php esc_attr_e( $_POST['billing_doc_number'] ); ?>" />
    </p>
    <!--BIRTH DATE-->
    <p class="form-row form-row-first">
        <label for="reg_billing_birth_date"><?php _e( 'Birth Date', 'kiboo' ); ?></label>
        <input type="date" max="<?=date('Y-m-d'); ?>" class="input-text" name="billing_birth_date" id="reg_billing_birth_date" value="<?php esc_attr_e( $_POST['billing_birth_date'] ); ?>" />
    </p>
    <!--PHONE-->
    <p class="form-row form-row-last">
        <label for="reg_billing_phone"><?php _e( 'Phone', 'kiboo' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e( $_POST['billing_phone'] ); ?>" />
    </p>

    <div class="clear"></div>

    <!--VAT TYPE-->
    <p class="form-row form-row-first">
        <label for="reg_billing_vat_type"><?php _e( 'VAT Type', 'kiboo' ); ?> <span class="required">*</span></label>
        <select name="billing_vat_type" id="reg_billing_vat_type">
        <?php
        foreach($vat_types as $vat_type){
            if($_POST['vat_type'] == $vat_type->ID || (!isset($_POST['vat_type']) && kibooGetOption('default_vat_type') == $vat_type->ID)){
                echo '<option value="'.$vat_type->ID.'" selected>'.$vat_type->name.'</option>';
            }else{
                echo '<option value="'.$vat_type->ID.'">'.$vat_type->name.'</option>';
            }
        }
        ?>
        </select>
    </p>

    <!--ADDRESS-->
    <p class="form-row form-row-last">
        <label for="reg_billing_address_1"><?php _e( 'Address', 'kiboo' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_address_1" id="reg_billing_address_1" value="<?php if ( ! empty( $_POST['billing_address_1'] ) ) esc_attr_e( $_POST['billing_address_1'] ); ?>" />
    </p>

    <div class="clear"></div>

    <!--NUMBER-->
    <p class="form-row form-row-first">
        <label for="reg_billing_address_number"><?php _e( 'Number', 'kiboo' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_address_number" id="reg_billing_address_number" value="<?php if ( ! empty( $_POST['billing_address_number'] ) ) esc_attr_e( $_POST['billing_address_number'] ); ?>" />
    </p>

    <!--APARTMENT-->
    <p class="form-row form-row-last">
        <label for="reg_billing_address_apt"><?php _e( 'Apartment', 'kiboo' ); ?></label>
        <input type="text" class="input-text" name="billing_address_apt" id="reg_billing_address_apt" value="<?php if ( ! empty( $_POST['billing_address_apt'] ) ) esc_attr_e( $_POST['billing_address_apt'] ); ?>" />
    </p>

    <div class="clear"></div>

    <!--NEIGHBORHOOD-->
    <p class="form-row form-row-first">
        <label for="reg_billing_address_2"><?php _e( 'Neighborhood', 'kiboo' ); ?></label>
        <input type="text" class="input-text" name="billing_address_2" id="reg_billing_address_2" value="<?php if ( ! empty( $_POST['billing_address_2'] ) ) esc_attr_e( $_POST['billing_address_2'] ); ?>" />
    </p>

    <!--ZIP CODE-->
    <p class="form-row form-row-last">
        <label for="reg_billing_postcode"><?php _e( 'ZIP Code', 'kiboo' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_postcode" id="reg_billing_postcode" value="<?php if ( ! empty( $_POST['billing_postcode'] ) ) esc_attr_e( $_POST['billing_postcode'] ); ?>" />
    </p>

    <div class="clear"></div>

    <!--STATE-->
    <p class="form-row form-row-first">
        <label for="reg_billing_state"><?php _e( 'State', 'kiboo' ); ?> <span class="required">*</span></label>
        <select name="billing_state" id="reg_billing_state" onChange="kiboo_get_cities()">
        <?php
        foreach($states as $state){
            if($_POST['billing_state'] == $state->ID || (!isset($_POST['billing_state']) && kibooGetOption('default_state') == $state->ID)){
                echo '<option value="'.$state->ID.'" selected>'.$state->name.'</option>';
            }else{
                echo '<option value="'.$state->ID.'">'.$state->name.'</option>';
            }
        }
        ?>
        </select>
    </p>
    <!--CITY-->
    <p class="form-row form-row-last">
        <label for="reg_billing_city"><?php _e( 'City', 'kiboo' ); ?> <span class="required">*</span></label>
        <select name="billing_city" id="reg_billing_city">
        <?php
        foreach($cities as $city){
            if($_POST['billing_city'] == $city->name || (!isset($_POST['billing_city']) && kibooGetOption('default_city') == $city->name)){
                echo '<option value="'.$city->name.'" selected>'.$city->name.'</option>';
            }else{
                echo '<option value="'.$city->name.'">'.$city->name.'</option>';
            }
        }
        ?>
        </select>
    </p>

    <div class="clear"></div>
    <?php
}
add_action( 'woocommerce_register_form', 'kibooRegistrationExtraFields' );




/**
 * VALIDATION
 */
function kibooRegistrationExtraFieldsValidation( $username, $email, $validation_errors ) {
    if ( isset( $_POST['billing_doc_type'] ) && empty( $_POST['billing_doc_type'] ) ) {
        $validation_errors->add( 'billing_doc_type_error', __( 'Doc Type is required!.', 'kiboo' ) );
    }

    if ( isset( $_POST['billing_doc_number'] ) && empty( $_POST['billing_doc_number'] ) ) {
        $validation_errors->add( 'billing_doc_number_error', __( 'Doc Number is required!', 'kiboo' ) );
    }

    /*if ( isset( $_POST['billing_birth_date'] ) && empty( $_POST['billing_birth_date'] ) ) {
        $validation_errors->add( 'billing_birth_date_error', __( 'Birth Date is required!', 'kiboo' ) );
    }*/

    if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
        $validation_errors->add( 'billing_phone_error', __( 'Phone is required!', 'kiboo' ) );
    }

    if ( isset( $_POST['billing_vat_type'] ) && empty( $_POST['billing_vat_type'] ) ) {
        $validation_errors->add( 'billing_vat_type_error', __( 'VAT Type is required!', 'kiboo' ) );
    }

    if ( isset( $_POST['billing_address_1'] ) && empty( $_POST['billing_address_1'] ) ) {
        $validation_errors->add( 'billing_address_1_error', __( 'Address is required!', 'kiboo' ) );
    }

    if ( isset( $_POST['billing_address_number'] ) && empty( $_POST['billing_address_number'] ) ) {
        $validation_errors->add( 'billing_address_number_error', __( 'Number is required!', 'kiboo' ) );
    }

    /*if ( isset( $_POST['billing_address_apt'] ) && empty( $_POST['billing_address_apt'] ) ) {
        $validation_errors->add( 'billing_address_apt_error', __( 'Apartment is required!', 'kiboo' ) );
    }*/

    if ( isset( $_POST['billing_address_2'] ) && empty( $_POST['billing_address_2'] ) ) {
        //$validation_errors->add( 'billing_address_2_error', __( 'Neighborhood is required!', 'kiboo' ) );
    }

    if ( isset( $_POST['billing_postcode'] ) && empty( $_POST['billing_postcode'] ) ) {
        $validation_errors->add( 'billing_postcode_error', __( 'ZIP Code is required!', 'kiboo' ) );
    }

    if ( isset( $_POST['billing_state'] ) && empty( $_POST['billing_state'] ) ) {
        $validation_errors->add( 'billing_state_error', __( 'State is required!', 'kiboo' ) );
    }

    if ( isset( $_POST['billing_city'] ) && empty( $_POST['billing_city'] ) ) {
        $validation_errors->add( 'billing_city_error', __( 'City is required!', 'kiboo' ) );
    }

       return $validation_errors;
}
add_action( 'woocommerce_register_post', 'kibooRegistrationExtraFieldsValidation', 10, 3 );




/**
 * SAVE DATA
 */
function kibooRegistrationExtraFieldsSave( $customer_id ) {

    //die('<pre>'.print_r($_POST, true).'</pre>');

    // FIRST NAME - LAST NAME
    if ( isset( $_POST['billing_first_name'] ) || isset( $_POST['billing_last_name'] ) ) {
        $name = trim($_POST['billing_first_name'] . ' ' . $_POST['billing_last_name']);

        update_user_meta( $customer_id, 'nickname', sanitize_text_field( $name ) );
        update_user_meta( $customer_id, 'display_name', sanitize_text_field( $name ) );

        update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

        update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

        update_user_meta( $customer_id, 'shipping_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        update_user_meta( $customer_id, 'shipping_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
    }

    if ( isset( $_POST['billing_doc_type'] ) ) {
        update_user_meta( $customer_id, 'billing_doc_type', sanitize_text_field( $_POST['billing_doc_type'] ) );
    }

    if ( isset( $_POST['billing_doc_number'] ) ) {
        update_user_meta( $customer_id, 'billing_doc_number', sanitize_text_field( $_POST['billing_doc_number'] ) );
    }

    if ( isset( $_POST['billing_birth_date'] ) ) {
        update_user_meta( $customer_id, 'billing_birth_date', sanitize_text_field( $_POST['billing_birth_date'] ) );
    }

    if ( isset( $_POST['billing_phone'] ) ) {
        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }

    if ( isset( $_POST['billing_vat_type'] ) ) {
        update_user_meta( $customer_id, 'billing_vat_type', sanitize_text_field( $_POST['billing_vat_type'] ) );
    }

    if ( isset( $_POST['billing_address_1'] ) ) {
        update_user_meta( $customer_id, 'billing_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );
        update_user_meta( $customer_id, 'shipping_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );
    }

    if ( isset( $_POST['billing_address_number'] ) ) {
        update_user_meta( $customer_id, 'billing_address_number', sanitize_text_field( $_POST['billing_address_number'] ) );
    }

    if ( isset( $_POST['billing_address_apt'] ) ) {
        update_user_meta( $customer_id, 'billing_address_apt', sanitize_text_field( $_POST['billing_address_apt'] ) );
    }

    if ( isset( $_POST['billing_address_2'] ) ) {
        update_user_meta( $customer_id, 'billing_address_2', sanitize_text_field( $_POST['billing_address_2'] ) );
        update_user_meta( $customer_id, 'shipping_address_2', sanitize_text_field( $_POST['billing_address_2'] ) );
    }

    if ( isset( $_POST['billing_postcode'] ) ) {
        update_user_meta( $customer_id, 'billing_postcode', sanitize_text_field( $_POST['billing_postcode'] ) );
        update_user_meta( $customer_id, 'shipping_postcode', sanitize_text_field( $_POST['billing_postcode'] ) );
    }

    if ( isset( $_POST['billing_state'] ) ) {
        update_user_meta( $customer_id, 'billing_state', sanitize_text_field( $_POST['billing_state'] ) );
        update_user_meta( $customer_id, 'shipping_state', sanitize_text_field( $_POST['billing_state'] ) );
    }

    if ( isset( $_POST['billing_city'] ) ) {
        update_user_meta( $customer_id, 'billing_city', sanitize_text_field( $_POST['billing_city'] ) );
        update_user_meta( $customer_id, 'shipping_city', sanitize_text_field( $_POST['billing_city'] ) );
    }

}
add_action( 'woocommerce_created_customer', 'kibooRegistrationExtraFieldsSave', 1, 1 );

/**
 * Set Price Type if not defined in the customer data
 */
function kibooRegistrationSetPriceType( $customer_id ) {
    $price_type = get_user_meta($customer_id, 'kiboo_price_type', true);
    if($price_type == ''){
        update_user_meta( $customer_id, 'kiboo_price_type', kibooGetOption('default_price'));
    }
}

add_action( 'woocommerce_created_customer', 'kibooRegistrationSetPriceType', 10, 1 );
?>