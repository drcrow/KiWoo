<?php


    if  (kibooGetOption('payments') == 'allclients') {
        add_filter( 'woocommerce_payment_gateways', 'add_custom_gateway_class' );
        add_action('plugins_loaded', 'init_custom_gateway_class');
    }

    if  (kibooGetOption('payments') == 'wholesaler') {
        require( ABSPATH . WPINC . '/pluggable.php' );
        require( ABSPATH . WPINC . '/pluggable-deprecated.php' );
        $current_user = wp_get_current_user();
        $wholesaler = get_user_meta($current_user->ID, 'wholesaler', true);  
        //die($wholesaler);
        if($wholesaler == 1){
            add_filter( 'woocommerce_payment_gateways', 'add_custom_gateway_class' );
            add_action('plugins_loaded', 'init_custom_gateway_class');
        }
    }

    function init_custom_gateway_class(){
    class WC_Gateway_Custom extends WC_Payment_Gateway {
        public $domain;
        public function __construct() {
            $this->domain = 'custom_payment';
            $this->id                 = 'ctacte';
            $this->icon               = apply_filters('woocommerce_custom_gateway_icon', '');
            $this->has_fields         = false;
            $this->method_title       = __( 'Custom', $this->domain );
            $this->method_description = __( 'Allows payments with custom gateway.', $this->domain );
            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();
            // Define user set variables
            $this->title        = $this->get_option( 'title' );
            $this->description  = $this->get_option( 'description' );
            $this->instructions = $this->get_option( 'instructions', $this->description );
            $this->order_status = $this->get_option( 'order_status', 'completed' );
            // Actions
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            // Customer Emails
            add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
        }
        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title'   => __( 'Enable/Disable', $this->domain ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Enable Custom Payment', $this->domain ),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title'       => __( 'Title', $this->domain ),
                    'type'        => 'text',
                    'description' => __( 'This controls the title which the user sees during checkout.', $this->domain ),
                    'default'     => __( 'Cuenta Corriente', $this->domain ),
                    'desc_tip'    => true,
                ),
                'order_status' => array(
                    'title'       => __( 'Order Status', $this->domain ),
                    'type'        => 'select',
                    'class'       => 'wc-enhanced-select',
                    'description' => __( 'Choose whether status you wish after checkout.', $this->domain ),
                    'default'     => 'wc-completed',
                    'desc_tip'    => true,
                    'options'     => wc_get_order_statuses()
                ),
                'description' => array(
                    'title'       => __( 'Description', $this->domain ),
                    'type'        => 'textarea',
                    'description' => __( 'Payment method description that the customer will see on your checkout.', $this->domain ),
                    'default'     => __('', $this->domain),
                    'desc_tip'    => true,
                ),
                'instructions' => array(
                    'title'       => __( 'Instructions', $this->domain ),
                    'type'        => 'textarea',
                    'description' => __( 'Instructions that will be added to the thank you page and emails.', $this->domain ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
            );
        }
        public function process_payment( $order_id ) {
            $order = wc_get_order( $order_id );
            $status = 'wc-' === substr( $this->order_status, 0, 3 ) ? substr( $this->order_status, 3 ) : $this->order_status;
            // Set order status
            $order->update_status( $status, __( 'Checkout with custom payment. ', $this->domain ) );
            // Reduce stock levels
            $order->reduce_order_stock();
            // Remove cart
            WC()->cart->empty_cart();
            // Return thankyou redirect
            return array(
                'result'    => 'success',
                'redirect'  => $this->get_return_url( $order )
            );
        }
    }
}
function add_custom_gateway_class( $methods ) {
    $methods[] = 'WC_Gateway_Custom'; 
    return $methods;
}
?>