<?php
/**
 * Plugin Name: Kiboo WooCommerce
 * Plugin URI: https://clarikagroup.com/
 * Description: Kiboo and WooCommerce integration
 * Version: 0.1.4
 * Author: Agustin Fiori
 * Author URI: https://drcrow.github.io/resume/
 * Text Domain: kiboo
 * Domain Path: /languages
 */

$API_NAMESPACE = 'kiboo/v2';


/**
 * Add "Settings" link in plugins list
 */
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'kibooSettingsLink');
function kibooSettingsLink( $links ) {
	$links[] = '<a href="'.admin_url( 'admin.php?page=kiboo-settings' ).'">'.__('Settings').'</a>';
	return $links;
}


/**
 * Settings Menu & Pages
 */
require('settings/index.php');

/**
 * Features (Functionalities)
 */
require('features/index.php');

/**
 * Backend Modiffications
 */
require('backend/index.php');

/**
 * Plugin Installation
 */
require('install/index.php');
register_activation_hook(__file__, 'kibooInstaller');
?>