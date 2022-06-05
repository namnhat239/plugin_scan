<?php
/**
 * Frontend Custom CSS Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Frontend;

/**
 * Frontend Custom CSS Class.
 */
class CustomCSS {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_head', array( $this, 'custom_css' ) );
	}

	/**
	 * Custom CSS.
	 *
	 * @return void
	 */
	public function custom_css() {
		$settings = get_option( rttlp_team()->options['settings'] );
		$output   = null;

		if ( ! empty( $settings['custom_css'] ) ) {
			$output .= "<style>{$settings['custom_css']}</style>";
		}

		echo $output;
	}
}
