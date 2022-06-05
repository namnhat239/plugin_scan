<?php
if ( ! function_exists( 'quality_companion_customize_register' ) ) :
	/**
	 * Quality Companion Customize Register
	 */
	 
	function quality_companion_customize_register( $wp_customize ) {
		$quality_features_content_control = $wp_customize->get_setting( 'quality_pro_options[quality_service_content]' );
		if ( ! empty( $quality_features_content_control ) ) {
			$quality_features_content_control->default = quality_get_service_default();
		}
	}

	add_action( 'customize_register', 'quality_companion_customize_register' );
endif;
