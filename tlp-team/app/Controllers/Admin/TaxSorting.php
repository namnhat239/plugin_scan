<?php
/**
 * Sorting Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin;

use RT\Team\Helpers\Fns;

/**
 * Sorting Class.
 */
class TaxSorting {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'admin_init', array( $this, 'refresh' ) );

		$taxo = array(
			'team_department',
			'team_designation',
		);

		foreach ( $taxo as $tx ) {
			add_filter( 'manage_edit-' . $tx . '_columns', array( &$this, 'term_column_header' ), 10, 1 );
			add_filter( 'manage_' . $tx . '_custom_column', array( &$this, 'term_column_value' ), 10, 3 );
		}

		add_action( 'pre_get_posts', array( $this, 'tlp_pre_get_posts' ) );
		add_action( 'wp_ajax_tlp-team-update-menu-order', array( $this, 'tlp_team_update_menu_order' ) );
		add_action( 'wp_ajax_ttp-term-update-order', array( $this, 'ttp_term_update_order' ) );
		add_action( 'wp_ajax_ttp-get-term-list', array( $this, 'ttp_get_term_list' ) );
	}


	function ttp_get_term_list() {

		$html  = $msg = null;
		$error = true;
		if ( Fns::verifyNonce() ) {
			$tax = ( ! empty( $_REQUEST['tax'] ) ? $_REQUEST['tax'] : null );
			if ( $tax ) {
				$error = false;
				$terms = get_terms(
					$tax,
					array(
						'orderby'    => 'meta_value_num',
						'meta_key'   => '_rt_order',
						'order'      => 'ASC',
						'hide_empty' => false,
					)
				);
				if ( ! empty( $terms ) ) {
					$html .= "<ul id='order-target' data-taxonomy='{$tax}'>";
					foreach ( $terms as $term ) {
						$html .= "<li data-id='{$term->term_id}'><span>{$term->name}</span></li>";
					}
					$html .= '</ul>';
				} else {
					$html .= '<p>' . esc_html__( 'No term found', 'tlp-team' ) . '</p>';
				}
			} else {
				$html .= '<p>' . esc_html__( 'Select a taxonomy', 'tlp-team' ) . '</p>';
			}
		} else {
			$html .= '<p>' . esc_html__( 'Security error', 'tlp-team' ) . '</p>';
		}

		wp_send_json(
			array(
				'data'  => $html,
				'error' => $error,
				'msg'   => $msg,
			)
		);
		die();
	}

	function term_column_header( $columns ) {
		$columns['order'] = esc_html__( 'Order', 'tlp-team' );
		return $columns;
	}

	function term_column_value( $empty, $custom_column, $term_id ) {
		$empty = '';

		if ( 'order' == $custom_column ) {
			return get_term_meta( $term_id, '_rt_order', true );
		}
	}

	function tlp_pre_get_posts( $wp_query ) {
		if ( is_admin() ) {
			if ( isset( $wp_query->query['post_type'] ) && ! isset( $_GET['orderby'] ) && $wp_query->query['post_type'] == 'team' && $wp_query->is_main_query() ) {
				$wp_query->set( 'orderby', 'menu_order' );
				$wp_query->set( 'order', 'ASC' );
			}
		}
	}

	function tlp_team_update_menu_order() {

		global $wpdb;
		$data = ( ! empty( $_POST['post'] ) ? $_POST['post'] : array() );

		if ( ! is_array( $data ) ) {
			return false;
		}

		$id_arr = array();
		foreach ( $data as $position => $id ) {
			$id_arr[] = $id;
		}

		$menu_order_arr = array();
		foreach ( $id_arr as $key => $id ) {
			$results = $wpdb->get_results( "SELECT menu_order FROM $wpdb->posts WHERE ID = " . intval( $id ) );
			foreach ( $results as $result ) {
				$menu_order_arr[] = $result->menu_order;
			}
		}

		sort( $menu_order_arr );

		foreach ( $data as $position => $id ) {
			$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_order_arr[ $position ] ), array( 'ID' => intval( $id ) ) );
		}

		wp_send_json_success();
	}

	/**
	 *
	 */
	function refresh() {
		global $wpdb;

		$results = $wpdb->get_results(
			"
		SELECT ID
		FROM $wpdb->posts
		WHERE post_type = '" . rttlp_team()->post_type . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
		ORDER BY menu_order ASC
	"
		);
		foreach ( $results as $key => $result ) {
			$wpdb->update( $wpdb->posts, array( 'menu_order' => $key + 1 ), array( 'ID' => $result->ID ) );
		}
	}

	/**
	 * @return bool
	 */
	function ttp_term_update_order() {

		$data = ( ! empty( $_POST['terms'] ) ? explode( ',', $_POST['terms'] ) : array() );
		if ( ! is_array( $data ) && empty( $data ) ) {
			return false;
		}
		// sort( $order_arr );

		foreach ( $data as $position => $id ) {
			update_term_meta( intval( $id ), '_rt_order', $position );
		}
		die();
	}
}
