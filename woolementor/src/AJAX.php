<?php
/**
 * All AJAX related functions
 */
namespace Codexpert\Woolementor;
use Codexpert\Plugin\Base;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage AJAX
 * @author Codexpert <hi@codexpert.io>
 */
class AJAX extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}

	public function fetch_docs() {
		if( ! is_wp_error( $_posts_data = wp_remote_get( "https://help.codexpert.io/wp-json/wp/v2/docs/?parent={$this->plugin['doc_id']}&per_page=100" ) ) && is_array( $posts = json_decode( $_posts_data['body'], true ) ) && count( $posts ) > 0 ) {
		    update_option( 'woolementor-docs_json', $posts );
		}
	}

	/**
	 * Adds a product to the wishlist
	 * Removes a product from the wishlist
	 *
	 * @TODO: change method name 
	 *
	 * @since 1.0
	 */
	public function add_to_wish() {
		$response = [
			 'status'	=> 0,
			 'message'	=>__( 'Unauthorized!', 'woolementor' )
		];

		if( !wp_verify_nonce( $_POST['_wpnonce'], $this->slug ) ) {
		    wp_send_json( $response );
		}

		if( !isset( $_POST['product_id'] ) ) {
			$response['message'] = __( 'No product selected!', 'woolementor' );
		    wp_send_json( $response );
		}

		extract( $_POST );

		$user_id = get_current_user_id();
		$wishlist = wcd_get_wishlist( $user_id );

		// if the product is already in the wishlist, remove
		if ( ( $key = array_search( $product_id, $wishlist ) ) !== false ) {
			$response['action'] = 'removed';
		    unset( $wishlist[ $key ] );
		}

		// add to wishlist
		else {
			$response['action'] = 'added';
			$wishlist[] = $product_id;
		}

		$wishlist = array_unique( $wishlist );

		// update wishlist
		wcd_set_wishlist( $wishlist, $user_id );

		// send response
		$response['status'] = 1;
		$response['message'] = sprintf( __( 'Wishlist item %s!', 'woolementor' ), $response['action'] );
		wp_send_json( $response );
	}

	public function add_variations_to_cart() {
		$response['status'] 	= 0;
		$response['message'] 	= __( 'Something is wrong!', 'woolementor' );
		
		if( !wp_verify_nonce( $_POST['_wpnonce'], 'woolementor' ) ) {
			$response['message'] = __( 'Unauthorized!', 'woolementor' );
		    wp_send_json( $response );
		}

		$variations 		= isset( $_POST['variation'] ) ? array_map( 'codesigner_sanitize_number', $_POST['variation'] ) : [] ;
		$product_id 		= isset( $_POST['product_id'] ) ? codesigner_sanitize_number( $_POST['product_id'] ) : 0 ;
		$attributes 		= isset( $_POST['attributes'] ) ? $_POST['attributes'] : []; // sanitized later @L:110
		$variation_checked 	= isset( $_POST['variation_checked'] ) ? array_map( 'sanitize_text_field',  $_POST['variation_checked'] ) : [] ;
		
		$checked_items 		= array_intersect_key( $variations, $variation_checked );

		if ( count( $checked_items ) < 1 ) {
			$response['message'] = __( 'No variations selected!', 'woolementor' );
			wp_send_json( $response );
		}
		
		foreach ( $checked_items as $variation_id => $qty ) {
			$_attribute = [];

			if ( isset( $attributes[ $variation_id ] ) && !is_null( $attributes[ $variation_id ] ) ) {
				$_attribute = array_map( 'sanitize_text_field', $attributes[ $variation_id ] );
			}

			WC()->cart->add_to_cart( $product_id, codesigner_sanitize_number( $qty ), codesigner_sanitize_number( $variation_id ), $attribute );
		}

		$response['checked_items'] 	= $checked_items;
		$response['status'] 	= 1;
		$response['message'] 	= __( 'Product Added', 'woolementor' );
		wp_send_json( $response );
	}

	public function multiple_product_add_to_cart() {
		$response['status'] 	= 0;
		$response['message'] 	= __( 'Something is wrong!', 'woolementor' );
		
		if( !wp_verify_nonce( $_POST['_wpnonce'], 'woolementor' ) ) {
			$response['message'] = __( 'Unauthorized!', 'woolementor' );
		    wp_send_json( $response );
		}

		$_checked_items = isset( $_POST['cart_item_ids'] ) ? array_map( 'codesigner_sanitize_number', $_POST['cart_item_ids'] ) : [];
		$_multiple_qty 	= isset( $_POST['multiple_qty'] ) ? array_map( 'codesigner_sanitize_number', $_POST['multiple_qty'] ) : [];

		if ( count( $_checked_items ) < 1 ) {
			$response['message'] = __( 'No products selected!', 'woolementor' );
			wp_send_json( $response );
		}

		foreach ( $checked_items as $key => $item ) {
			$qty = is_null( $multiple_qty ) && !isset( $multiple_qty[ $item ] ) ? 1 : $multiple_qty[ $item ];
			WC()->cart->add_to_cart( codesigner_sanitize_number( $item ), codesigner_sanitize_number( $qty ) );
		}

		$response['status'] 	= 1;
		$response['checked_items'] 	= $checked_items;
		$response['multiple_qty'] 	= $multiple_qty;

		$response['_checked_items'] = $_checked_items;
		$response['_multiple_qty'] 	= $_multiple_qty;
		$response['message'] 	= __( 'Product Added!', 'woolementor' );
		wp_send_json( $response );
	}

	public function template_sync(){
		$response['status'] 	= 0;
		$response['message'] 	= __( 'Something is wrong!', 'woolementor' );
		
		if( !wp_verify_nonce( $_POST['_wpnonce'], 'woolementor' ) ) {
			$response['message'] = __( 'Unauthorized!', 'woolementor' );
		    wp_send_json( $response );
		}

		Library_Source::get_library_data( true );

		$response['status'] 	= 1;
		$response['message'] 	= __( 'Synchronization Complete', 'woolementor' );
		wp_send_json( $response );
	}
}