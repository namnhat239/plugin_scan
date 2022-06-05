<?php
/**
 * @author William Sergio Minossi
 * @copyright 2020
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


global $wpdb;

$stopbadbots_table = $wpdb->prefix . 'sbb_visitorslog';

$recordsTotal = $wpdb->get_var("SELECT  COUNT(*)  FROM $stopbadbots_table");



if ( $recordsTotal < 1 ) {
	echo '<br>';
	echo '<br><h3>';
	echo esc_attr__( 'Empty Table. Please, try again later.', 'stopbadbots' );
	sleep( 5 );
	echo '<br></h3>';
	return;

}
?>

<style>
div.dataTables_wrapper div.dataTables_processing {
   top: 0;
}
</style>

<div id="stopbadbots-logo">
  <img alt="logo" src="<?php echo esc_attr( STOPBADBOTSIMAGES ); ?>/logo.png" width="250px" />
</div>
<div id="stopbadbots_help_title">
  <?php esc_attr_e( 'Visits Log', 'stopbadbots' ); ?>
</div>
<div class="table-responsive" style="margin-top: 0px; margin-right:20px; width: 99%; max-width:99%;">
  <table style="margin-right:20px; cellpadding=" 0" cellspacing="0" border="1px" class="dataTable" id="dataTableVisitorsSBB">
	<thead>
	  <tr>
		<th></th>
		<th>date</th>
		<th>access</th>
		<th>ip</th>
		<th>reason</th>
		<th>response</th>
		<th>method</th>
		<th>user agent</th>
		<th>url</th>
		<th>referer</th>
	  </tr>
	</thead>
	<tfoot>
	  <tr>
		<th></th>
		<th>date</th>
		<th>access</th>
		<th>ip</th>
		<th>reason</th>
		<th>response</th>
		<th>method</th>
		<th>user agent</th>
		<th>url</th>
		<th>referer</th>
	  </tr>
	</tfoot>
	<tbody>
	</tbody>
  </table>
  <div id="dialog-confirm" title="Confirm">
	<div id="modal-body">
	</div>
  </div>
