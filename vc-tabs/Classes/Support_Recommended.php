<?php

namespace OXI_TABS_PLUGINS\Classes;

/**
 * Description of Support_Recommended
 *
 * @author biplo
 */
class Support_Recommended {

    const GET_LOCAL_PLUGINS = 'get_all_oxilab_plugins';
    const PLUGINS = 'https://www.oxilab.org/wp-json/oxilabplugins/v2/all_plugins';

    public $get_plugins = [];
    public $current_plugins = 'vc-tabs/index.php';

    /**
     * Revoke this function when the object is created.
     *
     */
    public function __construct() {
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
        if (!current_user_can('install_plugins')):
            return;
        endif;
        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }
        $this->extension();
        add_action('admin_notices', array($this, 'first_install'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_oxi_tabs_admin_recommended', array($this, 'notice_dissmiss'));
        add_action('admin_notices', array($this, 'dismiss_button_scripts'));
    }

    public function extension() {
        $response = get_transient(self::GET_LOCAL_PLUGINS);
        if (!$response || !is_array($response)) {
            $URL = self::PLUGINS;
            $request = wp_remote_request($URL);
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::GET_LOCAL_PLUGINS, $response, 10 * DAY_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }
        $this->get_plugins = $response;
    }

    /**
     * First Installation Track
     * @return void
     */
    public function first_install() {

        $installed_plugins = get_plugins();

        $plugin = [];
        $i = 1;

        foreach ($this->get_plugins as $key => $value) {
            if (!isset($installed_plugins[$value['modules-path']])):
                $plugin[$i] = $value;
                $i++;
            endif;
        }

        $recommend = [];

        for ($p = 1; $p < 100; $p++):
            if (isset($plugin[$p])):
                if (isset($plugin[$p]['dependency']) && $plugin[$p]['dependency'] != ''):
                    if (isset($installed_plugins[$plugin[$p]['dependency']])):
                        $recommend = $plugin[$p];
                        $p = 100;
                    endif;
                elseif ($plugin[$p]['modules-path'] != $this->current_plugins):
                    $recommend = $plugin[$p];
                    $p = 100;
                endif;
            else:
                $p = 100;
            endif;
        endfor;

        if (count($recommend) > 2 && $recommend['modules-path'] != ''):
            $plugin = explode('/', $recommend['modules-path'])[0];

            $massage = '<p>Thank you for using my Responsive Tabs with Accordions. ' . $recommend['modules-massage'] . '</p>';

            $install_url = wp_nonce_url(add_query_arg(array('action' => 'install-plugin', 'plugin' => $plugin), admin_url('update.php')), 'install-plugin' . '_' . $plugin);
            echo '<div class="wrap oxi-addons-admin-notifications" style=" width: auto;">
                        <h3>
                            <span class="dashicons dashicons-flag"></span>
                            Notifications
                        </h3>
                        <p></p>
                        <div class="oxi-addons-admin-notifications-holder">
                            <div class="oxi-addons-admin-notifications-alert">
                                ' . $massage . '
                                <p>' . sprintf('<a href="%s" class="button button-large button-primary">%s</a>', $install_url, __('Install Now', OXI_TABS_TEXTDOMAIN)) . ' &nbsp;&nbsp;<a href="#" class="button button-large button-secondary oxi-tabs-admin-recommended-dismiss" sup-data="done">No, Thanks</a></p>
                            </div>
                        </div>
                        <p></p>
                    </div>';
        endif;
    }

    /**
     * Admin Notice CSS file loader
     * @return void
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_script("jquery");
        wp_enqueue_style('oxilab_tabs-admin-notice-css', OXI_TABS_URL . '/assets/backend/css/notice.css', false, OXI_TABS_PLUGIN_VERSION);
        $this->dismiss_button_scripts();
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function dismiss_button_scripts() {
        wp_enqueue_script('oxi_tabs-admin-recommended', OXI_TABS_URL . '/assets/backend/custom/admin-recommended.js', false, OXI_TABS_PLUGIN_VERSION);
        wp_localize_script('oxi_tabs-admin-recommended', 'oxi_tabs_admin_recommended', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('oxi_tabs_admin_recommended')));
    }

    /**
     * Admin Notice Ajax  loader
     * @return void
     */
    public function notice_dissmiss() {
        if (isset($_POST['_wpnonce']) || wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'oxi_tabs_admin_recommended')):
            $data = 'done';
            update_option('responsive_tabs_with_accordions_recommended', $data);
            echo 'done';
        else:
            return;
        endif;

        die();
    }

}
