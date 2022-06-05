<?php
/*
* Plugin Name: Smart Manager - WooCommerce Inventory Management, Bulk Edit & more...
* Plugin URI: https://www.storeapps.org/product/smart-manager/
* Description: <strong>Lite Version Installed</strong>. The #1 and a powerful tool to manage stock, inventory from a single place. Super quick and super easy.
* Version: 6.1.1
* Author: StoreApps
* Author URI: https://www.storeapps.org/
* Text Domain: smart-manager-for-wp-e-commerce
* Domain Path: /languages/
* Requires at least: 4.8
* Tested up to: 6.0.0
* Requires PHP: 5.6+
* WC requires at least: 2.0.0
* WC tested up to: 6.5.1
* Copyright (c) 2010 - 2022 StoreApps. All rights reserved.
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'SM_PLUGIN_FILE' ) ) {
	define( 'SM_PLUGIN_FILE', __FILE__ );
}

if ( ! class_exists( 'Smart_Manager' ) && file_exists( ( dirname( __FILE__ ) ) . '/class-smart-manager.php' ) ) {
	include_once 'class-smart-manager.php';
}
