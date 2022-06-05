<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction,$current_user;

if (
	check_ajax_referer('set_delete_ticket', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

if (!($current_user->ID && $current_user->has_cap('wpsc_agent'))) { exit; }

$ticket_id  = isset($_POST['ticket_id']) ? (sanitize_text_field($_POST['ticket_id'])) : '' ;

if($wpscfunction->has_permission('delete_ticket',$ticket_id)){
	 $wpscfunction->delete_tickets($ticket_id);
}

