<?php
/**
 * Share Widget.
 *
 * @package SocialMediaFeather
 */

/**
 * Social Share Widget Class.
 */
class SynvedSocialShareWidget extends SynvedSocialWidget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( 'synved_social_share', __( 'Social Media Feather: Sharing', 'social-media-feather' ) );
	}

	/**
	 * Override defaults.
	 *
	 * @return string[]
	 */
	public function get_defaults() {
		$defaults = parent::get_defaults();

		return array_merge( $defaults, array( 'title' => __( 'Sharing', 'social-media-feather' ) ) );
	}

	/**
	 * Render social markup.
	 *
	 * NOTE: Escaped via wp_kses_post().
	 *
	 * @param array $params Parmeter array.
	 *
	 * @return void
	 */
	public function render_social_markup( $params = null ) {
		// Escaped using wp_kses_post.
		echo synved_social_share_markup( null, null, $params );  // phpcs:ignore
	}
}
