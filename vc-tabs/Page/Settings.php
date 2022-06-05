<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OXI_TABS_PLUGINS\Page;

/**
 * Description of Settings
 *
 * @author biplo
 */
class Settings {

    use \OXI_TABS_PLUGINS\Helper\CSS_JS_Loader;

    public $roles;
    public $saved_role;
    public $license;
    public $status;
    public $oxi_fixed_header;
    public $installed_plugins;

    /**
     * Constructor of Oxilab tabs Home Page
     *
     * @since 2.0.0
     */
    public function __construct() {
        $this->admin();
        $this->admin_ajax();
        $this->Render();
    }

    public function admin() {
        global $wp_roles;
        $this->roles = $wp_roles->get_names();
        $this->saved_role = get_option('oxi_addons_user_permission');
        $this->license = get_option('responsive_tabs_with_accordions_license_key');
        $this->status = get_option('responsive_tabs_with_accordions_license_status');
        $this->oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        $this->installed_plugins = get_plugins();
        if (empty($this->oxi_fixed_header)) {
            update_option('oxi_addons_fixed_header_size', 0);
        }
    }


   public function Render() {
        $this->admin_css_loader();
        ?>
        <div class="wrap">
            <?php
            echo apply_filters('oxi-tabs-plugin/admin_menu', TRUE);
            ?>
            <div class="oxi-addons-row oxi-addons-admin-settings">
                <form method="post">
                    <h2>General</h2>
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="oxi_addons_user_permission">Who Can Edit?</label>
                                </th>
                                <td>
                                    <fieldset>
                                        <select name="oxi_addons_user_permission" id="oxi_addons_user_permission">
                                            <?php foreach ($this->roles as $key => $role) { ?>
                                                <option value="<?php echo esc_attr($key); ?>" <?php selected($this->saved_role, $key); ?>><?php echo esc_html($role); ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="oxi-addons-settings-connfirmation oxi_addons_user_permission"></span>
                                        <br>
                                        <p class="description"><?php _e('Select the Role who can manage This Plugins.'); ?> <a target="_blank" href="https://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table">Help</a></p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="oxi_addons_font_awesome">Font Awesome Support</label>
                                </th>
                                <td>
                                    <fieldset>
                                        <label for="oxi_addons_font_awesome[yes]">
                                            <input type="radio" class="radio" id="oxi_addons_font_awesome[]" name="oxi_addons_font_awesome" value="" <?php checked('', get_option('oxi_addons_font_awesome'), true); ?>>Yes</label>
                                        <label for="oxi_addons_font_awesome[no]">
                                            <input type="radio" class="radio" id="oxi_addons_font_awesome[no]" name="oxi_addons_font_awesome" value="no"  <?php checked('no', get_option('oxi_addons_font_awesome'), true); ?>>No
                                        </label>
                                        <span class="oxi-addons-settings-connfirmation oxi_addons_font_awesome"></span>
                                        <br>
                                        <p class="description">Load Font Awesome CSS at shortcode loading, If your theme already loaded select No for faster loading</p>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="oxi_addons_fixed_header_size">Fixed Header Size</label>
                                </th>
                                <td class="valid">
                                    <input type="text" class="regular-text" id="oxi_addons_fixed_header_size" name="oxi_addons_fixed_header_size" value="<?php echo esc_attr($this->oxi_fixed_header); ?>">
                                    <span class="oxi-addons-settings-connfirmation oxi_addons_fixed_header_size "></span>
                                    <p class="description">Set Fixed Header Size for Responsive Tabs with Accordions.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <br>

                    <h2>Product License</h2>
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="responsive_tabs_with_accordions_license_key">License Key</label>
                                </th>
                                <td class="valid">
                                    <input type="text" class="regular-text" id="responsive_tabs_with_accordions_license_key" name="responsive_tabs_with_accordions_license_key" value="<?php echo esc_attr($this->license); ?>">
                                    <span class="oxi-addons-settings-connfirmation responsive_tabs_with_accordions_license_massage">
                                        <?php
                                        if ($this->status == 'valid' && empty($this->license)):
                                            echo '<span class="oxi-confirmation-success"></span>';
                                        elseif ($this->status == 'valid' && !empty($this->license)):
                                            echo '<span class="oxi-confirmation-success"></span>';
                                        elseif (!empty($this->license)):
                                            echo '<span class="oxi-confirmation-failed"></span>';
                                        else:
                                            echo '<span class="oxi-confirmation-blank"></span>';
                                        endif;
                                        ?>
                                    </span>
                                    <span class="oxi-addons-settings-connfirmation responsive_tabs_with_accordions_license_text">
                                        <?php
                                        if ($this->status == 'valid' && empty($this->license)):
                                            echo '<span class="oxi-addons-settings-massage">Pre Active</span>';
                                        elseif ($this->status == 'valid' && !empty($this->license)):
                                            echo '<span class="oxi-addons-settings-massage">Active</span>';
                                        elseif (!empty($this->license)):
                                            echo '<span class="oxi-addons-settings-massage">' . esc_html__($this->status, 'vc-tabs') . '</span>';
                                        else:
                                            echo '<span class="oxi-addons-settings-massage"></span>';
                                        endif;
                                        ?>
                                    </span>
                                    <p class="description">Activate your License to get direct plugin updates and official support.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function admin_ajax() {
        wp_enqueue_script('oxi-tabs-create', OXI_TABS_URL . '/assets/backend/custom/settings.js', false, OXI_TABS_TEXTDOMAIN);
    }
}
