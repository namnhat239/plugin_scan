<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('wpsc_agent'))) {
	exit;
}
$ticket_id   = isset($_POST['ticket_id']) ? sanitize_text_field($_POST['ticket_id']) : '' ;
$wpsc_appearance_modal_window = get_option('wpsc_modal_window');

ob_start();
$assigned_agents = $wpscfunction->get_ticket_meta($ticket_id,'assigned_agent');
?>
<form id="frm_get_ticket_assign_agent">
	<div id="assigned_agent">
		<div class="form-group wpsc_display_assign_agent ">
		    <input class="form-control  wpsc_assign_agents ui-autocomplete-input" name="assigned_agent"  type="text" autocomplete="off" placeholder="<?php _e('Search agent ...','supportcandy')?>" />
				<ui class="wpsp_filter_display_container"></ui>
		</div>
	</div>
	<div id="assigned_agents" class="form-group col-md-12">
		<?php
		   foreach ( $assigned_agents as $agent ) {
				 $agent_name = get_term_meta( $agent, 'label', true);
				 	
					if($agent && $agent_name):
		 ?>
							<div class="form-group wpsp_filter_display_element wpsc_assign_agents ">
								<div class="flex-container" style="padding:10px;font-size:1.0em;">
									<?php echo esc_attr($agent_name)?><span onclick="wpsc_remove_filter(this);"><i class="fa fa-times"></i></span>
									  <input type="hidden" name="assigned_agent[]" value="<?php echo esc_attr($agent) ?>" />
								</div>
							</div>
		<?php
				endif;
			 }
		?>
  </div>
		<input type="hidden" name="action" value="wpsc_tickets" />
		<input type="hidden" name="setting_action" value="set_change_assign_agent" />
		<input type="hidden" name="ticket_id" value="<?php echo esc_attr($ticket_id) ?>" />
		<input type="hidden" name="_ajax_nonce" value="<?php echo wp_create_nonce('set_change_assign_agent')?>">
</form>

<script>
jQuery(document).ready(function(){
	
	jQuery("input[name='assigned_agent']").keypress(function(e) {
		//Enter key
		if (e.which == 13) {
			return false;
		}
	});
	
	jQuery( ".wpsc_assign_agents" ).autocomplete({
			minLength: 0,
			appendTo: jQuery('.wpsc_assign_agents').parent(),
			source: function( request, response ) {
				var term = request.term;
				request = {
					action: 'wpsc_tickets',
					setting_action : 'filter_autocomplete',
					term : term,
					field : 'assigned_agent',
				}
				jQuery.getJSON( wpsc_admin.ajax_url, request, function( data, status, xhr ) {
					response(data);
				});
			},
			select: function (event, ui) {
				var html_str = '<li class="wpsp_filter_display_element">'
												+'<div class="flex-container">'
													+'<div class="wpsp_filter_display_text">'
														+ui.item.label
														+'<input type="hidden" name="assigned_agent[]" value="'+ui.item.flag_val+'">'
													+'</div>'
													+'<div class="wpsp_filter_display_remove" onclick="wpsc_remove_filter(this);"><i class="fa fa-times"></i></div>'
												+'</div>'
											+'</li>';
				jQuery('#assigned_agent .wpsp_filter_display_container').append(html_str);
			  jQuery(this).val(''); return false;
			}
	}).focus(function() {
			jQuery(this).autocomplete("search", "");
	});

});
</script>

<?php

$body = ob_get_clean();

ob_start();

?>
<button type="button" class="btn wpsc_popup_close"  style="background-color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_close_button_bg_color'])?> !important;color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_close_button_text_color'])?> !important;"    onclick="wpsc_modal_close();"><?php _e('Close','supportcandy');?></button>
<button type="button" class="btn wpsc_popup_action" style="background-color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_action_button_bg_color'])?> !important;color:<?php echo esc_attr($wpsc_appearance_modal_window['wpsc_action_button_text_color'])?> !important;" onclick="wpsc_set_change_assign_agent(<?php echo esc_attr($ticket_id) ?>);"><?php _e('Save','supportcandy');?></button>

<?php
$footer = ob_get_clean();

$output = array(
    'body'      => $body,
    'footer'    => $footer
);

wp_send_json($output);
