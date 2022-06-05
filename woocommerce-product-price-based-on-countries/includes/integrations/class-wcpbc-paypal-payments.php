<?php
/**
 * Handle integration with PayPal Payments plugin.
 *
 * @see https://wordpress.org/plugins/woocommerce-paypal-payments/
 *
 * @since 2.2.0
 * @package WCPBC/Integrations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCPBC_PayPal_Payments' ) ) :

	/**
	 * WCPBC_Payu_Payment_Gateway class.
	 */
	class WCPBC_PayPal_Payments {

		/**
		 * Hook actions and filters
		 */
		public static function init() {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'paypal_payments_scripts' ), 50 );
			add_filter( 'woocommerce_paypal_payments_modules', array( __CLASS__, 'paypal_payments_modules' ) );
			add_filter( 'woocommerce_update_order_review_fragments', array( __CLASS__, 'update_order_review_fragments' ) );
		}

		/**
		 * PayPal Payments integration script.
		 */
		public static function paypal_payments_scripts() {
			if ( ! wp_script_is( 'ppcp-smart-button', 'queue' ) || ! is_checkout() ) {
				return;
			}

			// Enqueue the script.
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'wc-price-based-ppec-compatibility', WCPBC()->plugin_url() . 'assets/js/paypal-checkout-sdk-compatibility' . $suffix . '.js', array(), WCPBC()->version, true );

		}

		/**
		 * PayPal Payments modules.
		 *
		 * @param array $modules Array of modules.
		 */
		public static function paypal_payments_modules( $modules ) {

			if ( ! class_exists( 'WCPBC_PayPal_Api_Client_Module' ) ) {
				include dirname( __FILE__ ) . '/paypal-payments/class-wcpbc-paypal-api-client-module.php';
			}

			foreach ( $modules as $index => $module ) {
				if ( is_a( $module, 'WooCommerce\PayPalCommerce\ApiClient\ApiModule' ) ) {
					$modules[ $index ] = new WCPBC_PayPal_Api_Client_Module();
				}
			}
			return $modules;
		}

		/**
		 * Add the current currency to the update_order_review fragments.
		 *
		 * @param array $fragments Array of fragments to return in the AJAX call update_order_review.
		 * @return array
		 */
		public static function update_order_review_fragments( $fragments ) {
			if ( ! is_array( $fragments ) ) {
				$fragments = array();
			}
			$fragments['wcpbc_currency'] = get_woocommerce_currency();

			return $fragments;
		}
	}

	WCPBC_PayPal_Payments::init();
endif;
