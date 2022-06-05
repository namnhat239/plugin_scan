<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;

if (
	check_ajax_referer('delete_support_agent', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$agent_id = isset($_POST) && isset($_POST['agent_id']) ? intval($_POST['agent_id']) : 0;
if (!$agent_id) {exit;}

$user_id = get_term_meta( $agent_id, 'user_id', true);
$user = get_user_by('id',$user_id);
if($user){
	$user->remove_cap('wpsc_agent');
	delete_user_option($user_id,'wpsc_agent_role');
}

wp_delete_term($agent_id, 'wpsc_agents');

do_action('wpsc_delete_agent',$agent_id);

echo '{ "sucess_status":"1","messege":"'.__('Agent deleted successfully.','supportcandy').'" }';
