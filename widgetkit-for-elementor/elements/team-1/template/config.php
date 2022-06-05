<?php


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
Use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor WidgetKit Team 1
 *
 * Elementor widget for WidgetKit team 1
 *
 * @since 1.0.0
 */
class wkfe_team_1 extends Widget_Base {

	public function get_name() {
		return 'widgetkit-for-elementor-team-1';
	} 

	public function get_title() {
		return esc_html__( 'Team Overlay', 'widgetkit-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-person wk-icon';
	}

	public function get_categories() {
		return [ 'widgetkit_deprecated_elements' ];
	}

	/**
	 * A list of style that the widgets is depended in
	 **/
	public function get_style_depends() {
        return [
            'widgetkit_bs',
            'widgetkit_main',
        ];
    }
	/**
	 * A list of scripts that the widgets is depended in
	 **/
	public function get_script_depends() {
		return [ 
			'widgetkit-main',
		 ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Team Content', 'widgetkit-for-elementor' ),
			]
		);

		$this->add_control(
	       'team_image',
		        [
		           'label' => esc_html__( 'Upload Team Image', 'widgetkit-for-elementor' ),
		           'type'  => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
				  	],
		     ]
	    );



	    $this->add_control(
		    'team_name',
		      	[
		          'label' => esc_html__( 'Team Name', 'widgetkit-for-elementor' ),
		          'type'  => Controls_Manager::TEXTAREA,
		          'default' => esc_html__( 'Jonathan Morgan', 'widgetkit-for-elementor' ),
		    	]
	    );

	    $this->add_control(
            'image_external_link',
            [
                'label' => esc_html__( 'Link To', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::URL,
                'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://themesgrove.com', 'widgetkit-for-elementor' ),
				'default' => [
					'url' => 'https://themesgrove.com', 
				],
            ]
        );


	   	$this->add_control(
		    'designation',
		      	[
		          'label' => esc_html__( 'Team Designation', 'widgetkit-for-elementor' ),
		          'type'  => Controls_Manager::TEXTAREA,
		          'default' => esc_html__( 'Graphics Designer', 'widgetkit-for-elementor' ),
		      	]
		);

		$this->add_control(
		    'content',
		      	[
		          'label' => esc_html__( 'Team Description', 'widgetkit-for-elementor' ),
		          'type'  => Controls_Manager::TEXTAREA,
		          'default' => esc_html__( 'Corem ipsum dolor si amet consectetur adipisic ingelit sed do adipisicido executiv
						sunse pit lore kome.', 'widgetkit-for-elementor' ),
		      	]
		);

			$repeater = new Repeater();

			    $repeater->add_control(
			      'title',
			      [
			          'label'   => esc_html__( 'Social Name', 'widgetkit-for-elementor' ),
			          'type'    => Controls_Manager::TEXT,
			          'default' => esc_html__( 'Facebook', 'widgetkit-for-elementor' ),
			      ]
			    );


			$repeater->add_control(
		            'social_link',
		            [
		                'label' => esc_html__( 'Social Link', 'widgetkit-for-elementor' ),
		                'type'  => Controls_Manager::URL,
		                'dynamic' => [
							'active' => true,
						],
						'placeholder' => __( 'https://www.facebook.com/themesgrove', 'widgetkit-for-elementor' ),
						'default' => [
							'url' => 'https://www.facebook.com/themesgrove', 
						],
		            ]
		        );

		        $repeater->add_control(
		            'social_icon',
		            [
		                'label' => esc_html__( 'Social Icon', 'widgetkit-for-elementor' ),
		                'type'  => Controls_Manager::ICON,
		                'default' => 'fa fa-facebook',
		            ]
		        );


			$this->add_control(
			    'social_share_1',
			      [
			          'label'       => esc_html__( 'Social Share', 'widgetkit-for-elementor' ),
			          'type'        => Controls_Manager::REPEATER,
			          'show_label'  => true,
			          'default'     => [
			              [
			              	'title'       => esc_html__( 'Facebook', 'widgetkit-for-elementor' ),
			                'social_link' => esc_html__( 'https://www.facebook.com/themesgrove', 'widgetkit-for-elementor' ),
			                'social_icon' => 'fa fa-facebook',
			 
			              ],
			              [
			              	'title'       => esc_html__( 'Twitter', 'widgetkit-for-elementor' ),
			                'social_link' => esc_html__( 'https://www.twitter.com/themesgrove', 'widgetkit-for-elementor' ),
			                'social_icon' => 'fa fa-twitter',
			 
			              ],
			              [
			              	'title'       => esc_html__( 'Linkedin', 'widgetkit-for-elementor' ),
			                'social_link' => esc_html__( 'https://www.linkedin.com/themesgrove', 'widgetkit-for-elementor' ),
			                'social_icon' => 'fa fa-linkedin',
			 
			              ]
			          ],
			          'fields'      => $repeater->get_controls(),
			          'title_field' => '{{{title}}}',
			      ]
			  );

		$this->end_controls_section();

			
	/**
	 * Pro control panel 
	 */
	if(!apply_filters('wkpro_enabled', false)):
		$this->start_controls_section(
			'section_widgetkit_pro_box',
			[
				'label' => esc_html__( 'Go Premium for more layout & feature', 'widgetkit-for-elementor' ),
			]
		);
			$this->add_control(
				'wkfe_control_go_pro',
				[
					'label' => __('Unlock more possibilities', 'widgetkit-for-elementor'),
					'type' => Controls_Manager::CHOOSE,
					'default' => '1',
					'description' => '<div class="elementor-nerd-box">
					<div class="elementor-nerd-box-message"> Get the  <a href="https://themesgrove.com/widgetkit-for-elementor/" target="_blank">Pro version</a> of <a href="https://themesgrove.com/widgetkit-for-elementor/" target="_blank">WidgetKit</a> for more stunning elements and customization options.</div>
					<a class="widgetkit-go-pro elementor-nerd-box-link elementor-button elementor-button-default elementor-go-pro" href="https://themesgrove.com/widgetkit-for-elementor/" target="_blank">Go Pro</a>
					</div>',
				]
			);
		$this->end_controls_section();
	endif;

	


		$this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__( 'Title', 'widgetkit-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'team_align',
			[
				'label'     => esc_html__( 'Info Alignment', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => [
					'left'     => esc_html__( 'Left', 'widgetkit-for-elementor' ),
					'center'   => esc_html__( 'Center', 'widgetkit-for-elementor' ),
					'right'    => esc_html__( 'Right', 'widgetkit-for-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .tgx-team-1 .team-container .team-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'info_bg_color',
			[
				'label'     => esc_html__( 'Info Bg Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f1f1f1',
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-info' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_space',
			[
				'label' => esc_html__( 'Spacing', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'default'  => [
					'size' => 16,
				],
				'range' => [
					'px' => [
						'min' => -10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-info .team-title' => 'margin: {{SIZE}}{{UNIT}} 0 5px;',
				],
			]
		);


		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#404040',
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-info .team-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
				[
					'name'     => 'title_typography',
					'label'    => esc_html__( 'Typography', 'widgetkit-for-elementor' ),
					'scheme'   => Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-info .team-title',
				]
		);


	$this->end_controls_section();

	$this->start_controls_section(
			'Designation_style',
			[
				'label' => esc_html__( 'Designation', 'widgetkit-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'designation_color',
			[
				'label'     => esc_html__( 'Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8c8c8c',
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-info .team-designation' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
				[
					'name'     => 'designation_typography',
					'label'    => esc_html__( 'Typography', 'widgetkit-for-elementor' ),
					'scheme'   => Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-info .team-designation',
				]
		);


		$this->add_control(
            'team_1_desc_heading',
            [
                'label' => esc_html__( 'Description', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
				[
					'name'     => 'team_1_desc_typography',
					'label'    => esc_html__( 'Typography', 'widgetkit-for-elementor' ),
					'scheme'   => Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .tgx-team-1 .team-container .team-content',
				]
		);

		$this->add_control(
			'desc_position',
			[
				'label' => esc_html__( 'Position', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'default'  => [
					'size' => 30,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}  .tgx-team-1 .team-container .team-content' => 'top: {{SIZE}}%;',
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label'     => esc_html__( 'Overlay Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ed485f',
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-block:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .tgx-team-1 .team-container .team-block:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_style',
			[
				'label' => esc_html__( 'Social Icon', 'widgetkit-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Font Size', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'default'  => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 12,
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}}  .tgx-team-1 .team-container .team-each-wrap .team-social a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_bottom_space',
			[
				'label' => esc_html__( 'Spacing', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'default'  => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => -10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-social' => 'margin: {{SIZE}}{{UNIT}} 0;',
				],
			]
		);

		$this->add_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8c8c8c',
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-social a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label'     => esc_html__( 'Bg Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-social a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-social a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_hover_bg_color',
			[
				'label'     => esc_html__( 'Hover Background Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ed485f',
				'selectors' => [
					'{{WRAPPER}} .tgx-team-1 .team-container .team-each-wrap .team-social a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

	$this->end_controls_section();
	}

	protected function render() {
		require WK_PATH . '/elements/team-1/template/view.php';
	}


}