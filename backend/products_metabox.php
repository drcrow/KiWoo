<?php
/**
 * Products Metabox for Kiboo info
 */

add_action( 'admin_menu', 'kibooMetaboxProducts' );

function kibooMetaboxProducts() {

	add_meta_box(
		'kiboo_metabox_products', // metabox ID
		'Kiboo Info', // title
		'kiboo_metabox_products_callback', // callback function
		'product', // post type or post types in array
		'side', // position (normal, side, advanced)
		'default' // priority (default, low, high, core)
	);

}

function kiboo_metabox_products_callback( $post ) {
    $prices = kibooGetProductPrices($post->ID);

    echo __( '<b>eCommerce ID: </b>', 'kiboo' );
    echo $post->ID;
    echo '<br>';
    echo __( '<b>Kiboo ID: </b>', 'kiboo' );
    echo get_post_meta($post->ID, 'kiboo_id', true);
    echo '<br>';
    echo __( '<b>Price A: </b>', 'kiboo' ).'$'.@$prices['NET_PRICE_A'];
    echo '<br>';
    echo __( '<b>Price B: </b>', 'kiboo' ).'$'.@$prices['NET_PRICE_B'];
    echo '<br>';
    echo __( '<b>Price C: </b>', 'kiboo' ).'$'.@$prices['NET_PRICE_C'];
    echo '<br>';
    echo __( '<b>Price D: </b>', 'kiboo' ).'$'.@$prices['NET_PRICE_D'];




}
?>