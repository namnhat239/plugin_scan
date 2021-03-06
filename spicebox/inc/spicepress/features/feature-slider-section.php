<?php if ( ! function_exists( 'spiceb_spicepress_slider_customize_register' ) ) :
function spiceb_spicepress_slider_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

/* Slider Section */
	$wp_customize->add_section( 'slider_section' , array(
		'title'      => __('Slider settings', 'spicepress'),
		'panel'  => 'section_settings',
		'priority'   => 1,
   	) );
		
		// Enable slider
		$wp_customize->add_setting( 'home_page_slider_enabled' , array( 'default' => 'on') );
		$wp_customize->add_control(	'home_page_slider_enabled' , array(
				'label'    => __( 'Enable slider', 'spicepress' ),
				'section'  => 'slider_section',
				'type'     => 'radio',
				'choices' => array(
					'on'=>__('ON', 'spicepress'),
					'off'=>__('OFF', 'spicepress')
				)
		));
		
		
		//Slider Image
		$wp_customize->add_setting( 'home_slider_image',array('default' => SPICEB_PLUGIN_URL .'inc/spicepress/images/slider/slider.jpg',
		'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'home_slider_image',
				array(
					'type'        => 'upload',
					'label' => __('Image','spicepress'),
					'settings' =>'home_slider_image',
					'section' => 'slider_section',
					
				)
			)
		);
		
		
		// Slider title
		$wp_customize->add_setting( 'home_slider_title',array(
		'default' => __('Welcome to SpicePress Theme','spicepress'),
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_slider_title',array(
		'label'   => __('Title','spicepress'),
		'section' => 'slider_section',
		'type' => 'text',
		));	
		
		//Slider discription
		$wp_customize->add_setting( 'home_slider_discription',array(
		'default' => 'Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim mei ludus efficiendi ei sea summo mazim ex.',
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_slider_discription',array(
		'label'   => __('Description','spicepress'),
		'section' => 'slider_section',
		'type' => 'textarea',
		));
		
		
		// Slider button text
		$wp_customize->add_setting( 'home_slider_btn_txt',array(
		'default' => __('Read more','spicepress'),
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_slider_btn_txt',array(
		'label'   => __('Button Text','spicepress'),
		'section' => 'slider_section',
		'type' => 'text',
		));
		
		// Slider button link
		$wp_customize->add_setting( 'home_slider_btn_link',array(
		'default' => __('#','spicepress'),
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_slider_btn_link',array(
		'label'   => __('Button Link','spicepress'),
		'section' => 'slider_section',
		'type' => 'text',
		));
		
		// Slider button target
		$wp_customize->add_setting(
		'home_slider_btn_target', 
			array(
			'default'        => false,
			'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		));
		$wp_customize->add_control('home_slider_btn_target', array(
			'label'   => __('Open link in new tab', 'spicepress'),
			'section' => 'slider_section',
			'type' => 'checkbox',
		));
		
		
		
}

add_action( 'customize_register', 'spiceb_spicepress_slider_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_spicepress_register_home_slider_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'home_slider_image', array(
		'selector'            => '.slider .item',
		'settings'            => 'home_slider_image',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'home_slider_title', array(
		'selector'            => '.format-standard .slide-text-bg1 h1',
		'settings'            => 'home_slider_title',
		'render_callback'  => 'spiceb_spicepress_slider_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_slider_discription', array(
		'selector'            => '.format-standard .slide-text-bg1 p',
		'settings'            => 'home_slider_discription',
		'render_callback'  => 'spiceb_spicepress_slider_section_discription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_slider_btn_txt', array(
		'selector'            => '.slide-btn-sm',
		'settings'            => 'home_slider_btn_txt',
		'render_callback'  => 'spiceb_spicepress_slider_btn_render_callback',
	
	) );
}

add_action( 'customize_register', 'spiceb_spicepress_register_home_slider_section_partials' );


function spiceb_spicepress_slider_section_title_render_callback() {
	return get_theme_mod( 'home_slider_title' );
}

function spiceb_spicepress_slider_section_discription_render_callback() {
	return get_theme_mod( 'home_slider_discription' );
}

function spiceb_spicepress_slider_btn_render_callback() {
	return get_theme_mod( 'home_slider_btn_txt' );
}

function spiceb_spicepress_home_page_sanitize_text( $input ) {

		return wp_kses_post( force_balance_tags( $input ) );

}