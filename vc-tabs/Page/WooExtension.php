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
class WooExtension {

    use \OXI_TABS_PLUGINS\Helper\CSS_JS_Loader;

    /**
     * Constructor of Oxilab tabs Home Page
     *
     * @since 2.0.0
     */
    public $get_style;
    public $default_tabs;
    public $customize_default_tabs;

    public function __construct() {
        $this->admin();
        $this->admin_ajax();
        $this->Render();
    }

    public function admin() {
        $new = new \OXI_TABS_PLUGINS\Modules\Shortcode();
        $this->get_style = $new->get_all_style();
        $this->default_tabs = get_option('oxilab_tabs_woocommerce_default');
        $this->customize_default_tabs = json_decode(stripslashes(get_option('oxilab_tabs_woocommerce_customize_default_tabs')), true);
        if (!is_array($this->customize_default_tabs)):
            $this->customize_default_tabs();
        endif;
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function admin_ajax() {
        wp_enqueue_script("jquery");
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-draggable');
       
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script("jquery");

        wp_enqueue_script('jquery.serializejson.min', OXI_TABS_URL . 'assets/backend/js/jquery.serializejson.min.js', false, OXI_TABS_PLUGIN_VERSION);
        wp_enqueue_style('fontawesome-iconpicker', OXI_TABS_URL . 'assets/backend/css/fontawesome-iconpicker.css', false, OXI_TABS_PLUGIN_VERSION);
        wp_enqueue_script('fontawesome-iconpicker', OXI_TABS_URL . 'assets/backend/js/fontawesome-iconpicker.js', false, OXI_TABS_PLUGIN_VERSION);

        wp_enqueue_script('oxi-tabs-create', OXI_TABS_URL . '/assets/backend/custom/woo-extension.js', false, OXI_TABS_TEXTDOMAIN);
        wp_localize_script('oxi-tabs-create', 'oxilabtabsultimate', array(
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest')
        ));
        wp_enqueue_style('oxilab_tabs_woo-styles', OXI_TABS_URL . 'assets/woocommerce/css/admin.css', false, OXI_TABS_PLUGIN_VERSION);
    }


    public function Render() {
        $this->admin_css_loader();
        ?>
        <div class="wrap">   
            <?php
            echo apply_filters('oxi-tabs-plugin/admin_menu', TRUE);
            ?>
            <div class="oxi-addons-row">

                <div class="about-wrap text-center">
                    <h1>WooCommerce Tabs Extension</h1>
                    <div class="about-text">
                        Thank you for Installing Our Responsive Tabs, The most friendly Tabs, Also simply best Tabs extension for WooCommerce. Here's how to get started.
                    </div>
                </div>
                <div class="feature-section">
                    <div class="about-container">
                        <div class="about-addons-videos"><iframe src="https://www.youtube.com/embed/LLhW2Nv1WDo" frameborder="0" allowfullscreen="" class="about-video"></iframe></div>
                    </div>
                </div>


                <div class="oxi-addons-tabs-woo-extension row">
                    <div class="col-lg-6 col-md-12">
                        <div class="sa-el-admin-wrapper">
                            <div class="sa-el-admin-block">
                                <div class="sa-el-admin-header">
                                    <div class="sa-el-admin-header-icon">
                                        <span class="dashicons dashicons-format-aside"></span>
                                    </div>    
                                    <h4 class="sa-el-admin-header-title">Global Settings</h4>  
                                </div>
                                <div class="sa-el-admin-block-content">
                                    <div class="oxi-sa-cards">
                                        <div class="oxi-sa-cards-h1">
                                            Active Extension
                                            <p>Active our Tabs custom layouts..</p>
                                        </div>
                                        <div class="responsive_tabs_with_accordions_license_massage"></div>
                                        <div class="oxi-sa-cards-switcher ">
                                            <input type="checkbox" class="oxi-addons-switcher-btn" id="oxilab_tabs_woocommerce" name="oxilab_tabs_woocommerce" <?php echo  get_option('oxilab_tabs_woocommerce') == 'yes' ? 'checked="checked"' : ''; ?>>
                                            <label for="oxilab_tabs_woocommerce" class="oxi-addons-switcher-label"></label>
                                        </div>
                                    </div>
                                    <div class="oxi-sa-cards oxilab_tabs_woocommerce_active">
                                        <div class="oxi-sa-cards-h1">
                                            Use a Custom Filter
                                            <p>If you're using a page builder and you're having issues toggle this setting on. This will allow other plugins to use the WordPress 'the_content' filter will we use our own custom version.</p>
                                        </div>
                                        <div class="responsive_tabs_with_accordions_license_massage"></div>
                                        <div class="oxi-sa-cards-switcher ">
                                            <input type="checkbox" class="oxi-addons-switcher-btn" id="oxi_tabs_use_the_content" name="oxi_tabs_use_the_content" <?php echo get_option('oxi_tabs_use_the_content') == 'yes' ? 'checked="checked"' : ''; ?>>
                                            <label for="oxi_tabs_use_the_content" class="oxi-addons-switcher-label"></label>
                                        </div>
                                    </div>
                                    <div class="oxi-sa-cards oxilab_tabs_woocommerce_active">
                                        <div class="oxi-sa-cards-h1">
                                            Default Layouts
                                            <p>Select Default layouts for WooCommerce. Default Layouts will works globally while empty selected from woocommerce product page</p>
                                        </div>
                                        <div class="responsive_tabs_with_accordions_license_massage"></div>
                                        <div class="oxi-sa-cards-switcher ">
                                            <select name="oxilab_tabs_woocommerce_default" id="oxilab_tabs_woocommerce_default">
                                                <?php foreach ($this->get_style as $key => $value) { ?>
                                                    <option value="<?php echo $key; ?>" <?php selected($this->default_tabs, $key); ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 oxilab_tabs_woocommerce_active">
                        <form method="post" id="oxi-addons-customize_default_tabs_form">
                            <div class="woo-oxilab-tabs-admin-container">
                                <div class="woo-oxilab-tabs-admin-header">
                                    <h3>Default Tabs Customization</h3>
                                    <p>Customization default tabs globally,  include tabs priority value and custom callback function if you want.</p>
                                </div>
                                <div class="woo-oxilab-tabs-admin-body">
                                    <?php
                                    foreach ($this->customize_default_tabs as $key => $value) {
                                        ?>
                                        <div class="woo-oxilab-tabs-admin-tabs oxi-hidden">
                                            <div class="oxi-woo-header">
                                                <div class="oxi-woo-header-text"><?php echo ucfirst($key) ?></div>
                                                <div class="oxi-delete-button"></div>
                                            </div>
                                            <div class="woo-oxi-content">
                                                <p class="form-field [<?php echo $key; ?>][unset]_field ">
                                                    <label for="[<?php echo $key; ?>][unset]">Unset This Tabs</label>
                                                    <span class="oxi-sa-cards-switcher ">
                                                        <input type="checkbox" class="oxi-addons-switcher-btn oxi-addons-switcher-btn-unset" id="[<?php echo $key; ?>][unset]" name="[<?php echo $key; ?>][unset]" <?php echo isset($value['unset']) && $value['unset'] == 'on' ? 'checked="checked"' : ''; ?>>
                                                        <label for="[<?php echo $key; ?>][unset]" class="oxi-addons-switcher-label"></label>
                                                    </span>
                                                </p>
                                                <p class="form-field [<?php echo $key; ?>][title]_field ">
                                                    <label for="[<?php echo $key; ?>][title]">Tab Title</label>
                                                    <input type="text" class="oxilab_tabs_woo_layouts_title_field" name="[<?php echo $key; ?>][title]" id="[<?php echo $key; ?>][title]" value="<?php echo $value['title']; ?>" placeholder="Write New Title else make it Blank"> 
                                                </p>
                                                <p class="form-field [<?php echo $key; ?>][icon]_field">
                                                    <label for="[<?php echo $key; ?>][icon]">Custom Icon</label>
                                                    <input type="text" class="oxilab_tabs_woo_layouts_icon_field" style="" name="[<?php echo $key; ?>][icon]" id="[<?php echo $key; ?>][icon]" value="<?php echo $value['icon'] ?>" placeholder="Select Icon for <?php echo ucfirst($key); ?>">
                                                </p>
                                                <p class="form-field [<?php echo $key; ?>][priority]_field">
                                                    <label for="[<?php echo $key; ?>][priority]">Tab Priority</label>
                                                    <input type="text" class="oxilab_tabs_woo_layouts_priority_field" style="" name="[<?php echo $key; ?>][priority]" id="[<?php echo $key; ?>][priority]" value="<?php echo $value['priority'] ?>" placeholder="Tabs Priority">
                                                </p>

                                                <p class="form-field [<?php echo $key; ?>][callback]_field ">
                                                    <label for="[<?php echo $key; ?>][callback]">Callback Function</label>
                                                    <input type="text" class="oxilab_tabs_woo_layouts_callback_field" style="" name="[<?php echo $key; ?>][callback]" id="[<?php echo $key; ?>][callback]" value="<?php echo $value['callback'] ?>" placeholder="Add callback function else make it blank"> 
                                                </p>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                </div>
                                <div class="oxi-woo-tabs-add-rows">
                                    <input type="submit" class="oxi-woo-tabs-add-rows-button"value="Save Tabs">


                                </div>
                            </div>
                        </form>

                    </div>

                </div>

            </div>
        </div>  
        <?php
    }

    public function customize_default_tabs() {
        $this->customize_default_tabs = [
            'description' => [
                'unset' => false,
                'title' => '',
                'icon' => '',
                'priority' => '',
                'callback' => '',
            ],
            'reviews' => [
                'unset' => false,
                'title' => '',
                'icon' => '',
                'priority' => '',
                'callback' => '',
            ],
            'additional_information' => [
                'unset' => false,
                'title' => '',
                'icon' => '',
                'priority' => '',
                'callback' => '',
            ]
        ];
    }
}
