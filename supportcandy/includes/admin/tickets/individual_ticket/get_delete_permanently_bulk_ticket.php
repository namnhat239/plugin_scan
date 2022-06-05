<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user;

$ticket_id = isset($_POST['ticket_id']) ? sanitize_text_field($_POST['ticket_id']) : '';

ob_start();

?>
<form id="frm_bulk_delete_ticket">
    <div class="form-group">
        <p><?php _e('Are you sure to delete these tickets permanently?','supportcandy');?></p>
    </div>
    
    <input type="hidden" name="action" value="wpsc_tickets" />
    <input type="hidden" name="setting_action" value="set_delete_permanently_bulk_ticket" />
    <input type="hidden" name="_ajax_nonce" value="<?php echo wp_create_nonce('set_delete_permanently_bulk_ticket_'.esc_html($ticket_id))?>">
    <input type="hidden" name="ticket_id" value="<?php echo esc_attr($ticket_id) ?>" />
</form>

<?php

$body = ob_get_clean();

ob_start();

?>
<button type="button" class="btn wpsc_popup_close" onclick="wpsc_modal_close();"><?php _e('Cancel','supportcandy');?></button>
<button type="button" class="btn wpsc_popup_action" onclick="wpsc_set_delete_permanently_bulk_ticket();"><?php _e('Confirm','supportcandy');?></button>
<?php

$footer = ob_get_clean();

$response = array(
    'body'      => $body,
    'footer'    => $footer
);

echo json_encode($response);