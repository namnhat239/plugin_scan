<?php
/**
 * WooCommerce Braintree Gateway
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@woocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Braintree Gateway to newer
 * versions in the future. If you wish to customize WooCommerce Braintree Gateway for your
 * needs please refer to http://docs.woocommerce.com/document/braintree/
 *
 * @package   WC-Braintree/Gateway
 * @author    WooCommerce
 * @copyright Copyright: (c) 2016-2020, Automattic, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

use SkyVerge\WooCommerce\PluginFramework\v5_10_12 as Framework;

defined( 'ABSPATH' ) or exit;

/**
 * WooCommerce Gateway Braintree Main Plugin Class.
 *
 * @since 2.0.0
 */
class WC_Braintree extends Framework\SV_WC_Payment_Gateway_Plugin {


	/** plugin version number */
	const VERSION = '2.6.4';

	/** Braintree JS SDK version  */
	const BRAINTREE_JS_SDK_VERSION = '3.73.1';

	/** @var WC_Braintree single instance of this plugin */
	protected static $instance;

	/** plugin id */
	const PLUGIN_ID = 'braintree';

	/** credit card gateway class name */
	const CREDIT_CARD_GATEWAY_CLASS_NAME = 'WC_Gateway_Braintree_Credit_Card';

	/** credit card gateway ID */
	const CREDIT_CARD_GATEWAY_ID = 'braintree_credit_card';

	/** PayPal gateway class name */
	const PAYPAL_GATEWAY_CLASS_NAME = 'WC_Gateway_Braintree_PayPal';

	/** PayPal gateway ID */
	const PAYPAL_GATEWAY_ID = 'braintree_paypal';

	/** @var \WC_Braintree_Frontend the frontend instance */
	protected $frontend;


	/**
	 * Initializes the plugin
	 *
	 * @since 2.0
	 */
	public function __construct() {

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			array(
				'text_domain' => 'woocommerce-gateway-paypal-powered-by-braintree',
				'gateways'    => array(
					self::CREDIT_CARD_GATEWAY_ID => self::CREDIT_CARD_GATEWAY_CLASS_NAME,
					self::PAYPAL_GATEWAY_ID      => self::PAYPAL_GATEWAY_CLASS_NAME,
				),
				'require_ssl' => false,
				'supports'    => array(
					self::FEATURE_CAPTURE_CHARGE,
					self::FEATURE_MY_PAYMENT_METHODS,
					self::FEATURE_CUSTOMER_ID,
				),
				'dependencies' => [
					'php_extensions' => [ 'curl', 'dom', 'hash', 'openssl', 'SimpleXML', 'xmlwriter' ],
				],
			)
		);

		// include required files
		$this->includes();

		// add class aliases for framework classes renamed in 2.4.0.
		$this->add_framework_class_aliases();

		// handle Braintree Auth connect/disconnect
		add_action( 'admin_init', [ $this, 'handle_auth_connect' ] );
		add_action( 'admin_init', [ $this, 'handle_auth_disconnect' ] );
	}


	/**
	 * Include required files
	 *
	 * @since 2.0
	 */
	public function includes() {

		// frontend instance
		if ( ! is_admin() && ! wp_doing_ajax() ) {
			$this->frontend = $this->load_class( '/includes/class-wc-braintree-frontend.php', 'WC_Braintree_Frontend' );
		}

		// gateways
		require_once( $this->get_plugin_path() . '/includes/class-wc-gateway-braintree.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-gateway-braintree-credit-card.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-gateway-braintree-paypal.php' );

		// payment method
		require_once( $this->get_plugin_path() . '/includes/class-wc-payment-token-braintree-paypal.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-braintree-payment-method-handler.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-braintree-payment-method.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-braintree-my-payment-methods.php' );

		// payment forms
		require_once( $this->get_plugin_path() . '/includes/payment-forms/abstract-wc-braintree-payment-form.php' );
		require_once( $this->get_plugin_path() . '/includes/payment-forms/class-wc-braintree-hosted-fields-payment-form.php' );
		require_once( $this->get_plugin_path() . '/includes/payment-forms/class-wc-braintree-paypal-payment-form.php' );

		// payment buttons
		require_once( $this->get_plugin_path() . '/includes/PayPal/Buttons/Abstract_Button.php' );
		require_once( $this->get_plugin_path() . '/includes/PayPal/Buttons/Cart.php' );
		require_once( $this->get_plugin_path() . '/includes/PayPal/Buttons/Product.php' );

		// integrations
		if ( $this->is_plugin_active( 'woocommerce-product-addons.php' ) ) {

			$this->load_class( '/includes/integrations/Product_Addons.php', '\\WC_Braintree\\Integrations\\Product_Addons' );
		}
	}


	/**
	 * Adds class aliases for framework classes renamed in 2.4.0.
	 *
	 * TODO: remove this method by version 3.0.0 or by 2021-06-10 {WV 2020-06-10}
	 *
	 * @since 2.4.0
	 */
	private function add_framework_class_aliases() {

		// framework classes available in version 2.3.x of the plugin
		$class_names = [
			'Addresses\\Address',
			'Addresses\\Customer_Address',
			'Admin\\Setup_Wizard',
			'Payment_Gateway\\Admin\\Setup_Wizard',
			'Payment_Gateway\\Handlers\\Capture',
			'Payment_Gateway\\REST_API',
			'Plugin\\Lifecycle',
			'REST_API',
			'SV_WC_API_Base',
			'SV_WC_API_Exception',
			'SV_WC_API_JSON_Request',
			'SV_WC_API_JSON_Response',
			'SV_WC_API_Request',
			'SV_WC_API_Response',
			'SV_WC_API_XML_Request',
			'SV_WC_API_XML_Response',
			'SV_WC_Admin_Notice_Handler',
			'SV_WC_Data_Compatibility',
			'SV_WC_DateTime',
			'SV_WC_Helper',
			'SV_WC_Hook_Deprecator',
			'SV_WC_Order_Compatibility',
			'SV_WC_Payment_Gateway',
			'SV_WC_Payment_Gateway_API',
			'SV_WC_Payment_Gateway_API_Authorization_Response',
			'SV_WC_Payment_Gateway_API_Create_Payment_Token_Response',
			'SV_WC_Payment_Gateway_API_Customer_Response',
			'SV_WC_Payment_Gateway_API_Get_Tokenized_Payment_Methods_Response',
			'SV_WC_Payment_Gateway_API_Payment_Notification_Credit_Card_Response',
			'SV_WC_Payment_Gateway_API_Payment_Notification_Response',
			'SV_WC_Payment_Gateway_API_Payment_Notification_eCheck_Response',
			'SV_WC_Payment_Gateway_API_Request',
			'SV_WC_Payment_Gateway_API_Response',
			'SV_WC_Payment_Gateway_API_Response_Message_Helper',
			'SV_WC_Payment_Gateway_Admin_Order',
			'SV_WC_Payment_Gateway_Admin_Payment_Token_Editor',
			'SV_WC_Payment_Gateway_Admin_User_Handler',
			'SV_WC_Payment_Gateway_Apple_Pay',
			'SV_WC_Payment_Gateway_Apple_Pay_AJAX',
			'SV_WC_Payment_Gateway_Apple_Pay_API',
			'SV_WC_Payment_Gateway_Apple_Pay_API_Request',
			'SV_WC_Payment_Gateway_Apple_Pay_API_Response',
			'SV_WC_Payment_Gateway_Apple_Pay_Admin',
			'SV_WC_Payment_Gateway_Apple_Pay_Frontend',
			'SV_WC_Payment_Gateway_Apple_Pay_Payment_Response',
			'SV_WC_Payment_Gateway_Direct',
			'SV_WC_Payment_Gateway_Exception',
			'SV_WC_Payment_Gateway_Helper',
			'SV_WC_Payment_Gateway_Hosted',
			'SV_WC_Payment_Gateway_Integration',
			'SV_WC_Payment_Gateway_My_Payment_Methods',
			'SV_WC_Payment_Gateway_Payment_Form',
			'SV_WC_Payment_Gateway_Payment_Notification_Tokenization_Response',
			'SV_WC_Payment_Gateway_Payment_Token',
			'SV_WC_Payment_Gateway_Payment_Tokens_Handler',
			'SV_WC_Payment_Gateway_Plugin',
			'SV_WC_Payment_Gateway_Privacy',
			'SV_WC_Plugin',
			'SV_WC_Plugin_Compatibility',
			'SV_WC_Plugin_Dependencies',
			'SV_WC_Plugin_Exception',
			'SV_WC_Product_Compatibility',
			'SV_WP_Admin_Message_Handler',
			'SV_WP_Async_Request',
			'SV_WP_Background_Job_Handler',
			'SV_WP_Job_Batch_Handler',
		];

		// subscriptions
		if ( $this->is_subscriptions_active() ) {
			$class_names[] = 'SV_WC_Payment_Gateway_Integration_Subscriptions';
		}

		// pre-orders
		if ( $this->is_pre_orders_active() ) {
			$class_names[] = 'SV_WC_Payment_Gateway_Integration_Pre_Orders';
		}

		// require classes that are not loaded by default to prevent 'Class not found' warnings while defining aliases
		require_once $this->get_framework_path() . '/utilities/class-sv-wp-async-request.php';
		require_once $this->get_framework_path() . '/utilities/class-sv-wp-background-job-handler.php';
		require_once $this->get_framework_path() . '/utilities/class-sv-wp-job-batch-handler.php';

		require_once $this->get_payment_gateway_framework_path() . '/admin/abstract-sv-wc-payment-gateway-plugin-admin-setup-wizard.php';
		require_once $this->get_payment_gateway_framework_path() . '/External_Checkout/apple-pay/api/class-sv-wc-payment-gateway-apple-pay-api.php';
		require_once $this->get_payment_gateway_framework_path() . '/External_Checkout/apple-pay/api/class-sv-wc-payment-gateway-apple-pay-api-request.php';
		require_once $this->get_payment_gateway_framework_path() . '/External_Checkout/apple-pay/api/class-sv-wc-payment-gateway-apple-pay-api-response.php';

		foreach ( $class_names as $class_name ) {
			class_alias( "SkyVerge\\WooCommerce\\PluginFramework\\v5_10_12\\{$class_name}", "WC_Braintree\\Plugin_Framework\\{$class_name}" );
		}
	}


	/**
	 * Gets the deprecated hooks and their replacements, if any.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	protected function get_deprecated_hooks() {

		$hooks = array(
			'wc_gateway_paypal_braintree_card_icons_image_url' => array(
				'version'     => '2.0.0',
				'removed'     => true,
				'replacement' => 'wc_braintree_credit_card_icon',
				'map'         => true,
			),
			'wc_gateway_paypal_braintree_sale_args' => array(
				'version'     => '2.0.0',
				'removed'     => true,
				'replacement' => 'wc_braintree_transaction_data',
				'map'         => true,
			),
			'wc_gateway_paypal_braintree_data' => array(
				'version'     => '2.0.0',
				'removed'     => true, // TODO: determine if anything can be mapped here
			),
		);

		return $hooks;
	}


	/**
	 * Initializes the plugin lifecycle handler.
	 *
	 * @since 2.2.0
	 */
	public function init_lifecycle_handler() {

		require_once( $this->get_plugin_path() . '/includes/class-wc-braintree-lifecycle.php' );

		$this->lifecycle_handler = new \WC_Braintree\Lifecycle( $this );
	}


	/**
	 * Handles the Braintree Auth connection response.
	 *
	 * @since 2.0.0
	 */
	public function handle_auth_connect() {

		// if this is not a gateway settings page, bail
		if ( ! $this->is_plugin_settings() ) {
			return;
		}

		// if there was already a successful disconnect, just display a notice
		if ( $connected = Framework\SV_WC_Helper::get_requested_value( 'wc_braintree_connected' ) ) {

			if ( $connected ) {
				$message = __( 'Connected successfully.', 'woocommerce-gateway-paypal-powered-by-braintree' );
				$class   = 'updated';
			} else {
				$message = __( 'There was an error connecting your Braintree account. Please try again.', 'woocommerce-gateway-paypal-powered-by-braintree' );
				$class   = 'error';
			}

			$this->get_admin_notice_handler()->add_admin_notice(
				$message,
				'connection-notice',
				array(
					'dismissible'  => true,
					'notice_class' => $class,
				)
			);

			return;
		}

		$nonce = Framework\SV_WC_Helper::get_requested_value( 'wc_paypal_braintree_admin_nonce' );

		// if no nonce is present, then this probably wasn't a connection response
		if ( ! $nonce ) {
			return;
		}

		// if there is already a stored access token, bail
		if ( $this->get_gateway()->get_auth_access_token() ) {
			return;
		}

		// verify the nonce
		if ( ! wp_verify_nonce( $nonce, 'connect_paypal_braintree' ) ) {
			wp_die( __( 'Invalid connection request', 'woocommerce-gateway-paypal-powered-by-braintree' ) );
		}

		if ( $access_token = sanitize_text_field( urldecode( Framework\SV_WC_Helper::get_requested_value( 'braintree_access_token' ) ) ) ) {

			update_option( 'wc_braintree_auth_access_token', $access_token );

			list( $token_key, $environment, $merchant_id, $raw_token ) = explode( '$', $access_token );

			update_option( 'wc_braintree_auth_environment', $environment );
			update_option( 'wc_braintree_auth_merchant_id', $merchant_id );

			$connected = true;

		} else {

			$this->log( 'Could not connect to Braintree. Invalid access token', $this->get_gateway()->get_id() );

			$connected = false;
		}

		wp_safe_redirect( add_query_arg( 'wc_braintree_connected', $connected, $this->get_settings_url() ) );
		exit;
	}


	/**
	 * Handles a Braintree Auth disconnect request.
	 *
	 * @since 2.0.0
	 */
	public function handle_auth_disconnect() {

		// if this is not a gateway settings page, bail
		if ( ! $this->is_plugin_settings() ) {
			return;
		}

		// if there was already a successful disconnect, just display a notice
		if ( Framework\SV_WC_Helper::get_requested_value( 'wc_braintree_disconnected' ) ) {

			$this->get_admin_notice_handler()->add_admin_notice(
				__( 'Disconnected successfully.', 'woocommerce-gateway-paypal-powered-by-braintree' ),
				'disconnect-successful-notice',
				array(
					'dismissible'  => true,
					'notice_class' => 'updated',
				)
			);

			return;
		}

		// if this is not a disconnect request, bail
		if ( ! Framework\SV_WC_Helper::get_requested_value( 'disconnect_paypal_braintree' ) ) {
			return;
		}

		$nonce = Framework\SV_WC_Helper::get_requested_value( 'wc_paypal_braintree_admin_nonce' );

		// if no nonce is present, then this probably wasn't a disconnect request
		if ( ! $nonce ) {
			return;
		}

		// verify the nonce
		if ( ! wp_verify_nonce( $nonce, 'disconnect_paypal_braintree' ) ) {
			wp_die( __( 'Invalid disconnect request', 'woocommerce-gateway-paypal-powered-by-braintree' ) );
		}

		delete_option( 'wc_braintree_auth_access_token' );
		delete_option( 'wc_braintree_auth_environment' );
		delete_option( 'wc_braintree_auth_merchant_id' );

		wp_safe_redirect( add_query_arg( 'wc_braintree_disconnected', true, $this->get_settings_url() ) );
		exit;
	}


	/**
	 * Initializes the PayPal cart handler.
	 *
	 * @since 2.0.0
	 * @deprecated since 2.3.0
	 */
	public function maybe_init_paypal_cart() {

		wc_deprecated_function( __METHOD__, '2.3.0' );
	}


	/**
	 * Gets the PayPal cart handler instance.
	 *
	 * @since 2.0.0
	 * @deprecated since 2.3.0
	 */
	public function get_paypal_cart_instance() {

		wc_deprecated_function( __METHOD__, '2.3.0' );
	}


	/** Apple Pay Methods *********************************************************************************************/


	/**
	 * Initializes the Apple Pay feature.
	 *
	 * The framework requires this be enabled by filter due to the complicated setup that's usually required. Braintree
	 * makes the process a bit easier, so let's enable it by default.
	 *
	 * @since 2.2.0
	 */
	public function maybe_init_apple_pay() {

		add_filter( 'wc_payment_gateway_' . $this->get_id() . '_activate_apple_pay', '__return_true' );

		parent::maybe_init_apple_pay();
	}


	/**
	 * Builds the Apple Pay handler instance.
	 *
	 * @since 2.2.0
	 *
	 * @return \WC_Braintree\Apple_Pay
	 */
	protected function build_apple_pay_instance() {

		// include the overridden handler classes
		require_once( $this->get_plugin_path() . '/includes/apple-pay/class-wc-braintree-apple-pay.php' );
		require_once( $this->get_plugin_path() . '/includes/apple-pay/class-wc-braintree-apple-pay-frontend.php' );
		require_once( $this->get_plugin_path() . '/includes/apple-pay/api/class-wc-braintree-apple-pay-api-payment-response.php' );

		return new \WC_Braintree\Apple_Pay( $this );
	}


	/** Admin methods ******************************************************/


	/**
	 * Render a notice for the user to select their desired export format
	 *
	 * @since 2.1.3
	 * @see SV_WC_Plugin::add_admin_notices()
	 */
	public function add_admin_notices() {

		// show any dependency notices
		parent::add_admin_notices();

		/** @var \WC_Gateway_Braintree_Credit_Card $credit_card_gateway */
		$credit_card_gateway = $this->get_gateway( self::CREDIT_CARD_GATEWAY_ID );

		if ( $credit_card_gateway->is_advanced_fraud_tool_enabled() && ! $this->get_admin_notice_handler()->is_notice_dismissed( 'fraud-tool-notice' ) ) {

			$this->get_admin_notice_handler()->add_admin_notice(
				sprintf( __( 'Heads up! You\'ve enabled advanced fraud tools for Braintree. Please make sure that advanced fraud tools are also enabled in your Braintree account. Need help? See the %1$sdocumentation%2$s.', 'woocommerce-gateway-paypal-powered-by-braintree' ),
					'<a target="_blank" href="' . $this->get_documentation_url() . '">',
					'</a>'
				), 'fraud-tool-notice', array( 'always_show_on_settings' => false, 'dismissible' => true, 'notice_class' => 'updated' )
			);
		}

		$credit_card_settings = get_option( 'woocommerce_braintree_credit_card_settings' );
		$paypal_settings      = get_option( 'woocommerce_braintree_paypal_settings' );

		// install notice
		if ( ! $this->is_plugin_settings() ) {

			if ( ( $credit_card_gateway->can_connect() && ! $credit_card_gateway->is_connected() ) && empty( $credit_card_settings ) && empty( $paypal_settings ) && ! $this->get_admin_notice_handler()->is_notice_dismissed( 'install-notice' ) ) {

				$this->get_admin_notice_handler()->add_admin_notice(
					sprintf(
						/** translators: Placeholders: %1$s - <a> tag, %2$s - </a> tag */
						__( 'Braintree for WooCommerce is almost ready. To get started, %1$sconnect your Braintree account%2$s.', 'woocommerce-gateway-paypal-powered-by-braintree' ),
						'<a href="' . esc_url( $this->get_settings_url() ) . '">', '</a>'
					), 'install-notice', array( 'notice_class' => 'updated' )
				);

			} elseif ( 'yes' === get_option( 'wc_braintree_legacy_migrated' ) ) {

				delete_option( 'wc_braintree_legacy_migrated' );

				$this->get_admin_notice_handler()->add_admin_notice(
					sprintf(
						/** translators: Placeholders: %1$s - <a> tag, %2$s - </a> tag */
						__( 'Upgrade successful! WooCommerce Braintree deactivated, and Braintree for WooCommerce has been %1$sconfigured with your previous settings%2$s.', 'woocommerce-gateway-paypal-powered-by-braintree' ),
						'<a href="' . esc_url( $this->get_settings_url() ) . '">', '</a>'
					), 'install-notice', array( 'notice_class' => 'updated' )
				);
			}
		}

		// SSL check (only when PayPal is enabled in production mode)
		if ( isset( $paypal_settings['enabled'] ) && 'yes' === $paypal_settings['enabled'] ) {
			if ( isset( $paypal_settings['environment'] ) && 'production' === $paypal_settings['environment'] ) {

				if ( ! wc_checkout_is_https() && ! $this->get_admin_notice_handler()->is_notice_dismissed( 'ssl-recommended-notice' ) ) {

					$this->get_admin_notice_handler()->add_admin_notice( __( 'WooCommerce is not being forced over SSL -- Using PayPal with Braintree requires that checkout to be forced over SSL.', 'woocommerce-gateway-paypal-powered-by-braintree' ), 'ssl-recommended-notice' );
				}
			}
		}
	}


	/**
	 * Adds delayed admin notices for invalid Dynamic Descriptor Name values.
	 *
	 * @since 2.1.0
	 */
	public function add_delayed_admin_notices() {

		parent::add_delayed_admin_notices();

		if ( $this->is_plugin_settings() ) {

			foreach ( $this->get_gateways() as $gateway ) {

				$settings = $this->get_gateway_settings( $gateway->get_id() );

				if ( ! empty( $settings['inherit_settings'] ) && 'yes' === $settings['inherit_settings'] ) {
					continue;
				}

				foreach ( array( 'name', 'phone', 'url' ) as $type ) {

					$validation_method = "is_{$type}_dynamic_descriptor_valid";
					$settings_key      = "{$type}_dynamic_descriptor";

					if ( ! empty( $settings[ $settings_key ] ) && is_callable( array( $gateway, $validation_method ) ) && ! $gateway->$validation_method( $settings[ $settings_key ] ) ) {

						$this->get_admin_notice_handler()->add_admin_notice(
							/* translators: Placeholders: %1$s - payment gateway name tag, %2$s - <a> tag, %3$s - </a> tag */
							sprintf( __( '%1$s: Heads up! Your %2$s dynamic descriptor is invalid and will not be used. Need help? See the %3$sdocumentation%4$s.', 'woocommerce-gateway-paypal-powered-by-braintree' ),
								'<strong>' . esc_html( $gateway->get_method_title() ) . '</strong>',
								'<strong>' . esc_html( $type ) . '</strong>',
								'<a target="_blank" href="https://docs.woocommerce.com/document/woocommerce-gateway-paypal-powered-by-braintree/#section-21">',
								'</a>'
							), $gateway->get_id() . '-' . $type . '-dynamic-descriptor-notice', array( 'notice_class' => 'error' )
						);

						break;
					}
				}
			}
		}
	}


	/** Helper methods ******************************************************/


	/**
	 * Main Braintree Instance, ensures only one instance is/can be loaded
	 *
	 * @since 2.2.0
	 * @see wc_braintree()
	 * @return WC_Braintree
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Gets the frontend class instance.
	 *
	 * @since 2.0.0
	 * @return \WC_Braintree_Frontend
	 */
	public function get_frontend_instance() {
		return $this->frontend;
	}

	/**
	 * Overrides the default SV framework implementation of payment methods in My Account.
	 *
	 * @since 2.6.2
	 * @return \WC_Braintree_My_Payment_Methods
	 */
	public function get_my_payment_methods_instance() {
		return new WC_Braintree_My_Payment_Methods( $this );
	}

	/**
	 * Returns the plugin name, localized
	 *
	 * @since 2.1
	 * @see SV_WC_Plugin::get_plugin_name()
	 * @return string the plugin name
	 */
	public function get_plugin_name() {
		return __( 'Braintree for WooCommerce Payment Gateway', 'woocommerce-gateway-paypal-powered-by-braintree' );
	}


	/**
	 * Returns __FILE__
	 *
	 * @since 2.1
	 * @see SV_WC_Plugin::get_file()
	 * @return string the full path and filename of the plugin file
	 */
	protected function get_file() {
		return WC_PAYPAL_BRAINTREE_FILE;
	}


	/**
	 * Gets the plugin documentation url
	 *
	 * @since 2.1
	 * @see SV_WC_Plugin::get_documentation_url()
	 * @return string documentation URL
	 */
	public function get_documentation_url() {
		return 'http://docs.woocommerce.com/document/woocommerce-gateway-paypal-powered-by-braintree/';
	}


	/**
	 * Gets the plugin support URL
	 *
	 * @since 2.3.0
	 * @see SV_WC_Plugin::get_support_url()
	 * @return string
	 */
	public function get_support_url() {
		return 'https://wordpress.org/support/plugin/woocommerce-gateway-paypal-powered-by-braintree/';
	}


	/**
	 * Returns the "Configure Credit Card" or "Configure PayPal" plugin action
	 * links that go directly to the gateway settings page
	 *
	 * @since 3.0.0
	 * @see SV_WC_Payment_Gateway_Plugin::get_settings_url()
	 * @param string $gateway_id the gateway identifier
	 * @return string plugin configure link
	 */
	public function get_settings_link( $gateway_id = null ) {

		return sprintf( '<a href="%s">%s</a>',
			$this->get_settings_url( $gateway_id ),
			self::CREDIT_CARD_GATEWAY_ID === $gateway_id ? __( 'Configure Credit Card', 'woocommerce-gateway-paypal-powered-by-braintree' ) : __( 'Configure PayPal', 'woocommerce-gateway-paypal-powered-by-braintree' )
		);
	}


	/**
	 * Determines if WooCommerce is active.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public static function is_woocommerce_active() {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}


} // end \WC_Braintree


/**
 * Returns the One True Instance of Braintree
 *
 * @since 2.2.0
 * @return WC_Braintree
 */
function wc_braintree() {

	return WC_Braintree::instance();
}
