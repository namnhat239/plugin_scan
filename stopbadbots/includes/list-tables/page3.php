<?php
/**
 * WP List Table admin page view
 * Bill
 *
 * @license   GPL-2.0+
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form id="badbots-filter" method="get">
		<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />


		<?php $stopbadbots_list_table3->search_box( __( 'Search Referer', 'stopbadbots' ), 'search-table' ); ?>

		<?php $stopbadbots_list_table3->display(); ?>
	</form>
</div>

