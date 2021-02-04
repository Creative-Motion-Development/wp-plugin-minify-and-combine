<?php
/**
 * Base class
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 2018 Webraftic Ltd
 * @version       1.0
 */

if( !defined('ABSPATH') ) {
	exit;
}

/**
 * Class WMAC_PluginBase
 */
class WMAC_ScriptsToFooter {

	/**
	 * Constructor
	 *
	 * @return WMAC_ScriptsToFooter
	 * @since  1.1.1
	 */
	public function __construct()
	{

		if( !is_admin() ) {
			add_action('wp_enqueue_scripts', array($this, 'move_scripts'));
			add_action('wp_head', array($this, 'preload_scripts'), PHP_INT_MAX - 1);

			// Add select scripts into the header
			add_action('wp_head', array($this, 'print_head_scripts'), 999);
		}
	}


	/**
	 * The holy grail: print select scripts in the header!
	 *
	 * @since 0.6.0
	 */
	function print_head_scripts()
	{
		$excluded_handles = [];
		$excluded_scripts = explode(',', str_replace([
			" ",
			"\r",
			"\n"
		], "", WMAC_Plugin::app()->getPopulateOption('excluded_move_to_footer_scripts')));

		$scripts = wp_scripts();

		if( !empty($scripts) && !empty($excluded_scripts) ) {
			foreach($scripts->queue as $handle) {
				if( $script = $scripts->query($handle, 'registered') ) {
					foreach($excluded_scripts as $src) {
						if( false !== strpos($script->src, (string)$src) ) {
							$excluded_handles[] = $handle;
							break;
						}
					}
				}
			}
		}

		if( WMAC_Plugin::app()->getPopulateOption('dont_move_jquery_to_footer') ) {
			$excluded_handles[] = 'jquery';
		}

		$excluded_handles = apply_filters('wmac/excluded_move_to_footer_scripts', $excluded_handles);

		foreach((array)$excluded_handles as $handle) {
			if( !is_string($handle) ) {
				continue;
			}

			// If the script is enqueued for the page, print it
			if( wp_script_is($handle) ) {
				wp_print_scripts($handle);
			}
		}
	}

	/**
	 * Move scripts from head to bottom/footer.
	 *
	 * @return void
	 * @since  1.0.1
	 */
	public function move_scripts()
	{
		// clean head
		remove_action('wp_head', 'wp_print_scripts');
		remove_action('wp_head', 'wp_print_head_scripts', 9);
		remove_action('wp_head', 'wp_enqueue_scripts', 1);

		// move script to footer
		add_action('wp_footer', 'wp_print_scripts', 5);
		add_action('wp_footer', 'wp_print_head_scripts', 5);
		add_action('wp_footer', 'wp_enqueue_scripts', 5);
	}


	/**
	 * Preloads script in the head
	 *
	 * @return void
	 * @since  1.0.3
	 */
	public function preload_scripts()
	{
		$wp_scripts = wp_scripts();

		foreach($wp_scripts->queue as $handle) {
			if( !empty($wp_scripts->registered[$handle]->src) ) {

				if( isset($wp_scripts->registered[$handle]->extra['conditional']) ) {
					echo '<!--[if ' . $wp_scripts->registered[$handle]->extra['conditional'] . '>' . "\r\n";
				}

				echo '<link rel="preload" href="' . $wp_scripts->registered[$handle]->src . '" as="script">' . "\r\n";

				if( isset($wp_scripts->registered[$handle]->extra['conditional']) ) {
					echo '<![endif]-->' . "\r\n";
				}
			}
		}
	}
}

new WMAC_ScriptsToFooter();