<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpscfunction, $current_user;

if (
	check_ajax_referer('set_add_ticket_users', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

if (!($current_user->ID)) {exit;}

$ticket_id = isset($_POST['ticket_id']) ? sanitize_text_field($_POST['ticket_id']) : '' ;
if(!$ticket_id) die();

$extr_users = isset($_POST) && isset($_POST['wpsc_ticket_et_user']) ? sanitize_textarea_field($_POST['wpsc_ticket_et_user']) : '';
$extra_users = $extr_users ? explode("\n", $extr_users) : array();

$extra_users = $wpscfunction->sanitize_array($extra_users);

$old_extra_ticket_users = $wpscfunction->get_ticket_meta($ticket_id,'extra_ticket_users');

$extra_user = array();

foreach( $extra_users as $users => $value ){
	if ($value){
    $extra_user[] = $value;
	}
}

$extra_ticket_users = array_unique($extra_user);

if( ($old_extra_ticket_users != $extra_ticket_users)){
    $wpscfunction->add_extra_users( $ticket_id, $extra_ticket_users);
}	

do_action('wpsc_set_add_ticket_users',$ticket_id);