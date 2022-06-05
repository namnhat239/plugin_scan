<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction ,$wp;

if (
	check_ajax_referer('set_delete_thread', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

if (!($current_user->ID && $current_user->has_cap('wpsc_agent'))) {
	exit;
}

$thread_id  = isset($_POST['thread_id']) ? sanitize_text_field($_POST['thread_id']) : 0 ;
wp_delete_post($thread_id);
?>