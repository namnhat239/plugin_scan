<?php

namespace OXI_TABS_PLUGINS\Classes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Installation
 *
 * @author biplo
 */
class Installation {

    protected static $lfe_instance = NULL;

    /**
     * Constructor of Shortcode Addons
     *
     * @since 2.0.0
     */
    public function __construct() {

    }

    /**
     * Access plugin instance. You can create further instances by calling
     */
    public static function get_instance() {
        if (NULL === self::$lfe_instance)
            self::$lfe_instance = new self;

        return self::$lfe_instance;
    }

    public function Datatase() {
        $database = new \OXI_TABS_PLUGINS\Helper\Database();
        $database->update_database();
    }

    public function Tabs_Datatase() {
        $this->Datatase();
        $headersize = 0;
        add_option('oxi_addons_fixed_header_size', $headersize);
    }

    /**
     * Check woocommerce during active.
     * @return mixed
     */
    public function check_woocommerce_during_active() {
        $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
        if (stripos(implode($all_plugins), 'woocommerce.php')) {
            $value = 'yes';
            update_option('oxilab_tabs_woocommerce', $value);
        }
        return true;
    }

    /**
     * Plugin activation hook
     *
     * @since 3.1.0
     */
    public function plugin_activation_hook() {

        $this->Tabs_Datatase();
        $this->Tabs_Post_Count();
        $this->check_woocommerce_during_active();
        // Redirect to options page
        set_transient('oxi_tabs_activation_redirect', true, 30);
    }

    /**
     * Tabs Popular Post Count Query
     *
     * @since 3.3.0
     */
    public function Tabs_Post_Count() {
        $allposts = get_posts('numberposts=-1&post_type=post&post_status=any');
        foreach ($allposts as $postinfo) {
            add_post_meta($postinfo->ID, '_oxi_post_view_count', 0, true);
        }
    }

    /**
     * Plugin upgrade hook
     *
     * @since 1.0.0
     */
    public function plugin_upgrade_hook($upgrader_object, $options) {
        if ($options['action'] == 'update' && $options['type'] == 'plugin') {
            if (isset($options['plugins'][OXI_TABS_TEXTDOMAIN])) {
                $this->Tabs_Datatase();
                $this->Tabs_Post_Count();
            }
        }
    }

}
