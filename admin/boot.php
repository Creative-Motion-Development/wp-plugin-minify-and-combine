<?php
	/**
	 * Admin boot
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright Webcraftic 25.05.2017
	 * @version 1.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	function wbcr_mac_group_options($options)
	{
		/**
		 * Js optimize
		 */
		$options[] = array(
			'name' => 'js_optimize',
			'title' => __('Optimize JavaScript Code?', 'minify-and-combine'),
			'tags' => array('optimize_code', 'hide_my_wp')
		);
		$options[] = array(
			'name' => 'js_aggregate',
			'title' => __('Aggregate JS-files?', 'minify-and-combine'),
			'tags' => array('optimize_code', 'hide_my_wp')
		);
		$options[] = array(
			'name' => 'js_include_inline',
			'title' => __('Also aggregate inline JS?', 'minify-and-combine'),
			'tags' => array()
		);
		$options[] = array(
			'name' => 'js_forcehead',
			'title' => __('Force JavaScript in &lt;head&gt;?', 'minify-and-combine'),
			'tags' => array()
		);
		$options[] = array(
			'name' => 'js_exclude',
			'title' => __('Exclude scripts from Мinify And Combine:', 'minify-and-combine'),
			'tags' => array()
		);
		$options[] = array(
			'name' => 'js_trycatch',
			'title' => __('Add try-catch wrapping?', 'minify-and-combine'),
			'tags' => array()
		);
		/**
		 * CSS optimize
		 */
		$options[] = array(
			'name' => 'css_optimize',
			'title' => __('Optimize CSS Code?', 'minify-and-combine'),
			'tags' => array('optimize_code', 'hide_my_wp')
		);

		$options[] = array(
			'name' => 'css_aggregate',
			'title' => __('Aggregate CSS-files?', 'minify-and-combine'),
			'tags' => array()
		);

		$options[] = array(
			'name' => 'css_include_inline',
			'title' => __('Also aggregate inline CSS?', 'minify-and-combine'),
			'tags' => array()
		);

		$options[] = array(
			'name' => 'css_datauris',
			'title' => __('Generate data: URIs for images?', 'minify-and-combine'),
			'tags' => array()
		);

		$options[] = array(
			'name' => 'css_defer',
			'title' => __('Inline and Defer CSS?', 'minify-and-combine'),
			'tags' => array()
		);

		$options[] = array(
			'name' => 'css_inline',
			'title' => __('Inline all CSS?', 'minify-and-combine'),
			'tags' => array()
		);

		$options[] = array(
			'name' => 'css_exclude',
			'title' => __('Exclude CSS from Мinify And Combine', 'minify-and-combine'),
			'tags' => array()
		);

		return $options;
	}

	add_filter("wbcr_clearfy_group_options", 'wbcr_mac_group_options');

	/**
	 * Adds a new mode to the Quick Setup page
	 *
	 * @param array $mods
	 * @return mixed
	 */
	function wbcr_mac_allow_quick_mods($mods)
	{
		if( !defined('WHTM_PLUGIN_ACTIVE') ) {
			$title = __('One click optimize scripts (js, css)', 'minify-and-combine');
		} else {
			$title = __('One click optimize html code and scripts', 'minify-and-combine');
		}

		$mod['optimize_code'] = array(
			'title' => $title,
			'icon' => 'dashicons-performance'
		);

		return $mod + $mods;
	}

	add_filter("wbcr_clearfy_allow_quick_mods", 'wbcr_mac_allow_quick_mods');
