<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (
	check_ajax_referer('set_add_customer_list_field', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

global $current_user, $wpdb, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$field_id = isset($_POST) && isset($_POST['field_id']) ? intval($_POST['field_id']) : 0;
if (!$field_id) {exit;}

$old_status = get_term_meta( $field_id, 'wpsc_customer_ticket_list_status', true);

if ($old_status) {
  echo '{ "sucess_status":"0","messege":"'.__('Already available in list.','supportcandy').'" }';
} else {
  $load_order = $wpdb->get_var("select max(meta_value) as load_order from {$wpdb->prefix}termmeta WHERE meta_key='wpsc_tl_customer_load_order'");
  update_term_meta ($field_id, 'wpsc_customer_ticket_list_status', '1');
  update_term_meta ($field_id, 'wpsc_tl_customer_load_order', ++$load_order);
  echo '{ "sucess_status":"1","messege":"'.__('Added successfully.','supportcandy').'" }';
}
