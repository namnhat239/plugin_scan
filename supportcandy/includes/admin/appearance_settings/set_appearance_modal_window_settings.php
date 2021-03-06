<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (
	check_ajax_referer('set_appearance_modal_window_settings', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_appearance_modal_window = isset($_POST) && isset($_POST['appearance_modal_window']) ? $wpscfunction->sanitize_array($_POST['appearance_modal_window']) : array();

update_option('wpsc_modal_window',$wpsc_appearance_modal_window);

do_action('wpsc_set_modal_window_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','supportcandy').'" }';