<?php

use TierPricingTable\TierPricingTablePlugin;

/**
 * Plugin Name: WooCommerce Tiered Price Table
 * Description:       Allows you to set price for a certain quantity of a product. Shows pricing and product summary table. Supports displaying pricing table in a tooltip.
 * Version:           2.8.0
 * Author:            bycrik
 * Author URI:        https://u2code.com
 * Plugin URI:        https://u2code.com/plugins/tiered-pricing-table-for-woocommerce/
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tier-pricing-table
 * Domain Path:       /languages/
 *
 * WC requires at least: 4.0
 * WC tested up to: 6.6
 *
  */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( class_exists( '\TierPricingTable\TierPricingTablePlugin' ) ) {
	add_action( 'admin_notices', function () {
		?>
        <div class='notice notice-error'>
            <h2>Tiered Pricing Table for WooCommerce</h2>
            <p>
				<?php esc_html_e( 'It looks like the free version of the plugin is active. To use the premium, please deactivate the
                    free version.', 'tier-pricing-table' ); ?>
                <b><?php esc_html_e( 'All the data will be saved.', 'tier-pricing-table' ); ?></b>
            </p>
        </div>
		<?php
	} );

	return false;
}

require_once 'licence-init.php';

call_user_func( function () {

	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

	$main = new TierPricingTablePlugin( __FILE__ );

	register_activation_hook( __FILE__, array( $main, 'activate' ) );

	register_deactivation_hook( __FILE__, array( $main, 'deactivate' ) );

	add_action( 'uninstall', array( TierPricingTablePlugin::class, 'uninstall' ) );

	$main->run();
} );