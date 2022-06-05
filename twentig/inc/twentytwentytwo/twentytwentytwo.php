<?php
/**
 * Functionalities for Twenty Twenty-Two.
 *
 * @package twentig
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue styles for the theme.
 */
function twentig_twentytwo_enqueue_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style(
		'twentig-twentytwo',
		TWENTIG_ASSETS_URI . "/css/twentytwentytwo/style{$min}.css",
		array(),
		TWENTIG_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'twentig_twentytwo_enqueue_scripts', 12 );

/**
 * Enqueue styles inside the editor.
 */
function twentig_twentytwo_editor_styles() {
	add_editor_style( TWENTIG_ASSETS_URI . "/css/twentytwentytwo/editor.css" );
}
add_action( 'admin_init', 'twentig_twentytwo_editor_styles' );

/**
 * Updates post editor settings to add fonts settings.
 *
 * @param array  $settings Default editor settings.
 *
 * @return array Filtered editor settings.
 */
function twentig_twentytwo_filter_global_styles_settings( $settings ) {
	if ( isset( $settings['__experimentalFeatures'] ) ) {
		$theme_sizes = $settings['__experimentalFeatures']['typography']['fontSizes']['theme'];
		$settings['__experimentalFeatures']['typography']['fontSizes']['theme'] = twentig_twentytwo_get_font_sizes( $theme_sizes );
	}
	return $settings;
}
add_filter( 'block_editor_settings_all', 'twentig_twentytwo_filter_global_styles_settings' );

/**
 * Retrieve editor font sizes.
 */
function twentig_twentytwo_get_font_sizes( $sizes ) {
	$additional_sizes = array(
		array(
			'name' => __( 'Extra Extra Large', 'twentig' ),
			'size' => 'clamp(2rem, 4vw, 2.75rem)',
			'slug' => 'xx-large',
		),
	);
	return array_merge( $sizes, $additional_sizes );
}

/**
 * Unregister theme patterns.
 */
function twentig_twentytwo_register_block_patterns() {
	if ( ! twentig_is_option_enabled( 'twentig_core_block_patterns' ) ) {
		add_filter( 'twentytwentytwo_block_patterns', '__return_empty_array' );
	}
}
add_action( 'init', 'twentig_twentytwo_register_block_patterns', 9 );
