<?php
/**
 * Settings Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Ajax;

use RT\Team\Helpers\Fns;

/**
 * Settings Ajax Class.
 */
class Settings {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_tlpTeamSettings', array( $this, 'response' ) );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$error = true;
		$msg   = '';
		if ( Fns::verifyNonce() ) {
			unset( $_REQUEST['action'] );
			unset( $_REQUEST['tlp_nonce'] );
			unset( $_REQUEST['_wp_http_referer'] );
			$_REQUEST['slug'] = isset( $_REQUEST['slug'] ) ? sanitize_title_with_dashes( $_REQUEST['slug'] ) : 'team';

			update_option( rttlp_team()->options['settings'], $_REQUEST );
			flush_rewrite_rules();
			$error = false;
			$msg   = esc_html__( 'Settings successfully updated', 'tlp-team' );
		} else {
			$msg = esc_html__( 'Security Error !!', 'tlp-team' );
		}
		wp_send_json( array(
			'error' => $error,
			'msg'   => $msg
		) );
	}
}
