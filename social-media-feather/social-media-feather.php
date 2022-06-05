<?php
/**
 * Plugin Name: Social Media Feather
 * Contributors: scottstorebloom, scottmweaver, socialmediafeather
 * Plugin URI: https://sharethis.com/platform/wordpress-social-media-feather/
 * Description: Super lightweight social media plugin to add nice and effective social media sharing and following buttons and icons anywhere on your site quickly and easily.
 * Author: socialmediafeather
 * Version: 2.1.1
 * Author URI: https://sharethis.com/platform/wordpress-social-media-feather/
 *
 * @package SocialMediaFeather
 */

define( 'SOCIAL_MEDIA_FEATHER_PLUGIN_DIRECTORY', plugin_dir_path( __FILE__ ) );
define( 'SOCIAL_MEDIA_FEATHER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

const SOCIAL_MEDIA_FEATHER_PLUGIN_MAIN_FILE = __FILE__;

const SYNVED_VERSION = '2.1.1';

if ( false === function_exists( 'synved_wp_social_load' ) ) {
	/**
	 * WP Social Load.
	 *
	 * @return void
	 */
	function synved_wp_social_load() {
		global $plugin;

		$path = __FILE__;

		if ( true === defined( 'SYNVED_SOCIAL_INCLUDE_PATH' ) ) {
			$path = SYNVED_SOCIAL_INCLUDE_PATH;
		} elseif ( isset( $plugin ) ) {
			/* This is mostly for symlink support */
			$real_plugin = realpath( $plugin );

			if ( strtolower( $real_plugin ) === strtolower( __FILE__ ) ) {
				$path = $plugin;
			}
		}

		$dir = dirname( $path ) . DIRECTORY_SEPARATOR;

		if ( false === function_exists( 'synved_plugout_module_import' ) ) {
			include $dir . 'synved-plugout' . DIRECTORY_SEPARATOR . 'synved-plugout.php';
		}

		// Load extra icons.
		include_once SOCIAL_MEDIA_FEATHER_PLUGIN_DIRECTORY . 'synved-social/addons/extra-icons/extra-icons.php';

		/* Register used modules */
		synved_plugout_module_register( 'synved-connect' );
		synved_plugout_module_path_add( 'synved-connect', 'core', $dir . 'synved-connect' );
		synved_plugout_module_register( 'synved-option' );
		synved_plugout_module_path_add( 'synved-option', 'core', $dir . 'synved-option' );
		synved_plugout_module_register( 'synved-social' );
		synved_plugout_module_path_add( 'synved-social', 'core', $dir . 'synved-social' );
		synved_plugout_module_path_add( 'synved-social', 'provider', __FILE__ );

		/* Import modules */
		synved_plugout_module_import( 'synved-connect' );
		synved_plugout_module_import( 'synved-option' );
		synved_plugout_module_import( 'synved-social' );
	}

	synved_wp_social_load();
}

synved_plugout_module_path_add( 'synved-connect', 'addon', dirname( ( defined( 'SYNVED_SOCIAL_INCLUDE_PATH' ) ? SYNVED_SOCIAL_INCLUDE_PATH : __FILE__ ) ) . '/synved-connect/addons' );
synved_plugout_module_path_add( 'synved-option', 'addon', dirname( ( defined( 'SYNVED_SOCIAL_INCLUDE_PATH' ) ? SYNVED_SOCIAL_INCLUDE_PATH : __FILE__ ) ) . '/synved-option/addons' );
synved_plugout_module_path_add( 'synved-social', 'addon', dirname( ( defined( 'SYNVED_SOCIAL_INCLUDE_PATH' ) ? SYNVED_SOCIAL_INCLUDE_PATH : __FILE__ ) ) . '/synved-social/addons' );

require_once __DIR__ . '/instance.php';
