<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$key = isset($_POST) && isset($_POST['key']) ? sanitize_text_field($_POST['key']) : 0;
if (!$key) {exit;}

$options = $wpscfunction->get_condition_options($key);

foreach ( $options as $option ) :
  ?>
  <option value="<?php echo esc_attr($option['value'])?>"><?php echo esc_attr($option['label'])?></option>
  <?php 
endforeach;
