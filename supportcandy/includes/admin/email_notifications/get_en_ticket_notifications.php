<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

$notification_types = $wpscfunction->get_email_notification_types();

$email_templates = get_terms([
	'taxonomy'   => 'wpsc_en',
	'hide_empty' => false,
	'orderby'    => 'ID',
	'order'      => 'ASC',
]);

?>
<h4>
	<?php _e('Ticket Notifications','supportcandy');?>
	<button style="margin-left:10px;" class="btn btn-success btn-sm" onclick="wpsc_get_add_ticket_notification();"><?php _e('+Add New','supportcandy');?></button>
</h4>

<div class="wpsc_padding_space"></div>

<table class="table table-striped table-hover">
  <tr>
    <th><?php _e('Title','supportcandy')?></th>
    <th><?php _e('Notification Type','supportcandy')?></th>
    <th><?php _e('Actions','supportcandy')?></th>
  </tr>
  <?php foreach ( $email_templates as $email_template ) :
    $title = $email_template->name;
    $type  = get_term_meta( $email_template->term_id, 'type', true);
    $type  = isset($notification_types[$type]) ? $notification_types[$type] : '';
    ?>
    <tr>
      <td><?php echo esc_attr($title)?></td>
      <td><?php echo esc_attr($type)?></td>
      <td>
        <div class="wpsc_flex">
					<div onclick="wpsc_get_edit_ticket_notification(<?php echo esc_attr($email_template->term_id);?>);" style="cursor:pointer;"><i class="fas fa-edit"></i></div>
          <div onclick="wpsc_clone_ticket_notification(<?php echo esc_attr($email_template->term_id);?>,'<?php echo wp_create_nonce('clone_ticket_notification')?>');" style="cursor:pointer; padding-left: 10px;"><i class="far fa-clone"></i></div>
					<div onclick="wpsc_delete_ticket_notification(<?php echo esc_attr($email_template->term_id);?>,'<?php echo wp_create_nonce('delete_ticket_notification')?>');" style="cursor:pointer; padding-left: 10px;"><i class="fas fa-trash"></i></div>
        </div>
      </td>
    </tr>
	<?php endforeach;?>
</table>