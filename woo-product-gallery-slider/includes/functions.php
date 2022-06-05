<?php

if ( !function_exists( 'wpgs_get_option' ) ) {
	/**
	 * Get Setting option
	 *
	 * @param  [string] $option
	 * @param  [string] $section
	 * @param  string   $default
	 * @return void
	 */
	function wpgs_get_option( $option, $section = 'wpgs_form', $default = '' ) {
		$options = get_option( $section );

		if ( isset( $options[$option] ) ) {
			return $options[$option];
		}

		return $default;
	}
}

if ( !function_exists( 'cix_only_pro' ) ) {
	/**
	 * @param $value
	 */
	function cix_only_pro( $value ) {
		if ( $value == 'only_pro' || $value == 'ondemand' || $value == 'progressive' || $value == true || $value == false || $value == 'x' ) {
			return esc_html__( 'Available in PRO', 'wpgs-td' );
		}
	}
}

if ( !function_exists( 'cix_get_wp_image_sizes' ) ) {
	/**
	 * @param $value
	 */
	function cix_get_wp_image_sizes() {
		// Get the image sizes.
		global $_wp_additional_image_sizes;
		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ), true ) ) {

				$width  = get_option( "{$_size}_size_w" );
				$height = get_option( "{$_size}_size_h" );
				$crop   = (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[$_size] = ucfirst( "{$_size} - $crop:{$width}x{$height}" );

			} elseif ( isset( $_wp_additional_image_sizes[$_size] ) ) {

				$width  = $_wp_additional_image_sizes[$_size]['width'];
				$height = $_wp_additional_image_sizes[$_size]['height'];
				$crop   = $_wp_additional_image_sizes[$_size]['crop'] ? 'hard' : 'soft';

				$sizes[$_size] = ucfirst( "{$_size} - $crop:{$width}X{$height}" );
			}
		}
		return $sizes;
	}
}

