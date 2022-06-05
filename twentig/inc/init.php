<?php
/**
 * Twentig plugin file.
 *
 * @package twentig
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require TWENTIG_PATH . 'inc/about.php';
require TWENTIG_PATH . 'inc/settings.php';
require TWENTIG_PATH . 'inc/blocks.php';
require TWENTIG_PATH . 'inc/block-styles.php';
require TWENTIG_PATH . 'inc/block-presets.php';
require TWENTIG_PATH . 'inc/block-patterns.php';

function twentig_theme_support_includes() {
	$template = get_template();

	if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
		require TWENTIG_PATH . 'inc/block-themes/index.php';
		if ( 'twentytwentytwo' === $template ) {
			require TWENTIG_PATH . 'inc/twentytwentytwo/twentytwentytwo.php';
		}
	} elseif ( 'twentytwentyone' === $template ) {
		require TWENTIG_PATH . 'inc/twentytwentyone/twentytwentyone.php';
	} elseif ( 'twentytwenty' === $template ) {
		require TWENTIG_PATH . 'inc/twentytwenty/twentytwenty.php';
	}
}
twentig_theme_support_includes();

/**
 * Redirects the Twentig editor tour to edit the front page or add a new page.
 */
function twentig_redirect_editor_tour() {
	global $pagenow;
	
	if ( 'post-new.php' === $pagenow && isset( $_GET['twentig_tour_editor'] ) ) {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			$home_id = (int) get_option( 'page_on_front' );
			if ( $home_id ) {
				wp_safe_redirect( admin_url( 'post.php?post=' . $home_id . '&action=edit&twentig_tour=1' ) );
				exit;
			}
		} else {
			wp_safe_redirect( admin_url( 'post-new.php?post_type=page&twentig_tour=1' ) );
			exit;
		}
	}
}
add_action( 'admin_init', 'twentig_redirect_editor_tour' );
