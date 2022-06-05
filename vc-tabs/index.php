<?php

/*
  Plugin Name: Tabs – Responsive Tabs with WooCommerce Product Tab Extension
  Plugin URI: https://www.oxilabdemos.com/responsive-tabs/
  Description: Tabs – Responsive Tabs with WooCommerce Product Tab Extension is essayist way to awesome WordPress Responsive Tabs Plugin with many features.
  Author: Biplob Adhikari
  Author URI: http://www.oxilab.org/
  Version: 3.9.1
 */
if (!defined('ABSPATH'))
    exit;

define('OXI_TABS_FILE', __FILE__);
define('OXI_TABS_BASENAME', plugin_basename(__FILE__));
define('OXI_TABS_PATH', plugin_dir_path(__FILE__));
define('OXI_TABS_URL', plugins_url('/', __FILE__));
define('OXI_TABS_PLUGIN_VERSION', '3.9.1');
define('OXI_TABS_TEXTDOMAIN', 'oxi-tabs-plugin');

/**
 * Including composer autoloader globally.
 *
 * @since 3.1.0
 */
require_once OXI_TABS_PATH . 'autoloader.php';

/**
 * Run plugin after all others plugins
 *
 * @since 3.1.0
 */
add_action('plugins_loaded', function () {
    \OXI_TABS_PLUGINS\Classes\Bootstrap::instance();
});

/**
 * Activation hook
 *
 * @since 3.1.0
 */
register_activation_hook(__FILE__, function () {
    $Installation = new \OXI_TABS_PLUGINS\Classes\Installation();
    $Installation->plugin_activation_hook();
});

/**
 * Upgrade hook
 *
 * @since 3.1.0
 */
add_action('upgrader_process_complete', function ($upgrader_object, $options) {
    $Installation = new \OXI_TABS_PLUGINS\Classes\Installation();
    $Installation->plugin_upgrade_hook($upgrader_object, $options);
}, 10, 2);

