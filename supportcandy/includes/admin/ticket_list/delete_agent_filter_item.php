<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (
	check_ajax_referer('delete_agent_filter_item', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

global $current_user, $wpdb, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$field_id = isset($_POST) && isset($_POST['field_id']) ? intval($_POST['field_id']) : 0;
if (!$field_id) {exit;}

update_term_meta ($field_id, 'wpsc_agent_ticket_filter_status', '0');

echo '{ "sucess_status":"1","messege":"'.__('Removed successfully.','supportcandy').'" }';
