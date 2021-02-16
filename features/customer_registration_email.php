<?php
/**
 * Disable Customer Registration Email
 * (when all the customers are synced the site sends an email to each one, this is an error)
 */

add_filter('wp_mail','disabling_emails', 10, 1);
function disabling_emails( $args ){
    if($_GET['disable_email'] == true){// this is set in api_customer.php before customer creation
        $args['to'] = '';
    }
    return $args;
}
?>