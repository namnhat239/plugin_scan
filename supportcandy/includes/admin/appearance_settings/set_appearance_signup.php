<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (
	check_ajax_referer('set_appearance_signup', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$wpsc_appearance_signup = isset($_POST) && isset($_POST['wpsc_appearance_signup']) ? $wpscfunction->sanitize_array($_POST['wpsc_appearance_signup']) : array();

update_option('wpsc_appearance_signup',$wpsc_appearance_signup);
	
do_action('wpsc_set_appearance_sign_up');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','supportcandy').'" }';