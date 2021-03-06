<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

if (
	check_ajax_referer('set_edit_agent_role', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$role_id = isset($_POST) && isset($_POST['role_id']) ? intval($_POST['role_id']) : 0;
if (!$role_id) {
  exit;
}

$agent_role = get_option('wpsc_agent_role');

$agent_role_item = isset($_POST) && isset($_POST['agentrole']) && is_array($_POST['agentrole']) ? $wpscfunction->sanitize_array($_POST['agentrole']) : array();

foreach($agent_role as $key => $val){
  $agent_role_item[sanitize_key($key)] = sanitize_text_field($val);
}

$agent_role[$role_id] = $agent_role_item;

update_option('wpsc_agent_role',$agent_role);

do_action('wpsc_set_edit_agent_role');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','supportcandy').'" }';

$label_count_history = get_option( 'wpsc_label_count_history' );
update_option('wpsc_label_count_history',++$label_count_history);
