<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (
	check_ajax_referer('set_appearance_general_settings', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

global $current_user,$wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_appearance_general_settings = isset($_POST) && isset($_POST['general_settings']) ? $wpscfunction->sanitize_array($_POST['general_settings']) : array();

update_option('wpsc_appearance_general_settings',$wpsc_appearance_general_settings);

do_action('wpsc_set_appearance_general_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','supportcandy').'" }';
