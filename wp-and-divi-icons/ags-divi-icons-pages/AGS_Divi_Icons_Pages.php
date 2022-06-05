<?php

/**
 * Class AGS_Divi_Icons_Pages .
 *
 * Determine which pages are loaded for FontAwesome
 */

class AGS_Divi_Icons_Pages {
	
	/**
	 * @var array $admin_post_pages_to_load_font_awesome
	 */
	private $allowedPages = [
		'post.php',
		'post-new.php',
		'customize.php'
	];
	/**
	 * @var array $divi_builder_pages
	 */
	private $alloweDiviBuilderPages = [
		'et_theme_builder',
		'et_divi_options',
		'ds-icon-expansion'
	];
	
	/**
	 * Allowed pages (backend) to load FontAwesome
	 *
	 * @return bool
	 */
	public function isAllowedPages() {
		global $pagenow;
		
		return in_array( $pagenow, $this->allowedPages );
	}
	
	/**
	 * Allowed Divi Builder pages (backend) to load FontAwesome
	 *
	 * @return bool
	 */
	public function IsDiviBuilderAllowedPages() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check
		if ( isset( $_GET['page'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check
			return in_array( $_GET['page'], $this->alloweDiviBuilderPages );
		}
		
		return false;
	}
	
	/**
	 * Allowed Divi post type (et_pb_layout) to load FontAwesome
	 *
	 * @return bool
	 */
	public function isDiviLayout() {
		$post_type = get_query_var( 'post_type' );
		
		if ( 'et_pb_layout' === $post_type ) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Front pages to load FontAwesome
	 *
	 * @return bool
	 */
	/*
	public function isFrontendPostsOrPages() {
		if ( 'posts' === get_option( 'show_on_front' ) || 'page' === get_option( 'show_on_front' ) ) {
			return true;
		}
		
		return false;
	}
	*/
}