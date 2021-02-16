<?php
/**
 * This runs on activation to create all the DB tables
 */



function kibooInstaller(){
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();

    // TAXES
    // Taxes is the only one added to a woo table, the other info (cities, states, etc) are added to new kiboo tables
    // Taxes live in two woo tables

    // DATA
    require_once('data_taxes.php');

    $table_name = $wpdb->prefix . "wc_tax_rate_classes";

    foreach($taxes_sources as $tax){
        $class = $tax['class'];
        $row = $wpdb->get_row( "SELECT * FROM {$table_name} WHERE slug = '{$class}'" );
        if($row == false){
            $wpdb->insert($table_name, array(
                'name'      => $class,
                'slug'      => $class
            ));
        }
    }

    $table_name = $wpdb->prefix . "woocommerce_tax_rates";

    foreach($taxes_sources as $tax){
        $class  = $tax['class'];
        $rate   = $tax['rate'];
        $name   = $tax['name'];
        $row = $wpdb->get_row( "SELECT * FROM {$table_name} WHERE tax_rate_class = '{$class}'" );
        if($row == false){
            $wpdb->insert($table_name, array(
                'tax_rate'          => $rate,
                'tax_rate_name'     => $name,
                'tax_rate_priority' => 1,
                'tax_rate_compound' => 0,
                'tax_rate_shipping' => 0,
                'tax_rate_order'    => 0,
                'tax_rate_class'    => $class

            ));
        }
    }

    // LOG
    $table_name = $wpdb->prefix . "kiboo_log";

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
                `ID` int(11) NOT NULL AUTO_INCREMENT,
                `date_add` datetime NOT NULL,
                `details` text NOT NULL,
                `body` text NOT NULL,
                `response` text NOT NULL,
                PRIMARY KEY  (ID)
        ) $charset_collate;";

        dbDelta($sql);
    }

    // CURRENT ACCOUNTS
    $table_name = $wpdb->prefix . "kiboo_current_accounts";

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
                `ID` int(11) NOT NULL AUTO_INCREMENT,
                `kiboo_customer_id` varchar(50) NOT NULL,
                `ca_limit` float NOT NULL,
                `ca_available` float NOT NULL,
                `ca_last_payment_date` date NOT NULL,
                `ca_balance` float NOT NULL,
                PRIMARY KEY  (ID)
        ) $charset_collate;";

        dbDelta($sql);
    }

    // CURRENT ACCOUNTS MOVEMENTS
    $table_name = $wpdb->prefix . "kiboo_current_accounts_movements";

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
                `ID` int(11) NOT NULL AUTO_INCREMENT,
                `kiboo_id` varchar(50) NOT NULL,
                `kiboo_customer_id` varchar(50) NOT NULL,
                `cam_code` varchar(100) NOT NULL,
                `cam_operation_date` date NOT NULL,
                `cam_debit` float NOT NULL,
                `cam_credit` float NOT NULL,
                `cam_balance` float NOT NULL,
                PRIMARY KEY  (ID)
        ) $charset_collate;";

        dbDelta($sql);
    }

    // VAT TYPES
    $table_name = $wpdb->prefix . "kiboo_vat_types";

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
                `ID` int(11) NOT NULL,
                `name` varchar(100) DEFAULT NULL,
                PRIMARY KEY  (ID)
        ) $charset_collate;";

        dbDelta($sql);

        // DATA
        require_once('data_vat_types.php');
        foreach($vat_types_source as $vat_type){
            $wpdb->insert($table_name, array(
                'ID'        => $vat_type['ID'],
                'name'      => $vat_type['name']
            ));
        }
    }

    // DOC TYPES
    $table_name = $wpdb->prefix . "kiboo_doc_types";

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
                `ID` int(11) NOT NULL,
                `name` varchar(100) DEFAULT NULL,
                PRIMARY KEY  (ID)
        ) $charset_collate;";

        dbDelta($sql);

        // DATA
        require_once('data_doc_types.php');
        foreach($doc_types_source as $doc_type){
            $wpdb->insert($table_name, array(
                'ID'        => $doc_type['ID'],
                'name'      => $doc_type['name']
            ));
        }
    }

    // STATES
    $table_name = $wpdb->prefix . "kiboo_states";

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
                `ID` int(11) NOT NULL,
                `name` varchar(100) DEFAULT NULL,
                PRIMARY KEY  (ID)
        ) $charset_collate;";

        dbDelta($sql);

        // DATA
        require_once('data_states.php');
        foreach($states_source as $city){
            $wpdb->insert($table_name, array(
                'ID'        => $city['ID'],
                'name'      => $city['name']
            ));
        }
    }

    // CITIES
    $table_name = $wpdb->prefix . "kiboo_cities";

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
                `ID` int(11) NOT NULL,
                `name` varchar(100) DEFAULT NULL,
                `state_id` int(11) NOT NULL,
                PRIMARY KEY  (ID)
        ) $charset_collate;";

        dbDelta($sql);

        // DATA
        require_once('data_cities.php');
        foreach($cities_source as $city){
            $wpdb->insert($table_name, array(
                'ID'        => $city['ID'],
                'name'      => $city['name'],
                'state_id'  => $city['state_id']
            ));
        }
    }
}

?>