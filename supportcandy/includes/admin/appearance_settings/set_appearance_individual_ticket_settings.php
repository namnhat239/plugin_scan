<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (
	check_ajax_referer('set_appearance_individual_ticket_settings', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_appearance_individual_ticket_page = isset($_POST) && isset($_POST['individual_ticket_page']) ? $wpscfunction->sanitize_array($_POST['individual_ticket_page']) : array();

update_option('wpsc_individual_ticket_page',$wpsc_appearance_individual_ticket_page);
	
do_action('wpsc_set_appearance_individual_ticket_page_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','supportcandy').'" }';