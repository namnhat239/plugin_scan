<?php
/**
 *  Adds admin notices
 *  - Review notice
 *  - License is not active notice (based on the version)
 *  - Welcome message
 *  - When plugin is updated to inform about new features
 *
 *  Loads on admin pages only
 *
 */

defined( 'ABSPATH' ) || die();

class AGS_Divi_Icons_Notices {

	/**
	 * Display review notice after number of days.
	 *
	 * @used in notice_admin_conditions()
	 */

	const NOTICE_DAYS = 14;

	public static function setup() {

		if ( self::notice_admin_review_conditions() ) {
			add_action( 'wp_ajax_ds-icon-expansion_notice_hide', [
				'AGS_Divi_Icons_Notices',
				'notice_admin_review_hide'
			] );
			add_action( 'admin_notices', [ 'AGS_Divi_Icons_Notices', 'notice_admin_review_content' ] );
			add_action( 'admin_enqueue_scripts', [ 'AGS_Divi_Icons_Notices', 'admin_scripts' ], 11 );
		}

		


		
	}

	/**
	 * Enqueue scripts for all admin pages.
	 * Called in setup()
	 *
	 * @since 1.0.0
	 *
	 */
	public static function admin_scripts() {
		wp_enqueue_script( 'wadip-notices-admin', plugin_dir_url( __FILE__ ) . 'js/admin.min.js', [ 'jquery' ], AGS_Divi_Icons::PLUGIN_VERSION, true );
	}

	/**
	 * Review Notice:
	 * Conditions based on which notice is displayed
	 */
	public static function notice_admin_review_conditions() {
		return get_option( 'ds-icon-expansion_notice_hidden' ) != 1 && time() - get_option( 'ds-icon-expansion_first_activate' ) >= ( self::NOTICE_DAYS * 86400 );
	}

	

	/**
	 * Review Notice:
	 * Content of the notice
	 */
	public static function notice_admin_review_content() {
		
		$link = 'https://www.elegantthemes.com/marketplace/wp-and-divi-icons-pro/';
		
		
		

		// translators: 1 is the plugin name, 2 and 3 are <a> tags
		$message = sprintf( esc_html__( 'Do you love using %1$s plugin? Please support us by %2$swriting a review%3$s.', 'ds-icon-expansion' ),
			'<strong>' . esc_html( AGS_Divi_Icons::PLUGIN_NAME ) . '</strong>',
			'<a href="' . esc_html( $link ) . '" target="_blank">', '</a>'
		);

		printf( '<div id="%1$s" class="updated notice is-dismissible"><p>%2$s</p></div>',
			esc_attr( AGS_Divi_Icons::PLUGIN_SLUG ) . '-notice',
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$message
		);
	}

	/**
	 * Review Notice:
	 * Triggered on dismiss notice button click
	 */
	public static function notice_admin_review_hide() {
		update_option( 'ds-icon-expansion_notice_hidden', 1 );
	}

	


	

}

AGS_Divi_Icons_Notices::setup();