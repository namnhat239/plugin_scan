<?php
/**
 * Shortcode List Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Ajax;

/**
 * Shortcode List Ajax Class.
 */
class Shortcode {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_teamShortcodeList', array( $this, 'response' ) );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$html = null;
		$scQ  = new \WP_Query(
			array(
				'post_type'      => rttlp_team()->shortCodePT,
				'order_by'       => 'title',
				'order'          => 'DESC',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			)
		);
		if ( $scQ->have_posts() ) {

			$html .= "<div class='mce-container mce-form'>";
			$html .= "<div class='mce-container-body'>";
			$html .= '<label class="mce-widget mce-label" style="padding: 20px;font-weight: bold;" for="scid">' . __( 'Select Shortcode', 'tlp-team' ) . '</label>';
			$html .= "<select name='id' id='scid' style='width: 150px;margin: 15px;'>";
			$html .= "<option value=''>" . __( 'Default', 'tlp-team' ) . '</option>';
			while ( $scQ->have_posts() ) {
				$scQ->the_post();
				$html .= "<option value='" . get_the_ID() . "'>" . get_the_title() . '</option>';
			}
				wp_reset_postdata();
			$html .= '</select>';
			$html .= '</div>';
			$html .= '</div>';
		} else {
			$html .= '<div>' . __( 'No shortCode found.', 'tlp-team' ) . '</div>';
		}
		echo $html;
		die();
	}
}
