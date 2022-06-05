<?php
// Format the raw post_status
function woo_st_format_post_status( $post_status = '' ) {

	$output = $post_status;
	switch( $post_status ) {

		case 'publish':
			$output = __( 'Publish', 'woocommerce-store-toolkit' );
			break;

		case 'draft':
			$output = __( 'Draft', 'woocommerce-store-toolkit' );
			break;

		case 'pending':
			$output = __( 'Pending', 'woocommerce-store-toolkit' );
			break;

		case 'private':
			$output = __( 'Private', 'woocommerce-store-toolkit' );
			break;

		case 'trash':
			$output = __( 'Trash', 'woocommerce-store-toolkit' );
			break;

		case 'future':
			$output = __( 'Future', 'woocommerce-exporter' );
			break;

		case 'auto-draft':
			$output = __( 'Auto draft', 'woocommerce-exporter' );
			break;

		case 'inherit':
			$output = __( 'Inherit', 'woocommerce-exporter' );
			break;

		/* Order Status */

		case 'cancelled':
			$output = __( 'Cancelled', 'woocommerce-store-toolkit' );
			break;

	}
	$output = apply_filters( 'woo_st_format_post_status', $output, $post_status );
	return $output;

}

function woo_st_format_payment_gateway_label( $payment_id = '' ) {

	if( empty( $payment_id ) )
		$output = __( 'N/A', 'woocommerce-store-toolkit' );

	$output = ucfirst( $payment_id );

	return $output;

}