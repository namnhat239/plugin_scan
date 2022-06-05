<?php
if (!defined('WHMCS_BRIDGE')) define('WHMCS_BRIDGE','WHMCS Bridge');
if (!defined('WHMCS_BRIDGE_COMPANY')) define('WHMCS_BRIDGE_COMPANY','i-Plugins');
if (!defined('WHMCS_BRIDGE_PAGE')) define('WHMCS_BRIDGE_PAGE','WHMCS');

define("CC_WHMCS_BRIDGE_VERSION","6.7b");

if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}


$compatibleWHMCSBridgeProVersions=array('2.0.1'); //kept for compatibility with older Pro versions, not used since version 2.0.0

// Pre-2.6 compatibility for wp-content folder location

if (!defined("WP_CONTENT_URL")) {
    define("WP_CONTENT_URL", get_option("siteurl") . "/wp-content");
}

if (!defined("WP_CONTENT_DIR")) {
    define("WP_CONTENT_DIR", ABSPATH . "wp-content");
}

if (!defined("CC_WHMCS_BRIDGE_PLUGIN")) {
    $cc_whmcs_bridge_plugin = str_replace(realpath(dirname(__FILE__).'/..'),"",dirname(__FILE__));
    $cc_whmcs_bridge_plugin = substr($cc_whmcs_bridge_plugin,1);
    define("CC_WHMCS_BRIDGE_PLUGIN", $cc_whmcs_bridge_plugin);
}

if (!defined("BLOGUPLOADDIR")) {
    $upload=wp_upload_dir();
    define("BLOGUPLOADDIR",$upload['path']);
}

define("CC_WHMCS_BRIDGE_URL", WP_CONTENT_URL . "/plugins/".CC_WHMCS_BRIDGE_PLUGIN."/");

$cc_whmcs_bridge_version = get_option("cc_whmcs_bridge_version");

if ($cc_whmcs_bridge_version) {
    add_action("init", "cc_whmcs_bridge_init");

    if (get_option('cc_whmcs_bridge_footer')=='Site')
        add_filter('wp_footer','cc_whmcs_bridge_footer');

    add_filter('the_content', 'cc_whmcs_bridge_content', 10, 2);

    add_action('wp_head','cc_whmcs_bridge_header',10);
    add_action("plugins_loaded", "cc_whmcs_sidebar_init");
    add_action('wp_ajax_check_bridge', 'cc_whmcs_bridge_checks');
}

add_action('admin_head','cc_whmcs_bridge_admin_header');
add_action('admin_notices','cc_whmcs_admin_notices');

add_action('wp_loaded', 'cc_whmcs_close_my_session', 30);
function cc_whmcs_close_my_session() {
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_write_close();
    }
}

require_once(dirname(__FILE__) . '/includes/shared.inc.php');
require_once(dirname(__FILE__) . '/includes/request.class.php');
require_once(dirname(__FILE__) . '/includes/footer.inc.php');
require_once(dirname(__FILE__) . '/includes/integrator.inc.php');
require_once(dirname(__FILE__) . '/bridge_cp.php');

if (!class_exists('iplug_simple_html_dom_node'))
    require_once(dirname(__FILE__) . '/includes/simple_html_dom.php');

require(dirname(__FILE__).'/includes/sidebars.php');
require(dirname(__FILE__).'/includes/parser.inc.php');

function cc_whmcs_admin_notices() {
    global $wpdb;
    $errors = array();
    $warnings = array();
    $notices = array();
    $files = array();
    $dirs=array();

    $cc_whmcs_bridge_version=get_option("cc_whmcs_bridge_version");
    if ($cc_whmcs_bridge_version && $cc_whmcs_bridge_version != CC_WHMCS_BRIDGE_VERSION)
        $warnings [] = 'You downloaded version '.CC_WHMCS_BRIDGE_VERSION.' and need to update your settings (currently at version '.$cc_whmcs_bridge_version.') by verifying your settings and clicking the "Save Settings" button on the <a href="options-general.php?page=cc-ce-bridge-cp">bridge control panel</a>.';

    $upload = wp_upload_dir();

    if (cc_whmcs_bridge_mainpage()) {
        if (isset($_REQUEST['whmcs_clear'])) $warnings[] = 'Cache clear has been triggered.';

        $cache = (int)get_option('cc_whmcs_bridge_sso_cache');
        if ($cache != false && $cache > 0 && !is_writable(dirname(__FILE__).'/cache'))
            $warnings[] = 'Your cache directory is not writable. Please make sure the "cache" folder inside your whmcs-bridge plugin folder is writable.';

        if ($upload['error'])
            $errors[]=$upload['error'];

        if (!get_option('cc_whmcs_bridge_url'))
            $warnings[]="Please update your WHMCS connection settings on the plugin control panel";

        if (phpversion() < '7')
            $warnings[]="You are running PHP version ".phpversion().". We recommend you upgrade to PHP 7.2 or higher.";

        if (!function_exists('curl_init')) $errors[]="You need to have cURL installed. Contact your hosting provider to do so.";
    }

    if (get_option("cc_whmcs_bridge_url") && !preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', get_option("cc_whmcs_bridge_url")))
        $errors[] = 'Your WHMCS URL '.get_option("cc_whmcs_bridge_url").' seems to be incorrect, please verify it and make sure it starts with http or https.';

    if (count($errors) > 0) {
        foreach ($errors as $message)  {
            echo "<div id='zing-warning' style='background-color:pink' class='updated fade'><p><strong>";
            echo WHMCS_BRIDGE.':'.$message.'<br />';
            echo "</strong> "."</p></div>";
        }
    }
    if (count($warnings) > 0) {
        foreach ($warnings as $message) {
            echo "<div id='zing-warning' style='background-color:greenyellow' class='updated fade'><p><strong>";
            echo WHMCS_BRIDGE.': '.$message.'<br />';
            echo "</strong> "."</p></div>";
        }
    }
    if (isset($_REQUEST['page']) && ($_REQUEST['page']=='cc-ce-bridge-cp') && count($notices) > 0) {
        foreach ($notices as $message) {
            echo "<div id='zing-warning' style='background-color:lightyellow' class='updated fade'><p><strong>";
            echo $message.'<br />';
            echo "</strong> "."</p></div>";
        }
    }

    return array('errors'=> $errors, 'warnings' => $warnings);
}

function c_whmcs_bridge_check_for_cache() {
    $url = site_url();
    $header = get_headers($url);
    $messages = [];

    foreach ($header as $key=>$value) {
        if (stripos($value, "Cache") !== false && stripos($value, "no-cache") === false) {
            $message = "Cache headers found in HTTP requests";
            $messages[$message] = $message;
        } elseif ( stripos($value, "cloudflare") !== false ) {
            $message = "CloudFlare detected. Please note WHMCS Bridge does not work with CloudFlare, "
                ."please either revert to your original name servers (sometimes this is the only option), or completely disable CloudFlare "
                ."and all it's services on your bridge URL (yourdomain.tld/yourwhmcsbridgepage/*)";
            $messages[$message] = $message;
        } elseif ( stripos($value, "proxy") !== false ) {
            $message = "Proxy headers detected, this does not necessarily mean cache is enabled, but could cause unwanted issues";
            $messages[$message] = $message;
        } elseif (stripos($value, "varnish") !== false ) {
            $message = "Varnish headers detected, this does not necessarily mean cache is enabled, but could cause unwanted issues";
            $messages[$message] = $message;
        } elseif (stripos($value, "Vary: X-Forwarded-Proto") !== false) {
            $message = "Vary headers found, this may be due to a CDN (Akamai/CloudFront etc.), please make sure it is not set to cache the CSS/JS within the bridge page";
            $messages[$message] = $message;
        } elseif (stripos($value, "P-LB") !== false ) {
            $message = "Load balancer detected, this does not necessarily mean cache is enabled, but please make sure the bridge page is not being cached";
            $messages[$message] = $message;
        } elseif ( stripos($value, "Cache-Control") !== false && stripos($value, "no-cache") === false) {
            $message = "Cache control header detected, please make sure any cache plugins are disabled for the bridge page";
            $messages[$message] = $message;
        }
    }

    $all_active_plugins = wp_get_active_and_valid_plugins();

    foreach ($all_active_plugins as $key=>$value) {
        if ((stripos($value, "cache") !== false) && (stripos($value, "detect-cache") == false)) {
            $message = "A cache plugin was detected";
            $messages[$message] = $message;
        }
    }

    return $messages;
}

function cc_whmcs_bridge_checks() {
    if (!wp_verify_nonce($_POST['nonce'], 'whmcs_bridge_check_bridge') ){
        die('Permission Denied.');
    }

    $whmcs_url = get_option('cc_whmcs_bridge_url');

    $return_errors = array(
        'filename' => "Your WHMCS URL should not include any filenames, only to your WHMCS folder (not admin), eg: http://yourdomain.tld/whmcs/",

        'network_blurb' => "<span style='color:orangered'>The bridge needs to be able to use your WordPress website to call your WHMCS URL; if they are hosted on the same machine then the "
                    ."hosting provider must allow for traffic to be routed back to the same host; this functionality is standard on most hosting providers so it should not "
                    ."be a problem for it to be resolved. There is no need to query i-Plugins on this issue as we are unable to change settings on your server.</span>",

        'api_response_noresult' => "The bridge was unable to connect your WHMCS API page, please make sure <a href='{$whmcs_url}' target='_blank'>{$whmcs_url}</a> is the location of your direct WHMCS installation "
            ."(the bridge requires you already have WHMCS set up and installed - if you don't have WHMCS, please "
            ."<a target=\"_blank\" href=\"http://www.whmcs.com/members/aff.php?aff=23386\">click here</a>).",

        'api_response_error' => "The bridge is receiving an error when accessing the WHMCS API, the error message is: RESPONSE<br>Note: Please make sure you have allowed "
                ."your WordPress IP (returned in the error message if it is an IP error) to access your API as well as white listing it via your WHMCS Admin. Not doing this can cause your "
                ."WP IP to be banned due to incorrect login attempts which would render your bridge page empty (Allow via: Setup > General Settings > Security)",

        'api_credentials' => "WHMCS Bridge was unable to authenticate against the WHMCS API. Please make sure you have filled in correct "
                ."<a href='https://docs.whmcs.com/API_Authentication_Credentials' target='_blank'>API credentials</a>.",

        'http_codes' => "<span style='color:blue'>Details of response codes:"
            ."<ul><li style='padding-left:20px;'><u>Code of 403</u> Please make sure you have allowed your WordPress IP (returned in the error message) to access your API as well as "
                ."white listing it via your WHMCS Admin. Not doing this can cause your WP IP to be banned due to incorrect login attempts which would render your "
                ."bridge page empty (Allow via: Setup > General Settings > Security)</li>"
            ."<li style='padding-left:20px;'><u>Code of 404</u> Incorrect URL has been entered (Not Found), make sure you have put in the full URL of your WHMCS installation - "
                ."if you don't have WHMCS, please <a target=\"_blank\" href=\"http://www.whmcs.com/members/aff.php?aff=23386\">click here</a>).</li>"
            ."<li style='padding-left:20px;'><u>Code of 500</u> Your WHMCS URL is triggering an internal server error. This is something you will need to check with your "
                ."hosting provider who will need to check error logs. You may want to <a target='_blank' href='https://docs.whmcs.com/Enabling_Error_Reporting'>enable error reporting "
                ."in WHMCS</a> to find the error."
            ."</ul></span>",

        'permalinks' => "WHMCS Bridge requires WordPress to have pretty permalinks enabled, please enable them by '
                .'<a href='https://codex.wordpress.org/Using_Permalinks#mod_rewrite:_.22Pretty_Permalinks.22' target='_blank'>following the instructions here</a> via '
                .'<a href='options-permalink.php'>the Permalinks settings in WordPress Admin</a> (use \"Post name\").",

        'mismatched_domain' => '<span style="color:blue">Your WHMCS is running on WHMCS_DOMAIN, and your WordPress is running on WORDPRESS_DOMAIN, this can cause crossdomain errors '
                .'which may result in '
                .'your WHMCS icons not displaying correctly / not displaying at all. '
                .'<a href="https://i-plugins.com/whmcs-bridge/knowledgebase/1089/My-icons-are-not-displaying-or-displaying-as-blocks-instead-of-the-correct-icon.html/" target="_blank">Please see our KB article here</a>. '
                .'(Note: This message will not go away if you have resolved the issue, this is just a warning)</span>',

        'password_reset' => 'Since WHMCS 7.5.1, WHMCS has changed the way the password reset page works and our addon for WHMCS is unable to redirect correctly, if you have bridgewp '
            .'enabled <a target="_blank" href="https://i-plugins.com/whmcs-bridge/knowledgebase/1098/Important-.htaccess-redirect-for-WHMCS-Bridge.html/">please see this KB article</a>.',

        'cache' => "If you are making changes (eg: whmcs template) and it's not updating your WHMCS bridge page, or if you are seeing other users details when logged in (or logged in "
            ."users details without logging in) then you need to please disable cache for the whmcs bridge page and all child pages. <br><br>"
            ."Most good cache plugins allow you to specify a directory / url keyword to exclude from caching; simply add your /whmcs-bridge/ page slug to this setting, clear all saved "
            ."cache and update your plugin.<br><br>To speed up your WHMCS Bridge, please enable caching within the WHMCS Bridge Settings page (Pro Plugin).",
    );

    $return_message = array();
    $proceed = true;

    if (!get_option('permalink_structure')) {
        $return_message[] = $return_errors['permalinks'];
        $proceed = false;
    }

    if (stristr($whmcs_url, '.php') !== false) {
        $return_message[] = $return_errors['filename'];
        $proceed = false;
    }

    $cache = c_whmcs_bridge_check_for_cache();
    if (!empty($cache)) {
        $return_message[] = "<span style='color:orangered;'>Cache check results: ".implode('. ', $cache)."<br>".$return_errors['cache'].'</span>';
    }

    if ($proceed) {
        $whmcs_domain = strtolower(parse_url($whmcs_url, PHP_URL_HOST));
        $wordpress_domain = strtolower(parse_url(get_site_url(), PHP_URL_HOST));

        if ($whmcs_domain != $wordpress_domain) {
            $return_message[] = str_replace(['WHMCS_DOMAIN', 'WORDPRESS_DOMAIN'], [$whmcs_domain, $wordpress_domain], $return_errors['mismatched_domain']);
            $proceed = false;
        }

        $ch = curl_init();    // initialize curl handle
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 30s
        if (stristr($whmcs_url, "https") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, NULL);
            curl_setopt($ch, CURLOPT_CAPATH, NULL);
        }

        curl_setopt($ch, CURLOPT_URL, $whmcs_url.'/login'); // set url to post to
        $data = curl_exec($ch); // run the whole process
        $code2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_setopt($ch, CURLOPT_URL, $whmcs_url); // set url to post to
        $data = curl_exec($ch); // run the whole process
        $code3 = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code2 != 200) {
            curl_setopt($ch, CURLOPT_URL, $whmcs_url.'/index.php?rp=login'); // set url to post to
            $data = curl_exec($ch); // run the whole process
            $code2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($code2 != 200) {
                $return_message[] = "Your WHMCS login page is returning an HTTP response code of <u>{$code2}</u>.";
                $codekey = true;
                $proceed = false;
            }
        }
        if ($code3 != 200 && $code3 != 302 && $code3 != 301) {
            $return_message[] = "Your WHMCS home page is returning an HTTP response code of <u>{$code3}</u>.";
            $codekey = true;
            $proceed = false;
        }

        if (defined("CC_WHMCS_BRIDGE_SSO_PLUGIN") && class_exists('WhmcsProLicense') && class_exists('whmcs')) {
            $whmcs = new whmcs();
            $results = $whmcs->connect("GetStats", ['timeline_days' => 1]);

            if (!is_array($results)) {
                $return_message[] = $return_errors['api_credentials'];
                $proceed = false;

                curl_setopt($ch, CURLOPT_URL, $whmcs_url.'/includes/api.php'); // set url to post to
                $data = curl_exec($ch);

                if (strstr($data, 'result=') === false) {
                    $return_message[] = $return_errors['api_response_noresult'].' ('.$data.')';
                    $proceed = false;
                } else {
                    $results = explode(';', $data);
                    if (count($results) > 1) {
                        if (strtolower(trim($results[0])) == 'result=error') {
                            $return_message[] = str_replace('RESPONSE', strip_tags($data), $return_errors['api_response_error']);
                            $proceed = false;
                        }
                    }
                }

            } else if (isset($results["result"]) && ($results["result"]=="error")) {
                $return_message[] = "Your WHMCS API returned an error: ".$results['message'];
                $proceed = false;
            }

            curl_setopt($ch, CURLOPT_URL, $whmcs_url.'/password/reset/redeem/12345');
            $data = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($code != 302 && $code != 301) {
                $return_message[] = $return_errors['password_reset'];
                $proceed = false;
            }
        }

        curl_close($ch);
    }

    if (count($return_message) > 0) {
        if (!empty($codekey))
            $return_message[] = $return_errors['http_codes'].$return_errors['network_blurb'];

        echo "<ul style='margin-left:20px; font-weight: bold; color:#cc0000'><li style='list-style-type: square;'>";
        echo implode("</li><li style='list-style-type: square;'>", $return_message);
        echo "</li></ul>";
    } else {
        echo "As far as we can see, it all looks good!";
    }

    wp_die();
}

/**
 * Activation: creation of database tables & set up of pages
 * @return unknown_type
 */
function cc_whmcs_bridge_activate() {
    //nothing much to do
}

function cc_whmcs_bridge_install() {
    global $wpdb,$current_user,$wp_rewrite;

    ob_start();
    cc_whmcs_log();
    set_error_handler('cc_whmcs_log');
    error_reporting(E_ALL & ~E_NOTICE);

    $cc_whmcs_bridge_version=get_option("cc_whmcs_bridge_version");
    if (!$cc_whmcs_bridge_version) add_option("cc_whmcs_bridge_version",CC_WHMCS_BRIDGE_VERSION);
    else update_option("cc_whmcs_bridge_version",CC_WHMCS_BRIDGE_VERSION);

    $cc_whmcs_bridge_page=get_option("cc_whmcs_bridge_pages");
    $create_page = false;
    if (is_numeric($cc_whmcs_bridge_page) && $cc_whmcs_bridge_page > 0) {
        $query = '';
        $pages = get_pages(array(
            'post_type' => 'page',
            'post_status' => 'publish',
        ));
        $found = false;
        foreach ($pages as $p) {
            if ($p->ID == $cc_whmcs_bridge_page) {
                $found = true;
                break;
            }
        }
        if (!$found) $create_page = true;
    } else {
        $create_page = true;
    }

    //create pages
    if ($create_page) {
        cc_whmcs_log(0,'Creating pages');
        $pages=array();
        $pages[]=array(WHMCS_BRIDGE_PAGE.'-bridge',WHMCS_BRIDGE_PAGE,"*",0);

        $ids="";
        foreach ($pages as $i =>$p)
        {
            $my_post = array();
            $my_post['post_title'] = $p['0'];
            $my_post['post_content'] = '';
            $my_post['post_status'] = 'publish';
            $my_post['post_author'] = 1;
            $my_post['post_type'] = 'page';
            $my_post['menu_order'] = 100+$i;
            $my_post['comment_status'] = 'closed';
            $id=wp_insert_post( $my_post );
            if (empty($ids)) { $ids.=$id; } else { $ids.=",".$id; }
            if (!empty($p[1])) add_post_meta($id,'cc_whmcs_bridge_page',$p[1]);
        }
        update_option("cc_whmcs_bridge_pages",$ids);
    }

    restore_error_handler();

    $wp_rewrite->flush_rules();

    return true;
}

function cc_whmcs_bridge_uninstall() {
    $cc_whmcs_bridge_options=cc_whmcs_bridge_options();

    delete_option('cc_whmcs_bridge_log');
    foreach ($cc_whmcs_bridge_options as $value) {
        delete_option( $value['id'] );
    }

    delete_option("cc_whmcs_bridge_page");
    delete_option("cc_whmcs_bridge_pages");
    delete_option("cc_whmcs_bridge_log");
    delete_option("cc_whmcs_bridge_ftp_user"); //legacy
    delete_option("cc_whmcs_bridge_ftp_password"); //legacy
    delete_option("cc_whmcs_bridge_version");
    delete_option("cc_whmcs_bridge_pages");
    delete_option('cc-ce-bridge-cp-support-us');
}

/**
 * Deactivation: nothing to do
 * @return void
 */
function cc_whmcs_bridge_deactivate() {
    $ids=get_option("cc_whmcs_bridge_pages");
    $ida=explode(",",$ids);
    foreach ($ida as $id) {
        wp_delete_post($id);
    }
}

function cc_whmcs_bridge_output($page = null) {
    global $post;
    global $wpdb;
    global $wordpressPageName;
    global $cc_whmcs_bridge_loaded;
    global $cc_whmcs_bridge_to_include;

    $ajax = false;

    $ref = rand(100, 999);

    if (isset($post)) {
        $post_id = $post->ID;
    } else {
        $post_id = 1;
    }

    $cf = get_post_custom($post_id);

    if ($page) {
        $cc_whmcs_bridge_to_include = $page;
    } elseif (isset($_REQUEST['ccce']) && (isset($_REQUEST['ajax']) && $_REQUEST['ajax'])) {
        $cc_whmcs_bridge_to_include = sanitize_text_field($_REQUEST['ccce']);
        $ajax = intval($_REQUEST['ajax']);
    } elseif (isset($_REQUEST['ccce'])) {
        $cc_whmcs_bridge_to_include = sanitize_text_field($_REQUEST['ccce']);
    } elseif (isset($cf['cc_whmcs_bridge_page']) && $cf['cc_whmcs_bridge_page'][0] == WHMCS_BRIDGE_PAGE) {
        $cc_whmcs_bridge_to_include = "index";
    } else {
        $cc_whmcs_bridge_to_include = "index";
    }

    cc_whmcs_log(0, '[URL Init '.$ref.'] '.$cc_whmcs_bridge_to_include);

    if ($cc_whmcs_bridge_to_include == "index" && !empty($_GET['gid']) && count($_GET) < 2) {
        cc_whmcs_bridge_home($home, $pid, false);
        wp_redirect($home."?ccce=cart&gid=".$_REQUEST['gid']);
        exit;
    }

    $http = cc_whmcs_bridge_http($cc_whmcs_bridge_to_include);

    cc_whmcs_log(0, '[URL '.$ref.'] '.$http);

    if (stristr($http, 'viewinvoice.php') !== false && stristr($http, 'ccce=viewinvoice') !== false) {
        $http = str_replace("&ccce=viewinvoice", "", $http);
        cc_whmcs_log(0, '[URL '.$ref.'] '.$http);
    }

    if (strstr($http, '?a=checkout') !== false && isset($_REQUEST['action']) && $_REQUEST['action'] == 'doPayment') {
        $http = str_replace('?a=checkout', '?a=complete', $http);
        cc_whmcs_log(0, '[URL '.$ref.'] URL Adjusted to ?a=complete');
    }

    if (strstr($http, 'index.php?type=q&id') !== false) {
        $http = str_replace('index.php', 'dl.php', $http);
        $cc_whmcs_bridge_to_include = "dl";
    }

    if (strstr($http, 'index.php') !== false && isset($_REQUEST['a']) && in_array($_REQUEST['a'], array('addToCart', 'updateDomainPeriod')) && isset($_REQUEST['domain'])) {
        $http = str_replace('index.php', 'cart.php', $http);
        cc_whmcs_log(0, '[URL '.$ref.'] URL Adjusted to ?a=complete');
    }

    $news = new bridgeHttpRequest($http,'whmcs-bridge-sso');
    $news->debugFunction = 'cc_whmcs_log';

    if (function_exists('cc_whmcs_bridge_sso_httpHeaders')) $news->httpHeaders=cc_whmcs_bridge_sso_httpHeaders($news->httpHeaders);

    if (isset($news->post['whmcsname'])) {
        $news->post['name'] = $news->post['whmcsname'];
        unset($news->post['whmcsname']);
    }

    $news = apply_filters('bridge_http',$news);
    $news->forceWithRedirect['systpl'] = get_option('cc_whmcs_bridge_template') ? get_option('cc_whmcs_bridge_template') : 'five';

    if (!function_exists('cc_whmcs_bridge_parser_with_permalinks') && !in_array($news->forceWithRedirect['systpl'], array('portal', 'five', 'six'))) {
        $news->forceWithRedirect['systpl'] = 'five';
    }

    if ($cc_whmcs_bridge_to_include=='login' && !empty($news->post['username'])) {
        $news->post['rememberme']='on';
    }

    if (!$news->curlInstalled()) {
        cc_whmcs_log('Error','CURL not installed');
        return "cURL not installed";
    } elseif (!$news->live()) {
        cc_whmcs_log('Error','A HTTP Error occurred');
        return "A HTTP Error occurred";
    } else {
        if ($cc_whmcs_bridge_to_include == 'verifyimage' ||
            (isset($_REQUEST['ccce']) && stristr($_REQUEST['ccce'], '/qr/') !== false) ||
            (isset($_REQUEST['showqrimage']) && $_REQUEST['showqrimage'] == 1)
            || (isset($_REQUEST['js']) && (
                    stristr($_REQUEST['js'], '.jpg') !== false ||
                    stristr($_REQUEST['js'], '.png') !== false ||
                    stristr($_REQUEST['js'], '.jpeg') !== false ||
                    stristr($_REQUEST['js'], '.gif') !== false ||
                    stristr($_REQUEST['js'], '.svg') !== false
                ))
        ) {
            $output = $news->DownloadToString();
            while (count(ob_get_status(true)) > 0) ob_end_clean();

            $cache_setting = (int)get_option("cc_whmcs_bridge_sso_cache");
            if (is_numeric($cache_setting) && $cache_setting > 0 &&
                !(isset($_REQUEST['ccce']) && stristr($_REQUEST['ccce'], '/qr/') !== false) &&
                $cc_whmcs_bridge_to_include != 'verifyimage' && !isset($_REQUEST['showqrimage'])) {
                $cache_dir = dirname(__FILE__) . '/cache/';
                if (file_exists($cache_dir) && is_writable($cache_dir)) {
                    $extension = pathinfo($http, PATHINFO_EXTENSION);

                    $filename = md5($_REQUEST['js']) . '_parsed_' . strtotime('+' . $cache_setting . ' minutes') . '.' . $extension;

                    file_put_contents($cache_dir . $filename, $news->body);
                    cc_whmcs_log(0, '[1] Image cache written for ' . $_REQUEST['js']);
                    // log cached file
                    cc_update_cache($_REQUEST['js'], $filename);
                }
            }

            if (isset($_REQUEST['ccce']) && stristr($_REQUEST['ccce'], '/qr/') !== false) {
                $filename = 'topt';
                $file_extension = 'png';
            } else {
                $filename = basename($_REQUEST['js']);
                $file_extension = strtolower(substr(strrchr($filename, "."), 1));
            }

            switch ($file_extension) {
                case "gif": $ctype="image/gif"; break;
                case "png": $ctype="image/png"; break;
                case "jpeg":
                case "jpg": $ctype="image/jpeg"; break;
                case "svg": $ctype="image/svg+xml"; break;
                default: $ctype="image"; break;
            }

            header("Content-Type: $ctype");
            echo $news->body;

            die();
        } elseif (stristr($cc_whmcs_bridge_to_include, 'announcementsrss') !== false
            || (
                isset($_REQUEST['rp']) && $_REQUEST['rp'] == '/announcements/rss'
            )
            || stristr($cc_whmcs_bridge_to_include, 'networkissuesrss') !== false
        ) {
            while (count(ob_get_status(true)) > 0) ob_end_clean();
            $output = $news->DownloadToString();
            header('Content-Type: application/rss+xml; charset=utf-8');
            echo $news->body;
            die();
        } elseif (($cc_whmcs_bridge_to_include=='dl' && $news->headers['content-type'] != 'text/html') ||
            (isset($_REQUEST['playlist'], $_REQUEST['device_mac'])) ||
            (isset($_REQUEST['act']) && $_REQUEST['act'] == 'download') ||
            (isset($_REQUEST['XCAction']) && $_REQUEST['XCAction'] == 'downloadCallHistory') ||
            (isset($_REQUEST['playlist'], $_REQUEST['action']) && $_REQUEST['action'] == 'productdetails') ||
            (isset($_REQUEST['vp_login'], $_REQUEST['action']) && $_REQUEST['action'] == 'productdetails') ||
            (isset($_REQUEST['a']) && $_REQUEST['a'] == 'CreateEmailAccount') ||
            (isset($_REQUEST['action'], $_REQUEST['service-id']) && $_REQUEST['action'] == 'manage-service') ||
            (isset($_REQUEST['action'], $_REQUEST['m']) && $_GET['action'] == 'download' && $_GET['m'] == 'invoiceme') ||
            (stristr($cc_whmcs_bridge_to_include, 'wbteampro') !== false && isset($_REQUEST['view']) && $_REQUEST['view'] == 'raw') ||
            (stristr($cc_whmcs_bridge_to_include, 'wbteampro') !== false && isset($_REQUEST['act']) && $_REQUEST['act'] == 'download') ||
            (stristr($cc_whmcs_bridge_to_include, 'project_management') !== false && isset($_REQUEST['action']) && $_REQUEST['action'] == 'dl') ||
            (stristr($cc_whmcs_bridge_to_include, 'project_management') !== false && stristr($cc_whmcs_bridge_to_include, '.css') !== false)
        ) {
            while (count(ob_get_status(true)) > 0) ob_end_clean();

            $output = $news->DownloadToString();

            if (strstr($output, 'name="password"')) {
                if ($wordpressPageName) $p=$wordpressPageName;
                else $p='/';

                header('location:'.get_option('home').$p.'?ccce=clientarea');
            } else {
                header("Content-Disposition: ".$news->headers['content-disposition']);
                header("Content-Type: ".$news->headers['content-type']);
                echo $news->body;
                die();
            }
        } elseif ($ajax == 1 ||
            (isset($_REQUEST['mg-page'], $_REQUEST['mg-action'])) ||
            (isset($_REQUEST['vserverid'])) ||
            (isset($_REQUEST['vserverid'])) ||
            (isset($_REQUEST['_vnc']) && $_REQUEST['ccce'] == 'vnc') ||
            (stristr($cc_whmcs_bridge_to_include, 'serverstatus') !== false && isset($_REQUEST['num'])) ||
            (isset($_REQUEST['action'])	&& $_REQUEST['action'] == 'getcustomfields') ||
            (isset($_REQUEST['check'], $_REQUEST['addtocart'], $_REQUEST['domain'])) ||
            (isset($_REQUEST['a'], $_REQUEST['domain']) && $_REQUEST['a'] == 'updateDomainPeriod') ||
            (isset($_REQUEST['a'], $_REQUEST['domain']) && $_REQUEST['a'] == 'validateCaptcha') ||
            (isset($_REQUEST['a'], $_REQUEST['domain']) && $_REQUEST['a'] == 'checkDomain') ||
            (isset($_REQUEST['responseType']) && $_REQUEST['responseType'] == 'json') ||
            (isset($_REQUEST['action']) && $_REQUEST['action'] == 'doPayment') ||
            (stristr($cc_whmcs_bridge_to_include, 'creditcard') !== false && isset($_REQUEST['cccvv']) && $_REQUEST['action'] == 'submit' && stristr($news->DownloadToString(), 'twocheckout.php') !== false) ||
            (stristr($cc_whmcs_bridge_to_include, 'cart') !== false && isset($_REQUEST['cccvv'], $_REQUEST['paymentmethod']) && $_REQUEST['paymentmethod'] == 'twocheckout') ||
            (isset($_REQUEST['PaRes']) && isset($_REQUEST['MD'])) ||
            (isset($_REQUEST['select_modal'])) ||
            (isset($_REQUEST['rp']) && strstr($_REQUEST['rp'], '/domain') !== false && strstr($_REQUEST['rp'], '/renew') === false) ||
            (isset($_REQUEST['rp']) && strstr($_REQUEST['rp'], '/auth/provider/')) ||
            (isset($_REQUEST['action']) && $_REQUEST['action'] == 'productdetails' && isset($_REQUEST['give'])) ||
            (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        ) {
            cc_whmcs_log(0, "ajax 0 - ".json_encode($_REQUEST));
            $output=$news->DownloadToString();
            if (!$news->redirect) {
                cc_whmcs_log("ajax Request");
                while (count(ob_get_status(true)) > 0) ob_end_clean();
                $body=$news->body;

                if ((isset($_REQUEST['give']) && stristr($_REQUEST['give'], '.html') !== false)
                || (isset($_REQUEST['give']) && stristr($_REQUEST['give'], '.js') !== false)
                ) {
                    $body = cc_whmcs_bridge_parser_ajax1($body, $cc_whmcs_bridge_to_include);
                } elseif (strstr($cc_whmcs_bridge_to_include, 'creditcard') !== false && strstr($body, 'twocheckout') !== false) {
                    $body = cc_whmcs_bridge_parser($output);
                    $body = '<html><head>'.$body['head'].'</head><body onload="callcreatetoken()">'.str_replace('<script>', '<script type="text/javascript">', $body['main']).'</body></html>';
                } elseif (strstr($cc_whmcs_bridge_to_include, 'creditcard') !== false) {
                    return $output;
                } else if (isset($_REQUEST['mg-page'], $_REQUEST['mg-action'])) {
                    header('Content-Type: application/json');
                    echo $body;
                    die();                    
                } else if (isset($_REQUEST['vserverid']) || (isset($_REQUEST['rp']) && strstr($_REQUEST['rp'], '/auth/provider/'))) {
                    if (isset($_REQUEST['rp']) && $_REQUEST['rp'] == '/auth/provider/google_signin/finalize') {
                        if (class_exists('wpusers')) {
                            $wpusers=new wpusers();
                            $wpusers->loginWpUserOauth('google', trim($body));
                        }
                    }
                    header('Content-Type: application/json');
                    echo $body;
                    die();
                } else {
                    cc_whmcs_log(0, "ajax1: {$cc_whmcs_bridge_to_include}");
                    $body = cc_whmcs_bridge_parser_ajax1($body, $cc_whmcs_bridge_to_include);
                }

                $bodyTst = json_decode($body, true);

                if (is_array($bodyTst)) {
                    header('Content-Type: application/json');
                    echo $body;
                    die();
                } else if ((isset($_REQUEST['js']) && stristr($_REQUEST['js'], '.css') !== false) || (isset($_REQUEST['give']) && stristr($_REQUEST['give'], '.css') !== false)) {
                    header('Content-Type: text/css');

                    echo $body;

                    die();
                } else if ((isset($_REQUEST['js']) && stristr($_REQUEST['js'], '.js') !== false) || (isset($_REQUEST['give']) && stristr($_REQUEST['give'], '.js') !== false)) {
                    header('Content-Type: application/javascript');
                } else if (
                    isset($_REQUEST['a'], $_REQUEST['type'], $_REQUEST['domain']) ||
                    isset($_REQUEST['responseType']) && $_REQUEST['responseType'] == 'json' ||
                    (isset($_REQUEST['js']) && strstr($_REQUEST['js'], 'ispapi') !== false) ||
                    (isset($_REQUEST['rp']) && strstr($_REQUEST['rp'], '/domain') !== false) ||
                    isset($_REQUEST['a'], $_REQUEST['domain'])) {
                    header('Content-Type: application/json');
                } else if (isset($_REQUEST['select_modal'])) {
                    header('Content-Type: application/json');
                    $body = str_replace(array('\u0027', '\u0022', '\u002'), '\"', $body);
                    $body = str_replace('2cart', 'cart', $body);
                    echo $body;
                    die();
                }
                echo $body;
                die();
            } else {
                cc_whmcs_log('Notification','[A] Redirect to: '.$output);
                header('Location:'.$output);
                die();
            }
        } elseif ($ajax==2) {
            while (count(ob_get_status(true)) > 0) ob_end_clean();
            $output=$news->DownloadToString();
            $body=$news->body;
            $body=cc_whmcs_bridge_parser_ajax2($body);

            if (isset($_REQUEST['js']) && stristr($_REQUEST['js'], '.css') !== false) {
                header('Content-Type: text/css');
            } else if (isset($_REQUEST['js']) && stristr($_REQUEST['js'], '.js') !== false) {
                header('Content-Type: application/javascript');
            } else {
                header('HTTP/1.1 200 OK');
            }

            echo $body;
            die();
        } elseif ($news->redirect) {
            $output=$news->DownloadToString();
            if ($wordpressPageName) $p=$wordpressPageName;
            else $p='/';
            $f[]='/.*\/([a-zA-Z\_]*?).php.(.*?)/';
            $r[]=get_option('home').$p.'?ccce=$1&$2';
            $f[]='/([a-zA-Z\_]*?).php.(.*?)/';
            $r[]=get_option('home').$p.'?ccce=$1&$2';
            $output=preg_replace($f,$r,$news->location,-1,$count);
            cc_whmcs_log('Notification','[1] Redirect to: '.$output);
            header('Location:'.$output);
            die();
        } else {
            if (isset($_REQUEST['aff'])) $news->follow=false;
            $output=$news->DownloadToString();

            if ($news->redirect) {
                header('Location:'.$output);
                die();
            }
            if (isset($_REQUEST['aff']) && isset($news->headers['location'])) {
                if ($wordpressPageName) $p=$wordpressPageName;
                else $p='/';
                $f[]='/.*\/([a-zA-Z\_]*?).php.(.*?)/';
                $r[]=get_option('home').$p.'?ccce=$1&$2';
                $f[]='/([a-zA-Z\_]*?).php.(.*?)/';
                $r[]=get_option('home').$p.'?ccce=$1&$2';
                $output=preg_replace($f,$r,$news->headers['location'],-1,$count);
                cc_whmcs_log('Notification','[2] Redirect to: '.$output);
                header('Location:'.$output);

                //if (strstr($news->headers['location'],get_option('home')))
                //    header('Location:'.$news->headers['location']);
                //else header('Location:'.get_option('home'));
                die();
            }

//            cc_whmcs_log(0, '[URL '.$ref.'] Remote fetch completed (standard content)');

            return $output;
        }
    }
}

/**
 * Page content filter
 * @param $content
 * @return unknown_type
 */
function cc_whmcs_bridge_content($content) {
    global $cc_whmcs_bridge_content,$post;

    if (!is_page()) return $content;
//    if (!in_the_loop()) return $content;
//    if (!is_singular()) return $content;
    if (!is_main_query()) return $content;

    $cf = get_post_custom($post->ID);

    if (isset($_REQUEST['ccce']) || (isset($cf['cc_whmcs_bridge_page']) && $cf['cc_whmcs_bridge_page'][0]==WHMCS_BRIDGE_PAGE)) {
        if (!isset($cc_whmcs_bridge_content)) { //support Gantry framework
            $cc_whmcs_bridge_content = cc_whmcs_bridge_parser();
        }
        if ($cc_whmcs_bridge_content) {
            $content='';
            ob_start();

            if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('whmcs-top-page')):
            endif;

            $content .= ob_get_clean();
            $content .= '<div id="bridge">';

            if (is_array($cc_whmcs_bridge_content) && isset($cc_whmcs_bridge_content['main']))
                $content .= $cc_whmcs_bridge_content['main'];

            $content .= '</div><!--end bridge-->';

            ob_start();

            if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('whmcs-bottom-page')):
            endif;

            $content .= ob_get_clean();

            if (get_option('cc_whmcs_bridge_footer')=='Page')
                $content .= cc_whmcs_bridge_footer(true);

//            if (stristr($content, '<div id="bridge">') !== false)
//                remove_filter('the_content', 'cc_whmcs_bridge_content');
        }
    }

    return $content;
}

function cc_whmcs_bridge_header() {
    global $cc_whmcs_bridge_content,$post;

    if (!(isset($post->ID))) return;

    $cf=get_post_custom($post->ID);
    if (isset($_REQUEST['ccce']) || (isset($cf['cc_whmcs_bridge_page']) && $cf['cc_whmcs_bridge_page'][0]==WHMCS_BRIDGE_PAGE)) {
        if (isset($_REQUEST['ajax']) && in_array($_REQUEST['ajax'], array(1,2))) return;

        if (!isset($cc_whmcs_bridge_content)) {
            $cc_whmcs_bridge_content=cc_whmcs_bridge_parser();
        }

        if (isset($cc_whmcs_bridge_content['head'])) echo $cc_whmcs_bridge_content['head'];

        echo '<style type="text/css">#whmcsimglogo { display: none }</style>';

        if (get_option('cc_whmcs_bridge_css')) {
            echo '<style type="text/css">'.get_option('cc_whmcs_bridge_css').'</style>';
        }
        if (get_option('cc_whmcs_bridge_sso_js')) {
            echo '<script type="text/javascript">'.stripslashes(get_option('cc_whmcs_bridge_sso_js')).'</script>';
        }
    }
    if (get_option('cc_whmcs_bridge_jquery')=='wp') echo '<script type="text/javascript">$=jQuery;</script>';
}

function cc_whmcs_bridge_admin_header() {

}

function cc_whmcs_bridge_http($page = "index") {
    $gotoPage = parse_url($page);
    $extension = true;
    $ignoreGets = [
        'page_id', 'ccce', 'whmcspage'
    ];
    $noPhpEnds = [
        'download', '.html',
        'login', 'store', '.php', '/',
    ];
    $noPhpContains = [
        'two-factor', 'challenge', 'password/reset',
    ];

    $whmcs = cc_whmcs_bridge_url()."/";
    if ((strpos($whmcs,'https://') !== 0) && isset($_REQUEST['sec']) && ($_REQUEST['sec'] == '1'))
        $whmcs = str_replace('http://','https://', $whmcs);
    $vars = "";

    if ($page == 'verifyimage') {
        $http = $whmcs.'includes/'.$page.'.php';
        return $http;
    } else if (!empty($_REQUEST['ccce']) && $_REQUEST['ccce'] == 'js') {
        if (isset($_REQUEST['js'])) {
            $http = $whmcs . sanitize_text_field($_REQUEST['js']);
            return $http;
        }
    } else {
        foreach ($noPhpContains as $npE) {
            if (stristr($page, $npE) !== false)
                $extension = false;
        }
        foreach ($noPhpEnds as $npE) {
            $offset = strlen($npE) * -1;
            if (substr($page, $offset) == $npE)
                $extension = false;
        }
    }

    if ($extension)
        $http = $whmcs.$page.'.php';
    else
        $http = $whmcs.$page;

    $params = [];
    if (!empty($_GET)) {
        foreach ($_GET as $k => $v) {
            if (!in_array($k, $ignoreGets)) {
                if (is_array($v)) {
                    foreach ($v as $kk => $vv) {
                        if (is_array($vv)) {
                            foreach ($vv as $kkk => $vvv) {
                                $params[$k][$kk][$kkk] = sanitize_text_field($vvv);
                            }
                        } else {
                            $params[$k][$kk] = sanitize_text_field($vv);
                        }
                    }
                } else {
                    $params[$k] = sanitize_text_field($v);
                }
            }
        }
    }
    if (!empty($_GET['whmcspage']))
        $params['whmcspage'] = sanitize_text_field($_GET['whmcspage']);

    $systpl = get_option('cc_whmcs_bridge_template') ? get_option('cc_whmcs_bridge_template') : 'twenty-one';

    if (!function_exists('cc_whmcs_bridge_parser_with_permalinks')
        && !in_array($systpl, array('portal', 'five', 'six'))) {
        $systpl = 'six';
    }

    $params['systpl'] = $systpl;

    $and = '&';
    if (function_exists('cc_whmcs_bridge_sso_http'))
        cc_whmcs_bridge_sso_http(http_build_query($params), $and);

    if (!empty($params))
        $http .= '?'.http_build_query($params);

    return $http;
}

function cc_whmcs_bridge_title($title,$id=0) {
    global $cc_whmcs_bridge_content;


    if (!in_the_loop()) {
        return $title;
    }
    //if ($id == 0) {
    //  return $title;
    //}

    if (!isset($cc_whmcs_bridge_content) || !$cc_whmcs_bridge_content) {
        $cc_whmcs_bridge_content = cc_whmcs_bridge_parser();
    }

    #if (isset($cc_whmcs_bridge_content['page_title']) && $cc_whmcs_bridge_content['page_title'] != '') {
    #    $p_title = explode('-', $cc_whmcs_bridge_content['page_title']);
    #    $title = trim($p_title[0]);
    #}

    return $title;
}

function cc_whmcs_bridge_default_page($pid) {
    $isPage=false;
    $ids=get_option("cc_whmcs_bridge_pages");
    $ida=explode(",",$ids);
    foreach ($ida as $id) {
        if (!empty($id) && $pid==$id) $isPage=true;
    }
    return $isPage;
}

function cc_whmcs_bridge_mainpage() {
    $ids=get_option("cc_whmcs_bridge_pages");
    $ida=explode(",",$ids);
    return $ida[0];
}

function cc_whmcs_bridge_init() {
    ob_start();
    if (function_exists('cc_whmcsbridge_sso_session'))
        cc_whmcsbridge_sso_session();

    if (session_status() == PHP_SESSION_NONE && !headers_sent())
        session_start();

    register_sidebars(1,array('name'=>'WHMCS Top Page Widget Area','id'=>'whmcs-top-page',));

    if (get_option('cc_whmcs_bridge_jquery') == 'wp') {
        wp_enqueue_script(array('jquery','jquery-ui','jquery-ui-slider','jquery-ui-button'));
    }

    if (is_admin() && isset($_REQUEST['page']) && ($_REQUEST['page']=='cc-ce-bridge-cp')) {
        wp_enqueue_script(array('jquery-ui-tabs'));
        wp_enqueue_style('jquery-style', plugins_url('jquery-ui.css', __FILE__));
        wp_enqueue_style('cc-style', plugins_url('cc.css', __FILE__));
        wp_enqueue_style('fa-style', plugins_url('fa.css', __FILE__));
    }
}

function cc_whmcs_log($type=0,$msg='',$filename="",$linenum=0) {
    if ($type==0) $type='Debug';
    if (get_option('cc_whmcs_bridge_debug')) {
        if (is_array($msg)) $msg = json_encode($msg);
        $v=get_option('cc_whmcs_bridge_log');
        if (!is_array($v)) $v=array();
        array_unshift($v,array(microtime(),$type,$msg));
        if (count($v) > 100) array_pop($v);
        update_option('cc_whmcs_bridge_log',$v);
    }
}

function cc_get_cache($flag = '') {
    if ($flag = 'all') {
        $loop = array('', '_js', '_css');
    } else {
        $loop = array('');
    }

    $cache = array();
    foreach ($loop as $cache_flag) {
        $current = unserialize(get_option('cc_whmcs_bridge_cache' . $cache_flag));

        if (!is_array($current))
            $current = array();

        $cache_dir = dirname(__FILE__) . '/cache/';

        // check current cache
        foreach ($current as $url => $val) {
            $expires = explode('_', $val);
            // if expired, delete
            if (time() > $expires[(count($expires) - 1)] || !file_exists($cache_dir . $val)) {
                if (file_exists($cache_dir . $val))
                    unlink($cache_dir . $val);
                unset($current[$url]);
            } else {
                $cache[$url] = $val;
            }
        }
    }

    return $cache;
}

function cc_update_cache($url, $cached_filename) {
    if (substr(strtolower($url), -4) == '.css') {
        $flag = '_css';
    } else if (substr(strtolower($url), -3) == '.js') {
        $flag = '_js';
    } else {
        $flag = '';
    }
    $current = cc_get_cache($flag);
    $current[$url] = $cached_filename;
    update_option('cc_whmcs_bridge_cache'.$flag, serialize($current));

    cc_whmcs_log(0, 'Updating '.$flag.' cache {'.$url.'} {'.$cached_filename.'} - '.json_encode($current));
}

function cc_whmcs_bridge_url() {
    $url = get_option('cc_whmcs_bridge_url');
    if (substr($url,-1)=='/') $url=substr($url,0,-1);
    return $url;
}

//Kept for compatibility reasons
if (class_exists('bridgeHttpRequest')) {
    class HTTPRequestWHMCS extends bridgeHttpRequest {}
}
