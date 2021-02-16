<?php
global $wpdb;
//$states = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_states", OBJECT );
$db_cities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kiboo_cities ORDER BY name", OBJECT );

global $cities;

$cities['AR'] = array();

foreach($db_cities AS $city){
  $cities['AR'][$city->state_id][] = $city->name;
}
?>