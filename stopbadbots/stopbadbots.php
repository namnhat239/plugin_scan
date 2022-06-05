<?php /*
Plugin Name: StopBadBots
Plugin URI: http://stopbadbots.com
Description: Stop Bad Bots, SPAM bots and spiders. No DNS or Cloud Traffic Redirection. No Slow Down Your Site!
Version: 7.13
Text Domain: stopbadbots
Domain Path: /language
Author: Bill Minozzi
Author URI: http://stopbadbots.com
License:     GPL2
Copyright (c) 2016 Bill Minozzi
Stopbadbots is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
StopBadBots_optin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with StopBadBots_optin. If not, see {License URI}.
Permission is hereby granted, free of charge subject to the following conditions:
The above copyright notice and this FULL permission notice shall be included in
all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/*
 **********************
// First time...
// Not Suspect...
*****  */

// ob_start();
// Fix memory
$stopbadbots_maxMemory = @ini_get( 'memory_limit' );
$stopbadbots_last      = strtolower( substr( $stopbadbots_maxMemory, -1 ) );
$stopbadbots_maxMemory = (int) $stopbadbots_maxMemory;
if ( $stopbadbots_last == 'g' ) {
	$stopbadbots_maxMemory = $stopbadbots_maxMemory * 1024 * 1024 * 1024;
} elseif ( $stopbadbots_last == 'm' ) {
	$stopbadbots_maxMemory = $stopbadbots_maxMemory * 1024 * 1024;
} elseif ( $stopbadbots_last == 'k' ) {
	$stopbadbots_maxMemory = $stopbadbots_maxMemory * 1024;
}
if ( $stopbadbots_maxMemory < 134217728 /* 128 MB */ && $stopbadbots_maxMemory > 0 ) {
	if ( strpos( ini_get( 'disable_functions' ), 'ini_set' ) === false ) {
		@ini_set( 'memory_limit', '128M' );
	}
}
if ( null !== ini_get( 'max_execution_time' ) ) {
	if ( ini_get( 'max_execution_time' ) < 60 ) {
		ini_set( 'max_execution_time', 60 );
	}
}

global $wpdb;

$stopbadbot_plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ), false );
define( 'STOPBADBOTSVERSION', $stopbadbot_plugin_data['Version'] );

define( 'STOPBADBOTSPATH', plugin_dir_path( __file__ ) );
define( 'STOPBADBOTSURL', plugin_dir_url( __file__ ) );
define( 'STOPBADBOTSDOMAIN', get_site_url() );
define( 'STOPBADBOTSIMAGES', plugin_dir_url( __file__ ) . 'assets/images' );
define( 'STOPBADBOTSPAGE', trim( sanitize_text_field( $GLOBALS['pagenow'] ) ) );

define( 'STOPBADBOTS_CHROME', '90' );
define( 'STOPBADBOTS_FIREFOX', '88' );
define( 'STOPBADBOTSPATHLANGUAGE', dirname( plugin_basename( __FILE__ ) ) . '/language/');

if ( ! defined( 'STOPBADBOTSHOMEURL' ) ) {
	define( 'STOPBADBOTSHOMEURL', admin_url() );
}

require_once ABSPATH . 'wp-includes/pluggable.php';

/*
if(is_admin())
  add_action( 'plugins_loaded', 'stop_localization_init' );
// */

if(is_admin())
  add_action( 'plugins_loaded', 'stop_localization_init' );

$stopbadbotsserver = sanitize_text_field( $_SERVER['SERVER_NAME'] );

$stopbadbots_request_url = sanitize_text_field( $_SERVER['REQUEST_URI'] );

$stopbadbots_method  = sanitize_text_field( $_SERVER['REQUEST_METHOD'] );
$stopbadbots_referer = stopbadbots_get_referer();

$stopbadbots_version           = trim( sanitize_text_field( get_site_option( 'stopbadbots_version', '' ) ) );
$stopbadbots_string_whitelist  = trim( sanitize_text_field( get_site_option( 'stopbadbots_string_whitelist', '' ) ) );
$astopbadbots_string_whitelist = explode( ' ', $stopbadbots_string_whitelist );
$stopbadbots_ip_whitelist      = trim( sanitize_text_field( get_site_option( 'stopbadbots_ip_whitelist', '' ) ) );
$astopbadbots_ip_whitelist     = explode( ' ', $stopbadbots_ip_whitelist );

// update_option('stopbadbots_notif_level', time());
$stopbadbots_notif_level = trim( sanitize_text_field( get_site_option( 'stopbadbots_notif_level', '0' ) ) );


if ( ! function_exists( 'wp_get_current_user' ) ) {
	include_once ABSPATH . 'wp-includes/pluggable.php';
}

if ( is_admin() || is_super_admin() ) {
	if ( strpos( $stopbadbots_request_url, 'page=jetpack' ) ) {
		return;
	}
}


add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'stopbadbots_add_action_links' );
function stopbadbots_add_action_links( $links ) {
	$mylinks = array(
		'<a href="' . admin_url( 'admin.php?page=settings-stop-bad-bots' ) . '">Settings</a>',
	);
    return array_merge( $links, $mylinks );
}



// require_once(STOPBADBOTSPATH . "debug.php");
// add_action('shutdown', 'mostra_log', 999);



/* Begin Language */
if ( is_admin() ) {
	function stop_localization_init_fail() {

		if(get_option('stopbadbots_dismiss_language') == '1')
		return;


		echo '<div id="stopbadbots_an2"  class="notice notice-warning is-dismissible">';
		echo '<br />';
		echo esc_attr__( 'Stop Bad Bots: Could not load the localization file (Language file)', 'stopbadbots' );
		echo '.<br />';
		echo 'Please, contact me at our Support Page to translate it on your language.';
		echo '.<br /><br /></div>';
	}

	/*
	if ( isset( $_GET['page'] ) ) {
		$mypage = sanitize_text_field( $_GET['page'] );
		if ( $mypage == 'stop_bad_bots_plugin' || $mypage == 'sbb_my-custom-submenu-page' ) {
			$mypath = dirname( plugin_basename( __FILE__ ) ) . '/language/';
			$mypath = basename( dirname( __FILE__ ) ) . '/language';
		//	$loaded = load_plugin_textdomain( 'stopbadbots', false, $mypath );
			//if ( ! $loaded and get_locale() != 'en_US' ) {
				// if( function_exists('stop_localization_init_fail'))
				// add_action( 'admin_notices', 'stop_localization_init_fail' );
			//}
		}
	}
	*/
} 
// stopbadbots_dismissible_notice2
function stopbadbots_dismissible_notice2() {
	$r = update_option('stopbadbots_dismiss_language', '1');
	if (!$r) {
		$r = add_option('stopbadbots_dismiss_language', '1');
	}
	if($r)
	  die('OK!!!!!');
	else
	  die('NNNN');
}
add_action('wp_ajax_stopbadbots_dismissible_notice2', 'stopbadbots_dismissible_notice2');


//	add_action( 'plugins_loaded', 'stop_localization_init' );

function stop_localization_init() {

	$loaded = load_plugin_textdomain( 'stopbadbots', false, STOPBADBOTSPATHLANGUAGE );

	if (!$loaded and get_locale() <> 'en_US') {
        if (function_exists('stop_localization_init_fail'))
            add_action('admin_notices', 'stop_localization_init_fail');
    }

	//$x = get_translations_for_domain( 'stopbadbots');
	//var_dump($x);
	//die();

}
/* End language */



require_once STOPBADBOTSPATH . 'settings/load-plugin.php';
$stopbadbots_block_spam_contacts = sanitize_text_field( get_option( 'stopbadbots_block_spam_contacts', 'no' ) );
$stopbadbots_block_spam_comments = sanitize_text_field( get_option( 'stopbadbots_block_spam_comments', 'no' ) );
$stopbadbots_block_spam_login    = sanitize_text_field( get_option( 'stopbadbots_block_spam_login', 'no' ) );
$stopbadbots_checkversion        = sanitize_text_field( get_option( 'stopbadbots_checkversion', '' ) );
$stopbadbots_checkversion        = trim( $stopbadbots_checkversion );
$stopbadbots_rate_penalty        = sanitize_text_field( get_option( 'stopbadbots_rate_penalty', 'unlimited' ) );
$stopbadbots_block_http_tools    = sanitize_text_field( get_option( 'stopbadbots_block_http_tools', 'no' ) );
$stopbadbots_enable_whitelist    = sanitize_text_field( get_option( 'stopbadbots_enable_whitelist', 'no' ) );
$stopbadbots_limit_visits        = sanitize_text_field( get_option( 'stopbadbots_limit_visits', 'no' ) );
$stopbadbots_bill_go_pro_hide    = sanitize_text_field( get_option( 'bill_go_pro_hide', '' ) );

$stopbadbots_rate404_limiting = sanitize_text_field( get_option( 'stopbadbots_rate404_limiting', 'unlimited' ) );

$stopbadbots_install_anti_hacker = sanitize_text_field( get_option( 'stopbadbots_install_anti_hacker', '' ) );

$stopbadbots_keep_log = sanitize_text_field( get_option( 'stopbadbots_keep_log', '30' ) );


$stopbadbots_update_http_tools = sanitize_text_field( get_option( 'stopbadbots_update_http_tools', 'no' ) );

$stopbadbots_install_anti_hacker = sanitize_text_field( get_option( 'stopbadbots_install_anti_hacker', 'no' ) );

$stopbadbots_install_recaptcha = sanitize_text_field( get_option( 'stopbadbots_install_recaptcha', 'no' ) );

$stopbadbots_block_china = sanitize_text_field( get_option( 'stopbadbots_block_china', 'no' ) );


// Report All
$stopbadbots_my_radio_report_all_visits = sanitize_text_field( get_option( 'stopbadbots_my_radio_report_all_visits', 'no' ) );
$stopbadbots_my_radio_report_all_visits = strtolower( $stopbadbots_my_radio_report_all_visits );

$stop_bad_bots_engine_option = sanitize_text_field( get_option( 'stop_bad_bots_engine_option', 'conservative' ) );


require_once STOPBADBOTSPATH . 'functions/functions.php';

// require_once ABSPATH . 'wp-includes/pluggable.php';
if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
require dirname( __FILE__ ) . '/includes/list-tables/class-sbb-list-table.php';
require dirname( __FILE__ ) . '/includes/list-tables/class-sbb-list-table2.php';
require dirname( __FILE__ ) . '/includes/list-tables/class-sbb-list-table3.php';
$stop_bad_bots_firewall = sanitize_text_field( get_option( 'stop_bad_bots_firewall', 'yes' ) );
if ( $stopbadbots_checkversion != '' ) {
	$stop_bad_bots_firewall = strtolower( $stop_bad_bots_firewall );
} else {
	$stop_bad_bots_firewall = 'no';
}

if ( stopbadbots_isourserver() ) {

	$stop_bad_bots_firewall = 'no';
}

require_once STOPBADBOTSPATH . 'dashboard/main.php';


//require_once STOPBADBOTSPATH . 'settings/load-plugin.php';
//require_once STOPBADBOTSPATH . 'settings/options/plugin_options_tabbed.php';

if (is_admin()) {
    //require_once(WPTOOLSPATH . 'includes/help/help.php');
	add_action('setup_theme', 'stopbadbots_load_settings');
	
	function stopbadbots_load_settings() {
		require_once(STOPBADBOTSPATH . "settings/load-plugin.php");
		require_once(STOPBADBOTSPATH . "settings/options/plugin_options_tabbed.php");
	}
	
}

if ( is_admin() or is_super_admin() ) {
	include_once STOPBADBOTSPATH . 'functions/health.php';
	function stopbadbots_add_admscripts() {

		global $stopbadbots_request_url;

		wp_enqueue_style( 'sbb-bill-datatables-jquery', STOPBADBOTSURL . 'assets/css/jquery.dataTables.min.css' );

		wp_enqueue_style( 'admin_enqueue_scripts', STOPBADBOTSURL . 'settings/styles/admin-settings.css' );

		$pos = strpos( $stopbadbots_request_url, 'page=stopbadbots' );
		if ( $pos !== false ) {

			wp_enqueue_script(
				'sbb-botstrap',
				STOPBADBOTSURL .
				'assets/js/bootstrap.bundle.min.js',
				array( 'jquery' )
			);
		}

		wp_enqueue_style( 'sbb-bill-datatables-jquery', STOPBADBOTSURL . 'assets/css/jquery.dataTables.min.css' );

		$pos  = strpos( $stopbadbots_request_url, 'page=stop_bad_bots_plugin' );
		$pos2 = strpos( $stopbadbots_request_url, 'wp-admin/index.php' );

		$pos3 = substr( $stopbadbots_request_url, -10 ) == '/wp-admin/';

		if ( $pos !== false or $pos2 !== false or $pos3 ) {
			wp_enqueue_script(
				'sbb-flot',
				STOPBADBOTSURL .
				'assets/js/jquery.flot.min.js',
				array( 'jquery' )
			);
			wp_enqueue_script(
				'sbb-flotpie',
				STOPBADBOTSURL .
				'assets/js/jquery.flot.pie.js',
				array( 'jquery' )
			);
		}

		wp_enqueue_script(
			'sbb-circle',
			STOPBADBOTSURL .
			'assets/js/radialIndicator.js',
			array( 'jquery' )
		);
		wp_enqueue_script(
			'sbb-easing',
			STOPBADBOTSURL .
			'assets/js/jquery.easing.min.js',
			array( 'jquery' )
		);
		wp_enqueue_script(
			'sbb-datatables10',
			STOPBADBOTSURL .
			'assets/js/jquery.dataTables.min.js',
			array( 'jquery' )
		);
		wp_localize_script( 'sbb-datatables10', 'datatablesajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script(
			'botstrap40',
			STOPBADBOTSURL .
			'assets/js/dataTables.bootstrap4.min.js',
			array( 'jquery' )
		);
		wp_enqueue_script(
			'sbb-datatables20',
			STOPBADBOTSURL .
			'assets/js/dataTables.buttons.min.js',
			array( 'jquery' )
		);
		wp_register_script(
			'sbb-datatables_visitors_sbb',
			STOPBADBOTSURL .
			'assets/js/stopbadbots_table.js',
			array(),
			'1.0',
			true
		);
		wp_enqueue_script( 'sbb-datatables_visitors_sbb' );
	}
	add_action( 'admin_enqueue_scripts', 'stopbadbots_add_admscripts', 1000 );
}
function stopbadbots_add_scripts() {
	wp_register_script(
		'stopbadbots-main-js',
		STOPBADBOTSURL .
		'assets/js/stopbadbots.js',
		array( 'jquery' )
	);
	wp_enqueue_script( 'stopbadbots-main-js' );

	
}
function stopbadbots_add_scripts_main() {
	wp_register_script(
		'stopbadbots-main-js',
		STOPBADBOTSURL .
		'assets/js/stopbadbots-main.js',
		array( 'jquery' )
	);
	wp_enqueue_script( 'stopbadbots-main-js' );
}

if ( is_admin() or is_super_admin() ) {
	add_action( 'admin_enqueue_scripts', 'stopbadbots_add_scripts_main' );
}

add_action( 'wp_enqueue_scripts', 'stopbadbots_add_scripts' );
   
add_action( 'admin_menu', 'sbb_add_menu_items' );
add_filter( 'set-screen-option', 'stopbadbots_set_screen_options', 10, 3 );
$stop_bad_bots_active         = sanitize_text_field( get_option( 'stop_bad_bots_active', 'yes' ) );
$stop_bad_bots_active         = strtolower( $stop_bad_bots_active );
$stop_bad_bots_ip_active      = sanitize_text_field( get_option( 'stop_bad_bots_ip_active', 'yes' ) );
$stop_bad_bots_ip_active      = strtolower( $stop_bad_bots_ip_active );
$stop_bad_bots_referer_active = sanitize_text_field( get_option( 'stop_bad_bots_referer_active', 'yes' ) );
$stop_bad_bots_referer_active = strtolower( $stop_bad_bots_referer_active );
// Report Firewall
$stopbadbots_Report_Blocked_Firewall = sanitize_text_field( get_option( 'stopbadbots_Blocked_Firewall', 'no' ) );
$stopbadbots_Report_Blocked_Firewall = strtolower( $stopbadbots_Report_Blocked_Firewall );

$stop_bad_bots_network             = sanitize_text_field( get_option( 'stop_bad_bots_network', 'yes' ) );
$stop_bad_bots_network             = strtolower( $stop_bad_bots_network );
$stop_bad_bots_blank_ua            = sanitize_text_field( get_option( 'stop_bad_bots_blank_ua', 'no' ) );
$stop_bad_bots_blank_ua            = strtolower( $stop_bad_bots_blank_ua );
$stopbadbots_block_pingbackrequest = sanitize_text_field( get_option( 'stopbadbots_block_pingbackrequest', 'no' ) );
$stopbadbots_block_enumeration     = sanitize_text_field( get_option( 'stopbadbots_block_enumeration', 'no' ) );
$stopbadbots_block_false_google    = sanitize_text_field( get_option( 'stopbadbots_block_false_google', 'no' ) );
$stopbadbots_rate_limiting         = sanitize_text_field( get_option( 'stopbadbots_rate_limiting', 'unlimited' ) );
$stopbadbots_rate_limiting_day     = sanitize_text_field( get_option( 'stopbadbots_rate_limiting_day', 'unlimited' ) );
// $stopbadbots_version = trim(sanitize_text_field(get_site_option('stopbadbots_version', '')));


$sbb_admin_email = trim( get_option( 'stopbadbots_my_email_to' ) );
if ( ! empty( $sbb_admin_email ) ) {
	if ( ! is_email( $sbb_admin_email ) ) {
		$sbb_admin_email = '';
		update_option( 'stopbadbots_my_email_to', '' );
	}
}
if ( empty( $sbb_admin_email ) ) {
	$sbb_admin_email = sanitize_text_field( get_option( 'admin_email' ) );
}
// Firewall
if ( ! is_admin() && ! is_super_admin() ) {
	if ( $stop_bad_bots_firewall != 'no' and $stopbadbots_checkversion != '' ) {
		$stopbadbots_request_uri_array   = array( '@eval', 'eval\(', 'UNION(.*)SELECT', '\(null\)', 'base64_', '\/localhost', '\%2Flocalhost', '\/pingserver', 'wp-config\.php', '\/config\.', '\/wwwroot', '\/makefile', 'crossdomain\.', 'proc\/self\/environ', 'usr\/bin\/perl', 'var\/lib\/php', 'etc\/passwd', '\/https\:', '\/http\:', '\/ftp\:', '\/file\:', '\/php\:', '\/cgi\/', '\.cgi', '\.cmd', '\.bat', '\.exe', '\.sql', '\.ini', '\.dll', '\.htacc', '\.htpas', '\.pass', '\.asp', '\.jsp', '\.bash', '\/\.git', '\/\.svn', ' ', '\<', '\>', '\/\=', '\.\.\.', '\+\+\+', '@@', '\/&&', '\/Nt\.', '\;Nt\.', '\=Nt\.', '\,Nt\.', '\.exec\(', '\)\.html\(', '\{x\.html\(', '\(function\(', '\.php\([0-9]+\)', '(benchmark|sleep)(\s|%20)*\(', 'indoxploi', 'xrumer' );
		$stopbadbots_query_string_array  = array( '@@', '\(0x', '0x3c62723e', '\;\!--\=', '\(\)\}', '\:\;\}\;', '\.\.\/', '127\.0\.0\.1', 'UNION(.*)SELECT', '@eval', 'eval\(', 'base64_', 'localhost', 'loopback', '\%0A', '\%0D', '\%00', '\%2e\%2e', 'allow_url_include', 'auto_prepend_file', 'disable_functions', 'input_file', 'execute', 'file_get_contents', 'mosconfig', 'open_basedir', '(benchmark|sleep)(\s|%20)*\(', 'phpinfo\(', 'shell_exec\(', '\/wwwroot', '\/makefile', 'path\=\.', 'mod\=\.', 'wp-config\.php', '\/config\.', '\$_session', '\$_request', '\$_env', '\$_server', '\$_post', '\$_get', 'indoxploi', 'xrumer' );
		$stopbadbots_request_uri_string  = false;
		$stopbadbots_query_string_string = false;
		if ( isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$stopbadbots_request_uri_string = sanitize_text_field($_SERVER['REQUEST_URI']);
		}
		if ( isset( $_SERVER['QUERY_STRING'] ) && ! empty( $_SERVER['QUERY_STRING'] ) ) {
			$stopbadbots_query_string_string = sanitize_text_field($_SERVER['QUERY_STRING']);
		}
		if ( $stopbadbots_request_uri_string || $stopbadbots_query_string_string ) {
			if ( preg_match( '/' . implode( '|', $stopbadbots_request_uri_array ) . '/i', $stopbadbots_request_uri_string, $matches )
				|| preg_match( '/' . implode( '|', $stopbadbots_query_string_array ) . '/i', $stopbadbots_query_string_string, $matches2 )
			) {
				sbb_stats_moreone( 'qfire' );
				if ( $stopbadbots_Report_Blocked_Firewall == 'yes' ) {
					if ( isset( $matches ) ) {
						if ( is_array( $matches ) ) {
							if ( count( $matches ) > 0 ) {
								sbb_alertme3( $matches[0] );
							}
						}
					}
					if ( isset( $matches2 ) ) {
						if ( is_array( $matches2 ) ) {
							if ( count( $matches2 ) > 0 ) {
								sbb_alertme3( $matches2[0] );
							}
						}
					}
				}
				stopbadbots_response( 'Firewall' );
				// wp_die("");
			} // Endif match...
		} // endif if ($stopbadbots_query_string_string || $user_agent_string)
	} // firewall <> no
}
if ( ! empty( $stopbadbots_userAgent ) and ! is_admin() and ! is_super_admin() and ! sbb_block_whitelist_string() and ! sbb_block_whitelist_IP() ) {
	if ( sbbcrawlerDetect( $stopbadbots_userAgent ) and $stop_bad_bots_active != 'no' ) {
		sbbmoreone( $stopbadbots_userAgentOri ); // +1
		sbb_stats_moreone( 'qnick' );
		if ( $stopbadbots_my_radio_report_all_visits == 'yes' ) {
			sbb_alertme( $stopbadbots_userAgentOri );
		}
		sbb_complete_bot_data( $sbb_found );
		if ( $stop_bad_bots_network != 'no' ) {
			upload_new_bots();
		}
		stopbadbots_response( 'Blocked by Name' );
	}
}
if ( ! empty( $stopbadbotsip ) and ! is_admin() and ! is_super_admin() ) {
	if ( sbbvisitoripDetect( $stopbadbotsip ) and $stop_bad_bots_ip_active != 'no' and ! sbb_block_whitelist_string() and ! sbb_block_whitelist_IP() ) {
		sbbmoreone2( $stopbadbotsip ); // +1
		sbb_stats_moreone( 'qip' );
		if ( $stopbadbots_my_radio_report_all_visits == 'yes' ) {
			sbb_alertme2( $stopbadbotsip );
		}
		stopbadbots_response( 'Blocked By IP' );
		// wp_die();
	}
}
// Block HTTP_tools
if ( ! empty( $stopbadbots_userAgent ) and ! is_admin() and ! is_super_admin() and ! sbb_block_whitelist_string() and ! sbb_block_whitelist_IP() ) {
	if ( ! empty( sbb_block_httptools() ) and $stopbadbots_block_http_tools != 'no' ) {
		sbbmoreone_http( sbb_block_httptools() ); // +1
		sbb_stats_moreone( 'qtools' );
		if ( $stopbadbots_my_radio_report_all_visits == 'yes' ) {
			sbb_alertme12( sbb_block_httptools() );
		}
		stopbadbots_response( 'HTTP Tools' );
		// wp_die();
	}
}



/* ------------ July 2021 ------------------- */

// -------------------------  Step 2
$pos = stripos( $stopbadbots_request_url, '_grava_fingerprint' );

function stopbadbots_check_ip_api( $ip ) {
	// LIMIT 45 minut
	$urlcurl = 'http://ip-api.com/json/' . $ip . '?fields=timezone,status,hosting,proxy';
	$data    = array(
		'ip' => $ip,
	);
	try {

		/*
		$ch = curl_init($urlcurl);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		*/

		$response = wp_remote_post(
			$url,
			array(
				'method'      => 'POST',
				'timeout'     => 10,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(),
				'body'        => $data,
				'cookies'     => array(),
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$r = json_decode( $result, true );

		if ( gettype( $r ) == 'array' ) {

			// falhou !
			if ( $r['status'] != 'success' ) {
				return false;
			}

			if ( $r['hosting'] == true ) {
				return true;
			}

			if ( $r['proxy'] == true ) {
				return true;
			}

			// if (strpos($r["timezone"], '#UTC#0') === false)
			// return true;
		}
	} catch ( Exception $e ) {

		return false;
	}
	return false;
}


if ( ! $stopbadbots_maybe_search_engine
	and ! sbb_block_whitelist_string()
	and $pos === false
	and ! stopbadbots_isourserver()
	and ! is_admin()
	and ! is_super_admin()
) {


	if ( $stopbadbots_is_human != '1' ) {


		// Chrome and firefox old and browser == linux
		$stopbadbots_ua_browser = stopbadbots_find_ua_browser( $stopbadbots_userAgentOri );
		$stopbadbots_ua_version = stopbadbots_find_ua_version( $stopbadbots_userAgentOri, $stopbadbots_ua_browser );

		$stopbadbots_ua_os = stopbadbots_find_ua_os( $stopbadbots_userAgentOri );

		$stopbadbots_template = false;

		if ( $stopbadbots_ua_os == 'Linux' ) {
			$stopbadbots_template = true;
		}


		if ( $stopbadbots_ua_browser == 'Chrome' and ! empty( $stopbadbots_ua_version ) ) {
			if ( version_compare( $stopbadbots_ua_version, STOPBADBOTS_CHROME ) <= 0 ) {
				$stopbadbots_template = true;
			}
		}

		if ( $stopbadbots_ua_browser == 'Firefox' and ! empty( $stopbadbots_ua_version ) ) {
			if ( version_compare( $stopbadbots_ua_version, STOPBADBOTS_FIREFOX ) <= 0 ) {
				$stopbadbots_template = true;
			}
		}

		/*
		if ($stopbadbots_ua_browser == 'Safari' and !empty($stopbadbots_ua_version))
				if (version_compare($stopbadbots_ua_version, '14') <= 0)
					$stopbadbots_template = true;
		*/

		if ( $stopbadbots_ua_browser == 'MSIE' and ! empty( $stopbadbots_ua_version ) ) {
			if ( version_compare( $stopbadbots_ua_version, '11' ) <= 0 ) {
				$stopbadbots_template = true;
			}
		}


		// second time...
		if ( $stopbadbots_is_human == '0' ) {
			$stopbadbots_template = true;
		}


		add_action( 'template_redirect', 'stopbadbots_final_step' );




		if ( $stop_bad_bots_engine_option == 'maximum' ) {
			$stopbadbots_template = true;
		}

		// Check host...
		if ( $stopbadbots_template ) {
			if ( ! isset( $_COOKIE['_ga'] ) and ! isset( $_COOKIE['__utma'] ) ) {

				/*
				// if(!stopbadbots_plugin_is_active('reCAPTCHA For All') and !stopbadbots_plugin_is_active('logplugin.php'))
				if (!stopbadbots_plugin_is_active('reCAPTCHA For All'))
				add_filter('template_include', 'stopbadbots_template_include');
				*/


				if ( $stop_bad_bots_engine_option != 'conservative' ) {

					if ( stopbadbots_is_bad_hosting( $stopbadbotsip ) ) {
						stopbadbots_add_temp_ip();
						sbb_stats_moreone( 'qbrowser' );
						if ( $stopbadbots_my_radio_report_all_visits == 'yes' ) {
							sbb_alertme14( $stopbadbotsip );
						}
						stopbadbots_record_log( 'Blocked Fake Browser (1)' );
						header( 'HTTP/1.1 403 Forbidden' );
						header( 'Status: 403 Forbidden' );
						header( 'Connection: Close' );
						die();
					}

					if ( stopbadbots_is_bad_hosting2( $stopbadbotsip ) ) {
						stopbadbots_add_temp_ip();
						sbb_stats_moreone( 'qbrowser' );
						if ( $stopbadbots_my_radio_report_all_visits == 'yes' ) {
							sbb_alertme14( $stopbadbotsip );
						}
						stopbadbots_record_log( 'Blocked Fake Browser (2)' );
						header( 'HTTP/1.1 403 Forbidden' );
						header( 'Status: 403 Forbidden' );
						header( 'Connection: Close' );
						die();
					}
				}

				if ( $stop_bad_bots_engine_option == 'maximum' ) {
					function stoppadbots_page_template() {
						return STOPBADBOTSPATH . 'template/content_stopbadbots.php';
					}
					add_filter( 'template_include', 'stoppadbots_page_template' );
					header( 'Refresh: 3;' );
				}
			}
		}
	}   // if ($stopbadbots_is_human == '1')
}



/*   ------------------------------     END STEP 2 */

/* ------------ End July 2021 ------------------- */


function sbb_render_list_page() {
	$test_list_table = new sbb_List_Table();
	$test_list_table->sbb_prepare_items();
	include dirname( __FILE__ ) . '/includes/list-tables/page.php';
}
function sbb_render_list_page2() {
	$stopbadbots_list_table2 = new sbb_List_Table2();
	$stopbadbots_list_table2->sbb_prepare_items2();
	include dirname( __FILE__ ) . '/includes/list-tables/page2.php';
}
function sbb_render_list_page3() {
	$stopbadbots_list_table3 = new sbb_List_Table3();
	$stopbadbots_list_table3->sbb_prepare_items3();
	include dirname( __FILE__ ) . '/includes/list-tables/page3.php';
}
register_activation_hook( __FILE__, 'sbb_plugin_was_activated' );
add_action( 'admin_menu', 'sbb_add_admin_menu' );
add_action( 'admin_menu', 'sbb_add_admin_menu2' );
add_action( 'admin_menu', 'sbb_add_admin_menu3' );
add_action( 'admin_init', 'sbb_settings_init' );
add_action( 'admin_init', 'sbb_settings2_init' );
add_action( 'admin_init', 'sbb_settings3_init' );
function stopbadbots_load_feedback() {
	if ( is_admin() or is_super_admin() ) {
		if ( file_exists( STOPBADBOTSPATH . 'includes/feedback/feedback.php' ) ) {
			include_once STOPBADBOTSPATH . 'includes/feedback/feedback.php';
		}
		if ( file_exists( STOPBADBOTSPATH . 'includes/feedback/feedback-last.php' ) ) {
			include_once STOPBADBOTSPATH . 'includes/feedback/feedback-last.php';
		}
	}
}
add_action( 'wp_loaded', 'stopbadbots_load_feedback' );
function stopbadbots_load_activate() {
	if ( is_admin() or is_super_admin() ) {
		// require_once STOPBADBOTSPATH . 'includes/feedback/activated-manager.php';
	}
}
add_action( 'in_admin_footer', 'stopbadbots_load_activate' );
// $buffer = ob_get_flush();
// add_action('admin_menu', 'sbb_add_menu_items9');
function sbb_custom_dashboard_help() {
	global $stopbadbots_checkversion;
	$perc = stopbadbots_find_perc();
	if ( $perc < 70 ) {
		$color = '#ff0000';
	} else {
		$color = '#000000';
	}
	echo '<img src="' . esc_url( STOPBADBOTSURL ) . '/images/logo.png" style="text-align:center; max-width: 200px;margin: 0px 0 auto;"  />';
	echo '<br />';
	echo '<br />';
	if ( $stopbadbots_checkversion == '' ) {
		echo '<img src="' . esc_url( STOPBADBOTSURL ) . '/assets/images/unlock-icon-red-small.png" style="text-align:center; max-width: 20px;margin: 0px 0 auto;"  />';
		echo '<h2 style="margin-top: -39px; margin-left: 30px; color:' . esc_attr( $color ) . '; font-weight: bold;" >';
	} else {
		echo '<h2 style="margin-top: -22px; margin-left: update0px; color:' . esc_attr( $color ) . '; font-weight: bold;">';
	}
	echo '<span style = "color:' . esc_attr( $color ) . '">';
	echo esc_attr__('Protection rate:','stopbadbots').' '. esc_attr( $perc ) . '%';
	echo '</h2>';
	$site = STOPBADBOTSHOMEURL . 'admin.php?page=stop_bad_bots_plugin';
	// echo 'For details, visit the plugin dashboard.';
	echo '<h3><a href="' . esc_url( $site ) . '">'.esc_attr__("For details, visit the plugin dashboard","stopbadbots").'</a></h3>';
	echo '<br />';
	echo '<center><strong><big>'.esc_attr__("Attacks Blocked Last 15 days","stopbadbots").'</big></strong></center>';
	echo '<br />';
	include_once 'dashboard/botsgraph.php';
	echo '<br />';
	echo '<hr>';
	echo '<br />';
	echo '<br />';
	echo '<center><strong><big>'.esc_attr__("Total Attacks Blocked By Type","stopbadbots").'</big></strong></center>';
	echo '<br />';
	include_once 'dashboard/botsgraph_pie.php';
	echo '<br />';
	echo '<br />';
	echo '<br />';
	echo '<br />';
	echo '<hr>';
	echo '<br />';
	echo '<center><strong><big>'. esc_attr__("Total Attacks Blocked By IP","stopbadbots").'</big></strong></center>';
	echo '<br />';
	include_once 'dashboard/topips.php';
	echo '<br />';
	echo '<br />';
	$site = esc_url( STOPBADBOTSHOMEURL ) . 'admin.php?page=stop_bad_bots_plugin';
	echo '<a href="' . esc_attr( $site ) . '" class="button button-primary">Details</a>';
	echo '<br /><br />';
	// echo esc_html($bd_msg);
	echo '</p>';
}
function sbb_add_dashboard_widgets() {
	// wp_add_dashboard_widget('stopbadbots-dashboard', 'Stop Bad Bots Activities', 'sbb_custom_dashboard_help', 'dashboardsbb', 'normal', 'high');
	wp_add_dashboard_widget( 'stopbadbots-dashboard', 'Stop Bad Bots Activities', 'sbb_custom_dashboard_help' );
}
if ( is_admin() or is_super_admin() ) {
	$pos2 = strpos( $stopbadbots_request_url, 'wp-admin/index.php' );
	$pos3 = strpos( $stopbadbots_request_url, 'page=' );
	$pos4 = strpos( $stopbadbots_request_url, 'stop_bad_bots_plugin' );
	if ( $pos2 !== false or ( $pos3 !== false and $pos4 !== false ) ) {
		add_action( 'wp_dashboard_setup', 'sbb_add_dashboard_widgets' );
	}
}
// Bad Referer
function stopbadbots_get_referer() {
	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
		$stopbadbots_referer = sanitize_text_field( $_SERVER['HTTP_REFERER'] );

		$stopbadbots_referer = trim( parse_url( $stopbadbots_referer, PHP_URL_HOST ) );
		if ( gettype( $stopbadbots_referer ) == 'string' ) {
			return $stopbadbots_referer;
		} else {
			return '';
		}
	} else {
		return '';
	}
}

if ( $stop_bad_bots_referer_active != 'no' ) {

	$badreferer = '';
	if ( sbbReferDetect( $stopbadbots_referer ) and ! is_admin() and ! is_super_admin() and ! sbb_block_whitelist_string() and ! sbb_block_whitelist_IP() ) {
		global $badreferer;
		sbbmoreone4( $badreferer ); // +1
		sbb_stats_moreone( 'qref' );
		if ( $stopbadbots_my_radio_report_all_visits == 'yes' ) {
			sbb_alertme4( $badreferer );
		}
		/*
		if($stop_bad_bots_network != 'no')
		upload_new_badreferer();
		exit;
		 */
		stopbadbots_response( 'Bad Referrer' );
	}
}
if ( $stop_bad_bots_blank_ua == 'yes' and ! is_admin() and ! is_super_admin() ) {


	if ( !stopbadbots_isourserver() ) {
		if ( empty( trim( $stopbadbots_userAgentOri ) ) ) {
			sbb_stats_moreone( 'qua' );
			if ( $stopbadbots_my_radio_report_all_visits == 'yes' ) {
				sbb_alertme5();
			}
			stopbadbots_response( 'Blank User Agent' );
		}
    }



}
if ( ! is_admin() and ! is_super_admin() ) {
	if ( $stopbadbots_block_pingbackrequest == 'yes' ) {
		add_action( 'xmlrpc_call', 'stopbadbots_block_pingback_hook' );
	}
	if ( $stopbadbots_block_enumeration == 'yes' ) {
		stopbadbots_block_enumeration();
	}
	if ( $stopbadbots_block_false_google == 'yes' ) {
		if ( stopbadbots_check_false_googlebot() ) {
			sbb_stats_moreone( 'qother' );
			if ( $stopbadbots_my_radio_report_all_visits == 'yes' ) {
				sbb_alertme8();
			}
			stopbadbots_response( 'False Google MSN/Bing or Yahoo Bot' );
		}
	}
}
function stop_bad_bots_init() {
	 global $stopbadbots_bill_go_pro_hide;
	$stop_bad_bots_today = date( 'Ymd', strtotime( '+0 days' ) );
	if ( $stopbadbots_bill_go_pro_hide < $stop_bad_bots_today or $stopbadbots_bill_go_pro_hide == '' ) {
		echo '<script type="text/javascript">
            jQuery(document).ready(function() {
            jQuery(".sbb_bill_go_pro_container").css("display", "block");
            }); // end (jQuery);
            </script>';
	} else {
		echo '<script type="text/javascript">
            jQuery(document).ready(function() {
            jQuery(".sbb_bill_go_pro_container").css("display", "none");
            }); // end (jQuery);
            </script>';
	}
}
add_action( 'admin_notices', 'stop_bad_bots_init' );
add_action( 'wp_ajax_stopbadbots_bill_go_pro_hide', 'stopbadbots_bill_go_pro_hide' );
// update_option('stopbadbots_bill_go_pro_hide','');
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
function stopbadbots_end_flush() {
	$levels = ob_get_level();
	for ( $i = 0; $i < $levels; $i++ ) {
		if ( ob_get_contents() ) {
			// ob_flush();
			if ( $i == 0 ) {
				@ob_end_flush();
			} else {
				ob_end_flush();
			}
		}
	}
}
add_action( 'shutdown', 'stopbadbots_end_flush', 10, 0 );
require_once STOPBADBOTSPATH . 'table/visitors.php';
add_action( 'admin_menu', 'sbb_add_menu_items9' );
add_action( 'wp_ajax_stopbadbots_add_whitelist', 'stopbadbots_add_whitelist' );
add_action( 'wp_ajax_nopriv_stopbadbots_add_whitelist', 'stopbadbots_add_whitelist' );

if ( ( $stopbadbots_install_anti_hacker != 'no' or $stopbadbots_install_recaptcha != 'no' ) and is_admin() ) {
	include_once STOPBADBOTSPATH . '/includes/tgm/pinstaller.php';
}

function stopbadbots_custom_toolbar_link( $wp_admin_bar ) {
	global $wp_admin_bar;
	$site = STOPBADBOTSHOMEURL . 'admin.php?page=stop_bad_bots_plugin&tab=notifications';
	$args = array(
		'id'    => 'stopbadbots',
		'title' => '<div class="stopbadbots-logo"></div><span class="text"> Stop Bad Bots</span>',
		'href'  => $site,
		'meta'  => array(
			'class' => 'stopbadbots',
			'title' => '',
		),
	);
	$wp_admin_bar->add_node( $args );
	echo '<style>';
	echo '#wpadminbar .stopbadbots  {
      background: red !important;
      color: black !important;
    }';
	$logourl = STOPBADBOTSIMAGES . '/sologo-gray.png';
	echo '#wpadminbar .stopbadbots-logo  {
      background-image: url("' . esc_url( $logourl ) . '");
      float: left;
      width: 26px;
      height: 30px;
      background-repeat: no-repeat;
      background-position: 0 6px;
      background-size: 20px;
    }';
	echo '</style>';
}

$stopbadbots_timeout_level = time() > ( $stopbadbots_notif_level + 60 * 60 * 24 * 7 );
// $stopbadbots_timeout_level = time() > ($stopbadbots_notif_level + 10 );

if ( $stopbadbots_timeout_level ) {

	if ( stopbadbots_find_perc() < 80 ) {
		$stopbadbots_timeout_level = true;
	} else {
		$stopbadbots_timeout_level = false;
	}
}

// var_dump($stopbadbots_timeout_level);

if ( $stopbadbots_timeout_level or $stop_bad_bots_active != 'yes' or $stop_bad_bots_ip_active != 'yes' or $stop_bad_bots_referer_active != 'yes' ) {
	if ( ! is_multisite() and is_admin() ) {
		add_action( 'admin_bar_menu', 'stopbadbots_custom_toolbar_link', 999 );
	}
}
//
// require_once STOPBADBOTSPATH . "functions/functions_api.php";
function stopbadbots_add_cors_http_header() {
	header( 'Access-Control-Allow-Origin: https://stopbadbots.com' );
}



if ( is_admin() or is_super_admin() ) {

	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	// 'plugin-directory/plugin-file.php'
	$myplugin = 'antihacker/antihacker.php';
	if ( ! is_plugin_active( $myplugin ) ) {
		if ( strpos( $stopbadbots_request_url, 'plugins.php?page=tgmpa-install-plugins' ) === false ) {
			if ( $stopbadbots_install_anti_hacker == 'yes' ) {
				// var_dump($stopbadbots_install_anti_hacker);
				$plugin_required = 'Anti Hacker';
				add_action( 'admin_notices', 'stop_bad_bots_install_required_extensions' );
			}
		}
	}
	$myplugin = 'recaptcha-for-all/recaptcha.php';
	if ( ! is_plugin_active( $plugin ) ) {
		if ( strpos( $stopbadbots_request_url, 'plugins.php?page=tgmpa-install-plugins' ) === false ) {
			if ( $stopbadbots_install_recaptcha == 'yes' ) {
				$plugin_required = 'Recaptcha For All';
				add_action( 'admin_notices', 'stop_bad_bots_install_required_extensions' );
			}
		}
	}
}

function stop_bad_bots_install_required_extensions() {
	// return;
	global $plugin_required;
	if ( empty( $plugin_required ) ) {
		return;
	}
	echo '<div class="notice notice-warning is-dismissible">';
	echo '<br /><b>';
	echo esc_attr__( 'Message from Stop Bad Bots Plugin', 'stopbadbots' );
	echo ':</b><br />';
	echo esc_attr__( 'To Install the extension:', 'stopbadbots' );
	echo ' ' . esc_html( $plugin_required );
	echo '<br />';
	echo ' <a class="button button-primary" href="plugins.php?page=tgmpa-install-plugins">';
	echo esc_attr__( 'click here', 'stopbadbots' );
	echo '</a>';
	echo '<br /><br /></div>';
}
function stopbadbots_more_plugins() {
	echo '<script>';
	echo 'window.location.replace("' . esc_url_raw( STOPBADBOTSHOMEURL ) . 'plugin-install.php?s=sminozzi&tab=search&type=author");';
	echo '</script>';
}


/*
function stopbadbots_more_plugins2()
{
	$url = esc_attr(STOPBADBOTSHOMEURL) . "plugin-install.php?s=sminozzi&tab=search&type=author";
?>
	<script>
		window.location.replace("<?php echo esc_url($url); ?>");
	</script>
<?php
}
*/
function stopbadbots_add_more_plugins() {
	if ( is_multisite() ) {
		add_submenu_page(
			'stop_bad_bots_plugin', // $parent_slug
			'More Tools Same Author', // string $page_title
			'More Tools Same Author', // string $menu_title
			'manage_options', // string $capability
			'stopbadbots_more_plugins', // menu slug
			'stopbadbots_more_plugins', // callable function
			11 // position
		);
	} else {

		add_submenu_page(
			'stop_bad_bots_plugin', // $parent_slug
			'More Tools Same Author', // string $page_title
			'More Tools Same Author', // string $menu_title
			'manage_options', // string $capability
			// 'wptools_options39', // menu slug
			// 'wptools_new_more_plugins', // callable function
			'stopbadbots_new_more_plugins', // menu slug
			'stopbadbots_new_more_plugins', // callable function
			33 // position
		);
	}
}
add_action( 'admin_menu', 'stopbadbots_add_more_plugins' );

/* =============================== */
function stopbadbots_new_more_plugins() {
	stopbadbots_show_logo();
	$plugins_to_install                   = array();
	$plugins_to_install[0]['Name']        = 'Anti Hacker Plugin';
	$plugins_to_install[0]['Description'] = 'Firewall, Scanner, Login Protect, block user enumeration and TOR, disable Json WordPress Rest API, xml-rpc (xmlrpc) & Pingback and more security tools...';
	$plugins_to_install[0]['image']       = 'https://ps.w.org/antihacker/assets/icon-256x256.gif?rev=2524575';
	$plugins_to_install[0]['slug']        = 'antihacker';
	$plugins_to_install[1]['Name']        = 'Stop Bad Bots';
	$plugins_to_install[1]['Description'] = 'Stop Bad Bots, Block SPAM bots, Crawlers and spiders also from botnets. Save bandwidth, avoid server overload and content steal. Blocks also by IP.';
	$plugins_to_install[1]['image']       = 'https://ps.w.org/stopbadbots/assets/icon-256x256.gif?rev=2524815';
	$plugins_to_install[1]['slug']        = 'stopbadbots';
	$plugins_to_install[2]['Name']        = 'WP Tools';
	$plugins_to_install[2]['Description'] = 'More than 35 useful tools! It is a swiss army knife, to take your site to the next level.';
	$plugins_to_install[2]['image']       = 'https://ps.w.org/wptools/assets/icon-256x256.gif?rev=2526088';
	$plugins_to_install[2]['slug']        = 'wptools';
	$plugins_to_install[3]['Name']        = 'reCAPTCHA For All';
	$plugins_to_install[3]['Description'] = 'Protect ALL Pages of your site against bots (spam, hackers, fake users and other types of automated abuse)
	with invisible reCaptcha V3 (Google). You can also block visitors from China.';
	$plugins_to_install[3]['image']       = 'https://ps.w.org/recaptcha-for-all/assets/icon-256x256.gif?rev=2544899';
	$plugins_to_install[3]['slug']        = 'recaptcha-for-all';
	$plugins_to_install[4]['Name']        = 'WP Memory';
	$plugins_to_install[4]['Description'] = 'Check High Memory Usage, Memory Limit, PHP Memory, show result in Site Health Page and fix php low memory limit.';
	$plugins_to_install[4]['image']       = 'https://ps.w.org/wp-memory/assets/icon-256x256.gif?rev=2525936';
	$plugins_to_install[4]['slug']        = 'wp-memory';
	$plugins_to_install[5]['Name']        = 'Truth Social';
	$plugins_to_install[5]['Description'] = 'Tools and feeds for Truth Social new social media platform and Twitter.';
	$plugins_to_install[5]['image']       = 'https://ps.w.org/toolstruthsocial/assets/icon-256x256.png?rev=2629666';
	$plugins_to_install[5]['slug']        = 'toolstruthsocial';
	?>
	<div style="padding-right:20px;">
		<br>
		<h1>Useful FREE Plugins of the same author</h1>
		<div id="bill-wrap-install" class="bill-wrap-install" style="display:none">
			<h3>Please wait</h3>
			<big>
				<h4>
					Installing plugin <div id="billpluginslug">...</div>
				</h4>
			</big>
			<img src="/wp-admin/images/wpspin_light-2x.gif" id="billimagewaitfbl" style="display:none;margin-left:0px;margin-top:0px;" />
			<br />
		</div>
		<table style="margin-right:20px; border-spacing: 0 25px; " class="widefat" cellspacing="0" id="stopbadbots-more-plugins-table">
			<tbody class="stopbadbots-more-plugins-body">
				<?php
				$counter = 0;
				$total   = count( $plugins_to_install );
				for ( $i = 0; $i < $total; $i++ ) {
					if ( $counter % 2 == 0 ) {
						echo '<tr style="background:#f6f6f1;">';
					}
					++$counter;
					if ( $counter % 2 == 1 ) {
						echo '<td style="max-width:140px; max-height:140px; padding-left: 40px;" >';
					} else {
						echo '<td style="max-width:140px; max-height:140px;" >';
					}
					echo '<img style="width:100px;" src="' . esc_url( $plugins_to_install[ $i ]['image'] ) . '">';
					echo '</td>';
					echo '<td style="width:40%;">';
					echo '<h3>' . esc_attr( $plugins_to_install[ $i ]['Name'] ) . '</h3>';
					echo esc_attr( $plugins_to_install[ $i ]['Description'] );
					echo '<br>';
					echo '</td>';
					echo '<td style="max-width:140px; max-height:140px;" >';
					if ( stopbadbots_plugin_installed( $plugins_to_install[ $i ]['slug'] ) ) {
						echo '<a href="#" class="button activate-now">Installed</a>';
					} else {
						echo '<a href="#" id="' . esc_attr( $plugins_to_install[ $i ]['slug'] ) . '"class="button button-primary bill-install-now">Install</a>';
					}
					echo '</td>';
					if ( $counter % 2 == 1 ) {
						echo '<td style="width; 100px; border-left: 1px solid gray;">';
						echo '</td>';
					}
					if ( $counter % 2 == 0 ) {
						echo '</tr>';
					}
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
}
function stopbadbots_plugin_installed( $slug ) {
	$all_plugins = get_plugins();
	foreach ( $all_plugins as $key => $value ) {
		$plugin_file    = $key;
		$slash_position = strpos( $plugin_file, '/' );
		$folder         = substr( $plugin_file, 0, $slash_position );
		// match FOLDER against SLUG
		if ( $slug == $folder ) {
			return true;
		}
	}
	return false;
}



function stopbadbots_load_upsell() {
	global $stopbadbots_checkversion;

	wp_enqueue_style( 'stopbadbots-more2', STOPBADBOTSURL . 'includes/more/more2.css' );
	wp_register_script( 'stopbadbots-more2-js', STOPBADBOTSURL . 'includes/more/more2.js', array( 'jquery' ) );
	wp_enqueue_script( 'stopbadbots-more2-js' );

	if ( ! empty( $stopbadbots_checkversion ) ) {
		return;
	}

	if(isset($_COOKIE["sbb_dismiss"])) {

		$today = time();
		if (!update_option('bill_go_pro_hide', $today))
			add_option('bill_go_pro_hide', $today);
	  }

	$stopbadbots_bill_go_pro_hide = trim( get_option( 'bill_go_pro_hide', '' ) );
	// $stopbadbots_bill_go_pro_hide = '';
	// Debug ...


	if(strlen($stopbadbots_bill_go_pro_hide) < 10)
	   $stopbadbots_bill_go_pro_hide = strtotime($stopbadbots_bill_go_pro_hide);
  

	if ( empty( trim( $stopbadbots_bill_go_pro_hide ) ) ) {

		// $wtime = strtotime('-5 days');
		$wtime = time() - ( 3600 * 24 * 5 );
		update_option( 'bill_go_pro_hide', $wtime );
		$stopbadbots_bill_go_pro_hide = $wtime;
		$delta                        = 0;
	} else {

		$now   = time();
		$delta = $now - $stopbadbots_bill_go_pro_hide;
	}


	//$delta = time();
	//die();

	// debug
	// 
	// $delta = time();
	// $delta = 0;
	if ( $delta > ( 3600 * 24 * 14 ) ) {

		$list = 'enqueued';
		if ( ! wp_script_is( 'bill-css-vendor-fix', $list ) ) {
			include_once STOPBADBOTSPATH . 'includes/vendor/vendor.php';
			wp_enqueue_style( 'bill-css-vendor-fix', STOPBADBOTSURL . 'includes/vendor/vendor_fix.css' );

			wp_register_script( 'bill-js-vendor', STOPBADBOTSURL . 'includes/vendor/vendor.js', array( 'jquery' ), STOPBADBOTSVERSION, true );
			wp_enqueue_script( 'bill-js-vendor' );
		}
	}

	wp_register_script( 'bill-js-vendor-sidebar', STOPBADBOTSURL . 'includes/vendor/vendor-sidebar.js', array( 'jquery' ), STOPBADBOTSVERSION, true );
	wp_enqueue_script( 'bill-js-vendor-sidebar' );

	wp_enqueue_style( 'bill-css-vendor-sbb', STOPBADBOTSURL . 'includes/vendor/vendor.css' );
}




if ( ! function_exists( 'wp_get_current_user' ) ) {
	include_once ABSPATH . 'wp-includes/pluggable.php';
}


if ( is_admin() or is_super_admin() ) {

	add_action( 'admin_enqueue_scripts', 'stopbadbots_load_upsell' );

	add_action( 'wp_ajax_stopbadbots_install_plugin', 'stopbadbots_install_plugin' );
}




function stopbadbots_install_plugin() {
	if ( isset( $_POST['slug'] ) ) {
		$slug = sanitize_text_field( $_POST['slug'] );
	} else {
		echo 'Fail error (-5)';
		wp_die();
	}
	$plugin['source'] = 'repo'; // $_GET['plugin_source']; // Plugin source.
	include_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // Need for plugins_api.
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php'; // Need for upgrade classes.
	// get plugin information
	$api = plugins_api(
		'plugin_information',
		array(
			'slug'   => $slug,
			'fields' => array( 'sections' => false ),
		)
	);
	if ( is_wp_error( $api ) ) {
		echo 'Fail error (-1)';
		wp_die();
		// proceed
	} else {
		// Set plugin source to WordPress API link if available.
		if ( isset( $api->download_link ) ) {
			$plugin['source'] = $api->download_link;
			$source           = $api->download_link;
		} else {
			echo 'Fail error (-2)';
			wp_die();
		}
		$nonce = 'install-plugin_' . $api->slug;
		/*
		$type = 'web';
		$url = $source;
		$title = 'wptools';
		*/
		$plugin = $slug;
		// verbose...
		// $upgrader = new Plugin_Upgrader($skin = new Plugin_Installer_Skin(compact('type', 'title', 'url', 'nonce', 'plugin', 'api')));
		class stopbadbots_QuietSkin extends \WP_Upgrader_Skin {

			public function feedback( $string, ...$args ) {
				/* no output */
			}
			public function header() {
				/* no output */
			}
			public function footer() {
				/* no output */
			}
		}
		$skin     = new stopbadbots_QuietSkin( array( 'api' => $api ) );
		$upgrader = new Plugin_Upgrader( $skin );
		// var_dump($upgrader);
		try {
			$upgrader->install( $source );
			// get all plugins
			$all_plugins = get_plugins();
			// scan existing plugins
			foreach ( $all_plugins as $key => $value ) {
				// get full path to plugin MAIN file
				// folder and filename
				$plugin_file    = $key;
				$slash_position = strpos( $plugin_file, '/' );
				$folder         = substr( $plugin_file, 0, $slash_position );
				// match FOLDER against SLUG
				// if matched then ACTIVATE it
				if ( $slug == $folder ) {
					// Activate
					$result = activate_plugin( ABSPATH . 'wp-content/plugins/' . $plugin_file );
					if ( is_wp_error( $result ) ) {
						// Process Error
						echo 'Fail error (-3)';
						wp_die();
					}
				} // if matched
			}
		} catch ( Exception $e ) {
			echo 'Fail error (-4)';
			wp_die();
		}
	} // activation
	echo 'OK';
	wp_die();
}

function stopbadbots_show_logo() {
	echo '<div id="stopbadbots_logo" style="margin-top:10px;">';
	// echo '<br>';
	echo '<img src="';
	echo esc_url( STOPBADBOTSIMAGES ) . '/logo.png';
	// https://boatplugin.com/wp-content/plugins/stopbadbots/assets/images/logo.png
	echo '">';
	echo '<br>';
	echo '</div>';
}




function stopbadbots_bill_go_pro_hide2() {
	// $today = date('Ymd', strtotime('+06 days'));
	$today = time();
	if ( ! update_option( 'bill_go_pro_hide', $today ) ) {
		add_option( 'bill_go_pro_hide', $today );
	}
			   wp_die();
}
add_action( 'wp_ajax_stopbadbots_bill_go_pro_hide2', 'stopbadbots_bill_go_pro_hide2' );
