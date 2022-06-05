<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpdb, $current_user, $wpscfunction;

if (!($current_user->ID && $current_user->has_cap('wpsc_agent'))) {
		exit;
}

if (
	check_ajax_referer('set_delete_ticket_permanently', '_ajax_nonce', false) != 1
) wp_send_json_error('Unauthorised request!', 400);

$ticket_id  = isset($_POST['ticket_id']) ? sanitize_text_field($_POST['ticket_id']) : '' ;

$wpdb->delete($wpdb->prefix.'wpsc_ticket', array( 'id' => $ticket_id));
$wpdb->delete($wpdb->prefix.'wpsc_ticketmeta', array('ticket_id' => $ticket_id));

$args = array(
	'post_type'      => 'wpsc_ticket_thread',
	'post_status'    => array('publish','trash'),
	'posts_per_page' => -1,
	'meta_query'     => array(
		 array(
			'key'     => 'ticket_id',
			'value'   => $ticket_id,
			'compare' => '='
		),
	),
);
$ticket_threads = get_posts($args);
if($ticket_threads) {
	foreach ($ticket_threads as $ticket_thread ) {
		
		$attachments  = get_post_meta( $ticket_thread->ID, 'attachments', true);
		foreach( $attachments as $attachment ){
			$attach      = array();
			$attach_meta = get_term_meta($attachment);
			foreach ($attach_meta as $key => $value) {
				$attach[$key] = $value[0];
			}

			$upload_dir   = wp_upload_dir();
			$wpsp_file = get_term_meta($attachment,'wpsp_file');

			if ($wpsp_file) {
				$filepath = $upload_dir['basedir']  . '/wpsp/'. $attach['save_file_name'];
			}else {
				if( isset($attach['is_restructured']) && $attach['is_restructured']){
				$updated_time   = get_term_meta($attachment,'time_uploaded',true);
				$time  = strtotime($updated_time);
				$month = date("m",$time);
				$year  = date("Y",$time);
				$filepath = $upload_dir['basedir'] . '/wpsc/'.$year.'/'.$month.'/'. $attach['save_file_name'];
				}else{
				$filepath = $upload_dir['basedir'] . '/wpsc/'. $attach['save_file_name'];
				}
			}
			if(file_exists($filepath)){
				unlink($filepath);
			}
		}

		wp_delete_post($ticket_thread->ID,true);
	}
}


