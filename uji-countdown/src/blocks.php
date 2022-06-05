<?php
/**
 * Uji Countdown Front
 *
 * Handles back-end blocks
 *
 * @author   WPmanage
 * @category Blocks support
 * @package  Uji-Countdown
 * @version  2.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 */
function uji_countdown_2020_uji_block_assets() { // phpcs:ignore
	// Register block styles for both frontend + backend.
	wp_register_style(
		'uji_countdown_2020-uji-style-css', // Handle.
		plugins_url( 'dist/style-ujicount.css', dirname( __FILE__ ) ), // Block style CSS.
		is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
		'uji_countdown_2020-uji-block-js', // Handle.
		plugins_url( '/dist/ujicount.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'uji_countdown_2020-uji-block-editor-css', // Handle.
		plugins_url( 'dist/style-ujicount.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `ujiGlobal` object.
	wp_localize_script(
		'uji_countdown_2020-uji-block-js',
		'ujiGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			// Add more data here that you want to access from `ujiGlobal` object.
		]
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'urc/block-uji-countdown-2020', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'uji_countdown_2020-uji-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'uji_countdown_2020-uji-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'uji_countdown_2020-uji-block-editor-css',
		)
	);
        
  
}

// Hook: Block assets.
add_action( 'init', 'uji_countdown_2020_uji_block_assets' );