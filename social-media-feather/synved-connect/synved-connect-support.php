<?php
/**
 * Connect support.
 *
 * @package SocialMediaFeather
 */

/**
 * Social follow render.
 *
 * @return string
 */
function synved_connect_support_social_follow_render() {
	$uri = synved_connect_path_uri();

	$out = null;

	$out .= '<div class="synved-connect-notice" style="z-index: 999; position:fixed; right:30px; bottom:35px; width:220px; background:#ececec; font-size:110%; color:#444;">';

	if ( true === empty( get_option( 'smf-hide-review' ) ) ) {
		$out .= '<div class="smf-review-us">';
		$out .= '<h3>';
		$out .= esc_html__( 'Love this plugin?', 'synved-connect' );
		$out .= '<p>';
		$out .= '<a href="https://wordpress.org/support/plugin/social-media-feather/reviews/#new-post" target="_blank">';
		$out .= esc_html__(
			'Please spread the word by leaving us a 5 star review!',
			'synved-connect'
		);
		$out .= '</a>';
		$out .= '</p>';
		$out .= '<div id="close-review-us">close</div>';
		$out .= '</h3>';
		$out .= '</div>';
	}

	$out .= '</div>';

	return $out;
}
