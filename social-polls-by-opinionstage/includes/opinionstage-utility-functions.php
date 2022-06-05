<?php
/**
 * Utility Functions
 *
 * @package   OpinionStageWordPressPlugin
 */

defined( 'ABSPATH' ) || die();

require_once OPINIONSTAGE_PLUGIN_DIR . 'includes/logging.php';

/**
 * Returns default UTM parameters and merges passed ones
 *
 * @param array $query array of url parameters.
 *
 * @return string
 */
function opinionstage_utm_query( $query = array() ) {
	return http_build_query( array_merge( $query, OPINIONSTAGE_UTM_PARAMETERS ) );
}

/**
 * Returns UTM url
 *
 * @param string $path api path.
 * @param array  $query query args.
 * @return string
 */
function opinionstage_utm_url( $path, $query = array() ) {
	return OPINIONSTAGE_SERVER_BASE . '/' . $path . '?' . opinionstage_utm_query( $query );
}

/**
 * Utility function to create a link with the correct host and all the required information.
 *
 * @param string $caption anchors caption.
 * @param string $path path.
 * @param string $css_class class.
 * @param array  $query list of parameters.
 * @return string
 */
function opinionstage_link( $caption, $path, $css_class = '', $query = array() ) {
	$link = opinionstage_utm_url( $path, $query );

	return "<a href='{$link}' target='_blank' class='{$css_class}'>{$caption}</a>";
}

/**
 * Returns Help links anchors
 *
 * @param string $caption anchors caption.
 * @param string $path path.
 * @param string $css_class class.
 * @param string $style inline styles.
 * @param array  $query_data list of parameters.
 * @return string
 */
function opinionstage_help_links( $caption, $path, $css_class = '', $style = '', $query_data = array() ) {
	$link = $path . '?' . opinionstage_utm_query( $query_data );

	return "<a href='{$link}' target='_blank' class='{$css_class}' style='{$style}'>{$caption}</a>";
}

/**
 * Registers JS on admin pages
 *
 * @param string $name asset name.
 * @param string $relative_path relative path.
 * @param array  $deps list of dependencies.
 * @param bool   $in_footer footer.
 */
function opinionstage_register_javascript_asset( $name, $relative_path, $deps = array(), $in_footer = true ) {
	$registered = wp_register_script(
		opinionstage_asset_name( $name ),
		plugins_url( opinionstage_asset_path() . '/js/' . $relative_path, plugin_dir_path( __FILE__ ) ),
		$deps,
		OPINIONSTAGE_WIDGET_VERSION,
		$in_footer
	);

	if ( ! $registered ) {
		opinionstage_error_log( "javascript asset '$name' registration failed" );
	}
}

/**
 * Registers CSS on admin pages
 *
 * @param string $name name.
 * @param string $relative_path relative path.
 */
function opinionstage_register_css_asset( $name, $relative_path ) {
	wp_register_style(
		opinionstage_asset_name( $name ),
		plugins_url( opinionstage_asset_path() . '/css/' . $relative_path, plugin_dir_path( __FILE__ ) ),
		null,
		OPINIONSTAGE_WIDGET_VERSION
	);
}

/**
 * Enqueue JS
 *
 * @param string $name name.
 */
function opinionstage_enqueue_js_asset( $name ) {
	wp_enqueue_script( opinionstage_asset_name( $name ) );
}

/**
 * Enqueue CSS
 *
 * @param string $name name.
 */
function opinionstage_enqueue_css_asset( $name ) {
	wp_enqueue_style( opinionstage_asset_name( $name ) );
}

/**
 * Generates name of asset
 *
 * @param string $name name.
 * @return string
 */
function opinionstage_asset_name( $name ) {
	return 'opinionstage-' . $name;
}

/**
 * Returns path of asset
 *
 * @return string
 */
function opinionstage_asset_path() {
	return is_admin() ? 'admin' : 'public';
}

/**
 * Generates a link for editing the flyout placement on Opinion Stage site
 *
 * @param string $tab tab.
 * @return string
 */
function opinionstage_flyout_edit_url( $tab ) {
	$os_options = (array) get_option( OPINIONSTAGE_OPTIONS_KEY );
	return OPINIONSTAGE_SERVER_BASE . '/containers/' . $os_options['fly_id'] . '/edit?selected_tab=' . $tab;
}

/**
 * Generates a link for editing the sidebar placement on Opinion Stage site
 *
 * @param string $tab tab.
 * @return string
 */
function opinionstage_sidebar_placement_edit_url( $tab ) {
	$os_options = (array) get_option( OPINIONSTAGE_OPTIONS_KEY );
	return OPINIONSTAGE_SERVER_BASE . '/containers/' . $os_options['sidebar_placement_id'] . '/edit?selected_tab=' . $tab;
}

/**
 * Returns create widget url
 *
 * @param string $w_type widget type.
 * @param string $css_class class.
 * @param string $title anchors caption.
 * @return string
 */
function opinionstage_create_widget_link( $w_type, $css_class, $title = 'CREATE NEW' ) {
	$url = add_query_arg( 'w_type', $w_type, OPINIONSTAGE_REDIRECT_CREATE_WIDGET_API_UTM );
	return sprintf( "<a href='%s' target='_blank' class='%s'>%s</a>", $url, $css_class, $title );
}

/**
 * Returns create poll anchor
 *
 * @param string $css_class class.
 * @param string $title anchors caption.
 * @return string
 */
function opinionstage_create_poll_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'poll', $css_class, $title );
}

/**
 * Returns create personality quiz anchor
 *
 * @param string $css_class class.
 * @param string $title anchors caption.
 * @return string
 */
function opinionstage_create_personality_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'outcome', $css_class, $title );
}

/**
 * Returns create trivia anchor
 *
 * @param string $css_class class.
 * @param string $title anchors caption.
 * @return string
 */
function opinionstage_create_trivia_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'quiz', $css_class, $title );
}

/**
 * Returns create survey anchor
 *
 * @param string $css_class class.
 * @param string $title anchors caption.
 * @return string
 */
function opinionstage_create_survey_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'survey', $css_class, $title );
}

/**
 * Returns create standard form anchor
 *
 * @param string $css_class class.
 * @param string $title anchors caption.
 * @return string
 */
function opinionstage_create_form_link( $css_class, $title = 'CREATE NEW' ) {
	return opinionstage_create_widget_link( 'contact_form', $css_class, $title );
}

/**
 * Generates a to the callback page used to connect the plugin to the Opinion Stage account
 */
function opinionstage_callback_url() {
	return menu_page_url( OPINIONSTAGE_LOGIN_CALLBACK_SLUG, false );
}


/**
 * Returns widget templates link
 *
 * @param string $type widget type.
 * @return string
 */
function opinionstage_get_templates_url_for_type( $type ) {
	return add_query_arg( 'page', $type, OPINIONSTAGE_REDIRECT_TEMPLATES_API_UTM );
}
