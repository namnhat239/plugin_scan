<?php
echo '<h3>' . __( 'User Orders', 'woocommerce-store-toolkit' ) . '</h3>';
echo '<table class="form-table">';
echo '<tr>';
echo '<th>';
echo '<label>' . __( 'Orders', 'woocommerce-store-toolkit' ) . '</label>';
echo '</th>';
echo '<td>';

echo '<table class="wp-list-table widefat fixed striped order_data" cellspacing="0">';

echo '<thead>';
echo '<tr>';
echo '<th class="manage-column">' . __( 'Order', 'woocommerce-store-toolkit' ) . '</th>';
echo '<th class="manage-column">' . __( 'Date', 'woocommerce-store-toolkit' ) . '</th>';
echo '<th scope="col" id="order_status" class="manage-column column-order_status">' . __( 'Status', 'woocommerce-store-toolkit' ) . '</th>';
echo '<th class="manage-column">' . __( 'Total', 'woocommerce-store-toolkit' ) . '</th>';
echo '</tr>';
echo '</thead>';

echo '<tbody class="the-list">';
if( !empty( $orders ) ) {
	foreach( $orders as $order ) {

		if( version_compare( WOOCOMMERCE_VERSION, '2.7', '>=' ) ) {
			// $order = wc_get_order( $order );
			$order = new WC_Order( $order );
			$order_id = trim( str_replace( '#', '', $order->get_order_number() ) );
			$payment_method_title = $order->get_payment_method_title();
			$order_date = $order->get_date_created();
			$order_status = $order->get_status();
			$order_total = $order->get_formatted_order_total();
		} else {
			$order = new WC_Order();
			$order->populate( $order );
			$order_id = $order->get_order_number();
			$order_data = (array)$order;
			$payment_method_title = $order->payment_method_title;
			$order_date = $order->order_date;
			$order_status = $order->post_status;
			$order_total = $order->get_formatted_order_total();
		}

		echo '<tr class="type-shop_order status-' . esc_attr( $order_status ) . '">';

		echo '<td>';
		echo '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $order->get_id() ) . '&action=edit' ) ) . '" class="row-title">';
		echo '<strong>#' . esc_html( $order_id ) . '</strong></a>';
		echo '</td>';

		echo '<td>';
		if( '0000-00-00 00:00:00' == $order_date ) {
			$t_time = $h_time = __( 'Unpublished', 'woocommerce' );
		} else {
			$t_time = get_date_from_gmt( $order_date, __( 'Y/m/d g:i:s A', 'woocommerce' ) );
			$h_time = get_date_from_gmt( $order_date, get_option( 'date_format' ) );
		}
		echo '<abbr title="' . esc_attr( $t_time ) . '">' . esc_html( $h_time ) . '</abbr>';

		echo '</td>';
		echo '<td class="order_status column-order_status" data-colname="' . __( 'Status', 'woocommerce-store-toolkit' ) . '">';
		echo '<mark class="order-status status-' . esc_attr( sanitize_title( $order_status ) ) . ' tips" data-tip="' . esc_attr( wc_get_order_status_name( $order_status ) ) . '" style="padding:0 0.8em;">' . esc_html( wc_get_order_status_name( $order_status ) ) . '</mark>';
		echo '</td>';

		echo '<td>';
		echo wp_kses_data( $order_total );
		if( $payment_method_title )
			echo '<small class="meta">' . __( 'Via', 'woocommerce' ) . ' ' . esc_html( $payment_method_title ) . '</small>';
		echo '</td>';

		echo '</tr>';

	}
} else {

	echo '<tr>';
	echo '<td colspan="4">';
	echo __( 'No Orders are associated with this User.', 'woocommerce-store-toolkit' );
	echo '</td>';
	echo '</tr>';

}
echo '</tbody>';
echo '</table>';

if( !empty( $orders ) ) {

	echo '<div class="tablenav top">';
	echo '<div class="tablenav-pages">';
	echo '<span class="displaying-num">' . esc_html( sprintf( __( '%d items', 'woocommerce-store-toolkit' ), $total_orders ) ) . '</span>';
	if( $paged == 1 ) {
		echo '<span class="pagination-links"><span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>';
		echo '<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>';
	} else {
		echo '<a class="first-page" href="' . esc_url( add_query_arg( array( 'paged' => NULL ) ) ) . '"><span class="screen-reader-text">First page</span><span aria-hidden="true">&laquo;</span></a>';
		echo '<a class="prev-page" href="' . esc_url( add_query_arg( array( 'paged' => ( $paged - 1 ) ) ) ) . '"><span class="screen-reader-text">Previous page</span><span aria-hidden="true">&lsaquo;</span></a>';
	}
	echo '<span class="screen-reader-text">' . __( 'Current Page', 'woocommerce-store-toolkit' ) . '</span>';
	echo '<span id="table-paging" class="paging-input"><span class="tablenav-paging-text">' . esc_html( $paged ) . ' of <span class="total-pages">' . esc_html( $max_page ) . '</span></span></span>';
	if( $paged == $max_page ) {
		echo '<span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>';
		echo '<span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>';
	} else {
		echo '<a class="next-page" href="' . esc_url( add_query_arg( array( 'paged' => ( $paged + 1 ) ) ) ) . '"><span class="screen-reader-text">Next page</span><span aria-hidden="true">&rsaquo;</span></a>';
		echo '<a class="last-page" href="' . esc_url( add_query_arg( array( 'paged' => $max_page ) ) ) . '"><span class="screen-reader-text">Last page</span><span aria-hidden="true">&raquo;</span></a></span>';
	}
	echo '</div>';
	echo '<!-- .tablenav-pages -->';
	echo '</div>';
	echo '<!-- .tablenav -->';

}

echo '</td>';
echo '</tr>';
echo '</table>';