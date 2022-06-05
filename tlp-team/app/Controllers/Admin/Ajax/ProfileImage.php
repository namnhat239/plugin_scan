<?php
/**
 * Profile Image Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Ajax;

use RT\Team\Helpers\Fns;

/**
 * Profile Image Ajax Class.
 */
class ProfileImage {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_tlp_team_profile_img_remove', array( $this, 'response' ) );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$error = true;
		$msg   = null;

		if ( $_REQUEST['id'] && $_REQUEST['post_ID'] && Fns::verifyNonce() ) {
			if ( delete_post_meta( $_REQUEST['post_ID'], 'tlp_team_gallery', $_REQUEST['id'] ) ) {
				$error = false;
				$msg   = 'successfully deleted';
			} else {
				$msg = 'Error!!';
			}
		}

		wp_send_json(
			array(
				'error' => $error,
				'msg'   => $msg,
			)
		);
	}
}
