<?php

if ( ! function_exists( 'quality_service_customize_register' ) ) :
function quality_service_customize_register($wp_customize){
	//Service section panel
$wp_customize->add_panel( 'quality_service_options', array(
		'priority'       => 600,
		'capability'     => 'edit_theme_options',
		'title'      => __('Service settings', 'webriti-companion'),
	) );
	$wp_customize->add_section(
				'service_settings', array(
					'title'    => __( 'Service', 'webriti-companion' ),
					'panel'    => 'quality_service_options',
					'priority' => 3,
				)
			);
			
			//Hide Index Service Section
			
			$wp_customize->add_setting(
			'quality_pro_options[service_enable]',
			array(
				'default' => true,
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'type' => 'option',
			)	
			);
			
			$wp_customize->add_control(
			'quality_pro_options[service_enable]',
			array(
				'label' => __('Enable Services on homepage.','webriti-companion'),
				'section' => 'service_settings',
				'type' => 'checkbox',
			)
			);
	
	$wp_customize->add_setting(
    'quality_pro_options[service_title]',
    array(
        'default' => __('Our nice services','webriti-companion'),
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'option',
    )	
	);
	$wp_customize->add_control(
    'quality_pro_options[service_title]',
    array(
        'label' => __('Title',''),
        'section' => 'service_settings',
        'type' => 'text',
    )
	);
	
	$wp_customize->add_setting(
    'quality_pro_options[service_description]',
    array(
        'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam scelerisque faucibus risus non iaculis.',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'option',
    )	
	);
	$wp_customize->add_control(
    'quality_pro_options[service_description]',
    array(
        'label' => __('Description',''),
        'section' => 'service_settings',
        'type' => 'text',
		'sanitize_callback' => 'sanitize_text_field',
    )
	);	
	
	if ( class_exists( 'Quality_Repeater' ) ) {
			$wp_customize->add_setting( 'quality_pro_options[quality_service_content]', array(
			'type'=> 'option',
			) );

			$wp_customize->add_control( new Quality_Repeater( $wp_customize, 'quality_pro_options[quality_service_content]', array(
				'label'                             => esc_html__( 'Service content', 'webriti-companion' ),
				'section'                           => 'service_settings',
				'add_field_label'                   => esc_html__( 'Add new service', 'webriti-companion' ),
				'item_name'                         => esc_html__( 'Service', 'webriti-companion' ),
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_link_control'  => true,
				'customizer_repeater_icon_control'  => true,
				) ) );
		}
}
add_action( 'customize_register', 'quality_service_customize_register' );
endif;
?>