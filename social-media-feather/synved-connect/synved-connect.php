<?php
/**
 * Module Name: Synved Connect
 * Description: Connect and sync components in a WordPress installation with a remote server
 * Author: Synved
 * Version: 1.0.4
 * Author URI: http://synved.com/
 * License: GPLv2
 *
 * LEGAL STATEMENTS
 *
 * NO WARRANTY
 * All products, support, services, information and software are provided "as is" without warranty of any kind, express or implied, including, but not limited to, the implied warranties of fitness for a particular purpose, and non-infringement.
 *
 * NO LIABILITY
 * In no event shall Synved Ltd. be liable to you or any third party for any direct or indirect, special, incidental, or consequential damages in connection with or arising from errors, omissions, delays or other cause of action that may be attributed to your use of any product, support, services, information or software provided, including, but not limited to, lost profits or lost data, even if Synved Ltd. had been advised of the possibility of such damages.
 *
 * @package SocialMediaFeather
 */

define( 'SYNVED_CONNECT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

const SYNVED_WP_MODERN_VERSION = '4.1';

require_once SYNVED_CONNECT_PLUGIN_PATH . 'synved-connect-key.php';
require_once SYNVED_CONNECT_PLUGIN_PATH . 'synved-connect-support.php';
require_once SYNVED_CONNECT_PLUGIN_PATH . 'synved-connect-sponsor.php';

$synved_connect = array();

/**
 * Path URI.
 *
 * @param string $path Path string.
 *
 * @return string
 */
function synved_connect_path_uri( $path = null ) {
	$uri = plugins_url( '/synved-wp-connect' ) . '/synved-connect';

	if ( function_exists( 'synved_plugout_module_uri_get' ) ) {
		$mod_uri = synved_plugout_module_uri_get( 'synved-connect' );

		if ( false === empty( $mod_uri ) ) {
			$uri = $mod_uri;
		}
	}

	if ( false === empty( $path ) ) {
		if ( '/' !== substr( $uri, -1 ) && '/' !== $path[0] ) {
			$uri .= '/';
		}

		$uri .= $path;
	}

	return $uri;
}

/**
 * ID get.
 *
 * @param string|null $component Component string.
 * @param string|null $part      Part string.
 *
 * @return false|mixed|void
 */
function synved_connect_id_get( $component = null, $part = null ) {
	$option_key = 'default';

	if ( false === empty( $component ) ) {
		$option_key = 'component_' . $component;
	}

	return get_option( 'synved_connect_id_' . $option_key );
}

/**
 * Enqueue scripts.
 *
 * @return void
 */
function synved_connect_enqueue_scripts() {
	$uri = synved_connect_path_uri();
	$dir = plugin_dir_path( __FILE__ );

	wp_register_style(
		'synved-connect-admin',
		$uri . '/style/admin.css',
		false,
		filemtime( $dir . '/style/admin.css' )
	);

	wp_enqueue_style( 'synved-connect-admin' );

	if ( true === version_compare( get_bloginfo( 'version' ), SYNVED_WP_MODERN_VERSION, 'lt' ) ) {
		wp_register_style(
			'synved-connect-old-wp-support-css',
			$uri . '/style/synved_old_wp_support.css',
			false,
			filemtime( $dir . '/style/synved_old_wp_support.css' ),
			'all'
		);
		wp_enqueue_style( 'synved-connect-old-wp-support-css' );
	}
}

/**
 * Connect init.
 *
 * @return void
 */
function synved_connect_init() {
	$install_date = get_option( 'synved_connect_install_date' );

	// Fresh installation.
	if ( true === empty( $install_date ) ) {
		update_option( 'synved_connect_install_date', time() );
		update_option( 'synved_version', SYNVED_VERSION );
		synved_option_set( 'synved_social', 'accepted_sharethis_terms', false );
	}

	$version = get_option( 'synved_version' );
	if ( SYNVED_VERSION !== $version ) {
		synved_connect_upgrade( $version );
	}
}

/**
 * Upgrades the plugin.
 *
 * @param string $version The current version.
 * @return void
 */
function synved_connect_upgrade( $version ) {
	// Show the ShareThis notice on version upgrade.
	synved_option_set( 'synved_social', 'hide_sharethis_terms', false );

	// Saves the new option in the DB.
	update_option( 'synved_version', SYNVED_VERSION );
}

add_action( 'init', 'synved_connect_init', 9 );
add_action( 'admin_enqueue_scripts', 'synved_connect_enqueue_scripts' );
add_action( 'wp_ajax_smf_ajax_hide_review', 'synved_hide_review' );

/**
 * Hide review.
 *
 * @return void
 */
function synved_hide_review() {
	update_option( 'smf-hide-review', true );

	wp_send_json_success( 'hidden' );
}
