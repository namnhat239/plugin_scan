<?php
/**
 * Plugin Name: WP Accessibility Helper
 * Plugin URI: https://accessibility-helper.co.il/
 * Description: WP Accessibility Helper sidebar
 * Author: Alex Volkov
 * Version: 0.6.2.2
 * Author URI: http://www.volkov.co.il
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-accessibility-helper
 *
 * @package WAH
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
define( 'WAH_VERSION', '0.6.2.2' );
define( 'WAHPRO_LINK', 'https://accessibility-helper.co.il/pro/' );

require_once dirname( __FILE__ ) . '/inc/wah-front-functions.php';

add_action( 'init', 'wp_access_helper_load_plugin_textdomain' );
add_action( 'wp_enqueue_scripts', 'wp_access_helper_scripts' );
add_action( 'admin_menu', 'wp_accessibility_helper_admin_actions' );
add_action( 'admin_head', 'admin_styles' );
add_action( 'admin_enqueue_scripts', 'plugin_admin_scripts' );
add_action( 'wp_footer', 'wp_access_helper_create_container' );
add_action( 'after_setup_theme', 'register_wah_skiplinks_menu' );

/**
 * Include WAH admin page
 */
function wah_admin() {
	include 'admin/pages/wah-admin.php';
}
/**
 * Include WAH attachments page
 */
function wah_attachments() {
	include 'admin/pages/wah-attachments.php';
}
/**
 * Include WAH landmark page
 */
function wah_landmark() {
	include 'admin/pages/wah-landmark.php';
}
/**
 * Include WAH sidebar controls page
 */
function wah_sidebar_controls() {
	include 'admin/pages/wah-sidebar-controls.php';
}
/**
 * Add WAH Admi menus
 */
function wp_accessibility_helper_admin_actions() {
	add_menu_page(
		__( 'Accessibility', 'wp-accessibility-helper' ),
		'Accessibility',
		'manage_options',
		'wp_accessibility',
		'wah_admin',
		'dashicons-universal-access-alt'
	);
	add_submenu_page(
		'wp_accessibility',
		__( 'Widgets Order', 'wp-accessibility-helper' ),
		'Widgets Order',
		'manage_options',
		'wp_accessibility_sidebar_controls',
		'wah_sidebar_controls'
	);
	add_submenu_page(
		'wp_accessibility',
		__( 'Attachments Control', 'wp-accessibility-helper' ),
		'Attachments Control',
		'manage_options',
		'wp_accessibility_image',
		'wah_attachments'
	);
	add_submenu_page(
		'wp_accessibility',
		__( 'Landmark & CSS', 'wp-accessibility-helper' ),
		'Landmark & CSS',
		'manage_options',
		'wp_accessibility_landmark',
		'wah_landmark'
	);
}
/**
 * Load WP Accessibility Helper TextDomain
 */
function wp_access_helper_load_plugin_textdomain() {
	$domain = 'wp-accessibility-helper';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	$loaded = load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	if ( $loaded ) {
		return $loaded;
	} else {
		load_plugin_textdomain( $domain, false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
}

/**
 * Register front styles & scripts
 */
function wp_access_helper_scripts() {
	wp_register_style( 'wpah-front-styles', plugin_dir_url( __FILE__ ) . 'assets/css/wp-accessibility-helper.min.css', null, '0.5.9.4' );
	wp_enqueue_style( 'wpah-front-styles' );
	wp_enqueue_script( 'wp-accessibility-helper', plugin_dir_url( __FILE__ ) . 'assets/js/wp-accessibility-helper.min.js', array( 'jquery' ), '1.0.0', true );
}
/**
 * Register admin styles
 */
function admin_styles() {
	$wah_admin_pages = get_wah_admin_pages();
	if ( isset( $_GET['page'] ) && $_GET['page'] && in_array( $_GET['page'], $wah_admin_pages, true ) ) {
		wp_register_style( 'wp-accessibility-helper', plugin_dir_url( __FILE__ ) . 'admin/css/wp-accessibility-helper.css', null, '0.5.9.4' );
		wp_enqueue_style( 'wp-accessibility-helper' );
		if ( is_rtl() ) {
			wp_register_style( 'wp-accessibility-helper-rtl', plugin_dir_url( __FILE__ ) . 'admin/css/wp-accessibility-helper_rtl.css', null, '0.5.9.4' );
			wp_enqueue_style( 'wp-accessibility-helper-rtl' );
		}
	}
}
/**
 * Register admin scripts
 */
function plugin_admin_scripts() {
	$wah_admin_pages = get_wah_admin_pages();
	if ( isset( $_GET['page'] ) && $_GET['page'] && in_array( $_GET['page'], $wah_admin_pages, true ) ) {
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_media();
		wp_enqueue_script( 'admin_colors', plugin_dir_url( __FILE__ ) . 'admin/js/jscolor.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'admin_scripts', plugin_dir_url( __FILE__ ) . 'admin/js/admin_scripts.js', array( 'jquery' ), '1.0.0', true );
	}
}
/**
 * WAH Admin pages slugs
 *
 * @return array array of slugs
 */
function get_wah_admin_pages() {
	return array(
		'wp_accessibility',
		'wp_accessibility_sidebar_controls',
		'wp_accessibility_image',
		'wp_accessibility_landmark',
		'wp_accessibility_contribute',
	);
}
/**
 * Create WP-Accessibility-Helper HTML Elements
 */
function wp_access_helper_create_container() {
	include_once dirname( __FILE__ ) . '/wp-accessibility-helper-view.php';
	include_once dirname( __FILE__ ) . '/inc/wah-skip-links.php';
}
if ( is_admin() ) {
	include_once dirname( __FILE__ ) . '/admin/functions.php';
	include_once dirname( __FILE__ ) . '/admin/ajax-functions.php';
}
/**
 * Register WAH Skiplinks
 */
function register_wah_skiplinks_menu() {
	register_nav_menu( 'wah_skiplinks', __( 'WAH Skiplinks menu', 'wp-accessibility-helper' ) );
}
