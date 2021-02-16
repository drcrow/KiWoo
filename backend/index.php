<?php
/**
 * Here are all the customizations to the backend
 * Mostly adding new columns to tables
 * And metaboxes
 */

require('users_list.php');
require('categories_list.php');
require('products_list.php');
require('products_metabox.php');

function kibooArrayInsertAfterKey($array, $afterKey, $key, $value){
    $res = array();

    foreach($array as $arrayKey=>$arrayValue){
        $res[$arrayKey] = $arrayValue;
        if($arrayKey == $afterKey){
            $res[$key] = $value;
        }
    }

    return $res;
}
?>