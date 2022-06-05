<?php

namespace OXI_TABS_PLUGINS\Helper;

trait Admin_helper {

    /**
     * Plugin fixed
     *
     * @since 3.1.0
     */
    public function fixed_data($agr) {
        return hex2bin($agr);
    }

    /**
     * Plugin fixed debugging data
     *
     * @since 3.1.0
     */
    public function fixed_debug_data($str) {
        return bin2hex($str);
    }

    public function Tabs_Icon() {
        ?>
        <style type='text/css' media='screen'>
            #adminmenu #toplevel_page_oxi-tabs-ultimate  div.wp-menu-image:before {
                content: "\f163";
            }
        </style>
        <?php
    }

    

    public function admin_url_convert($agr) {
        return admin_url(strpos($agr, 'edit') !== false ? $agr : 'admin.php?page=' . $agr);
    }

    public function SupportAndComments($agr) {
        echo '  <div class="oxi-addons-admin-notifications">
                    <h3>
                        <span class="dashicons dashicons-flag"></span>
                        Notifications
                    </h3>
                    <p></p>
                    <div class="oxi-addons-admin-notifications-holder">
                        <div class="oxi-addons-admin-notifications-alert">
                            <p>Thank you for using my Responsive Tabs with WooCommerce Extension. I Just wanted to see if you have any questions or concerns about my plugins. If you do, Please do not hesitate to <a href="https://wordpress.org/support/plugin/vc-tabs#new-post">file a bug report</a>. </p>
                            ' . (apply_filters('oxi-tabs-plugin/pro_version', false) ? '' : '<p>By the way, did you know we also have a <a href="https://www.oxilabdemos.com/responsive-tabs/pricing">Premium Version</a>? It offers lots of options with automatic update. It also comes with 16/5 personal support.</p>') . '
                            <p>Thanks Again!</p>
                            <p></p>
                        </div>
                    </div>
                    <p></p>
                </div>';
    }

    /**
     * Plugin Admin Top Menu
     *
     * @since 2.0.0
     */
    public function oxilab_admin_menu($agr) {

        $response = [
            'Shortcode' => [
                'name' => 'Shortcode',
                'homepage' => 'oxi-tabs-ultimate'
            ],
            'Create New' => [
                'name' => 'Create New',
                'homepage' => 'oxi-tabs-ultimate-new'
            ],
            'Import Template' => [
                'name' => 'Import Template',
                'homepage' => 'oxi-tabs-ultimate-new&import'
            ]
        ];

        $bgimage = OXI_TABS_URL . 'assets/image/sa-logo.png';
        $sub = '';
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxilab-new-admin-menu">
                <div class="oxi-site-logo">
                    <a href="<?php echo esc_url($this->admin_url_convert('oxi-tabs-ultimate')) ?>" class="header-logo" style=" background-image: url(<?php echo esc_url($bgimage) ?>);">
                    </a>
                </div>
                <nav class="oxilab-sa-admin-nav">
                    <ul class="oxilab-sa-admin-menu">
                        <?php
                        $GETPage = sanitize_text_field($_GET['page']);

                        foreach ($response as $key => $value) {
                            ?>
                            <li <?php
                            if ($GETPage == $value['homepage']):
                                echo ' class="active" ';
                            endif;
                            ?>><a href="<?php echo esc_url($this->admin_url_convert($value['homepage'])) ?>"><?php echo esc_html($this->name_converter($value['name'])) ?></a></li>
                                <?php
                            }
                            ?>

                    </ul>
                    <ul class="oxilab-sa-admin-menu2">
                        <?php
                        if (apply_filters('oxi-tabs-plugin/pro_version', false) == FALSE):
                            echo '<li class="fazil-class" ><a target="_blank" href="https://www.oxilabdemos.com/responsive-tabs/pricing">Upgrade</a></li>';
                        endif;
                        ?>
                        <li class="saadmin-doc"><a target="_black" href="https://www.oxilabdemos.com/responsive-tabs/docs/">Docs</a></li>
                        <li class="saadmin-doc"><a target="_black" href="https://wordpress.org/support/plugin/vc-tabs/">Support</a></li>
                        <li class="saadmin-set"><a href="<?php echo  esc_url(admin_url('admin.php?page=oxi-tabs-ultimate-settings')) ?>"><span class="dashicons dashicons-admin-generic"></span></a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <?php
    }

    public function Admin_Menu() {
        $user_role = get_option('oxi_addons_user_permission');
        $role_object = get_role($user_role);
        $first_key = '';
        if (isset($role_object->capabilities) && is_array($role_object->capabilities)) {
            reset($role_object->capabilities);
            $first_key = key($role_object->capabilities);
        } else {
            $first_key = 'manage_options';
        }
        add_menu_page('Content Tabs', 'Content Tabs', $first_key, 'oxi-tabs-ultimate', [$this, 'tabs_home']);
        add_submenu_page('oxi-tabs-ultimate', 'Content Tabs', 'Shortcode', $first_key, 'oxi-tabs-ultimate', [$this, 'tabs_home']);
        add_submenu_page('oxi-tabs-ultimate', 'Create New', 'Create New', $first_key, 'oxi-tabs-ultimate-new', [$this, 'tabs_create']);
        add_submenu_page('oxi-tabs-ultimate', 'Settings', 'Settings', $first_key, 'oxi-tabs-ultimate-settings', [$this, 'tabs_settings']);
        if (is_plugin_active('woocommerce/woocommerce.php')):
            add_submenu_page('oxi-tabs-ultimate', 'Woo Extension', 'Woo Extension', $first_key, 'oxi-tabs-ultimate-woo-extension', [$this, 'woo_extension']);
        endif;
        add_submenu_page('oxi-tabs-ultimate', 'Oxilab Plugins', 'Oxilab Plugins', $first_key, 'oxi-tabs-ultimate-plugins', [$this, 'oxilab_plugins']);
        add_submenu_page('oxi-tabs-ultimate', 'Welcome To Responsive Tabs with  Accordions', 'Support', $first_key, 'oxi-tabs-ultimate-welcome', [$this, 'oxi_tabs_welcome']);
    }

    public function tabs_home() {
        new \OXI_TABS_PLUGINS\Page\Home();
    }

    public function tabs_create() {
        $styleid = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        if (!empty($styleid) && $styleid > 0):
            $style = $this->database->wpdb->get_row($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
            $template = ucfirst($style['style_name']);
            if (!array_key_exists('rawdata', $style)):
                $Installation = new \OXI_TABS_PLUGINS\Classes\Installation();
                $Installation->Datatase();
                new \OXI_TABS_PLUGINS\Page\Create();
                return;
            endif;
            $row = json_decode(stripslashes($style['rawdata']), true);
            if (is_array($row)):
                $cls = '\OXI_TABS_PLUGINS\Render\Admin\\' . $template;
            else:
                $cls = '\OXI_TABS_PLUGINS\Render\Old_Admin\\' . $template;
            endif;
            new $cls();
        else:
            new \OXI_TABS_PLUGINS\Page\Create();
        endif;
    }

    public function tabs_settings() {
        new \OXI_TABS_PLUGINS\Page\Settings();
    }

    public function woo_extension() {
        new \OXI_TABS_PLUGINS\Page\WooExtension();
    }

    public function oxilab_plugins() {
        new \OXI_TABS_PLUGINS\Page\Plugins();
    }

    public function oxi_tabs_welcome() {
        new \OXI_TABS_PLUGINS\Page\Welcome();
    }

    public function User_Reviews() {
        $this->admin_recommended();
        $this->admin_notice();
    }

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_notice_status() {
        $data = get_option('responsive_tabs_with_accordions_no_bug');
        return $data;
    }

    /**
     * Admin Install date Check
     *
     * @since 2.0.0
     */
    public function installation_date() {
        $data = get_option('responsive_tabs_with_accordions_activation_date');
        if (empty($data)):
            $data = strtotime("now");
            update_option('responsive_tabs_with_accordions_activation_date', $data);
        endif;
        return $data;
    }

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_recommended_status() {
        $data = get_option('responsive_tabs_with_accordions_recommended');
        return $data;
    }

    public function admin_recommended() {
        if (!empty($this->admin_recommended_status())):
            return;
        endif;
        if (strtotime('-1 days') < $this->installation_date()):
            return;
        endif;

        new \OXI_TABS_PLUGINS\Classes\Support_Recommended();
    }

    public function admin_notice() {
        if (!empty($this->admin_notice_status())):
            return;
        endif;
        if (strtotime('-7 days') < $this->installation_date()):
            return;
        endif;
        new \OXI_TABS_PLUGINS\Classes\Support_Reviews();
    }
/**
     * Plugin check Current Tabs
     *
     * @since 2.0.0
     */
    public function check_current_tabs($agr) {
        $vs = get_option($this->fixed_data('726573706f6e736976655f746162735f776974685f6163636f7264696f6e735f6c6963656e73655f737461747573'));
        if ($vs == $this->fixed_data('76616c6964')) {
            return true;
        } else {
            return false;
        }
    }
}
