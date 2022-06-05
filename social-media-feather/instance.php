<?php
/**
 * Instantiates the Social Media Feather plugin.
 *
 * @package SocialMediaFeather
 */

namespace SocialMediaFeather;

require_once __DIR__ . '/php/class-plugin-base.php';
require_once __DIR__ . '/php/class-plugin.php';

/**
 * Social Media Feather Plugin Instance
 *
 * @return Plugin
 */
function plugin_instance() {
	static $instance = null;

	if ( true === is_null( $instance ) ) {
		$instance = new Plugin();
	}

	return $instance;
}

plugin_instance();
