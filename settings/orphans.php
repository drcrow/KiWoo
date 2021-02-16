<?php
/**
 * This section list the orphans orders and customers.
 * Orphans are elements that failed to synch to kiboo and dont have a kiboo_id
 */


if($_GET['resynch_type'] == 'customer'){
    kibooHookNewCustomer( $_GET['resynch_id'] );
    kibooMessageAlert(sprintf( __( 'Customer %s was synchronized. Check the LOG for results.', 'kiboo' ), $_GET['resynch_id'] ));
}

if($_GET['resynch_type'] == 'order'){
    kibooHookNewOrder( $_GET['resynch_id'] );
    kibooMessageAlert(sprintf( __( 'Order %s was synchronized. Check the LOG for results.', 'kiboo' ), $_GET['resynch_id'] ));
}


echo '<h4>'.__( 'Orders', 'kiboo' ).'</h4>';

global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'shop_order' ORDER BY ID DESC", OBJECT );

//echo '<pre>';
//print_r($results);
//echo '</pre>';

echo '<table class="wp-list-table widefat fixed striped posts">';
echo '<thead><tr>
<th width="5%">'.__( 'ID', 'kiboo' ).'</th>
<th width="15%">'.__( 'Date', 'kiboo' ).'</th>
<th width="35%">'.__( 'Order', 'kiboo' ).'</th>
<th width="35%">'.__( 'Customer', 'kiboo' ).'</th>
<th width="10%">'.__( 'Options', 'kiboo' ).'</th>
</tr></thead>';

foreach($results as $row){
    $kiboo_id = trim(get_post_meta( $row->ID , 'kiboo_id' , true ));

    if($kiboo_id == ''){
        echo '<tr>
        <td>'.$row->ID.'</td>
        <td>'.$row->post_date.'</td>
        <td>'.$row->post_title.'</td>
        <td>'.get_post_meta( $row->ID , '_customer_user' , true ).'</td>
        <td><a href="admin.php?page='.$_GET['page'].'&view='.$_GET['view'].'&resynch_type=order&resynch_id='.$row->ID.'">'.__( 'Resynch', 'kiboo' ).'</a></td>
        </tr>';
    }
}


echo '</table>';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo '<h4>'.__( 'Customers', 'kiboo' ).'</h4>';

global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}users ORDER BY ID DESC", OBJECT );

//echo '<pre>';
//print_r($results);
//echo '</pre>';

echo '<table class="wp-list-table widefat fixed striped posts">';
echo '<thead><tr>
<th width="5%">'.__( 'ID', 'kiboo' ).'</th>
<th width="15%">'.__( 'Date', 'kiboo' ).'</th>
<th width="35%">'.__( 'Name', 'kiboo' ).'</th>
<th width="35%">'.__( 'Email', 'kiboo' ).'</th>
<th width="10%">'.__( 'Options', 'kiboo' ).'</th>
</tr></thead>';

foreach($results as $row){
    $kiboo_id = trim(get_user_meta( $row->ID , 'kiboo_id' , true ));

    $user = get_userdata( $row->ID );

    if($kiboo_id == '' && in_array('customer', $user->roles)){
        echo '<tr>
        <td>'.$row->ID.'</td>
        <td>'.$row->user_registered.'</td>
        <td>'.$row->display_name.'</td>
        <td>'.$row->user_email.'</td>
        <td><a href="admin.php?page='.$_GET['page'].'&view='.$_GET['view'].'&resynch_type=customer&resynch_id='.$row->ID.'">'.__( 'Resynch', 'kiboo' ).'</a></td>
        </tr>';
    }
}


echo '</table>';