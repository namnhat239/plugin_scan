<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

if (
	check_ajax_referer('set_status_order', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$status_ids = isset($_POST) && isset($_POST['status_ids']) ? $wpscfunction->sanitize_array($_POST['status_ids']) : array();

foreach ($status_ids as $key => $status_id) {
	update_term_meta(intval($status_id), 'wpsc_status_load_order', intval($key));
}

echo '{ "sucess_status":"1","messege":"'.__('Status order saved.','supportcandy').'" }';
