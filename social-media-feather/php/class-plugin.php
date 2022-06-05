<?php
/**
 * Bootstraps the Social Media Feather plugin.
 *
 * @package SocialMediaFeather
 */

namespace SocialMediaFeather;

/**
 * Main plugin bootstrap file.
 */
class Plugin extends Plugin_Base {
	/**
	 * Plugin assets prefix.
	 *
	 * @var string Lowercased dashed prefix.
	 */
	public $assets_prefix;

	/**
	 * Plugin meta prefix.
	 *
	 * @var string Lowercased underscored prefix.
	 */
	public $meta_prefix;

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		parent::__construct();

		// Include SocialMediaFeather class intentionally.
		include_once trailingslashit( __DIR__ ) . 'class-socialmediafeather.php';

		// Initiate classes.
		$classes = array(
			new SocialMediaFeather( $this ),
		);

		// Add classes doc hooks.
		foreach ( $classes as $instance ) {
			$this->add_doc_hooks( $instance );
		}

		// Define some prefixes to use throughout the plugin.
		$this->assets_prefix = strtolower( preg_replace( '/\B([A-Z])/', '-$1', __NAMESPACE__ ) );
		$this->meta_prefix   = strtolower( preg_replace( '/\B([A-Z])/', '_$1', __NAMESPACE__ ) );
	}
}
