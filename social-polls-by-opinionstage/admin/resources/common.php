<?php
/**
 * Resources
 *
 * @package OpinionStageWordPressPlugin
 */

defined( 'ABSPATH' ) || die();

/**
 * Registers common assets
 */
function opinionstage_common_load_resources() {
	$current_screen = get_current_screen();
	if (
		'toplevel_page_opinionstage-settings' === $current_screen->id
		|| 'opinion-stage_page_opinionstage-help-resource' === $current_screen->id
		|| 'toplevel_page_opinionstage-getting-started' === $current_screen->id
	) {
		opinionstage_register_css_asset( 'menu-page', 'menu-page.css' );

		opinionstage_enqueue_css_asset( 'menu-page' );
	}

	if ( opinionstage_is_my_items_admin_page() ) {
		opinionstage_register_javascript_asset( 'menu-page', 'menu-page.js', array( 'jquery' ) );
		opinionstage_enqueue_js_asset( 'menu-page' );
	}
}

function opinionstage_common_load_header() {

}
function opinionstage_common_load_footer() {

}
