<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

if (
	check_ajax_referer('set_en_general_settings', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

// From Name
$from_name = isset($_POST) && isset($_POST['wpsc_en_from_name']) ? sanitize_text_field($_POST['wpsc_en_from_name']) : '';
update_option('wpsc_en_from_name',$from_name);

// From Email
$from_email = isset($_POST) && isset($_POST['wpsc_en_from_email']) ? sanitize_email($_POST['wpsc_en_from_email']) : '';
update_option('wpsc_en_from_email',$from_email);

// Reply To
$reply_to = isset($_POST) && isset($_POST['wpsc_en_reply_to']) ? sanitize_email($_POST['wpsc_en_reply_to']) : '';
update_option('wpsc_en_reply_to',$reply_to);

$ignore_list = isset($_POST) && isset($_POST['wpsc_en_ignore_emails']) && strlen($_POST['wpsc_en_ignore_emails']) ? sanitize_textarea_field($_POST['wpsc_en_ignore_emails']) : '';
$ignore_emails = $ignore_list ? explode("\n", $ignore_list) : array();

$ignore_emails = $wpscfunction->sanitize_array($ignore_emails);
update_option('wpsc_en_ignore_emails',$ignore_emails);


// Mail send count for every cron
$wpsc_en_send_mail_count = isset($_POST) && isset($_POST['wpsc_en_send_mail_count']) ? sanitize_text_field($_POST['wpsc_en_send_mail_count']) : '';
update_option('wpsc_en_send_mail_count',$wpsc_en_send_mail_count);

// Send email as per admin requirment
$wpsc_email_sending_method = isset($_POST) && isset($_POST['wpsc_email_sending_method']) ? sanitize_text_field($_POST['wpsc_email_sending_method']) : '';
update_option('wpsc_email_sending_method',$wpsc_email_sending_method);

do_action('wpsc_set_en_gerneral_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings saved.','supportcandy').'" }';
