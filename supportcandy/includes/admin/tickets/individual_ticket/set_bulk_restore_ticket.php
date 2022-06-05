<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction,$wpdb,$current_user;

if (
	check_ajax_referer('set_bulk_restore_ticket', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$ticket_id_data  = isset($_POST['ticket_id']) ? (sanitize_text_field($_POST['ticket_id'])) : '' ;
$ticket_ids = explode(',', $ticket_id_data);

foreach ($ticket_ids as $ticket_id){

	$meta_value =array(
		'active' => '1'
	);
	$wpdb->update($wpdb->prefix.'wpsc_ticket', $meta_value, array('id'=>$ticket_id));
	do_action('wpsc_restore_ticket',$ticket_id);
}
