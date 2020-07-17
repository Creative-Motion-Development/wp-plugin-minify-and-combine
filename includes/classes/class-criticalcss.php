<?php
/**
 * Operations with Critical CSS
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 2020 Webraftic Ltd
 * @version       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WMAC_PluginCriticalCss
 */
class WMAC_PluginCriticalCss extends WMAC_PluginStyles {

	private $dontmove = [];
	private $css_critical_style = '';
	private $css_critical = [];

	/**
	 * Reads the page and collects style tags.
	 *
	 * @param array $options
	 *
	 * @return bool
	 */
	public function read( $options ) {

		// Set critical css code
		// value: string
		$this->css_critical_style = $options['css_critical_style'];
		$this->css_critical_style = apply_filters( 'wmac_filter_css_critical_style', $this->css_critical_style, $this->content );

		// Set critical css files
		// value: array
		$this->css_critical = $options['css_critical'];
		$this->css_critical = apply_filters( 'wmac_filter_css_critical', $this->css_critical, $this->content );

		if ( '' !== $this->css_critical ) {
			$this->css_critical = array_filter( array_map( 'trim', explode( ',', $this->css_critical ) ) );
		} else {
			$this->css_critical = [];
		}

		// Get <style> and <link>.
		if ( preg_match_all( '#(<style[^>]*>.*</style>)|(<link[^>]*stylesheet[^>]*>)#Usmi', $this->content, $matches ) ) {

			foreach ( $matches[0] as $tag ) {
				if ( $this->isCritical( $tag ) ) {
					$this->content = str_replace( $tag, '', $this->content );

					$replaceTag = [ '<title', 'before' ];
					$replaceTag = apply_filters( 'wmac_filter_css_replacetag', $replaceTag, $this->content );
					$this->injectInHtml( $tag, $replaceTag );
				}
			}

			return true;
		}

		// Really, no styles?
		return false;
	}

	/**
	 * @param $tag
	 *
	 * @return bool
	 */
	private function isCritical( $tag ) {
		if ( is_array( $this->css_critical ) && ! empty( $this->css_critical ) ) {
			foreach ( $this->css_critical as $match ) {
				$pattern = str_replace( '.', '\.', $match );
				$pattern = str_replace( '*', '.*', $pattern );
				$pattern = str_replace( '/', '\/', $pattern );
				if ( preg_match( "/{$pattern}/", $tag ) ) {
					return true;
				}
			}
		}

		return false;
	}

}
