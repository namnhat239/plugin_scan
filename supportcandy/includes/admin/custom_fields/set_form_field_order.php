<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (
	check_ajax_referer('set_form_field_order', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$field_ids = isset($_POST) && isset($_POST['field_ids']) ? $wpscfunction->sanitize_array($_POST['field_ids']) : array();

foreach ($field_ids as $key => $field_id) {
	update_term_meta(intval($field_id), 'wpsc_tf_load_order', intval($key));
}

echo '{ "sucess_status":"1","messege":"'.__('Form field order saved.','supportcandy').'" }';
