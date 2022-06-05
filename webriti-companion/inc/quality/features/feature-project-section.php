<?php
function quality_project_customizer( $wp_customize ) {

//Home project Section
	$wp_customize->add_panel( 'quality_project_setting', array(
		'priority'       => 700,
		'capability'     => 'edit_theme_options',
		'title'      => __('Project settings', 'webriti-companion'),
	) );
	
	$wp_customize->add_section(
        'project_section_settings',
        array(
            'title' => __('Project settings','webriti-companion'),
			'panel'  => 'quality_project_setting',)
    );
	
	
	//Show and hide Project section
	$wp_customize->add_setting(
	'quality_pro_options[home_projects_enabled]'
    ,
    array(
        'default' => true,
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'option',
    )	
	);
	$wp_customize->add_control(
    'quality_pro_options[home_projects_enabled]',
    array(
        'label' => __('Enable Home Project section','webriti-companion'),
        'section' => 'project_section_settings',
        'type' => 'checkbox',
    )
	);
	
	// //Project Title
	$wp_customize->add_setting(
    'quality_pro_options[project_heading_one]',
    array(
        'default' => __('Featured portfolio project','webriti-companion'),
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'option',
		)
	);	
	$wp_customize->add_control('quality_pro_options[project_heading_one]',array(
    'label'   => __('Title','webriti-companion'),
    'section' => 'project_section_settings',
	 'type' => 'text',)  );	
	 
	//Project Description 
	 $wp_customize->add_setting(
    'quality_pro_options[project_tagline]',
    array(
        'default' => 'aecenas sit amet tincidunt elit. Pellentesque habitant morbi tristique senectus et netus et Nulla facilisi.',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
		'type' => 'option',
		)
	);	
	$wp_customize->add_control( 'quality_pro_options[project_tagline]',array(
    'label'   => __('Description','webriti-companion'),
    'section' => 'project_section_settings',
	 'type' => 'text',)  );
	 
	 
	

	//link
	class WP_project_Customize_Control extends WP_Customize_Control {
    public $type = 'new_menu';
    /**
    * Render the control's content.
    */
    public function render_content() {
    ?>
    <a href="<?php bloginfo ( 'url' );?>/wp-admin/edit.php?post_type=quality_portfolio" class="button"  target="_blank"><?php _e( 'Click here to add project', 'webriti-companion' ); ?></a>
    <?php
    }
}
$wp_customize->add_setting(
    'project',
    array(
        'default' => '',
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
    )	
);
$wp_customize->add_control( new WP_project_Customize_Control( $wp_customize, 'project', array(	
		'section' => 'project_section_settings',
    ))
);
}		
	add_action( 'customize_register', 'quality_project_customizer' );
	?>