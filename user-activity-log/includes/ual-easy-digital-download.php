<?php
/*
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
* Get activity for the user - Activate Discount code
*
* @param array $data dicount data
*/
if ( ! function_exists( 'ualActivateDiscountCode' ) ) {

	function ualActivateDiscountCode( $data ) {
		if ( $data['discount'] ) {
			$action     = 'Activate';
			$obj_type   = 'edd_discount';
			$post_id    = absint( $data['discount'] );
			$post_title = 'edd_discount Activate' . get_the_title( $post_id );
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}

add_action( 'edd_activate_discount', 'ualActivateDiscountCode' ); // new.

/*
* Get activity for the user - Deactivate Discount code
*
* @param array $data dicount data
*/
if ( ! function_exists( 'ualDeactivateDiscountCode' ) ) {

	function ualDeactivateDiscountCode( $data ) {
		if ( isset( $data['discount'] ) ) {
			$action     = 'Deactivate';
			$obj_type   = 'edd_discount';
			$post_id    = absint( $data['discount'] );
			$post_title = 'edd_discount Deactivate' . get_the_title( $post_id );
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}

add_action( 'edd_deactivate_discount', 'ualDeactivateDiscountCode' ); // new.

/*
* Get activity for the user - Add Discount
*
* @param array $data dicount data
*/
if ( ! function_exists( 'ualAddDiscountCode' ) ) {

	function ualAddDiscountCode( $data ) {
		if ( isset( $data['discount'] ) ) {
			$action     = 'Added';
			$obj_type   = 'edd_discount';
			$post_id    = $data['discount'];
			$post_title = 'Add Discount Code';
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}

add_action( 'edd_add_discount', 'ualAddDiscountCode' );


/*
* Get activity for the user - Delete Discount
*
* @param array $data dicount data
*/
if ( ! function_exists( 'ualDeleteDiscountCode' ) ) {

	function ualDeleteDiscountCode( $data ) {
		if ( isset( $data['discount'] ) ) {
			$action     = 'Deleted';
			$obj_type   = 'edd_discount';
			$post_id    = $data['discount'];
			$post_title = 'Discount Code Deleted';
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			$user = wp_get_current_user();
		}
	}
}

add_action( 'edd_delete_discount', 'ualDeleteDiscountCode' );

/*
* Get activity for the user - Edit Discount
*/
if ( ! function_exists( 'ualEditDiscount' ) ) {
	function ualEditDiscount() {
		if ( ! empty( $_POST['discount-id'] ) ) {
			$post_id = isset( $_POST['discount-id'] ) ? (int) $_POST['discount-id'] : 0;
			if ( ! $post_id ) {
				return;
			}
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			};
			$old_post_data  = array(
				'post_data' => '',
				'post_meta' => get_post_custom( $post_id ),
				'post_tax'  => '',
			);
			$post_meta_data = ualGetPostMetaData( 'discount' );
			$new_post_meta  = '';

			foreach ( $post_meta_data as $post_meta_key => $post_meta_value ) {
				if ( ! is_numeric( $post_meta_key ) ) {
					if ( isset( $_POST[ $post_meta_key ] ) ) {
						$meta_key_value                       = isset($_POST[ $post_meta_key ]) ? maybe_serialize( $_POST[ $post_meta_key ] ) : ''; //phpcs:ignore
						$new_post_meta[ $post_meta_value ][0] = sanitize_text_field( $meta_key_value );
					}
				} else {
					if ( isset( $_POST[ $post_meta_value ] ) ) {
						$meta_key_value                       = isset($_POST[ $post_meta_value ]) ? maybe_serialize( $_POST[ $post_meta_value ] ) : ''; //phpcs:ignore
						$new_post_meta[ $post_meta_value ][0] = sanitize_text_field( $meta_key_value );
					}
				}
			}
			$new_post_data  = array(
				'post_data' => '',
				'post_meta' => $new_post_meta,
				'post_tax'  => '',
			);
				$action     = 'updated';
				$obj_type   = 'edd_discount';
				$hook       = 'transition_post_status';
				$post_title = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
				$page_link  = get_edit_post_link( $post_id );
			if ( '' == $page_link ) {
				$page_link = get_the_permalink( $post_id );
			}
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
		}
	}
}
add_action( 'init', 'ualEditDiscount' );

/*
 * function to get all meta key of post
 */
if ( ! function_exists( 'ualGetPostMetaData' ) ) {
	function ualGetPostMetaData( $post_type = '' ) {
		$get_default_metakey_array = array( '_thumbnail_id', '_product_attributes', '_product_image_gallery', '_manage_stock', '_stock_status', '_stock', '_backorders', '_price', '_regular_price', '_sale_price', '_sale_price_dates_from', '_sale_price_dates_to', '_sold_individually', '_upsell_ids', '_crosssell_ids', '_tax_status', '_tax_class', '_weight', '_length', '_width', '_height', '_sku', '_featured', '_purchase_note', '_virtual', '_downloadable', '_visibility', 'discount_type', 'coupon_amount', 'individual_use', 'product_ids', 'exclude_product_ids', 'usage_limit', 'usage_limit_per_user', 'limit_usage_to_x_items', 'expiry_date', 'free_shipping', 'exclude_sale_items', 'product_categories', 'exclude_product_categories', 'minimum_amount', 'maximum_amount', 'customer_email', 'position', 'layout', 'hide_on_screen', 'rule', 'field_', '_edd_download_earnings', '_edd_download_sales', 'edd_price', 'edd_variable_prices', 'edd_download_files', '_edd_bundled_products', '_edd_bundled_products_conditions', '_variable_pricing', '_edd_default_price_id', '_edd_hide_purchase_link', '_edd_download_limit', '_edd_discount_code', '_edd_discount_name', '_edd_discount_status', '_edd_discount_max_uses', '_edd_discount_amount', '_edd_discount_start', '_edd_discount_expiration', '_edd_discount_type', '_edd_discount_min_price', '_edd_discount_product_reqs', '_edd_discount_product_condition', '_edd_discount_excluded_products', '_edd_discount_is_not_global', '_edd_discount_is_single_use', '_downloadable_files' );
		return apply_filters( 'ual_post_meta_array', $get_default_metakey_array, $post_type );
	}
}
