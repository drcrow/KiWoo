<?php
/**
 * Basic Authentication returns a string starting with "Basic" an "user:pass" in base 64
 */
function kibooAPIAuth(){
    //die(print_r($_SERVER['HTTP_AUTHORIZATION'], true));
    $auth = $_SERVER['HTTP_AUTHORIZATION'];
    $auth = str_replace('Basic', '', $auth);
    $auth = base64_decode($auth);
    $auth = explode(':', $auth);

    $user = kibooGetOption('woo_api_user');
    $pass = kibooGetOption('woo_api_pass');

    if($auth[0] == $user && $auth[1] == $pass){
        return true;
    }else{
        return false;
    }
}

/**
 * Get Term ID by Kiboo ID
 */
function kibooGetTermIDByKibooID($tax, $kiboo_id){
    $ID = 0;
    $terms = get_terms( array('taxonomy' => $tax, 'hide_empty' => false) );

    foreach($terms as $term){
        $term_kiboo_id = get_term_meta( $term->term_id, 'kiboo_id', true );
        if($kiboo_id != '' && $kiboo_id != 0 && $kiboo_id != null && $kiboo_id == $term_kiboo_id){
            $ID = $term->term_id;
            break;
        }
    }

    return (int)$ID;
}

/**
 * Get Term Slug by Kiboo ID
 */
function kibooGetTermSlugByKibooID($tax, $kiboo_id){
    $slug = false;
    $terms = get_terms( array('taxonomy' => $tax, 'hide_empty' => false) );

    foreach($terms as $term){
        $term_kiboo_id = get_term_meta( $term->term_id, 'kiboo_id', true );

        if($kiboo_id == $term_kiboo_id){
            $slug = $term->slug;
            //break;
        }
    }

    return $slug;
}

/**
 * Update Category Metas
 */
function kibooCategoryMetaUpdate($categoryid, $cat){
    // KIBOO
    update_term_meta( $categoryid, "kiboo_id", $cat['kiboo_id'] );
}

/**
 * Is API Enabled
 */
function kibooIsAPIEnabled($endpoint, $bool = false){
    $option = kibooGetOption('enable_'.$endpoint.'_synch');
    if($option == 0){
        if($bool){
            return false;
        }
        $error = new WP_Error( 'endpoint-disabled', __( "This endpoint is disabled", "kiboo" ), array( 'status' => 404 ) );
        return $error;
    }
    return true;
}
?>