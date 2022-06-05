<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user,$wpscfunction;
if (!($current_user->ID && $current_user->has_cap('wpsc_agent'))) {exit;}

$ticket_id 	 = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0 ;
$wpsc_appearance_modal_window = get_option('wpsc_modal_window');

$fields = get_terms([
	'taxonomy'   => 'wpsc_ticket_custom_fields',
	'hide_empty' => false,
	'orderby'    => 'meta_value_num',
	'meta_key'	 => 'wpsc_tf_load_order',
	'order'    	 => 'ASC',
	'meta_query' => array(
		array(
      'key'       => 'agentonly',
      'value'     => '1',
      'compare'   => '='
    )
	),
]);

include WPSC_ABSPATH . 'includes/admin/tickets/create_ticket/class-ticket-list-format.php';

$ticket_fields = new WPSC_Ticket_List();

ob_start();
?>
<form id="frm_get_agent_fields" method="post">
	<div id="wpsc_search_acf" class="row form-group col-md-4 col-xs-12" style="float:right;">	
		<input type="text" id="search_cf" class="form-control" placeholder="<?php _e('Search','supportcandy') ?>" name="search_cf" autocomplete="off">
	</div>
  	<div class="row" id="wpsc_edit_agent_fields" style="clear:both">
		<?php
					if($fields){
						foreach ($fields as $field) {
							  $ticket_fields->print_field($field);
						}
					}
					else{
						_e('No Agent fields','supportcandy');
					}
				?>
		</div>
	<input type="hidden" name="action" value="wpsc_tickets" />
	<input type="hidden" name="setting_action" value="set_change_agent_fields" />
	<input type="hidden" name="ticket_id" value="<?php echo esc_attr($ticket_id) ?>" />
	<input type="hidden" name="_ajax_nonce" value="<?php echo wp_create_nonce('set_change_ticket_fields')?>">
	<script>
	jQuery('#search_cf').keyup(function(e) {
		if (e.which == 13) return;
		var searchText = jQuery(this).val().trim();
		jQuery('#wpsc_edit_agent_fields > .wpsc_form_field').each(function(){
			var name = jQuery(this).find('label').text();
			var re = new RegExp(searchText, "i");
			var index = name.search(re);
			if (index < 0) {
				jQuery(this).hide();
			} else {
				jQuery(this).show();
			}
		})
	})
	</script>
</form>
<?php
$body = ob_get_clean();
ob_start();
?>
<button type="button" class="btn wpsc_popup_close"  style="background-color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_close_button_bg_color'])?> !important;color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_close_button_text_color'])?> !important;"    onclick="wpsc_modal_close();"><?php _e('Close','supportcandy');?></button>
<button type="button" class="btn wpsc_popup_action" style="background-color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_action_button_bg_color'])?> !important;color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_action_button_text_color'])?> !important;" onclick="wpsc_set_change_agent_fields(<?php echo esc_attr($ticket_id) ?>);"><?php _e('Save','supportcandy');?></button>
<?php
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

wp_send_json($output);

