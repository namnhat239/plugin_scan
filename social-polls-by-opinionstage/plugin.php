<?php
/**
 * Poll, Survey & Quiz Maker Plugin by Opinion Stage
 *
 * @package   OpinionStageWordPressPlugin
 *
 * @wordpress-plugin
 * Plugin Name: Poll, Survey & Quiz Maker Plugin by Opinion Stage
 * Plugin URI:  https://www.opinionstage.com
 * Description: Add a highly engaging poll, survey, quiz or contact form builder to your site. You can add the poll, survey, quiz or form to any post/page or to the sidebar.
 * Version:     19.8.14
 * Author:      OpinionStage.com
 * Author URI:  https://www.opinionstage.com
 * Text Domain: social-polls-by-opinionstage
 */

defined( 'ABSPATH' ) || die(); // block direct access to plugin PHP files.

define( 'OPINIONSTAGE_PLUGIN_FILE', __FILE__ );
define( 'OPINIONSTAGE_PLUGIN_DIR', plugin_dir_path( OPINIONSTAGE_PLUGIN_FILE ) );

require_once OPINIONSTAGE_PLUGIN_DIR . 'includes/logging.php';

$opinionstage_settings = array();

// don't even try to load any configuration settings,
// if WordPress is not in debug mode,
// as configuration settings are only for plugin development.
if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
	$opinionstage_dev_cfg_path = plugin_dir_path( __FILE__ ) . 'dev.ini';
	if ( file_exists( $opinionstage_dev_cfg_path ) ) {
		opinionstage_error_log( "loading configuration from file $opinionstage_dev_cfg_path" );
		$opinionstage_settings = parse_ini_file( $opinionstage_dev_cfg_path );
	}
}

define( 'OPINIONSTAGE_WIDGET_VERSION', '19.8.14' );

define( 'OPINIONSTAGE_TEXT_DOMAIN', 'social-polls-by-opinionstage' );
define( 'OPINIONSTAGE_WIDGET_API_KEY', 'wp35e8' );
define( 'OPINIONSTAGE_UTM_SOURCE', 'wordpress' );
define( 'OPINIONSTAGE_UTM_CAMPAIGN', 'WPMainPI' );
define( 'OPINIONSTAGE_UTM_MEDIUM', 'link' );
define( 'OPINIONSTAGE_UTM_CONNECT_MEDIUM', 'connect' );
define(
	'OPINIONSTAGE_UTM_PARAMETERS',
	array(
		'utm_source'   => OPINIONSTAGE_UTM_SOURCE,
		'utm_campaign' => OPINIONSTAGE_UTM_CAMPAIGN,
		'utm_medium'   => OPINIONSTAGE_UTM_MEDIUM,
		'o'            => OPINIONSTAGE_WIDGET_API_KEY,
	)
);
define( 'OPINIONSTAGE_SERVER_BASE', isset( $opinionstage_settings['server_base'] ) ? $opinionstage_settings['server_base'] : 'https://www.opinionstage.com' );
define( 'OPINIONSTAGE_API_PATH', OPINIONSTAGE_SERVER_BASE . '/api/v1' );
define( 'OPINIONSTAGE_LOGIN_PATH', OPINIONSTAGE_SERVER_BASE . '/api/wp/v1/auth/new' );
define( 'OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API', OPINIONSTAGE_SERVER_BASE . '/api/wp/v1/my/widgets' );
define( 'OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API_RECENT_UPDATE', OPINIONSTAGE_SERVER_BASE . '/api/wp/v1/my/widgets/recent-update' );
define( 'OPINIONSTAGE_DEACTIVATE_FEEDBACK_API', OPINIONSTAGE_SERVER_BASE . '/api/wp/v1/events' );
define( 'OPINIONSTAGE_MESSAGE_API', 'https://www.opinionstage.com/wp/msg-app/api/index.php' );

define(
	'OPINIONSTAGE_REDIRECT_TEMPLATES_API_UTM',
	add_query_arg(
		OPINIONSTAGE_UTM_PARAMETERS,
		OPINIONSTAGE_SERVER_BASE . '/api/wp/redirects/templates'
	)
);
define(
	'OPINIONSTAGE_REDIRECT_CREATE_WIDGET_API_UTM',
	add_query_arg(
		OPINIONSTAGE_UTM_PARAMETERS,
		OPINIONSTAGE_SERVER_BASE . '/api/wp/redirects/widgets/new'
	)
);

define( 'OPINIONSTAGE_OPTIONS_KEY', 'opinionstage_widget' );

define( 'OPINIONSTAGE_POLL_SHORTCODE', 'socialpoll' );
define( 'OPINIONSTAGE_WIDGET_SHORTCODE', 'os-widget' );
define( 'OPINIONSTAGE_PLACEMENT_SHORTCODE', 'osplacement' );

define( 'OPINIONSTAGE_MENU_SLUG', 'opinionstage-settings' );
define( 'OPINIONSTAGE_GETTING_STARTED_SLUG', 'opinionstage-getting-started' );
define( 'OPINIONSTAGE_HELP_RESOURCE_SLUG', 'opinionstage-help-resource' );

define( 'OPINIONSTAGE_LOGIN_CALLBACK_SLUG', 'opinionstage-login-callback' );
define( 'OPINIONSTAGE_DISCONNECT_PAGE', 'opinionstage-disconnect-page' );
define( 'OPINIONSTAGE_CONTENT_LOGIN_CALLBACK_SLUG', 'opinionstage-content-login-callback-page' );
define(
	'OPINIONSTAGE_LIVE_CHAT_URL_UTM',
	add_query_arg(
		OPINIONSTAGE_UTM_PARAMETERS,
		OPINIONSTAGE_SERVER_BASE . '/live-chat/'
	)
);

define('OPINIONSTAGE_REQUIRED_PHP_VERSION', '5.2' );

if ( ! version_compare( PHP_VERSION, OPINIONSTAGE_REQUIRED_PHP_VERSION, '>=' ) ) {
	add_action( 'admin_notices', 'opinionstage_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
	add_action( 'admin_notices', 'opinionstage_fail_wp_version' );
} else {
	// phpcs:disable Squiz.Commenting.FunctionComment
	function opinionstage_plugin_activated( $plugin ) {
		// Check if active plugin file is plugin.php on plugin activate hook.
		if ( plugin_basename( __FILE__ ) === $plugin ) {
			$redirect_to = opinionstage_user_logged_in() ? OPINIONSTAGE_MENU_SLUG : OPINIONSTAGE_GETTING_STARTED_SLUG;
			wp_safe_redirect( 'admin.php?page=' . $redirect_to );
			exit();
		}
	}
	// phpcs:disable Squiz.Commenting.FunctionComment
	function opinionstage_plugin_activate() {
		// all good: delete old file.
		$deprecated_file = plugin_dir_path( __FILE__ ) . 'opinionstage-polls.php';
		if ( file_exists( $deprecated_file ) && is_writable( $deprecated_file ) ) {
			unlink( $deprecated_file );
		}
		if ( ! get_option( 'oswp_installation_date' ) ) {
			update_option( 'oswp_installation_date', time(), false );
		}
	}
	register_activation_hook( __FILE__, 'opinionstage_plugin_activate' );
	add_action( 'init', 'opinionstage_plugin_activate' );
	add_action( 'activated_plugin', 'opinionstage_plugin_activated' );
	require_once plugin_dir_path( __FILE__ ) . 'includes/opinionstage-functions.php';

	// Check if another OpinionStage plugin already installed and display warning message.
	if ( opinionstage_check_plugin_available( 'opinionstage_popup' ) ) {
		add_action( 'admin_notices', 'opinionstage_other_plugin_installed_warning' );
	} else {
		require_once plugin_dir_path( __FILE__ ) . 'includes/opinionstage-utility-functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/opinionstage-article-placement-functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/opinionstage-sidebar-widget.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-opinionstagefeedback.php';

        if ( is_admin() ) {
            require plugin_dir_path( __FILE__ ) . 'admin/init.php';
        } else {
            require plugin_dir_path( __FILE__ ) . 'public/init.php';
        }
        require_once OPINIONSTAGE_PLUGIN_DIR . 'includes/gutenberg.php';

		add_action( 'widgets_init', 'opinionstage_init_widget' );
		add_action( 'plugins_loaded', 'opinionstage_init' );
	}

	register_deactivation_hook( __FILE__, 'opinionstage_plugin_deactivate' );
	function opinionstage_plugin_deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

		check_admin_referer( "deactivate-plugin_{$plugin}" );

		delete_option( 'oswp_installation_date' );
	}
}
/**
 * Opinionstage admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function opinionstage_fail_php_version() {
	/* translators: %s: PHP version */
	$message      = sprintf( esc_html__( 'Poll, Survey & Quiz Maker Plugin by Opinion Stage requires PHP version %s+, plugin is currently NOT RUNNING.', 'social-polls-by-opinionstage' ), OPINIONSTAGE_REQUIRED_PHP_VERSION );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Opinionstage admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @since 1.5.0
 *
 * @return void
 */
function opinionstage_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message      = sprintf( esc_html__( 'Poll, Survey & Quiz Maker Plugin by Opinion Stage requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'social-polls-by-opinionstage' ), '4.7' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
