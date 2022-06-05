<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction,$wpdb,$current_user;

$ticket_id_data  = isset($_POST['ticket_id']) ? (sanitize_text_field($_POST['ticket_id'])) : '' ;
$ticket_ids = explode(',', $ticket_id_data);

if (
	check_ajax_referer('set_delete_permanently_bulk_ticket_'.$ticket_id_data, '_ajax_nonce', false) != 1 || 
	!$current_user->has_cap('manage_options')
) wp_send_json_error('Unauthorised request!', 400);

foreach ($ticket_ids as $ticket_id ) {
	
	$wpdb->delete($wpdb->prefix.'wpsc_ticket', array( 'id' => $ticket_id));
	$wpdb->delete($wpdb->prefix.'wpsc_ticketmeta', array('ticket_id' => $ticket_id));
	
	$args = array(
		'post_type'      => 'wpsc_ticket_thread',
		'post_status'    => array('publish','trash'),
		'posts_per_page' => -1,
		'meta_query'     => array(
			 array(
				'key'     => 'ticket_id',
				'value'   => $ticket_id,
				'compare' => '='
			),
		),
	);
	$ticket_threads = get_posts($args);
	if($ticket_threads) {
		foreach ($ticket_threads as $ticket_thread ) {
			 wp_delete_post($ticket_thread->ID,true);
		}
	}
}

