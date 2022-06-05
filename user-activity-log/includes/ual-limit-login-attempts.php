<?php
/*
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Logger for the (old but still) very popular plugin Limit Login Attempts
 * https://wordpress.org/plugins/limit-login-attempts/
 */




/**
 * Fired when plugin options screen is loaded
 */
if( !function_exists( 'ual_load_settings_page' ) ){
    function ual_load_settings_page($a) {

        if ($_POST && wp_verify_nonce($_POST['_wpnonce'], 'limit-login-attempts-options')) {
    
            $action = "Updated Options";
            $obj_type = "Settings";
            $post_id = "";
            
            if (isset($_POST['clear_log'])) {
                $post_title = "Limit Login Attempts : Cleared IP log";
                ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
            }
            if (isset($_POST['reset_total'])) {
                $post_title = "Limit Login Attempts : Reseted lockout count";
                ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
            }
            if (isset($_POST['reset_current'])) {
                $post_title = "Limit Login Attempts : Cleared current lockouts";
                ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
            }
            if (isset($_POST['update_options'])) {
                $post_title = "Limit Login Attempts : Option Settings Updated";
                ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
            }
        }
    }
}
add_action('load-settings_page_limit-login-attempts', 'ual_load_settings_page', 10, 1);

/**
 * When option value is updated
 * do same checks as plugin itself does
 * and log if we match something
 */
if( !function_exists( 'ual_option_limit_login_lockouts_total' ) ){

    function ual_option_limit_login_lockouts_total($value) {
        global $limit_login_just_lockedout;
    
        if (! $limit_login_just_lockedout) {
            return $value;
        }
    
        $ip = limit_login_get_address();
        $whitelisted = is_limit_login_ip_whitelisted($ip);
    
        $retries = get_option('limit_login_retries');
        if (! is_array($retries)) {
            $retries = array();
        }
        $lockout_type = '';
        if (! isset($retries[ $ip ])) {
            /* longer lockout */
            $lockout_type = 'longer';
            $count = limit_login_option('allowed_retries') * limit_login_option('allowed_lockouts');
            $lockouts = limit_login_option('allowed_lockouts');
            $time = round(limit_login_option('long_duration') / 3600);
        } else {
            /* normal lockout */
            $lockout_type = 'normal';
            $count = $retries[ $ip ];
            $lockouts = floor($count / limit_login_option('allowed_retries'));
            $time = round(limit_login_option('lockout_duration') / 60);
        }
        if ($whitelisted) {
            $post_title = " Limit Login Attempts : Failed login attempt from whitelisted IP";
        } else {
            
            $post_title = " Limit Login Attempts : Was locked out because too many failed login attempts";
        }
    
        $action = "Updated Options";
        $obj_type = "Settings";
        $post_id = "";
        
    $uactid =  ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
    }
}

add_filter('pre_option_limit_login_lockouts_total', 'ual_option_limit_login_lockouts_total' , 10, 1);
