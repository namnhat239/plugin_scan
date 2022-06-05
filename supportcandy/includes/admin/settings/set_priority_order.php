<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

if (
	check_ajax_referer('set_priority_order', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$priority_ids = isset($_POST) && isset($_POST['priority_ids']) ? $wpscfunction->sanitize_array($_POST['priority_ids']) : array();

foreach ($priority_ids as $key => $priority_id) {
	update_term_meta(intval($priority_id), 'wpsc_priority_load_order', intval($key));
}

echo '{ "sucess_status":"1","messege":"'.__('Priority order saved.','supportcandy').'" }';
