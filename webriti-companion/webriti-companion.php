<?php
/*
Plugin Name: Webriti Companion
Plugin URI:
Description: Enhances Webriti themes with extra functionality.
Version: 0.1
Author: Webriti
Author URI: https://github.com
Text Domain: webriti-companion
*/
define( 'WC__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WC__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function webriti_companion_activate() {
	$theme = wp_get_theme(); // gets the current theme
	if ( 'Quality' == $theme->name) {
		require_once('inc/quality/features/feature-service-section.php');
		require_once('inc/quality/features/feature-project-section.php');
		require_once('inc/quality/sections/quality-portfolio-section.php');
		require_once('inc/quality/sections/quality-features-section.php');
		require_once('inc/quality/customizer.php');
	}

}
add_action( 'init', 'webriti_companion_activate' );

$theme = wp_get_theme();
if ( 'Quality' == $theme->name) {
require_once('inc/quality/features/custom-post-type/custom-post-type.php');
}
?>
