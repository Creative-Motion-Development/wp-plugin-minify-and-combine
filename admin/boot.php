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
		$options[] = array(
			'name' => 'disable_comments',
			'title' => __('Disable comments on the entire site', 'minify-and-combine'),
			'tags' => array('disable_all_comments'),
			'values' => array('disable_all_comments' => 'disable_comments')
		);
		$options[] = array(
			'name' => 'disable_comments_for_post_types',
			'title' => __('Select post types', 'minify-and-combine'),
			'tags' => array()
		);
		$options[] = array(
			'name' => 'comment_text_convert_links_pseudo',
			'title' => __('Replace external links in comments on the JavaScript code', 'minify-and-combine'),
			'tags' => array('recommended', 'seo_optimize')
		);
		$options[] = array(
			'name' => 'pseudo_comment_author_link',
			'title' => __('Replace external links from comment authors on the JavaScript code', 'minify-and-combine'),
			'tags' => array('recommended', 'seo_optimize')
		);
		$options[] = array(
			'name' => 'remove_x_pingback',
			'title' => __('Disable X-Pingback', 'minify-and-combine'),
			'tags' => array('recommended', 'defence', 'disable_all_comments')
		);
		$options[] = array(
			'name' => 'remove_url_from_comment_form',
			'title' => __('Remove field "site" in comment form', 'minify-and-combine'),
			'tags' => array()
		);

		return $options;
	}

	add_filter("wbcr_clearfy_group_options", 'wbcr_mac_group_options');

	function wbcr_mac_allow_quick_mods($mods)
	{
		$mod['minify_and_combine'] = array(
			'title' => __('One click optimize JS, CSS', 'minify-and-combine'),
			'icon' => 'dashicons-performance'
		);
		
		return $mod + $mods;
	}

	add_filter("wbcr_clearfy_allow_quick_mods", 'wbcr_mac_allow_quick_mods');
