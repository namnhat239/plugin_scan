<?php
function cc_whmcs_bridge_options() {
    global $cc_whmcs_bridge_shortname,$cc_login_type,$current_user;
    $cc_whmcs_bridge_shortname = "cc_whmcs_bridge";

    $is='This section customizes the way '.WHMCS_BRIDGE.' interacts with WordPress.<br/><strong>Important for Bridge Free users:</strong> Make sure you are using "Basic URLs" in your WHMCS Admin > General Settings > Friendly URLs. Bridge Pro users can utilise "Full Friendly Rewrite".';


    $cc_whmcs_bridge_options[100] = array(
        "name" => "Integration Settings",
        "type" => "heading",
        "desc" => $is
    );
    $cc_whmcs_bridge_options[110] = array(
        "name" => '<i class="fa fa-home"></i> '.WHMCS_BRIDGE_PAGE." URL",
        "desc" => "The site URL of your ".WHMCS_BRIDGE_PAGE." installation. Make sure this is exactly the same as the settings field 'WHMCS System URL'. If you want to use SSL (https), make sure this URL and the 'WHMCS System URL' are using the https URL. In all cases make sure the setting 'WHMCS SSL System URL' is left blank.",
        "id" => $cc_whmcs_bridge_shortname."_url",
        "type" => "text"
    );

    $cc_whmcs_bridge_options[200] = array(
        "name" => "Styling Settings",
        "type" => "heading",
        "desc" => "This section customizes the look and feel."
    );
    $cc_whmcs_bridge_options[202] = array(
        "name" => '<i class="fa fa-camera"></i> '."Scope ".WHMCS_BRIDGE_PAGE." CSS",
        "desc" => '(BETA) Select if you want to prefix '.WHMCS_BRIDGE_PAGE.' CSS rules with #bridge, this will help avoid styling conflicts',
        "id" => $cc_whmcs_bridge_shortname."_prefix",
        "type" => "checkbox"
    );
    $cc_whmcs_bridge_options[210] = array(
        "name" => '<i class="fa fa-link"></i> '."jQuery library",
        "desc" => "Select the jQuery library you want to load. If you have a theme using jQuery, you may be able to solve conflicts by choosing a different library or no library. Note that ".WHMCS_BRIDGE." uses the jQuery $ function, hence it needs to be defined if you manage the loading of jQuery in your WordPress theme.",
        "id" => $cc_whmcs_bridge_shortname."_jquery",
        "options" => array('' => WHMCS_BRIDGE_PAGE, 'wp' => 'WordPress', 'checked' => 'None'),
        "default" => 'wp',
        "type" => "selectwithkey"
    );

    $cc_whmcs_bridge_options[220] = array(
        "name" => '<i class="fa fa-code"></i> '."Custom CSS",
        "desc" => 'Enter your custom CSS styles here',
        "id" => $cc_whmcs_bridge_shortname."_css",
        "type" => "textarea"
    );
    $cc_whmcs_bridge_options[230] = array(
        "name" => '<i class="fa fa-paint-brush"></i> '."Load ".WHMCS_BRIDGE_PAGE." style",
        "desc" => 'Select if you want to load the '.WHMCS_BRIDGE_PAGE.' style.css style sheet.',
        "id" => $cc_whmcs_bridge_shortname."_style",
        "type" => "checkbox"
    );
    $cc_whmcs_bridge_options[232] = array(
        "name" => '<i class="fa fa-money"></i> '."Load ".WHMCS_BRIDGE_PAGE." invoice style",
        "desc" => 'Select if you want to load the '.WHMCS_BRIDGE_PAGE.' invoicestyle.css style sheet.',
        "id" => $cc_whmcs_bridge_shortname."_invoicestyle",
        "type" => "checkbox"
    );

    $cc_whmcs_bridge_options[300] = array(
        "name" => "Other Settings",
        "type" => "heading",
        "desc" => "This section customizes miscellaneous settings."
    );
    $cc_whmcs_bridge_options[310] = array(
        "name" => '<i class="fa fa-bug"></i> '."Debug",
        "desc" => "If you have problems with the plugin, activate the debug mode to generate a debug log for our support team",
        "id" => $cc_whmcs_bridge_shortname."_debug",
        "type" => "checkbox"
    );

    if (!get_option('cc_whmcs_bridge_sso_active')) {
        $cc_whmcs_bridge_options[250] = array(
            "name" => '<i class="fa fa-pencil-square-o"></i> '."Template",
            "desc" => "Choose which template you would like to use. If you are running WHMCS v5 you can select \"portal\", WHMCS 6 no longer supports the \"portal\" template. WHMCS 7 it's recommended you use 'six'",
            "id" => $cc_whmcs_bridge_shortname."_template",
            "std" => 'six',
            "type" => "select",
            "options" => array("twenty-one", "six", "five", "portal"),
        );

        $cc_whmcs_bridge_options[320] = array(
            "name" => '<i class="fa fa-flag"></i> '."Footer",
            "desc" => "Show your support by displaying the ".WHMCS_BRIDGE_COMPANY." footer on your site.",
            "id" => $cc_whmcs_bridge_shortname."_footer",
            "std" => 'None',
            "type" => "select",
            "options" => array('Page','Site','None')
        );
    }

    if (defined("CC_WHMCS_BRIDGE_SSO_PLUGIN") && file_exists(WP_PLUGIN_DIR.'/whmcs-bridge-sso/includes/controlpanel.inc.php')) {
        require(WP_PLUGIN_DIR.'/whmcs-bridge-sso/includes/controlpanel.inc.php');
    }
    
    ksort($cc_whmcs_bridge_options);

    return $cc_whmcs_bridge_options;
}

function cc_whmcs_bridge_add_admin() {

    global $cc_whmcs_bridge_shortname,$current_user;

    if (in_array('administrator', $current_user->roles)) {
        $cc_whmcs_bridge_options = cc_whmcs_bridge_options();

        if (isset($_GET['page']) && ($_GET['page'] == "cc-ce-bridge-cp")) {
            if (isset($_REQUEST['action']) && 'install' == $_REQUEST['action']) {
                check_admin_referer('cc_bridge_update_settings_submit');

                delete_option('cc_whmcs_bridge_log');
                delete_option('cc_whmcs_bridge_sso_local_key');

                foreach ($cc_whmcs_bridge_options as $value) {
                    if (isset($value['id']) && !empty($_REQUEST[$value['id']])) {
                        $post_value = $_REQUEST[$value['id']];
                        if ($value['type'] == 'password' && function_exists('whmcs_bridge_sso_password_scrambler')) {
                            $post_value = whmcs_bridge_sso_password_scrambler($post_value, false);
                        }
                        update_option($value['id'], $post_value);
                    } else if (isset($value['id']) && empty($_REQUEST[$value['id']])) {
                        delete_option($value['id']);
                    }
                }

                if (isset($_REQUEST['cc_whmcs_bridge_sso_cache'])) {
                    foreach (glob(dirname(__FILE__) . '/cache/*') as $file) {
                        @unlink($file);
                    }
                    $xtrarg = '&whmcs_clear=true';
                } else {
                    $xtrarg = '';
                }

                cc_whmcs_bridge_install();
                if (function_exists('cc_whmcs_bridge_sso_update'))
                    cc_whmcs_bridge_sso_update();

                header("Location: " . get_admin_url() . "options-general.php?page=cc-ce-bridge-cp&installed=true" . $xtrarg);
                die;
            }
        }

        add_options_page(WHMCS_BRIDGE, WHMCS_BRIDGE, 'administrator', 'cc-ce-bridge-cp', 'cc_whmcs_bridge_admin');
    }
}

function cc_whmcs_bridge_admin() {

    global $cc_whmcs_bridge_shortname, $current_user;

    if (in_array('administrator', $current_user->roles)) {
        $controlpanelOptions = cc_whmcs_bridge_options();

        if (isset($_REQUEST['installed']))
            echo wp_kses_post('<div id="message" class="updated fade"><p><strong>' . WHMCS_BRIDGE . ' installed.</strong></p></div>');
        if (isset($_REQUEST['error'])) {
            $error = $_REQUEST['error'];

            echo wp_kses_post('<div id="message" class="updated fade"><p>The following error occured: <strong>' . $error . '</strong></p></div>');
            if (strstr($_REQUEST['error'], 'parsing')) {
                echo wp_kses_post('<div id="message" class="updated fade"><p>Parse errors occur when the bridge is unable to connect to your WHMCS API, for more information please <a href="http://i-plugins.com/whmcs/knowledgebase/1082/I-am-getting-intermittent-DOMDocument-or-loadXML-errors-showing-up-on-the-bridge.html" target="_blank"><strong>click here</strong></a></p></div>');
            }
        }

        ?>
        <script>
            jQuery(function () {
                jQuery("#bridgetabs").tabs();
            });
        </script>

        <div class="wrap">
            <h2><b><?php echo WHMCS_BRIDGE; ?></b></h2>
            <div id="bridgetabs" style="width:68%;float:left;">

                <ul>
                    <li><a href="#bridgetabs-1"><i class="fa fa-cog"></i> Settings</a></li>
                    <li><a href="#bridgetabs-2"><i class="fa fa-bug"></i> Log</a></li>
                    <li><a href="#bridgetabs-3"><i class="fa fa-refresh"></i> Sync</a></li>
                    <li><a href="#bridgetabs-4"><i class="fa fa-info"></i> Help</a></li>
                </ul>

                <div id="bridgetabs-1">
                    <?php require(dirname(__FILE__) . '/pages/settings.php'); ?>
                </div>
                <div id="bridgetabs-2">
                    <?php require(dirname(__FILE__) . '/pages/log.php'); ?>
                </div>
                <div id="bridgetabs-3">
                    <?php require(dirname(__FILE__) . '/pages/sync.php'); ?>
                </div>
                <div id="bridgetabs-4">
                    <?php require(dirname(__FILE__) . '/pages/help.php'); ?>
                </div>

            </div> <!-- end bridgetabs -->
            <div style="width:30%;float:right;">
                <?php
                require(dirname(__FILE__) . '/support-us.inc.php');
                zing_support_us('whmcs-bridge', 'whmcs-bridge', 'cc-ce-bridge-cp', CC_WHMCS_BRIDGE_VERSION);
                ?>
            </div>
        </div> <!-- end wrap -->
        <?php
    }
}

add_action('admin_menu', 'cc_whmcs_bridge_add_admin'); ?>