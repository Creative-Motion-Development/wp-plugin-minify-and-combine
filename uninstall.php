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

	$plugin = new WMAC_PluginMain();
	$plugin->setup();

	WMAC_PluginCache::clearAll();

	// remove plugin options
	global $wpdb;

	$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'wbcr_mac_%';");