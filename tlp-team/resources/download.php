<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once $parse_uri[0] . 'wp-load.php';
if ( ! is_user_logged_in() ) {
	die( 'no access' );
}
$fileName = ! empty( $_REQUEST['file'] ) ? $_REQUEST['file'] : null;
if ( ! $fileName ) {
	exit();
}
$file = TLP_TEAM_DOWNLOAD_PATH . $fileName;
if ( file_exists( "$file" ) ) {
	// Set Headers:
	header( 'Pragma: public' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', filemtime( "$file" ) ) . ' GMT' );
	header( 'Content-Type: application/force-download' );
	header( "Content-Disposition: inline; filename=$fileName" );
	header( 'Content-Transfer-Encoding: binary' );
	header( 'Content-Length: ' . filesize( "$file" ) );
	header( 'Connection: close' );
	@readfile( "$file" );
	if ( file_exists( "$file" ) ) {
		unlink( "$file" );
	}
	exit();
}
