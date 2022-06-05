<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

if (
	check_ajax_referer('set_category_order', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$cat_ids = isset($_POST) && isset($_POST['cat_ids']) ? $wpscfunction->sanitize_array($_POST['cat_ids']) : array();

foreach ($cat_ids as $key => $cat_id) {
	update_term_meta(intval($cat_id), 'wpsc_category_load_order', intval($key));
}

echo '{ "sucess_status":"1","messege":"'.__('Category order saved.','supportcandy').'" }';
