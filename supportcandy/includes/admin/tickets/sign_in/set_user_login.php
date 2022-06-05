<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check nonce
if( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce']) ){
    die(__('Cheating huh?', 'supportcandy'));
}

$username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
if (!$username) die();

$password = isset($_POST['password']) ? $_POST['password'] : '';
if (!$password) die();

$remember = isset($_POST['remember']) ? true : false;
$wpsc_captcha = get_option('wpsc_login_captcha',0);

if ($wpsc_captcha) {
	
    $wpsc_recaptcha_type = get_option('wpsc_recaptcha_type');

    if ($wpsc_recaptcha_type){
      
        $captcha_key =  isset($_COOKIE) && isset($_COOKIE['wpsc_secure_code']) ? intval($_COOKIE['wpsc_secure_code']) : 0;
        if(!isset($_POST['captcha_code']) || !wp_verify_nonce($_POST['captcha_code'],$captcha_key)){
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

$creds = array(
  'user_login'    => $username,
  'user_password' => $password,
  'remember'      => $remember,
);
$user = wp_signon( $creds, false );

$response = array();

if ( is_wp_error( $user ) ) {
  $response['error'] = '1';
  $response['message'] = $user->get_error_message();
} else {
  $response['error'] = '0';
  $response['message'] = __('Success!','supportcandy');
}

echo json_encode( $response );