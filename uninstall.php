<?php

	// if uninstall.php is not called by WordPress, die
	if( !defined('WP_UNINSTALL_PLUGIN') ) {
		die;
	}

	if( !defined('WMAC_PLUGIN_DIR') ) {
		define('WMAC_PLUGIN_DIR', dirname(__FILE__));
	}

	require_once(WMAC_PLUGIN_DIR . '/includes/classes/class.mac-cache.php');
	require_once(WMAC_PLUGIN_DIR . '/includes/classes/class.mac-main.php');

	/**
	 * Удаление кеша и опций
	 */
	function uninstall()
	{
		// remove plugin options
		global $wpdb;

		$plugin = new WMAC_PluginMain();
		$plugin->setup();

		WMAC_PluginCache::clearAll();

		$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'wbcr_mac_%';");
	}

	if( is_multisite() ) {
		global $wpdb, $wp_version;

		$blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		if( !empty($blogs) ) {
			foreach($blogs as $id) {

				switch_to_blog($id);

				uninstall();

				restore_current_blog();
			}
		}
	} else {
		uninstall();
	}
