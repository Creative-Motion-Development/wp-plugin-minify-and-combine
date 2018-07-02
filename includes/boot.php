<?php
	/**
	 * Global boot
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 01.07.2018, Webcraftic
	 * @version 1.0
	 */

	function wbcr_mac_clear_cache()
	{
		if( isset($_GET['wbcr_mac_clear_cache']) && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'clear_all_cache') ) {
			WMAC_PluginCache::clearAll();

			wp_safe_redirect(add_query_arg(array('wbcr_mac_cache_cleared' => 1)));
		}
	}

	add_action('init', 'wbcr_mac_clear_cache');

	/**
	 * Добавляем кнопку сброса кеша в админ бар
	 *
	 * @param $wp_admin_bar
	 */
	function wbcr_mac_admin_bar_menu($wp_admin_bar)
	{
		if( !current_user_can('manage_options') ) {
			return;
		}
		$current_url = wp_nonce_url(add_query_arg(array('wbcr_mac_clear_cache' => 1)), 'clear_all_cache');

		$args = array(
			'id' => 'clear-cache-btn',
			'title' => __('Clear cache', 'minify-and-combibe') . ' (' . WMAC_PluginCache::getUsedCache()['percent'] . '% )',
			'href' => $current_url
		);
		$wp_admin_bar->add_menu($args);
	}

	/**
	 * Добавляем кнопку сброса кеша в Clearfy меню
	 */
	function wbcr_mac_clearfy_admin_bar_menu($menu_items)
	{
		$current_url = wp_nonce_url(add_query_arg(array('wbcr_mac_clear_cache' => 1)), 'clear_all_cache');

		$menu_items['mac-clear-cache'] = array(
			'title' => __('Clear cache', 'minify-and-combibe') . ' (' . WMAC_PluginCache::getUsedCache()['percent'] . '% )',
			'href' => $current_url
		);

		return $menu_items;
	}

	if( defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON') ) {
		add_action('wbcr_clearfy_admin_bar_menu_items', 'wbcr_mac_clearfy_admin_bar_menu');
	} else {
		add_action('admin_bar_menu', 'wbcr_mac_admin_bar_menu');
	}