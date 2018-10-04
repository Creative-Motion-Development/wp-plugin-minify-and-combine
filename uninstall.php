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
		$plugin = new WMAC_PluginMain();
		$plugin->setup();

		WMAC_PluginCache::clearAll();

		// remove plugin options
		global $wpdb;

		$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'wbcr_mac_%';");
	}

	if( is_multisite() ) {
		global $wp_version;
		if( version_compare($wp_version, '4.6', '>=') ) {
			$sites = get_sites($args);
		} else {
			$sites = wp_get_sites($args);
		}

		foreach($sites as $site) {
			if( version_compare($wp_version, '4.6', '>=') ) {
				$blog_id = $site->blog_id;
			} else {
				$blog_id = $site['blog_id'];
			}

			switch_to_blog($blog_id);

			uninstall();

			restore_current_blog();
		}
	} else {
		uninstall();
	}
