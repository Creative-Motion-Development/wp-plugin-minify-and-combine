<?php

// if uninstall.php is not called by WordPress, die
if( !defined('WP_UNINSTALL_PLUGIN') ) {
	die;
}

if ( ! defined( 'WMAC_PLUGIN_DIR' ) ) {
	define( 'WMAC_PLUGIN_DIR', dirname( __FILE__ ) );
}

require_once( WMAC_PLUGIN_DIR . '/includes/classes/class.mac-cache.php' );
require_once( WMAC_PLUGIN_DIR . '/includes/classes/class.mac-main.php' );

/**
* Удаление кеша и опций
*/
function uninstal() {
	$plugin = new WMAC_PluginMain();
	$plugin->setup();

	WMAC_PluginCache::clearAll();

	// remove plugin options
	global $wpdb;

	$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'wbcr_mac_%';");
}

if ( is_multisite() ) {
	$sites = get_sites( array(
		'archived' => 0,
		'mature'   => 0,
		'spam'     => 0,
		'deleted'  => 0,
	) );

	foreach ( $sites as $site ) {
		switch_to_blog( $site->blog_id );

		uninstal();

		restore_current_blog();
	}
} else {
	uninstal();
}
