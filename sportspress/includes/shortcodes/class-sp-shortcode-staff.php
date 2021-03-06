<?php
/**
 * Staff Shortcode
 *
 * @author      ThemeBoy
 * @category    Shortcodes
 * @package     SportsPress/Shortcodes/Staff
 * @version   2.5.5
 */
class SP_Shortcode_Staff {

	/**
	 * Output the staff shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) ) {
			$atts['id'] = $atts[0];
		}

		sp_get_template( 'staff-photo.php', $atts );
		sp_get_template( 'staff-details.php', $atts );
	}
}
