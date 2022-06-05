<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpdb, $wpsupportplus, $current_user,$wpscfunction;

$ticket_id  = isset($_POST['ticket_id']) ? sanitize_text_field($_POST['ticket_id']) : '' ;
$wpsc_appearance_modal_window = get_option('wpsc_modal_window');
$ticket_subject = $wpscfunction->get_ticket_fields($ticket_id,'ticket_subject');
$term = get_term_by('slug','ticket_subject','wpsc_ticket_custom_fields');
$limit = get_term_meta($term->term_id,'wpsc_tf_limit',true);

ob_start();
?>

<form id="frm_edit_subject" method="post">
  
    <div class="form-group wpsc_edit_subject_div">
  	   <input type="text" id="subject" class="form-control" name="subject" value="<?php echo esc_attr($ticket_subject) ?>" onkeypress="wpsc_text_limit(event,this,<?php echo esc_attr($limit) ?>);"/>
  	</div>

    <input type="hidden" name="action" value="wpsc_tickets" />
  	<input type="hidden" name="setting_action" value="set_edit_ticket_subject" />
    <input type="hidden" id="wpsc_post_id" name="ticket_id" value="<?php echo esc_attr($ticket_id)?>" />
    <input type="hidden" name="_ajax_nonce" value="<?php echo wp_create_nonce('set_edit_ticket_subject')?>">
</form>

<?php
$body = ob_get_clean();

ob_start();
?>
<button type="button" class="btn wpsc_popup_close"  style="background-color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_close_button_bg_color'])?> !important;color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_close_button_text_color'])?> !important;"     onclick="wpsc_modal_close();"><?php _e('Close','supportcandy');?></button>
<button type="button" class="btn wpsc_popup_action" style="background-color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_action_button_bg_color'])?> !important;color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_action_button_text_color'])?> !important;"  onclick="wpsc_set_edit_ticket_subject(<?php echo esc_attr($ticket_id)?>);"><?php _e('Save','supportcandy');?></button>
<?php
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

wp_send_json($output);