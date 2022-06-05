<?php

/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
$ualDeleteData = get_option( 'ualDeleteData', 0 );

if ( $ualDeleteData == 1 ) {
	delete_option( 'ual_promo_time' );
	delete_option( 'ual_is_optin' );
	delete_option( 'ual_version' );
	delete_option( 'enable_user_list' );
	delete_option( 'enable_role_list_temp' );
	delete_option( 'enable_role_list' );
	delete_option( 'enable_email' );
	delete_option( 'to_email' );
	delete_option( 'from_email' );
	delete_option( 'email_message' );
	delete_option( 'ualpAllowIp' );
	delete_option( 'ualpKeepLogsDay' );
	// delete database table
	global $wpdb;
	$table_name = $wpdb->prefix . 'ualp_user_activity';
	$wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
	delete_option( 'ualDeleteData' );
}

