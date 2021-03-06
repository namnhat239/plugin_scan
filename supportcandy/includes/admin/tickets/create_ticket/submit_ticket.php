<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wpscfunction;
$wpsc_captcha = get_option('wpsc_captcha',0);
$wpsc_guest_can_upload_files = get_option('wpsc_guest_can_upload_files');

if($wpsc_captcha){

	$wpsc_recaptcha_type = get_option('wpsc_recaptcha_type');

	if($wpsc_recaptcha_type){
		
		$captcha_key =  isset($_COOKIE) && isset($_COOKIE['wpsc_secure_code']) ? intval($_COOKIE['wpsc_secure_code']) : 0;
		if( !isset($_POST['captcha_code']) || !wp_verify_nonce($_POST['captcha_code'],$captcha_key) ){
		    die(__('Cheating huh?', 'supportcandy'));
		}

	} else {
		
		$captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
		if (!$captcha) die(__('Cheating huh?', 'supportcandy'));

		$secretKey = get_option('wpsc_get_secret_key');
		$ip = $_SERVER['REMOTE_ADDR'];

		$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
		$body = !is_wp_error($response) ? json_decode(wp_remote_retrieve_body($response)) : false;
		if (!$body || intval($body->success) !== 1) {
			die(__('Cheating huh?', 'supportcandy'));
		}
	}
	setcookie('wpsc_secure_code','123');
}

//check allow create ticket permission
$wpsc_allow_to_create_ticket = get_option('wpsc_allow_to_create_ticket');
$allow_create = false;
if(is_user_logged_in() && $current_user->has_cap('wpsc_agent')){ //agent
	$cu_role_id = get_user_option('wpsc_agent_role', $current_user->ID);
}elseif(is_user_logged_in() && !$current_user->has_cap('wpsc_agent')){ //customer
	$cu_role_id = 'customer';
}else{
	$customer_email = isset($_POST['customer_email']) ? sanitize_email($_POST['customer_email']) : '';
	$user = get_user_by('email', $customer_email);
	if($user){
		if($user->has_cap('wpsc_agent')){
			$cu_role_id = get_user_option('wpsc_agent_role', $user->ID);
		}else{
			$cu_role_id = 'customer';
		}
	}else{
		$cu_role_id = 'guest';
	}
}
if( !in_array($cu_role_id, $wpsc_allow_to_create_ticket)){
	die(__('Cheating huh? Not allowed to create ticket.', 'supportcandy'));
}


$args = array();

// Customer name
if(is_user_logged_in() && !$current_user->has_cap('wpsc_agent') ){
	$customer_name = $current_user->display_name;
}else{
	$customer_name = isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '';
}
$args['customer_name'] = $customer_name;

// Customer email
if(is_user_logged_in() && !$current_user->has_cap('wpsc_agent') ){
	$customer_email = $current_user->user_email;
	$args['customer_email']	= $customer_email;
}else{
	$customer_email = isset($_POST['customer_email']) ? sanitize_email($_POST['customer_email']) : '';
	$args['customer_email'] = $customer_email;
}

// Subject
$ticket_subject = isset($_POST['ticket_subject']) ? sanitize_text_field($_POST['ticket_subject']) : '';
if($ticket_subject) $args['ticket_subject'] = $ticket_subject;

// Description
$ticket_description = isset($_POST['ticket_description']) ? wp_kses_post(htmlspecialchars_decode($_POST['ticket_description'], ENT_QUOTES)) : '';
if($ticket_description) $args['ticket_description'] = $ticket_description;
if(is_user_logged_in() || $wpsc_guest_can_upload_files ){
	$description_attachment = isset($_POST['desc_attachment']) ? $wpscfunction->sanitize_array($_POST['desc_attachment']) : array();
	if($description_attachment) $args['desc_attachment'] = $description_attachment;
}

// Category
$ticket_category = isset($_POST['ticket_category']) ? intval($_POST['ticket_category']) : '';
if($ticket_category) $args['ticket_category'] = $ticket_category;

// Priority
$ticket_priority = isset($_POST['ticket_priority']) ? intval($_POST['ticket_priority']) : '';
if($ticket_priority) $args['ticket_priority'] = $ticket_priority;

// Custom fields
$fields = get_terms([
	'taxonomy'   => 'wpsc_ticket_custom_fields',
	'hide_empty' => false,
	'orderby'    => 'meta_value_num',
	'meta_key'	 => 'wpsc_tf_load_order',
	'order'    	 => 'ASC',
	'meta_query' => array(
		'relation' => 'AND',
		array(
      'key'       => 'agentonly',
      'value'     => '0',
      'compare'   => '='
    ),
		array(
      'key'       => 'wpsc_tf_type',
      'value'     => '0',
      'compare'   => '>'
    ),
	),
]);
foreach ($fields as $field) {
	$tf_type = get_term_meta( $field->term_id, 'wpsc_tf_type', true);
	switch ($tf_type) {
		case '1':	
		case '2':
		case '4':
		case '6':
		case '7':
		case '8':
		case '18':
			$text = isset($_POST[$field->slug]) ? sanitize_text_field($_POST[$field->slug]) : '';
			if($text) $args[$field->slug] = $text;
			break;
			
		case '3':
		case '10':
			$arrVal = isset($_POST[$field->slug]) ? $wpscfunction->sanitize_array($_POST[$field->slug]) : array();
			if($arrVal) $args[$field->slug] = $wpscfunction->sanitize_array($arrVal);
			break;
			
		case '5':
			$text = isset($_POST[$field->slug]) ? wp_kses_post(htmlspecialchars_decode($_POST[$field->slug], ENT_QUOTES)) : '';
			if($text) $args[$field->slug] = $text;
			break;
			
		case '9':
			$number = isset($_POST[$field->slug]) ? intval($_POST[$field->slug]) : '';
			if($number) $args[$field->slug] = $number;
			break;
		
		case '21':
			$text = isset($_POST[$field->slug]) ? sanitize_text_field($_POST[$field->slug]) : '';
			if($text) $args[$field->slug] = date("H:i:s " ,strtotime($text));
			break;

		default:	
			$args = apply_filters('wpsc_after_create_ticket_custom_field',$args,$field,$tf_type);
			break;		
	}
}

$args = apply_filters( 'wpsc_before_create_ticket_args', $args);

$ticket_id = $wpscfunction->create_ticket($args);
$thankyou_html = $wpscfunction->replace_macro(get_option('wpsc_thankyou_html'),$ticket_id);
$thankyou_html = apply_filters('wpsc_after_thankyou_page_button',$thankyou_html,$ticket_id);

ob_start();
?>
<div class="col-sm-12" style="margin-top:20px;">
	<?php echo wp_kses_post($thankyou_html)?>
</div>
<?php
$thankyou_html = ob_get_clean();

$response = array(
  'redirct_url'    => get_option('wpsc_thankyou_url'),
  'thank_you_page' => $thankyou_html,
);

echo json_encode($response);