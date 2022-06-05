<?php

namespace OXI_TABS_PLUGINS\Render\Admin;

/**
 * Description of Effects1
 *
 * @author biplo
 */
use OXI_TABS_PLUGINS\Render\Helper;
use OXI_TABS_PLUGINS\Render\Controls as Controls;

class Style19 extends Helper {

    public function register_header_general() {
        $this->start_controls_section(
                'oxi-tabs-head', [
            'label' => esc_html__('Header General', OXI_TABS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-tabs-head-aditional-location', $this->style, [
            'label' => __('Title Additional Location', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'options' => [
                'oxi-tab-header-aditional-left-position' => __('Left', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-aditional-top-position' => __('Top', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-aditional-right-position' => __('Right', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-aditional-bottom-position' => __('Bottom', OXI_TABS_TEXTDOMAIN),
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li' => '',
            ],
            'description' => 'Set the Location of Title’s Additionals (Icon, Image, or Number.)',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-alignment-left-right', $this->style, [
            'label' => __('Title Alignment', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'condition' => [
                'oxi-tabs-head-aditional-location' => ['oxi-tab-header-aditional-left-position', 'oxi-tab-header-aditional-right-position'],
            ],
            'options' => [
                '' => __('Default', OXI_TABS_TEXTDOMAIN),
                'flex-start' => __('Left', OXI_TABS_TEXTDOMAIN),
                'center' => __('Center', OXI_TABS_TEXTDOMAIN),
                'flex-end' => __('Right', OXI_TABS_TEXTDOMAIN),
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header .oxi-tabs-header-li.oxi-tab-header-aditional-left-position' => 'justify-content:{{VALUE}};',
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header .oxi-tabs-header-li.oxi-tab-header-aditional-right-position' => 'justify-content:{{VALUE}};',
            ],
            'description' => 'Set the Location of Title’s Alignment',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-alignment-top-bottom', $this->style, [
            'label' => __('Title Alignment', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'condition' => [
                'oxi-tabs-head-aditional-location' => ['oxi-tab-header-aditional-top-position', 'oxi-tab-header-aditional-bottom-position'],
            ],
            'options' => [
                '' => __('Default', OXI_TABS_TEXTDOMAIN),
                'flex-start' => __('Left', OXI_TABS_TEXTDOMAIN),
                'center' => __('Center', OXI_TABS_TEXTDOMAIN),
                'flex-end' => __('Right', OXI_TABS_TEXTDOMAIN),
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header .oxi-tabs-header-li.oxi-tab-header-aditional-top-position' => 'align-items:{{VALUE}};',
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header .oxi-tabs-header-li.oxi-tab-header-aditional-bottom-position' => 'align-items:{{VALUE}};',
            ],
            'description' => 'Set the Location of Title’s Alignment',
                ]
        );
        $this->add_control(
                'oxi-tabs-head-bg', $this->style, [
            'label' => __('Background', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::GRADIENT,
            'default' => 'rgba(171, 0, 201, 1)',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => 'background: {{VALUE}};',
            ],
            'description' => 'Set the Background of the Header on Normal Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-head-content-divider', $this->style, [
            'label' => __('Content Divider', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SINGLEBORDER,
            'selector' => [
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => 'border: {{SIZE}}px {{TYPE}} {{COLOR}};',
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-tabs-header-shape' => 'border: {{SIZE}}px {{TYPE}} {{COLOR}};',
            ],
            'description' => 'Customize Content Divider of the Header which divide with content. Set Type, Size, and Color.',
                ]
        );
         $this->add_control(
                'oxi-tabs-head-active-border', $this->style, [
            'label' => __('Active Border C0lor', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => 'rgba(171, 0, 201, 1)',
            'selector' => [
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-tabs-header-shape' => 'border-color: {{VALUE}};',
            ],
            'description' => 'Set the Background of the Header on Normal Mode.',
                ]
        );

        $this->add_responsive_control(
                'oxi-tabs-head-padding', $this->style, [
            'label' => __('Padding', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Header Content including background color.',
                ]
        );
        $this->end_controls_section();
    }
    
     public function register_desc_general() {
        $this->start_controls_section(
                'oxi-tabs-desc-general', [
            'label' => esc_html__('General Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-content-height', $this->style, [
            'label' => __('Content Height', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'toggle' => true,
            'options' => [
                'yes' => [
                    'title' => __('Equal', OXI_TABS_TEXTDOMAIN),
                ],
                'no' => [
                    'title' => __('Dynamic', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Select Content Height as Equal or Dynamic.',
                ]
        );


        $this->add_control(
                'oxi-tabs-desc-general-bg', $this->style, [
            'label' => __('Background', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::GRADIENT,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize the Body’s Background with Color, Gradient or Image properties.',
                ]
        );

        $this->add_group_control(
                'oxi-tabs-desc-general-boxshadow', $this->style, [
            'type' => Controls::BOXSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Add one or more shadows into the body and customize other Box-Shadow Options.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-desc-general-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-tabs-ultimate-style' => ''
                    ],
                    'description' => 'Customize Border of the Body. Set Type, Width, and Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-general-radius', $this->style, [
            'label' => __('Border Radius', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
            ],
            'description' => 'Add rounded corners to the Body’s Section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-general-padding', $this->style, [
            'label' => __('Padding', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style .oxi-tabs-body-tabs' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Content Body including background color.',
                ]
        );

        $this->end_controls_section();
    }

}
