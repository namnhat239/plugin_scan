<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// validate name
$value = stripslashes($post_data['form_name']);
if ( strlen($value)<2 ) {
	$error_class['form_name'] = true;
	$error = true;
}
$form_data['form_name'] = $value;

// validate email
$value = $post_data['form_email'];
if ( empty($value) ) {
	$error_class['form_email'] = true;
	$error = true;
}
$form_data['form_email'] = $value;

// validate subject
if ($subject_setting != 'yes') {
	$value = stripslashes($post_data['form_subject']);
	if ( strlen($value)<2 ) {
		$error_class['form_subject'] = true;
		$error = true;
	}
	$form_data['form_subject'] = $value;
}

// validate message
$value = stripslashes($post_data['form_message']);
if ( strlen($value)<10 ) {
	$error_class['form_message'] = true;
	$error = true;
}
$form_data['form_message'] = $value;

// validate first honeypot field
$value = stripslashes($post_data['form_firstname']);
if ( strlen($value)>0 ) {
	$error = true;
}
$form_data['form_firstname'] = $value;

// validate second honeypot field
$value = stripslashes($post_data['form_lastname']);
if ( strlen($value)>0 ) {
	$error = true;
}
$form_data['form_lastname'] = $value;

// validate time token
$value = base64_decode( stripslashes($post_data['form_token']) );
$minimum = 3;
if ( is_numeric($value) && (time() - $value < $minimum) ) {
	$error = true;
}
$form_data['form_token'] = $value;

// validate privacy
if ($privacy_setting == 'yes') {
	$value = $post_data['form_privacy'];
	if ( $value !=  'yes' ) {
		$error_class['form_privacy'] = true;
		$error = true;
	}
	$form_data['form_privacy'] = $value;
}
