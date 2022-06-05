<?php
if( !empty( $orders ) ) {
	echo '<ul>';
	foreach( $orders as $order ) {
		echo '<li>';
		echo '<a href="' . esc_url( add_query_arg( 'post', $order ) ) . '">' . esc_html( sprintf( '#%s', $order ) ) . '</a>';
		echo '</li>';
	}
	echo '</ul>';
	echo '<p class="description">';
	echo '* ';
	echo esc_html( sprintf( __( 'Orders matched by <code>%s</code>', 'woocommerce-store-toolkit' ), $matching ) );
	echo '</p>';
} else {
	_e( 'No other Orders were found.', 'woocommerce-store-toolkit' );
}