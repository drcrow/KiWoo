<?php
global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_log ORDER BY ID DESC LIMIT 20", OBJECT );

echo '<table class="wp-list-table widefat fixed striped posts">';
echo '<thead><tr>
<th width="5%">'.__( 'ID', 'kiboo' ).'</th>
<th width="10%">'.__( 'Date', 'kiboo' ).'</th>
<th width="10%">'.__( 'Event', 'kiboo' ).'</th>
<th>'.__( 'Body', 'kiboo' ).'</th>
<th>'.__( 'Response', 'kiboo' ).'</th>
</tr></thead>';

foreach($results as $row){
    echo '<tr>
    <td>'.$row->ID.'</td>
    <td>'.$row->date_add.'</td>
    <td>'.$row->details.'</td>
    <td><textarea rows="5" style="width:100%">'.json_encode(json_decode($row->body), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).'</textarea></td>
    <td><textarea rows="5" style="width:100%">'.json_encode(json_decode($row->response), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).'</textarea></td>
    </tr>';
}


echo '</table>';

/*
echo '<br><hr><br>';
echo '<h2>Attributes:</h2>';

$attributes = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies ORDER BY attribute_name", OBJECT );

foreach($attributes as $attribute){
    echo '<h3>'.$attribute->attribute_label.'</h3>';
    $terms_taxs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'pa_{$attribute->attribute_name}'", OBJECT );
    foreach($terms_taxs as $term_tax){
        $term = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}terms WHERE term_id = {$term_tax->term_taxonomy_id}", OBJECT );
        //echo '<p>'.$term_tax->term_taxonomy_id.'</p>';
        echo '<p>['.get_term_meta( $term->term_id, 'kiboo_id', true ).'] '.$term->name.'</p>';
    }
}
*/


/*
$kiboo_id = 111522;
$ec_id = kibooProductIDByKibooID($kiboo_id);



echo '<pre>';
print_r(kibooGetVariations($ec_id));
echo '</pre>';
*/

?>