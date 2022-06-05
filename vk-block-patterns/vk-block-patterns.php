<?php
/**
 * Plugin Name: VK Block Patterns
 * Plugin URI: https://github.com/vektor-inc/vk-block-patterns
 * Description: You can make and register your original custom block patterns.
 * Version: 1.18.0
 * Requires at least: 5.8
 * Author:  Vektor,Inc.
 * Author URI: https://vektor-inc.co.jp
 * Text Domain: vk-block-patterns
 * License: GPL 2.0 or Later
 *
 * @package VK Block Patterns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'register_block_pattern' ) ) {
	return;
}

// Define plugin version.
$plugin_data = get_file_data( __FILE__, array( 'version' => 'Version' ) );
define( 'VBP_VERSION', $plugin_data['version'] );

// Define plugin path.
define( 'VBP_PATH', plugin_dir_path( __FILE__ ) );

// Define plugin url.
define( 'VBP_URL', plugin_dir_url( __FILE__ ) );

// define plugin prefix.
global $vbp_prefix;
$vbp_prefix = apply_filters( 'vbp_prefix', 'VK ' );


/**
 * Plugin Loaded
 */
function vbp_plugin_loaded() {

	// Load Main File.
	require_once VBP_PATH . 'inc/vk-block-patterns/vk-block-patterns-config.php';
	// Load VKAdmin.
	require_once VBP_PATH . 'inc/vk-admin/vk-admin-config.php';
	// Load Edit Post Options.
	require_once VBP_PATH . 'inc/edit-post/vk-edit-post-config.php';
	// Load Admin Options.
	require_once VBP_PATH . 'admin/admin.php';
}
add_action( 'plugins_loaded', 'vbp_plugin_loaded' );

// Add a link to this plugin's settings page
function vbp_set_plugin_meta( $links ) {
	$settings_link = '<a href="options-general.php?page=vk_block_patterns_options">' . __( 'Setting', 'vk-block-patterns' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'vbp_set_plugin_meta', 10, 1 );
require dirname( __FILE__ ) . '/patterns-data/class-register-patterns-from-json.php';

function vbp_get_options() {
	$default = array(
		'role'             => 'author',
		'showPatternsLink' => true,
	);
	$options = get_option( 'vk_block_patterns_options' );
	// showPatternsLinkは後から追加したので、option値に保存されてない時にデフォルトとマージする
	$options = wp_parse_args( $options, $default );
	return $options;
}

/**
 * Add pattern library link
 *
 * @return void
 */
function vbp_add_pattern_link() {
	$parent_slug = 'edit.php?post_type=vk-block-patterns';
	$page_title  = 'パターンライブラリ';
	$menu_title  = 'パターンライブラリ';
	$capability  = 'edit_posts';
	$menu_slug   = 'https://patterns.vektor-inc.co.jp/';
	$function    = '';
	if ( 'ja' === get_locale() ) {
		add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' );
	}
}
add_action( 'admin_menu', 'vbp_add_pattern_link' );