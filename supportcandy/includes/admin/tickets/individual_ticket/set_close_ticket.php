<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpscfunction;

if (
	check_ajax_referer('set_close_ticket', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

$ticket_id  = isset($_POST['ticket_id']) ? sanitize_text_field($_POST['ticket_id']) : '' ;

$wpsc_close_ticket_status = get_option('wpsc_close_ticket_status');

if($wpsc_close_ticket_status!=''){
  $wpscfunction->change_status( $ticket_id, $wpsc_close_ticket_status);
}

?>