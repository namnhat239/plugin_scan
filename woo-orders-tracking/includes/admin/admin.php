<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class VI_WOO_ORDERS_TRACKING_ADMIN_ADMIN {
	protected $settings;

	public function __construct() {
		$this->settings = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
		add_action( 'init', array( $this, 'init' ) );
		add_filter(
			'plugin_action_links_woo-orders-tracking/woo-orders-tracking.php', array(
				$this,
				'settings_link'
			)
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'update_data_new_version' ), 1 );
	}

	public function update_data_new_version() {
		/*Use the version number of the premium version so that this does not run again if a premium user upgrades it from the free version*/
		if ( ! get_option( 'woo_orders_tracking_update_data_new_version_1.0.8' ) ) {
			if ( get_option( 'woo_orders_tracking_settings' ) ) {
				$defined_carriers = $this->settings->get_params( 'shipping_carriers_define_list' );
				if ( $defined_carriers ) {
					$defined_carriers = vi_wot_json_decode( $defined_carriers );
					$update           = false;
					foreach ( $defined_carriers as $key => $value ) {
						if ( $value['slug'] === 'fedex' ) {
							if ( $defined_carriers[ $key ]['url'] !== 'https://www.fedex.com/fedextrack/?cntry_code=us&tracknumbers={tracking_number}' ) {
								$defined_carriers[ $key ]['url'] = 'https://www.fedex.com/fedextrack/?cntry_code=us&tracknumbers={tracking_number}';
								$update                          = true;
							}
						} elseif ( $value['slug'] === 'portugal-post-ctt' ) {
							if ( $defined_carriers[ $key ]['url'] !== 'https://www.ctt.pt/feapl_2/app/open/objectSearch/objectSearch.jspx?objects={tracking_number}' ) {
								$defined_carriers[ $key ]['url'] = 'https://www.ctt.pt/feapl_2/app/open/objectSearch/objectSearch.jspx?objects={tracking_number}';
								$update                          = true;
							}
						}
					}
					if ( $update ) {
						$params                                  = $this->settings->get_params();
						$params['shipping_carriers_define_list'] = json_encode( $defined_carriers );
						update_option( 'woo_orders_tracking_settings', $params );
						$this->settings = VI_WOO_ORDERS_TRACKING_DATA::get_instance( true );
					}
				}
			}
			update_option( 'woo_orders_tracking_update_data_new_version_1.0.8', time() );
		}
	}

	protected function query_order_item_meta( $args1 = array(), $args2 = array(), $limit = 0 ) {
		global $wpdb;
		$sql  = "SELECT * FROM {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta WHERE woocommerce_order_items.order_item_id=woocommerce_order_itemmeta.order_item_id";
		$args = array();
		if ( count( $args1 ) ) {
			foreach ( $args1 as $key => $value ) {
				if ( is_array( $value ) ) {
					$sql .= " AND woocommerce_order_items.{$key} IN (" . implode( ', ', array_fill( 0, count( $value ), '%s' ) ) . ")";
					foreach ( $value as $v ) {
						$args[] = $v;
					}
				} else {
					$sql    .= " AND woocommerce_order_items.{$key}='%s'";
					$args[] = $value;
				}
			}
		}
		if ( count( $args2 ) ) {
			foreach ( $args2 as $key => $value ) {
				if ( is_array( $value ) ) {
					$sql .= " AND woocommerce_order_itemmeta.{$key} IN (" . implode( ', ', array_fill( 0, count( $value ), '%s' ) ) . ")";
					foreach ( $value as $v ) {
						$args[] = $v;
					}
				} else {
					$sql    .= " AND woocommerce_order_itemmeta.{$key}='%s'";
					$args[] = $value;
				}
			}
		}
		if ( $limit ) {
			$sql .= " LIMIT 0,{$limit}";
		}
		$query      = $wpdb->prepare( $sql, $args );
		$line_items = $wpdb->get_results( $query, ARRAY_A );

		return $line_items;
	}

	public function settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php' ) . '?page=woo-orders-tracking" title="' . __( 'Settings', 'woo-orders-tracking' ) . '">' . __( 'Settings', 'woo-orders-tracking' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woo-orders-tracking' );
		load_textdomain( 'woo-orders-tracking', VI_WOO_ORDERS_TRACKING_LANGUAGES . "woo-orders-tracking-$locale.mo" );
		load_plugin_textdomain( 'woo-orders-tracking', false, VI_WOO_ORDERS_TRACKING_LANGUAGES );

	}

	public function init() {
		$this->load_plugin_textdomain();
		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'   => 'https://wordpress.org/support/plugin/woo-orders-tracking/',
					'docs'      => 'http://docs.villatheme.com/?item=woo-orders-tracking',
					'review'    => 'https://wordpress.org/support/plugin/woo-orders-tracking/reviews/?rate=5#rate-response',
					'pro_url'   => 'https://1.envato.market/6ZPBE',
					'css'       => VI_WOO_ORDERS_TRACKING_CSS,
					'image'     => VI_WOO_ORDERS_TRACKING_IMAGES,
					'slug'      => 'woo-orders-tracking',
					'menu_slug' => 'woo-orders-tracking',
					'version'   => VI_WOO_ORDERS_TRACKING_VERSION
				)
			);

		}
	}
}