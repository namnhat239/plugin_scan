<?php
include_once( WOO_ST_PATH . 'includes/admin/meta_box.php' );
include_once( WOO_ST_PATH . 'includes/admin/dashboard.php' );

// Display admin notice on screen load
function woo_st_admin_notice( $message = '', $priority = 'updated', $screen = '' ) {

	if( $priority == false || $priority == '' )
		$priority = 'updated';
	if( $message <> '' ) {
		ob_start();
		woo_st_admin_notice_html( $message, $priority, $screen );
		$output = ob_get_contents();
		ob_end_clean();
		// Check if an existing notice is already in queue
		$existing_notice = get_transient( WOO_ST_PREFIX . '_notice' );
		if( $existing_notice !== false ) {
			$existing_notice = base64_decode( $existing_notice );
			$output = $existing_notice . $output;
		}
		set_transient( WOO_ST_PREFIX . '_notice', base64_encode( $output ), MINUTE_IN_SECONDS );
		add_action( 'admin_notices', 'woo_st_admin_notice_print' );
	}

}

// HTML template for admin notice
function woo_st_admin_notice_html( $message = '', $priority = 'updated', $screen = '' ) {

	// Display admin notice on specific screen
	if( !empty( $screen ) ) {

		global $pagenow;

		if( is_array( $screen ) ) {
			if( in_array( $pagenow, $screen ) == false )
				return;
		} else {
			if( $pagenow <> $screen )
				return;
		}

	}
	
	echo '<div id="message" class="' . esc_attr( $priority ) . '">';
	echo '<p>';
	echo wp_kses_post( $message );
	echo '</p>';
	echo '</div>';

}

// Grabs the WordPress transient that holds the admin notice and prints it
function woo_st_admin_notice_print() {

	$output = get_transient( WOO_ST_PREFIX . '_notice' );
	if( $output !== false ) {
		$output = base64_decode( $output );
		echo wp_kses_data( $output );
		delete_transient( WOO_ST_PREFIX . '_notice' );
	}

}

// Add Store Toolkit, Docs links to the Plugins screen
function woo_st_add_settings_link( $links, $file ) {

	$this_plugin = plugin_basename( WOO_ST_RELPATH );
	if( $file == $this_plugin ) {
		$docs_url = 'https://www.visser.com.au/docs/';
		$docs_link = sprintf( '<a href="%s" target="_blank">' . __( 'Docs', 'woocommerce-store-toolkit' ) . '</a>', esc_url( $docs_url ) );
		$settings_link = sprintf( '<a href="%s">' . __( 'Settings', 'woocommerce-store-toolkit' ) . '</a>', esc_url( add_query_arg( 'page', 'woo_st', 'admin.php' ) ) );
		array_unshift( $links, $docs_link );
		array_unshift( $links, $settings_link );
	}
	return $links;

}
add_filter( 'plugin_action_links', 'woo_st_add_settings_link', 10, 2 );

// Load CSS and jQuery scripts for Store Toolkit screen
function woo_st_enqueue_scripts( $hook ) {

	// Simple check that WooCommerce is activated
	if( class_exists( 'WooCommerce' ) ) {

		global $woocommerce;

		wp_enqueue_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css' );

	}

	if( $hook == 'woocommerce_page_woo_st' ) {
		// Time Picker Addon
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui-datepicker', plugins_url( '/templates/admin/jquery-ui-datepicker.css', WOO_ST_RELPATH ) );
/*
		wp_enqueue_script( 'jquery-ui-timepicker', plugins_url( '/js/jquery.timepicker.js', WOO_ST_RELPATH ) );
		wp_enqueue_style( 'jquery-ui-timepicker', plugins_url( '/templates/admin/jquery-ui-timepicker.css', WOO_ST_RELPATH ) );
*/
	}

	// Settings
	$pages = array( 'woocommerce_page_woo_st', 'edit-tags.php', 'user-edit.php', 'profile.php', 'index.php', 'post.php' );
	if( in_array( $hook, $pages ) ) {
		wp_enqueue_style( 'woo_st_styles', plugins_url( '/templates/admin/toolkit.css', WOO_ST_RELPATH ) );
		wp_enqueue_script( 'woo_st_scripts', plugins_url( '/templates/admin/toolkit.js', WOO_ST_RELPATH ), array( 'jquery' ) );
	}

}
add_action( 'admin_enqueue_scripts', 'woo_st_enqueue_scripts' );

function woo_st_permanent_delete_link( $actions, $post ) {

	// Check that the User can manage_woocommerce
	if( !current_user_can( apply_filters( 'woo_st_permanent_delete_capability', 'manage_woocommerce' ) ) ) {
		return $actions;
	}

	// Limit to the Product CPT screen
	if( $post->post_type != 'product' ) {
		return $actions;
	}

	// Do not show for the Trash screen
	$post_status = ( isset( $_REQUEST['post_status'] ) ? sanitize_text_field( $_REQUEST['post_status'] ) : false );
	if( !empty( $post_status ) ) {
		if( $post_status == 'trash' )
			return $actions;
	}

	// Check Settings option
	$permanently_delete_products = woo_st_get_option( 'permanently_delete_products', 1 );
	if( empty( $permanently_delete_products ) )
		return $actions;

	$post_id = absint( $post->ID ? $post->ID : false );

	$url = admin_url( 'edit.php?post_type=product&ids=' . $post_id . '&action=permanent_delete_product' );

	$actions['permanent_delete'] = '<span class="delete"><a href="' . wp_nonce_url( $url, 'woo_st-permanent_delete_' . $post_id ) . '" title="' . esc_attr__( 'Permanently delete this product', 'woocommerce-store-toolkit' ) . '" rel="permalink">' .  __( 'Delete Permanently', 'woocommerce' ) . '</a></span>';

	return $actions;

}
add_filter( 'post_row_actions', 'woo_st_permanent_delete_link', 10, 2 );
add_filter( 'page_row_actions', 'woo_st_permanent_delete_link', 10, 2 );

function woo_st_permanent_delete_product_action() {

	if ( empty( $_REQUEST['ids'] ) ) {
		wp_die( __( 'No product to permanently delete has been supplied!', 'woocommerce-store-toolkit' ) );
	}

	// Get the original page
	$id = isset( $_REQUEST['ids'] ) ? absint( $_REQUEST['ids'] ) : '';

	check_admin_referer( 'woo_st-permanent_delete_' . $id );

	// Delete the Post
	$deleted = 0;
	if ( !empty( $id ) ) {
		wp_delete_post( $id, true );
		$deleted++;
		$post_type = 'product';
		$url = add_query_arg( array( 'post_type' => $post_type, 'action' => null, '_wpnonce' => null, 'ids' => $id, 'deleted' => $deleted ), 'edit.php' );
		wp_redirect( $url );
		exit();
	} else {
		wp_die( __( 'Permanently delete Product failed, could not find original product:', 'woocommerce' ) . ' ' . $id );
	}

}
add_action( 'admin_action_permanent_delete_product', 'woo_st_permanent_delete_product_action' );

function woo_st_permanent_delete_product_bulk_admin_footer() {

	global $post_type;

	// Check that the User can manage_woocommerce
	if( !current_user_can( apply_filters( 'woo_st_permanent_delete_capability', 'manage_woocommerce' ) ) ) {
		return;
	}

	$post_status = ( isset( $_REQUEST['post_status'] ) ? sanitize_text_field( $_REQUEST['post_status'] ) : false );
	if(
		$post_type == 'product' && 
		( $post_status <> 'trash' )
	) {

		// Check Settings option
		$permanently_delete_products = woo_st_get_option( 'permanently_delete_products', 1 );
		if( empty( $permanently_delete_products ) )
			return;

?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		// Add Delete Permanently to bulk actions menu on the Products screen
		jQuery('<option>').val('permanent_delete').text('<?php _e( 'Delete Permanently' )?>').appendTo("select[name='action']");
		jQuery('<option>').val('permanent_delete').text('<?php _e( 'Delete Permanently' )?>').appendTo("select[name='action2']");
	});
</script>
<?php
	}

}
add_action( 'admin_footer-edit.php', 'woo_st_permanent_delete_product_bulk_admin_footer' );

function woo_st_permanent_delete_product_bulk_action() {

	$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
	$action = $wp_list_table->current_action();

	switch( $action ) {

		case 'permanent_delete':

			check_admin_referer( 'bulk-posts' );

			if ( empty( $_REQUEST['post'] ) ) {
				wp_die( __( 'No products to permanently delete have been supplied!', 'woocommerce-store-toolkit' ) );
			}

			$post_ids = ( isset( $_REQUEST['post'] ) ? array_map( 'absint', $_REQUEST['post'] ) : false );

			$deleted = 0;
			if( !empty( $post_ids ) ) {
				foreach( $post_ids as $post_id ) {
					wp_delete_post( $post_id, true );
					$deleted++;
				}
			}
			$post_type = 'product';
			$url = add_query_arg( array( 'post_type' => $post_type, 'action' => null, '_wpnonce' => null, 'deleted' => $deleted, 'ids' => join( ',', $post_ids ) ), 'edit.php' );
			wp_redirect( $url );
			exit();
			break;

		default:
			return;
			break;

	}

}
add_action( 'load-edit.php', 'woo_st_permanent_delete_product_bulk_action' );

function woo_st_restrict_manage_posts() {

	global $typenow;

	if( !current_user_can( 'manage_options' ) )
		return;

	if( in_array( $typenow, wc_get_order_types( 'order-meta-boxes' ) ) ) {
		woo_st_shop_order_filters();
	}

}
add_action( 'restrict_manage_posts', 'woo_st_restrict_manage_posts' );

function woo_st_shop_order_filters() {

	if( apply_filters( 'woo_st_shop_order_billing_country_filter', true ) )
		woo_st_shop_order_billing_country_filter();
	if( apply_filters( 'woo_st_shop_order_shipping_country_filter', true ) )
		woo_st_shop_order_shipping_country_filter();
	if( apply_filters( 'woo_st_shop_order_payment_gateway_filter', true ) )
		woo_st_shop_order_payment_gateway_filter();

}

function woo_st_shop_order_billing_country_filter() {

	$wc_countries = ( class_exists( 'WC_Countries' ) ? new WC_Countries() : false );
	$countries = ( method_exists( $wc_countries, 'get_countries' ) ? $wc_countries->get_countries() : false );
	if( empty( $countries ) )
		return;

	$selected = ( isset( $_GET['_customer_billing_country'] ) ? sanitize_text_field( $_GET['_customer_billing_country'] ) : false );
?>
<select name="_customer_billing_country">
	<option value=""><?php _e( 'All billing countries', 'woocommerce-store-toolkit' ); ?></option>
<?php foreach( $countries as $prefix => $country ) { ?>
	<option value="<?php echo esc_attr( $prefix ); ?>"<?php selected( $prefix, $selected ); ?>><?php echo esc_html( $country ); ?></option>
<?php } ?>
</select>
<?php

}

function woo_st_shop_order_shipping_country_filter() {

	$wc_countries = ( class_exists( 'WC_Countries' ) ? new WC_Countries() : false );
	$countries = ( method_exists( $wc_countries, 'get_countries' ) ? $wc_countries->get_countries() : false );
	if( empty( $countries ) )
		return;

	$selected = ( isset( $_GET['_customer_shipping_country'] ) ? sanitize_text_field( $_GET['_customer_shipping_country'] ) : false );
?>
<select name="_customer_shipping_country">
	<option value=""><?php _e( 'All shipping countries', 'woocommerce-store-toolkit' ); ?></option>
<?php foreach( $countries as $prefix => $country ) { ?>
	<option value="<?php echo esc_attr( $prefix ); ?>"<?php selected( $prefix, $selected ); ?>><?php echo esc_html( $country ); ?></option>
<?php } ?>
</select>
<?php

}

function woo_st_shop_order_payment_gateway_filter() {

	$payment_gateways = woo_st_get_payment_gateways();
	if( empty( $payment_gateways ) )
		return;

	$selected = ( isset( $_GET['_customer_payment_method'] ) ? sanitize_text_field( $_GET['_customer_payment_method'] ) : false );
?>
<select name="_customer_payment_method">
	<option value=""><?php _e( 'All payment methods', 'woocommerce-store-toolkit' ); ?></option>
<?php foreach( $payment_gateways as $payment_gateway ) { ?>
<?php
$payment_gateway_label = woo_st_format_payment_gateway_label( $payment_gateway->id );
?>
	<option value="<?php echo esc_attr( $payment_gateway->id ); ?>"<?php selected( $payment_gateway->id, $selected ); ?>><?php echo esc_html( $payment_gateway_label ); ?></option>
<?php } ?>
</select>
<?php

}

function woo_st_request_query( $vars ) {

	global $typenow;

	if( in_array( $typenow, wc_get_order_types( 'order-meta-boxes' ) ) ) {
		// Billing country
		if(
			isset( $_GET['_customer_billing_country'] ) && 
			$_GET['_customer_billing_country'] != ''
		) {
			$vars['meta_query'] = array(
				array(
					'key' => '_billing_country',
					'value' => sanitize_text_field( $_GET['_customer_billing_country'] ),
					'compare' => '='
				)
			);
		}

		// Shipping country
		if(
			isset( $_GET['_customer_shipping_country'] ) && 
			$_GET['_customer_shipping_country'] != ''
		) {
			$vars['meta_query'] = array(
				array(
					'key' => '_shipping_country',
					'value' => sanitize_text_field( $_GET['_customer_shipping_country'] ),
					'compare' => '='
				)
			);
		}

		// Payment method
		if(
			isset( $_GET['_customer_payment_method'] ) && 
			$_GET['_customer_payment_method'] != ''
		) {
			$vars['meta_query'] = array(
				array(
					'key' => '_payment_method',
					'value' => sanitize_text_field( $_GET['_customer_payment_method'] ),
					'compare' => '='
				)
			);
		}
	}

	return $vars;

}
add_filter( 'request', 'woo_st_request_query' );

function woo_st_get_custom_post_type_ids( $post_type = false ) {
	
	if( empty( $post_type ) )
		return;

	$posts_per_page = apply_filters( 'woo_st_get_custom_post_type_ids_posts_per_page', 10 );
	$args = array(
		'post_type' => $post_type,
		'post_status' => 'any',
		'posts_per_page' => $posts_per_page,
		'fields' => 'ids',
		'orderby' => 'rand'
	);
	$post_ids = new WP_Query( $args );
	if( !empty( $post_ids->posts ) ) {
		return $post_ids->posts;
	}

}

function woo_st_get_user_orders( $user_id = 0, $args = array(), $return = 'ids' ) {

	if( empty( $user_id ) )
		return;

	$defaults = array(
		'numberposts' => -1,
		'meta_key'    => '_customer_user',
		'meta_value'  => $user_id,
		'post_type'   => ( function_exists( 'wc_get_order_types' ) ? wc_get_order_types() : false ),
		'post_status' => ( function_exists( 'wc_get_order_statuses' ) ? array_keys( wc_get_order_statuses() ) : false ),
		'fields' => 'ids'
	);
	$args = wp_parse_args( $args, $defaults );
	$order_ids = new WP_Query( $args );
	if( $return == 'found_posts' )
		return $order_ids->found_posts;
	$orders = ( !empty( $order_ids->posts ) ? $order_ids->posts : false );

	return $orders;

}

function woo_st_get_order_refunds( $order_id = 0, $args = array() ) {

	if( empty( $order_id ) )
		return;

	$post_type = 'shop_order_refund';
	$defaults = array(
		'numberposts' => -1,
		'post_type'   => $post_type,
		'post_status' => ( function_exists( 'wc_get_order_statuses' ) ? array_keys( wc_get_order_statuses() ) : false ),
		'post_parent' => $order_id
	);
	$args = wp_parse_args( $args, $defaults );

	$refunds = get_posts( $args );
	if( !empty( $refunds ) ) {
		foreach( $refunds as $key => $refund ) {
			$refunds[$key]->meta = get_post_custom( $refund->ID );
		}
	}
	return $refunds;

}

function woo_st_get_payment_gateways() {

	global $woocommerce;

	$output = false;

	// Test that payment gateways exist with WooCommerce 1.6 compatibility
	if( version_compare( $woocommerce->version, '2.0.0', '<' ) ) {
		if( $woocommerce->payment_gateways )
			$output = $woocommerce->payment_gateways->payment_gateways;
	} else {
		if( $woocommerce->payment_gateways() )
			$output = $woocommerce->payment_gateways()->payment_gateways();
	}
	// Add Other to list of payment gateways
	$output['other'] = (object)array(
		'id' => 'other',
		'title' => __( 'Other', 'woocommerce-store-toolkit' ),
		'method_title' => __( 'Other', 'woocommerce-store-toolkit' )
	);
	return $output;

}

function woo_st_admin_footer_text( $footer_text = '' ) {

	if( !current_user_can( 'manage_options' ) )
		return $footer_text;

	if( apply_filters( 'woo_st_admin_footer_text', true ) ) {
		if( !empty( $footer_text ) )
			$footer_text .= ' | ';
		$footer_text .= __( 'Stopwatch', 'woocommerce-store-toolkit' ) . ': ' . timer_stop( 0, 3 ) . ' ' . __( 'seconds', 'woocommerce-store-toolkit' );
	}

	return $footer_text;

}

// HTML active class for the currently selected tab on the Store Toolkit screen
function woo_st_admin_active_tab( $tab_name = null, $tab = null ) {

	if( isset( $_GET['tab'] ) && !$tab )
		$tab = sanitize_text_field( $_GET['tab'] );
	else
		$tab = 'overview';

	$output = '';
	if( isset( $tab_name ) && $tab_name ) {
		if( $tab_name == $tab )
			$output = ' nav-tab-active';
	}
	return $output;

}

// HTML template for each tab on the Store Toolkit screen
function woo_st_tab_template( $tab = '' ) {

	if( !$tab )
		$tab = 'overview';

	switch( $tab ) {

		case 'nuke':

			// Check if a previous nuke failed mid-drop
			$in_progress = woo_st_get_option( 'in_progress', false );
			if( !empty( $in_progress ) ) {
				$message = sprintf( __( 'It looks like a previous nuke failed to clear that dataset, this is common in large catalogues and is likely due to WordPress hitting a memory limit or server timeout. Don\'t stress, <a href="%s">retry %s nuke?</a>', 'woocommerce-store-toolkit' ), esc_url( add_query_arg( array( 'action' => 'nuke', 'dataset' => $in_progress ) ) ), ucfirst( $in_progress ) );
				woo_st_admin_notice_html( $message, 'error' );
				delete_option( WOO_ST_PREFIX . '_in_progress' );
			}

			$products = woo_st_return_count( 'product' );
			if( !empty( $products ) ) {
				$product_statuses = woo_st_get_product_statuses();
			}
			$images = woo_st_return_count( 'product_image' );
			$tags = woo_st_return_count( 'product_tag' );
			$categories = woo_st_return_count( 'product_category' );
			if( !empty( $categories ) ) {
				$term_taxonomy = 'product_cat';
				$args = array(
					'hide_empty' => 0
				);
				$categories_data = get_terms( $term_taxonomy, $args );
			}
			$orders = woo_st_return_count( 'order' );
			if( !empty( $orders ) ) {
				$order_statuses = woo_st_get_order_statuses();
				$orders_date = false;
				$date_format = 'd/m/Y';
				$orders_date_from = woo_st_get_order_first_date( $date_format );
				$orders_date_to = date( $date_format );
			}
			$tax_rates = woo_st_return_count( 'tax_rate' );
			$download_permissions = woo_st_return_count( 'download_permission' );
			$coupons = woo_st_return_count( 'coupon' );
			$shipping_classes = woo_st_return_count( 'shipping_class' );
			$woocommerce_logs = woo_st_return_count( 'woocommerce_log' );
			$attributes = woo_st_return_count( 'attribute' );

			$brands = woo_st_return_count( 'product_brand' );
			$vendors = woo_st_return_count( 'product_vendor' );
			$store_exports_csv = woo_st_return_count( 'store_export_csv' );
			$store_exports_tsv = woo_st_return_count( 'store_export_tsv' );
			$store_exports_xls = woo_st_return_count( 'store_export_xls' );
			$store_exports_xlsx = woo_st_return_count( 'store_export_xlsx' );
			$store_exports_xml = woo_st_return_count( 'store_export_xml' );
			$store_exports_rss = woo_st_return_count( 'store_export_rss' );
			$credit_cards = woo_st_return_count( 'credit_card' );
			$google_product_feed = woo_st_return_count( 'google_product_feed' );

			$posts = woo_st_return_count( 'post' );
			$post_categories = woo_st_return_count( 'post_category' );
			$post_tags = woo_st_return_count( 'post_tag' );
			$links = woo_st_return_count( 'link' );
			$comments = woo_st_return_count( 'comment' );
			$media_images = woo_st_return_count( 'media_image' );

			$show_table = false;
			if( $products || $images || $tags || $categories || $orders || $store_exports_csv || $credit_cards || $attributes )
				$show_table = true;
			break;

		case 'post_types':
			$args = array();
			$output = 'objects';
			$post_types = get_post_types( $args, $output );
			$post_counts = array();
			$post_ids = array();
			if( !empty( $post_types ) ) {
				foreach( $post_types as $key => $post_type ) {
					$count_posts = wp_count_posts( $key );
					$post_counts[$key] = array_sum( (array)$count_posts );
					$post_ids[$key] = woo_st_get_custom_post_type_ids( $key );
				}
			}
			break;

		case 'tools':
			$autocomplete_order = get_option( WOO_ST_PREFIX . '_autocomplete_order', 0 );
			$unlock_variations = get_option( WOO_ST_PREFIX . '_unlock_variations', 0 );
			$unlock_related_orders = get_option( WOO_ST_PREFIX . '_unlock_related_orders', 0 );
			break;

		case 'settings':
			$troubleshooting_url = 'https://www.visser.com.au/documentation/store-toolkit/';

			$permanently_delete_products = woo_st_get_option( 'permanently_delete_products', 1 );

			$enable_cron = woo_st_get_option( 'enable_cron', 0 );
			$secret_key = woo_st_get_option( 'secret_key', '' );
			break;

	}
	if( $tab ) {
		if( file_exists( WOO_ST_PATH . 'templates/admin/tabs-' . $tab . '.php' ) ) {
			include_once( WOO_ST_PATH . 'templates/admin/tabs-' . $tab . '.php' );
		} else {
			$message = sprintf( __( 'We couldn\'t load the export template file <code>%s</code> within <code>%s</code>, this file should be present.', 'woocommerce-store-toolkit' ), 'tabs-' . esc_attr( $tab ) . '.php', WOO_CD_PATH . 'templates/admin/...' );
			woo_st_admin_notice_html( $message, 'error' );
			ob_start(); ?>
<p><?php _e( 'You can see this error for one of a few common reasons', 'woocommerce-store-toolkit' ); ?>:</p>
<ul class="ul-disc">
	<li><?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin files have been recently changed and there has been a file conflict', 'woocommerce-store-toolkit' ); ?></li>
	<li><?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', 'woocommerce-store-toolkit' ); ?></li>
</ul>
<p><?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', 'woocommerce-store-toolkit' ); ?></p>
<?php
			ob_end_flush();
		}
	}

}

function woo_st_admin_order_column_headers( $columns ) {

	// Check if another Plugin has registered this column
	if( !isset( $columns['user'] ) ) {
		$pos = array_search( 'order_total', array_keys( $columns ) );
		$columns = array_merge(
			array_slice( $columns, 0, $pos ),
			array( 'user' => __( 'User ID', 'woocommerce-store-toolkit' ) ),
			array_slice( $columns, $pos )
		);
	}
	return $columns;

}

function woo_st_admin_order_column_content( $column ) {

	global $post;

	if( $column == 'user' ) {

		$post_id = absint( $post->ID ? $post->ID : false );

		$user_id = get_post_meta( $post_id, '_customer_user', true );
		$user_id = absint( $user_id );
		if( !empty( $user_id ) ) {
			$url = get_edit_user_link( $user_id );
			echo '<a href="' . esc_url( $url ) . '">';
			echo esc_html( sprintf( '#%d', $user_id ) );
			echo '</a>';
		} else {
			echo '-';
		}

		// Allow Plugin/Theme authors to add their own content within this column
		do_action( 'woo_st_admin_order_user_column_content', $post->ID );

	}

}

function woo_st_add_user_column( $columns ) {

	if( !current_user_can( 'manage_woocommerce' ) )
		return $columns;

	$last_column = array_slice( $columns, -1, 1, true );
	array_pop( $columns );
	$columns['woocommerce_orders'] = __( 'Orders', 'woocommerce-store-toolkit' );
	$columns += $last_column;
	return $columns;

}

function woo_st_user_column_values( $value, $column_name, $user_id ) {

	if( $column_name <> 'woocommerce_orders' )
		return $value;

	$value = '0';
	$args = array(
		'fields' => 'ids'
	);
	$orders = woo_st_get_user_orders( $user_id, $args );
	if( !empty( $orders ) )
		$value = count( $orders );

	return $value;

}

function woo_st_woocommerce_register_post_type() {

	// So we can view the Edit Product screen for individual Variations
	$unlock_variations = get_option( WOO_ST_PREFIX . '_unlock_variations', 0 );
	if( !empty( $unlock_variations ) ) {
		add_filter( 'woocommerce_register_post_type_product_variation', 'woo_st_admin_unlock_variations_screen', 10, 1 );
	}

}
add_action( 'woocommerce_register_post_type', 'woo_st_woocommerce_register_post_type' );

function woo_st_extend_woocommerce_system_status_report() {

	global $_wp_additional_image_sizes;

	$image_sizes = get_intermediate_image_sizes();
	ob_start(); ?>
<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="2" data-export-label="Templates"><?php _e( 'Image Sizes', 'woocommerce-store-toolkit' ); ?><?php echo wc_help_tip( __( 'This section shows all available WordPress Image Sizes.', 'woocommerce-exporter' ) ); ?></th>
		</tr>
	</thead>
	<tbody>
<?php if( !empty( $image_sizes ) ) { ?>
	<?php foreach( $image_sizes as $image_size ) { ?>
		<tr>
			<td><?php echo esc_html( $image_size ); ?></td>
			<td>
		<?php if( isset( $_wp_additional_image_sizes[$image_size] ) ) { ?>
				<?php echo esc_html( print_r( $_wp_additional_image_sizes[$image_size], true ) ); ?>
		<?php } else { ?>
<?php
	// Check for default WordPress Image Sizes
	$size_info = array();
	switch( $image_size ) {

		case 'thumbnail':
			$size_info = array(
				'width' => get_option( 'thumbnail_size_w' ),
				'height' => get_option( 'thumbnail_size_h' ),
				'crop' => get_option( 'thumbnail_crop' )
			);
			break;

		case 'medium':
			$size_info = array(
				'width' => get_option( 'medium_size_w' ),
				'height' => get_option( 'medium_size_h' )
			);
			break;

		case 'medium_large':
			$size_info = array(
				'width' => get_option( 'medium_large_size_w' ),
				'height' => get_option( 'medium_large_size_h' )
			);

		case 'large':
			$size_info = array(
				'width' => get_option( 'large_size_w' ),
				'height' => get_option( 'large_size_h' )
			);
			break;

	}
?>
				<?php echo esc_html( !empty( $size_info ) ? print_r( $size_info, true ) : '-' ); ?>
		<?php } ?>
			</td>
		</tr>
	<?php } ?>
<?php } else { ?>
		<tr>
			<td colspan="2"><?php _e( 'No Image Sizes were available.', 'woocommerce-store-toolkit' ); ?></td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php
	ob_end_flush();

}
add_action( 'woocommerce_system_status_report', 'woo_st_extend_woocommerce_system_status_report' );