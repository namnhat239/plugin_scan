<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OXI_TABS_PLUGINS\Render;

/**
 * Description of Helper
 *
 * @author Oxizen
 */
use OXI_TABS_PLUGINS\Render\Admin;
use OXI_TABS_PLUGINS\Render\Controls as Controls;

class Helper extends Admin {

    public function get_initial_opening_list() {
        if (!isset($this->child)):
            return [];
        endif;
        $count = count($this->child);
        $r = [];
        for ($i = 0; $i < $count; $i++) {
            if ($i == 0):
                $r[':eq(' . $i . ')'] = 'First';
            elseif ($i == 1):
                $r[':eq(' . $i . ')'] = '2nd';
            elseif ($i == 2):
                $r[':eq(' . $i . ')'] = '3rd';
            else:
                $r[':eq(' . $i . ')'] = ($i + 1) . 'th';
            endif;
        }
        $r[':eq(100)'] = 'None';
        return $r;
    }

    public function register_general_parent() {
        //General Section
        $this->start_section_tabs(
                'oxi-tabs-start-tabs', [
            'condition' => [
                'oxi-tabs-start-tabs' => 'general-settings'
            ]
                ]
        );
        //Start Divider
        $this->start_section_devider();
        $this->register_gen_general();
        $this->end_section_devider();

        //Start Divider
        $this->start_section_devider();
        $this->register_gen_heading();
        $this->end_section_devider();
        $this->end_section_tabs();
        //General Section End
        //
    }

    public function register_gen_general() {
        $this->start_controls_section(
                'oxi-tabs-gen', [
            'label' => esc_html__('General Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-tabs-gen-trigger', $this->style, [
            'label' => __('Trigger', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'loader' => TRUE,
            'default' => '0',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_TABS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Enable Trigger to close the tab’s content with a Second click into the Same Tabs.',
                ]
        );
        $this->add_control(
                'oxi-tabs-gen-event', $this->style, [
            'label' => __('Activator Event', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 'oxi-tabs-click-event',
            'loader' => TRUE,
            'options' => [
                'oxi-tabs-click-event' => [
                    'title' => __('Click', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-hover-event' => [
                    'title' => __('Hover', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Select either your Tabs will open on Click or Hover.',
                ]
        );
        $this->add_control(
                'oxi-tabs-gen-opening', $this->style, [
            'label' => __('Initial Opening', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'default' => ':eq(0)',
            'loader' => TRUE,
            'options' => $this->get_initial_opening_list(),
            'description' => 'Select which Tab will Open at Page Load.',
                ]
        );
        $this->add_control(
                'oxi-tabs-gen-animation', $this->style, [
            'label' => __('Animation', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'default' => '',
            'loader' => TRUE,
            'options' => [
                'optgroup0' => [true, 'Attention Seekers'],
                '' => __('No Animation', OXI_TABS_TEXTDOMAIN),
                'animate__bounce' => __('Bounce', OXI_TABS_TEXTDOMAIN),
                'animate__flash' => __('Flash', OXI_TABS_TEXTDOMAIN),
                'animate__pulse' => __('Pulse', OXI_TABS_TEXTDOMAIN),
                'animate__rubberBand' => __('Rubber Band', OXI_TABS_TEXTDOMAIN),
                'animate__shakeX' => __('ShakeX', OXI_TABS_TEXTDOMAIN),
                'animate__headShake' => __('Head Shake', OXI_TABS_TEXTDOMAIN),
                'animate__swing' => __('Swing', OXI_TABS_TEXTDOMAIN),
                'animate__tada' => __('Tada', OXI_TABS_TEXTDOMAIN),
                'animate__wobble' => __('Wobble', OXI_TABS_TEXTDOMAIN),
                'animate__jello' => __('Jello', OXI_TABS_TEXTDOMAIN),
                'animate__heartBeat' => __('Heart Beat', OXI_TABS_TEXTDOMAIN),
                'optgroup1' => [false],
                'optgroup2' => [true, 'Back Entrances'],
                'animate__backInDown' => __('Back In Down', OXI_TABS_TEXTDOMAIN),
                'animate__backInLeft' => __('Back In Left', OXI_TABS_TEXTDOMAIN),
                'animate__backInRight' => __('Back In Right', OXI_TABS_TEXTDOMAIN),
                'animate__backInUp' => __('Back In Up', OXI_TABS_TEXTDOMAIN),
                'optgroup3' => [false],
                'optgroup4' => [true, 'Bouncing Entrances'],
                'animate__bounceIn' => __('Bounce In', OXI_TABS_TEXTDOMAIN),
                'animate__bounceInDown' => __('Bounce In Down', OXI_TABS_TEXTDOMAIN),
                'animate__bounceInLeft' => __('Bounce In Left', OXI_TABS_TEXTDOMAIN),
                'animate__bounceInRight' => __('Bounce In Right', OXI_TABS_TEXTDOMAIN),
                'animate__bounceInUp' => __('Bounce In Up', OXI_TABS_TEXTDOMAIN),
                'optgroup5' => [false],
                'optgroup6' => [true, 'Fading Entrances'],
                'animate__fadeIn' => __('Fade In', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInDown' => __('Fade In Down', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInDownBig' => __('Fade In Down Big', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInLeft' => __('Fade In Left', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInLeftBig' => __('Fade In Left Big', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInRight' => __('Fade In Right', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInRightBig' => __('Fade In Right Big', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInUp' => __('Fade In Up', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInUpBig' => __('Fade In Up Big', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInTopLeft' => __('Fade In Top Left', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInTopRight' => __('Fade In Top Right', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInBottomLeft' => __('Fade In Bottom Left', OXI_TABS_TEXTDOMAIN),
                'animate__fadeInBottomRight' => __('Fade In Bottom Right', OXI_TABS_TEXTDOMAIN),
                'optgroup7' => [false],
                'optgroup8' => [true, 'Flippers'],
                'animate__flip' => __('Flip', OXI_TABS_TEXTDOMAIN),
                'animate__flipInX' => __('Flip In X', OXI_TABS_TEXTDOMAIN),
                'animate__flipInY' => __('Flip In Y', OXI_TABS_TEXTDOMAIN),
                'optgroup9' => [false],
                'optgroup10' => [true, 'Lightspeed'],
                'animate__lightSpeedInRight' => __('Light Speed In Right', OXI_TABS_TEXTDOMAIN),
                'animate__lightSpeedInLeft' => __('Light Speed In Left', OXI_TABS_TEXTDOMAIN),
                'optgroup11' => [false],
                'optgroup12' => [true, 'Rotating Entrances'],
                'animate__rotateIn' => __('Rotate In', OXI_TABS_TEXTDOMAIN),
                'animate__rotateInDownLeft' => __('Rotate In Down Left', OXI_TABS_TEXTDOMAIN),
                'animate__rotateInDownRight' => __('Rotate In Down Right', OXI_TABS_TEXTDOMAIN),
                'animate__rotateInUpLeft' => __('Rotate In Up Left', OXI_TABS_TEXTDOMAIN),
                'animate__rotateInUpRight' => __('Rotate In Up Right', OXI_TABS_TEXTDOMAIN),
                'optgroup13' => [false],
                'optgroup14' => [true, 'Specials'],
                'animate__hinge' => __('Hinge', OXI_TABS_TEXTDOMAIN),
                'animate__jackInTheBox' => __('Jack In The Box', OXI_TABS_TEXTDOMAIN),
                'animate__rollIn' => __('Roll In', OXI_TABS_TEXTDOMAIN),
                'optgroup15' => [false],
                'optgroup16' => [true, 'Zooming Entrances'],
                'animate__zoomIn' => __('Zoom In', OXI_TABS_TEXTDOMAIN),
                'animate__zoomInDown' => __('Zoom In Down', OXI_TABS_TEXTDOMAIN),
                'animate__zoomInLeft' => __('Zoom In Left', OXI_TABS_TEXTDOMAIN),
                'animate__zoomInRight' => __('Zoom In Right', OXI_TABS_TEXTDOMAIN),
                'animate__zoomInUp' => __('Zoom In Up', OXI_TABS_TEXTDOMAIN),
                'optgroup17' => [false],
                'optgroup18' => [true, 'Sliding Entrances'],
                'animate__slideInDown' => __('Slide In Down', OXI_TABS_TEXTDOMAIN),
                'animate__slideInLeft' => __('Slide In Left', OXI_TABS_TEXTDOMAIN),
                'animate__slideInUp' => __('Slide In Up', OXI_TABS_TEXTDOMAIN),
                'optgroup19' => [false],
            ],
            'description' => 'Add Animation Effect on Tabs opening.',
                ]
        );

        $this->add_responsive_control(
                'oxi-tabs-general-margin', $this->style, [
            'label' => __('Margin', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Tabs Body.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_gen_heading() {
        $this->start_controls_section(
                'oxi-tabs-heading', [
            'label' => esc_html__('Header Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-alignment', $this->style, [
            'label' => __('Tabs Alignment', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                'oxi-tab-header-horizontal' => [
                    'title' => __('Horizontal', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tab-header-vertical' => [
                    'title' => __('Vertical', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Set the Tabs Alignment type.',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-horizontal-position', $this->style, [
            'label' => __('Horizontal Position', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-horizontal'
            ],
            'options' => [
                'oxi-tab-header-top-left-position' => __('Top Left', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-top-right-position' => __('Top Right', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-top-center-position' => __('Top Center', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-top-compact-position' => __('Top Compact', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-bottom-left-position' => __('Bottom Left', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-bottom-right-position' => __('Bottom Right', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-bottom-center-position' => __('Bottom Center', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-bottom-compact-position' => __('Bottom Compact', OXI_TABS_TEXTDOMAIN),
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Set the Horizontal Position of Tab’s header.',
                ]
        );

        $this->add_control(
                'oxi-tabs-heading-vertical-position', $this->style, [
            'label' => __('Vertical Position', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-vertical'
            ],
            'options' => [
                'oxi-tab-header-left-top-position' => __('Left Top', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-left-center-position' => __('Left Center', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-left-bottom-position' => __('Left Bottom', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-right-top-position' => __('Right Top', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-right-center-position' => __('Right Center', OXI_TABS_TEXTDOMAIN),
                'oxi-tab-header-right-bottom-position' => __('Right Bottom', OXI_TABS_TEXTDOMAIN),
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Set the Vertical Position of Tab’s header.',
                ]
        );

        $this->add_control(
                'oxi-tabs-gen-vertical-width', $this->style, [
            'label' => __('Header Width', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => '%',
                'size' => 100,
            ],
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-vertical'
            ],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1900,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tab-header-vertical > .oxi-tabs-ultimate-header-wrap' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Customize the Header Width (Pixel, Percent or EM).',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-responsive-mode', $this->style, [
            'label' => __('Responsive Behavior', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                'oxi-tabs-heading-responsive-dynamic' => [
                    'title' => __('Dynamic', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-responsive-static' => [
                    'title' => __('Static', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => 'oxi-tabs-heading-responsive-dynamic',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Set the Responsive Behavior of the Tab’s Header while Static will give you to set your custom settings.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-tabs-heading-renponsive-tabs',
                [
                    'options' => [
                        'tabs-settings' => esc_html__('Tabs Settings ', OXI_TABS_TEXTDOMAIN),
                        'mobile-settings' => esc_html__('Mobile Settings', OXI_TABS_TEXTDOMAIN),
                    ],
                    'condition' => [
                        'oxi-tabs-heading-responsive-mode' => 'oxi-tabs-heading-responsive-static'
                    ],
                ]
        );

        $this->start_controls_tab();

        $this->add_control(
                'oxi-tabs-header-horizontal-tabs-alignment-horizontal', $this->style, [
            'label' => __('Horizontal Position', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-horizontal',
            ],
            'options' => [
                'oxi-tabs-header-horizontal-tabs-alignment-horizontal-horizontal' => __('Column', OXI_TABS_TEXTDOMAIN),
                'oxi-tabs-header-horizontal-tabs-alignment-horizontal-vertical' => __('Row', OXI_TABS_TEXTDOMAIN),
                'oxi-tabs-header-horizontal-tabs-alignment-horizontal-compact' => __('Compact', OXI_TABS_TEXTDOMAIN),
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Set Horizontal Position of the Header either Column, Row, or Compact.',
                ]
        );

        $this->add_control(
                'oxi-tabs-header-vertical-tabs-alignment', $this->style, [
            'label' => __('Header Alignment', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-vertical'
            ],
            'options' => [
                'oxi-tabs-header-vertical-tabs-alignment-horizontal' => [
                    'title' => __('Horizontal', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-header-vertical-tabs-alignment-vertical' => [
                    'title' => __('Vertical', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Set the Tabs Alignment type for Medium Device.',
                ]
        );
        $this->add_control(
                'oxi-tabs-header-vertical-tabs-alignment-horizontal', $this->style, [
            'label' => __('Horizontal Position', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-vertical',
                'oxi-tabs-header-vertical-tabs-alignment' => 'oxi-tabs-header-vertical-tabs-alignment-horizontal'
            ],
            'options' => [
                'oxi-tabs-header-vertical-tabs-alignment-horizontal-horizontal' => __('Column', OXI_TABS_TEXTDOMAIN),
                'oxi-tabs-header-vertical-tabs-alignment-horizontal-vertical' => __('Row', OXI_TABS_TEXTDOMAIN),
                'oxi-tabs-header-vertical-tabs-alignment-horizontal-compact' => __('Compact', OXI_TABS_TEXTDOMAIN),
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Set Header Alignment Horizontal Position as Colum row or Compact.',
                ]
        );

        $this->add_control(
                'oxi-tabs-header-tab-vertical-width', $this->style, [
            'label' => __('Header Width', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'customresponsive' => 'tab',
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-vertical',
                'oxi-tabs-header-vertical-tabs-alignment' => 'oxi-tabs-header-vertical-tabs-alignment-vertical'
            ],
            'default' => [
                'unit' => '%',
                'size' => 25,
            ],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1900,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tab-header-vertical.oxi-tabs-heading-responsive-static.oxi-tabs-header-vertical-tabs-alignment-vertical > .oxi-tabs-ultimate-header-wrap' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Customize the Header Width (Pixel, Percent or EM).',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-tabs-show-title', $this->style, [
            'label' => __('Title', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                '' => [
                    'title' => __('Show', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-tabs-show-title-false' => [
                    'title' => __('Hide', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => '',
            ],
            'description' => 'Show/Hide the Title on Tabs Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-tabs-show-subtitle', $this->style, [
            'label' => __('Sub Title', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                '' => [
                    'title' => __('Show', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-tabs-show-subtitle-false' => [
                    'title' => __('Hide', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => '',
            ],
            'description' => 'Show/Hide the Sub Title on Tabs Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-tabs-show-icon', $this->style, [
            'label' => __('Icon', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                '' => [
                    'title' => __('Show', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-tabs-show-icon-false' => [
                    'title' => __('Hide', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => '',
            ],
            'description' => 'Show/Hide the header Icon on Tabs Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-tabs-show-number', $this->style, [
            'label' => __('Number', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                '' => [
                    'title' => __('Show', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-tabs-show-number-false' => [
                    'title' => __('Hide', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => '',
            ],
            'description' => 'Show/Hide the header Number on Tabs Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-tabs-show-image', $this->style, [
            'label' => __('Image', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                '' => [
                    'title' => __('Show', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-tabs-show-image-false' => [
                    'title' => __('Hide', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => '',
            ],
            'description' => 'Show/Hide the header Image on Tabs Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-tabs-header-horizontal-mobile-alignment-horizontal', $this->style, [
            'label' => __('Horizontal Position', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-horizontal',
            ],
            'options' => [
                'oxi-tabs-header-horizontal-mobile-alignment-horizontal-horizontal' => __('Column', OXI_TABS_TEXTDOMAIN),
                'oxi-tabs-header-horizontal-mobile-alignment-horizontal-vertical' => __('Row', OXI_TABS_TEXTDOMAIN),
                'oxi-tabs-header-horizontal-mobile-alignment-horizontal-compact' => __('Compact', OXI_TABS_TEXTDOMAIN),
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Set Horizontal Position of the Header either Column, Row, or Compact..',
                ]
        );
        $this->add_control(
                'oxi-tabs-header-vertical-mobile-alignment', $this->style, [
            'label' => __('Header Alignment', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-vertical'
            ],
            'options' => [
                'oxi-tabs-header-vertical-mobile-alignment-horizontal' => [
                    'title' => __('Horizontal', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-header-vertical-mobile-alignment-vertical' => [
                    'title' => __('Vertical', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Set the Tabs Alignment type for Small Device.',
                ]
        );

        $this->add_control(
                'oxi-tabs-header-vertical-mobile-alignment-horizontal', $this->style, [
            'label' => __('Horizontal Position', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-vertical',
                'oxi-tabs-header-vertical-mobile-alignment' => 'oxi-tabs-header-vertical-mobile-alignment-horizontal'
            ],
            'options' => [
                'oxi-tabs-header-vertical-mobile-alignment-horizontal-horizontal' => __('Column', OXI_TABS_TEXTDOMAIN),
                'oxi-tabs-header-vertical-mobile-alignment-horizontal-vertical' => __('Row', OXI_TABS_TEXTDOMAIN),
                'oxi-tabs-header-vertical-mobile-alignment-horizontal-compact' => __('Compact', OXI_TABS_TEXTDOMAIN),
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style' => '',
            ],
            'description' => 'Set Header Alignment Horizontal Position as Colum row or Compact.',
                ]
        );
        $this->add_control(
                'oxi-tabs-header-mobile-vertical-width', $this->style, [
            'label' => __('Header Width', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'customresponsive' => 'mobile',
            'condition' => [
                'oxi-tabs-heading-alignment' => 'oxi-tab-header-vertical',
                'oxi-tabs-header-vertical-mobile-alignment' => 'oxi-tabs-header-vertical-mobile-alignment-vertical'
            ],
            'default' => [
                'unit' => '%',
                'size' => 25,
            ],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 1900,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tab-header-vertical.oxi-tabs-heading-responsive-static.oxi-tabs-header-vertical-mobile-alignment-vertical > .oxi-tabs-ultimate-header-wrap' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Customize the Header Width (Pixel, Percent or EM) for Small Device.',
                ]
        );

        $this->add_control(
                'oxi-tabs-heading-mobile-show-title', $this->style, [
            'label' => __('Title', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                '' => [
                    'title' => __('Show', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-mobile-show-title-false' => [
                    'title' => __('Hide', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => '',
            ],
            'description' => 'Show/Hide the Title on Mobile Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-mobile-show-subtitle', $this->style, [
            'label' => __('Title', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                '' => [
                    'title' => __('Show', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-mobile-show-subtitle-false' => [
                    'title' => __('Hide', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => '',
            ],
            'description' => 'Show/Hide the Sub Title on Mobile Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-mobile-show-icon', $this->style, [
            'label' => __('Icon', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                '' => [
                    'title' => __('Show', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-mobile-show-icon-false' => [
                    'title' => __('Hide', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => '',
            ],
            'description' => 'Show/Hide the header Icon on Mobile Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-mobile-show-number', $this->style, [
            'label' => __('Number', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                '' => [
                    'title' => __('Show', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-mobile-show-number-false' => [
                    'title' => __('Hide', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => '',
            ],
            'description' => 'Show/Hide the header Number on Mobile Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-heading-mobile-show-image', $this->style, [
            'label' => __('Image', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'options' => [
                '' => [
                    'title' => __('Show', OXI_TABS_TEXTDOMAIN),
                ],
                'oxi-tabs-heading-mobile-show-image-false' => [
                    'title' => __('Hide', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'default' => '',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap' => '',
            ],
            'description' => 'Show/Hide the header Image on Mobile Mode.',
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function register_heading_parent() {
        //Heading Section
        $this->start_section_tabs(
                'oxi-tabs-start-tabs', [
            'condition' => [
                'oxi-tabs-start-tabs' => 'heading-settings'
            ]
                ]
        );
        //Start Divider
        $this->start_section_devider();
        $this->register_header_general();
        $this->end_section_devider();

        //Start Divider
        $this->start_section_devider();
        $this->register_header_title();
        $this->register_header_sub_title();
        $this->register_header_icon();
        $this->register_header_number();
        $this->register_header_image();
        $this->end_section_devider();
        $this->end_section_tabs();
    }

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
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header .oxi-tabs-header-li.oxi-tab-header-aditional-left-position' => 'justify-content:{{VALUE}};',
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header .oxi-tabs-header-li.oxi-tab-header-aditional-right-position' => 'justify-content:{{VALUE}};',
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
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header .oxi-tabs-header-li.oxi-tab-header-aditional-top-position' => 'align-items:{{VALUE}};',
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header .oxi-tabs-header-li.oxi-tab-header-aditional-bottom-position' => 'align-items:{{VALUE}};',
            ],
            'description' => 'Set the Location of Title’s Alignment',
                ]
        );

        $this->start_controls_tabs(
                'oxi-tabs-head-start-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_TABS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_TABS_TEXTDOMAIN),
                    ]
                ]
        );

        $this->start_controls_tab();
        $this->add_control(
                'oxi-tabs-head-bg', $this->style, [
            'label' => __('Background', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::GRADIENT,
            'default' => 'rgba(171, 0, 201, 1)',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header' => 'background: {{VALUE}};',
            ],
            'description' => 'Set the Background of the Header on Normal Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();

        $this->add_control(
                'oxi-tabs-head-ac-bg', $this->style, [
            'label' => __('Background', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::GRADIENT,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header .oxi-tabs-header-li.active' => 'background: {{VALUE}};',
            ],
            'description' => 'Set the Background of the Header.on Active Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(
                'oxi-tabs-head-inner-border',
                $this->style,
                [
                    'label' => __('Divider', OXI_TABS_TEXTDOMAIN),
                    'type' => Controls::SINGLEBORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li' => 'border-right: {{SIZE}}px {{TYPE}} {{COLOR}};border-bottom: {{SIZE}}px {{TYPE}} {{COLOR}};'
                    ],
                    'description' => 'Customize Divider Border of the Header. Set Type, Size, and Color.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-head-boxshadow', $this->style, [
            'type' => Controls::BOXSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header' => '',
            ],
            'description' => 'Add one or more shadows into Header Section and customize other Box-Shadow Options.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-head-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header' => ''
                    ],
                    'description' => 'Customize Border of the Header. Set Type, Width, and Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-radius', $this->style, [
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Header’s Section.',
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Header Content including background color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-margin', $this->style, [
            'label' => __('Margin', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-ultimate-header' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Header Section.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_title() {
        $this->start_controls_section(
                'oxi-tabs-head-title', [
            'label' => esc_html__('Title Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );

        $this->add_group_control(
                'oxi-tabs-head-title-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-main-title' => '',
            ],
            'description' => 'Customize the Typography options for the Tab’s Title.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-tabs-head-title-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_TABS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_TABS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-tabs-head-title-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-main-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Title Color on Normal Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-head-title-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-main-title' => '',
            ],
            'description' => 'Add one or more shadows into Title Texts and customize other Text-Shadow Options.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-tabs-head-title-ac-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-tabs-main-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Title Color on Active Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-head-title-ac-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-tabs-main-title' => '',
            ],
            'description' => 'Add one or more shadows into Title Texts and customize other Text-Shadow Options.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-tabs-head-title-margin', $this->style, [
            'label' => __('Margin', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-main-title' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Title.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_sub_title() {
        $this->start_controls_section(
                'oxi-tabs-head-sub-title', [
            'label' => esc_html__('Sub Title Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => false,
                ]
        );

        $this->add_group_control(
                'oxi-tabs-head-sub-title-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-sub-title' => '',
            ],
            'description' => 'Customize the Typography options for the Tab’s Sub Title.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-tabs-head-sub-title-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_TABS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_TABS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-tabs-head-sub-title-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-sub-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Sub Title Color on Normal Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-head-sub-title-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-sub-title' => '',
            ],
            'description' => 'Add one or more shadows into Sub Title Texts and customize other Text-Shadow Options.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-tabs-head-sub-title-ac-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-tabs-sub-title' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Sub Title Color on Active Mode.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-head-sub-title-ac-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-tabs-sub-title' => '',
            ],
            'description' => 'Add one or more shadows into Sub Title Texts and customize other Text-Shadow Options.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-tabs-head-sub-title-margin', $this->style, [
            'label' => __('Margin', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-sub-title' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Sub Title.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_icon() {
        $this->start_controls_section(
                'oxi-tabs-head-icon', [
            'label' => esc_html__('Icon Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => false,
                ]
        );
        $this->add_control(
                'oxi-tabs-head-icon-position',
                $this->style,
                [
                    'label' => __('Customization Interface', OXI_TABS_TEXTDOMAIN),
                    'type' => Controls::CHOOSE,
                    'operator' => Controls::OPERATOR_TEXT,
                    'toggle' => true,
                    'default' => 'simple',
                    'options' => [
                        'simple' => [
                            'title' => __('Simple', OXI_TABS_TEXTDOMAIN),
                        ],
                        'customizable' => [
                            'title' => __('Customizable', OXI_TABS_TEXTDOMAIN),
                        ],
                    ],
                    'description' => 'Set the Icon Customization Interface either Simple or fully Customizable.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-icon-width', $this->style, [
            'label' => __('Width', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-tabs-head-icon-position' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-icons' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon’s Width.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-icon-height', $this->style, [
            'label' => __('Height', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-tabs-head-icon-position' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-icons' => 'height:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon’s Height.',
                ]
        );

        $this->add_responsive_control(
                'oxi-tabs-head-icon-size', $this->style, [
            'label' => __('Icon Size', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 20,
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-icons' => 'font-size:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Icon Size (PX, % or EM).',
                ]
        );

        $this->start_controls_tabs(
                'oxi-tabs-head-icon-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_TABS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_TABS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-tabs-head-icon-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-icons' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Icon’s Color on Normal Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-head-icon-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => __('Background', OXI_TABS_TEXTDOMAIN),
            'condition' => [
                'oxi-tabs-head-icon-position' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-icons' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize Icon Background with Color, Gradient or Image properties for Normal Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-tabs-head-icon-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-tabs-head-icon-position' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-icons' => '',
            ],
            'description' => 'Customize Border of the Icon. Set Type, Width, and Color.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-tabs-head-icon-ac-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-icons' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Icon’s Color on Active Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-head-icon-ac-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => __('Background', OXI_TABS_TEXTDOMAIN),
            'condition' => [
                'oxi-tabs-head-icon-position' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-icons' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize Icon Background with Color, Gradient or Image properties for Active Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-tabs-head-icon-ac-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-tabs-head-icon-position' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-icons' => '',
            ],
            'description' => 'Customize Border of the Icon. Set Type, Width, and Color for Active Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-tabs-head-icon-border-radius', $this->style, [
            'label' => __('Border Radius', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'condition' => [
                'oxi-tabs-head-icon-position' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => .1,
                ],
                'px' => [
                    'min' => -200,
                    'max' => 200,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-icons' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Icon’s  Section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-icon-margin', $this->style, [
            'label' => __('Margin', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-icons' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Icon.',
                ]
        );

        $this->end_controls_section();
    }

    public function register_header_number() {
        $this->start_controls_section(
                'oxi-tabs-head-number', [
            'label' => esc_html__('Number Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => false,
                ]
        );
        $this->add_control(
                'oxi-tabs-head-number-interface',
                $this->style,
                [
                    'label' => __('Customization Interface', OXI_TABS_TEXTDOMAIN),
                    'type' => Controls::CHOOSE,
                    'operator' => Controls::OPERATOR_TEXT,
                    'toggle' => true,
                    'default' => 'simple',
                    'options' => [
                        'simple' => [
                            'title' => __('Simple', OXI_TABS_TEXTDOMAIN),
                        ],
                        'customizable' => [
                            'title' => __('Customizable', OXI_TABS_TEXTDOMAIN),
                        ],
                    ],
                    'description' => 'Set the Number Customization Interface either Simple or fully Customizable.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-number-width', $this->style, [
            'label' => __('Width', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-tabs-head-number-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li-number' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Number’s Width.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-number-height', $this->style, [
            'label' => __('Height', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'condition' => [
                'oxi-tabs-head-number-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li-number' => 'height:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Number’s Height.',
                ]
        );

        $this->add_group_control(
                'oxi-tabs-head-number-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li-number' => '',
            ],
            'description' => 'Customize the Typography options for the Number.',
                ]
        );

        $this->start_controls_tabs(
                'oxi-tabs-head-number-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_TABS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_TABS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();

        $this->add_control(
                'oxi-tabs-head-number-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li-number' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Number’s Color on Normal Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-head-number-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => __('Background', OXI_TABS_TEXTDOMAIN),
            'condition' => [
                'oxi-tabs-head-number-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li-number' => 'background:{{VALUE}};',
            ],
            'description' => 'Customize Number Background with Color, Gradient or Image properties for Normal Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-tabs-head-number-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-tabs-head-number-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li-number' => '',
            ],
            'description' => 'Customize Border of the Number. Set Type, Width, and Color.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-tabs-head-number-ac-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-tabs-header-li-number' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Number’s Color on Active Mode.',
                ]
        );
        $this->add_control(
                'oxi-tabs-head-number-ac-background', $this->style, [
            'type' => Controls::GRADIENT,
            'label' => __('Background', OXI_TABS_TEXTDOMAIN),
            'condition' => [
                'oxi-tabs-head-number-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-tabs-header-li-number' => 'background:{{VALUE}};',
            ],
            'description' => 'Customize Number Background with Color, Gradient or Image properties for Active Mode.',
                ]
        );

        $this->add_group_control(
                'oxi-tabs-head-number-ac-border', $this->style, [
            'type' => Controls::BORDER,
            'condition' => [
                'oxi-tabs-head-number-interface' => 'customizable',
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-tabs-header-li-number' => '',
            ],
            'description' => 'Customize Border of the Number. Set Type, Width, and Color for Active Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-tabs-head-number-border-radius', $this->style, [
            'label' => __('Border Radius', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'condition' => [
                'oxi-tabs-head-number-interface' => 'customizable',
            ],
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => .1,
                ],
                'px' => [
                    'min' => -200,
                    'max' => 200,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li-number' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Number’s border.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-number-margin', $this->style, [
            'label' => __('Margin', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li-number' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Number on the header.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_header_image() {
        $this->start_controls_section(
                'oxi-tabs-head-image', [
            'label' => esc_html__('Image Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => false,
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-image-width', $this->style, [
            'label' => __('Width', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => .1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 200,
                    'step' => 0.1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-tabs-header-li-image' => 'width:{{SIZE}}{{UNIT}};',
            ],
            'description' => 'Set the Image’s Width.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-tabs-head-number-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_TABS_TEXTDOMAIN),
                        'active' => esc_html__('Active', OXI_TABS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();
        $this->add_group_control(
                'oxi-tabs-head-image-border', $this->style, [
            'type' => Controls::BORDER,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li .oxi-tabs-header-li-image' => '',
            ],
            'description' => 'Customize Border of the Image. Set Type, Width, and Color.',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_group_control(
                'oxi-tabs-head-image-ac-border', $this->style, [
            'type' => Controls::BORDER,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li.active .oxi-tabs-header-li-image' => '',
            ],
            'description' => 'Customize Border of the Image. Set Type, Width, and Color for Active Mode.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-tabs-head-image-border-radius', $this->style, [
            'label' => __('Border Radius', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                '%' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => .1,
                ],
                'px' => [
                    'min' => -200,
                    'max' => 200,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => .1,
                ],
            ],
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li-image' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Image’s Section.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-head-image-margin', $this->style, [
            'label' => __('Margin', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::DIMENSIONS,
            'separator' => true,
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-header-wrap .oxi-tabs-header-li-image' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Image on the header.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_description_parent() {
        //Description Section
        $this->start_section_tabs(
                'oxi-tabs-start-tabs', [
            'condition' => [
                'oxi-tabs-start-tabs' => 'description-settings'
            ]
                ]
        );
        //Start Divider
        $this->start_section_devider();
        $this->register_desc_general();
        $this->register_desc_tags();
        $this->end_section_devider();

        //Start Divider
        $this->start_section_devider();
        $this->register_desc_content();
        $this->register_desc_popular();
        $this->register_desc_recent();
        $this->register_desc_comment();
        $this->end_section_devider();
        $this->end_section_tabs();
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content' => 'background: {{VALUE}};',
            ],
            'description' => 'Customize the Content’s Background with Color, Gradient or Image properties.',
                ]
        );

        $this->add_group_control(
                'oxi-tabs-desc-general-boxshadow', $this->style, [
            'type' => Controls::BOXSHADOW,
            'selector' => [
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content' => '',
            ],
            'description' => 'Add one or more shadows into the Content body and customize other Box-Shadow Options.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-desc-general-border',
                $this->style,
                [
                    'type' => Controls::BORDER,
                    'selector' => [
                        '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content' => ''
                    ],
                    'description' => 'Customize Border of the Content Body. Set Type, Width, and Color.',
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Add rounded corners to the Content’s Section.',
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content > .oxi-tabs-body-tabs' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Content Body including background color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-general-margin', $this->style, [
            'label' => __('Margin', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Create some Space outside of the Content Body.',
                ]
        );

        $this->end_controls_section();
    }

    public function register_desc_content() {
        $this->start_controls_section(
                'oxi-tabs-desc-content', [
            'label' => esc_html__('Content Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );

        $this->add_group_control(
                'oxi-tabs-desc-content-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            'include' => Controls::ALIGNNORMAL,
            'selector' => [
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content > .oxi-tabs-body-tabs' => '',
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content > .oxi-tabs-body-tabs p' => '',
            ],
            'description' => 'Customize the Typography options for the Tab’s Contents.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-content-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '#ffffff',
            'selector' => [
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content > .oxi-tabs-body-tabs' => 'color: {{VALUE}};',
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content >  .oxi-tabs-body-tabs p' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Color of Tab’s Contents.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-desc-content-tx-shadow', $this->style, [
            'type' => Controls::TEXTSHADOW,
            'selector' => [
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content >  .oxi-tabs-body-tabs' => '',
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content >  .oxi-tabs-body-tabs p' => '',
            ],
            'description' => 'Add one or more shadows into the Content Texts and customize other Text-Shadow Options.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-content-padding', $this->style, [
            'label' => __('Content Padding', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}}  > .oxi-tabs-ultimate-style > .oxi-tabs-ultimate-content-wrap > .oxi-tabs-ultimate-content >  .oxi-tabs-body-tabs p' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Adjust Your Content Padding for Peragraph Tag.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_desc_popular() {
        $this->start_controls_section(
                'oxi-tabs-desc-popular', [
            'label' => esc_html__('Popular Post Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => false,
                ]
        );
        //content Section
        $this->add_control(
                'oxi-tabs-desc-popular-post', $this->style, [
            'label' => esc_html__('Max Post', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 5,
            'description' => 'Write the Maximum amount of Popular Posts.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-tabs-desc-popular-tabs',
                [
                    'options' => [
                        'image' => esc_html__('Image ', OXI_TABS_TEXTDOMAIN),
                        'title' => esc_html__('Title', OXI_TABS_TEXTDOMAIN),
                        'meta' => esc_html__('Meta', OXI_TABS_TEXTDOMAIN),
                        'content' => esc_html__('Content', OXI_TABS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();
        //image Section
        $this->add_control(
                'oxi-tabs-desc-popular-thumb-condi', $this->style, [
            'label' => __('Show Image', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_TABS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide the image under the Popular Post.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-popular-thumb-max', $this->style, [
            'label' => esc_html__('Image Size (px)', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 65,
            'condition' => [
                'oxi-tabs-desc-popular-thumb-condi' => '1'
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-popular-post .oxi-tabs-popular-avatar' => 'max-width:{{VALUE}}px;',
            ],
            'description' => 'Set the Image Size (PX).',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-popular-thumb', $this->style, [
            'label' => __('Image Size', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'loader' => TRUE,
            'options' => $this->thumbnail_sizes(),
            'condition' => [
                'oxi-tabs-desc-popular-thumb-condi' => '1'
            ],
            'description' => 'Select a Pre-defined Image Thumbnail Size.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //title Section
        $this->add_group_control(
                'oxi-tabs-desc-popular-title-typo', $this->style, [
            'label' => 'Title Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-popular-body .oxi-tabs-popular-meta a' => '',
            ],
            'description' => 'Customize the Typography options for the Post’s Title.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-popular-title-color', $this->style, [
            'label' => __('Title Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-popular-body .oxi-tabs-popular-meta a' => 'color:{{VALUE}};',
            ],
            'description' => 'Set the Color of Post’s Title.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-popular-title-h-color', $this->style, [
            'label' => __('Title Hover Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-popular-body .oxi-tabs-popular-meta a:hover' => 'color:{{VALUE}};',
            ],
            'description' => 'Set the Color of Post’s Title in Hover view.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-popular-title-padding', $this->style, [
            'label' => __('Title Padding', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} .oxi-tabs-popular-body .oxi-tabs-popular-meta' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around at Post Title from other Content.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //meta Section
        $this->add_control(
                'oxi-tabs-desc-popular-meta-date', $this->style, [
            'label' => __('Show Date', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_TABS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide Meta Date in the Post?',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-popular-meta-comment', $this->style, [
            'label' => __('Show Comment', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_TABS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide Meta Comment in the Post?',
                ]
        );

        $this->add_group_control(
                'oxi-tabs-desc-popular-meta-typo', $this->style, [
            'label' => 'Meta Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-popular-body .oxi-tabs-popular-postmeta' => '',
                '{{WRAPPER}} .oxi-tabs-popular-postmeta .oxi-tabs-popular-date' => '',
                '{{WRAPPER}} .oxi-tabs-popular-postmeta .oxi-tabs-popular-comment' => '',
            ],
            'description' => 'Customize the Typography options for the Post’s Meta.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-popular-meta-color', $this->style, [
            'label' => __('Meta Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-popular-body .oxi-tabs-popular-postmeta' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-tabs-popular-postmeta .oxi-tabs-popular-date' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-tabs-popular-postmeta .oxi-tabs-popular-comment' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Meta Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-popular-meta-padding', $this->style, [
            'label' => __('Meta Padding', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} .oxi-tabs-popular-body .oxi-tabs-popular-postmeta' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Meta.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //content Section
        $this->add_control(
                'oxi-tabs-desc-popular-content-lenth', $this->style, [
            'label' => esc_html__('Content Lenth', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 90,
            'description' => 'Set the Max Content Length.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-desc-popular-content-typo', $this->style, [
            'label' => 'Content Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-popular-body .oxi-tabs-popular-content' => '',
            ],
            'description' => 'Customize the Typography options for the Post’s Content.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-popular-content-color', $this->style, [
            'label' => __('Content Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-popular-body .oxi-tabs-popular-content' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Content Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-popular-content-padding', $this->style, [
            'label' => __('Content Padding', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} .oxi-tabs-popular-body .oxi-tabs-popular-content' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Content.',
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-tabs-desc-popular-padding', $this->style, [
            'label' => __('Content Padding', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-popular-post' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Popular Post.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_desc_recent() {
        $this->start_controls_section(
                'oxi-tabs-desc-recent', [
            'label' => esc_html__('Recent Post Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => false,
                ]
        );
        //content Section
        $this->add_control(
                'oxi-tabs-desc-recent-post', $this->style, [
            'label' => esc_html__('Max Post', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 5,
            'description' => 'Write the Maximum amount of Recent Posts.',
                ]
        );
        $this->start_controls_tabs(
                'oxi-tabs-desc-recent-tabs',
                [
                    'options' => [
                        'image' => esc_html__('Image ', OXI_TABS_TEXTDOMAIN),
                        'title' => esc_html__('Title', OXI_TABS_TEXTDOMAIN),
                        'meta' => esc_html__('Meta', OXI_TABS_TEXTDOMAIN),
                        'content' => esc_html__('Content', OXI_TABS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();
        //image Section
        $this->add_control(
                'oxi-tabs-desc-recent-thumb-condi', $this->style, [
            'label' => __('Show Image', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_TABS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide the image under the Recent Post.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-recent-thumb-max', $this->style, [
            'label' => esc_html__('Image Size (px)', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 65,
            'condition' => [
                'oxi-tabs-desc-recent-thumb-condi' => '1'
            ],
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-recent-post .oxi-tabs-recent-avatar' => 'max-width:{{VALUE}}px;',
            ],
            'description' => 'Set the Image Size (PX).',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-recent-thumb', $this->style, [
            'label' => __('Image Size', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'loader' => TRUE,
            'options' => $this->thumbnail_sizes(),
            'condition' => [
                'oxi-tabs-desc-recent-thumb-condi' => '1'
            ],
            'description' => 'Select a Pre-defined Image Thumbnail Size.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //title Section
        $this->add_group_control(
                'oxi-tabs-desc-recent-title-typo', $this->style, [
            'label' => 'Title Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-recent-body .oxi-tabs-recent-meta a' => '',
            ],
            'description' => 'Customize the Typography options for the Recent Post’s Title.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-recent-title-color', $this->style, [
            'label' => __('Title Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-recent-body .oxi-tabs-recent-meta a' => 'color:{{VALUE}};',
            ],
            'description' => 'Set the Color of Post’s Title.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-recent-title-h-color', $this->style, [
            'label' => __('Title Hover Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-recent-body .oxi-tabs-recent-meta a:hover' => 'color:{{VALUE}};',
            ],
            'description' => 'Set the Color of Post’s Title in Hover view.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-recent-title-padding', $this->style, [
            'label' => __('Title Padding', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} .oxi-tabs-recent-body .oxi-tabs-recent-meta' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Title.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //meta Section
        $this->add_control(
                'oxi-tabs-desc-recent-meta-date', $this->style, [
            'label' => __('Show Date', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_TABS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide Meta Date in the Recent Post?',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-recent-meta-comment', $this->style, [
            'label' => __('Show Comment', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => '1',
            'options' => [
                '1' => [
                    'title' => __('True', OXI_TABS_TEXTDOMAIN),
                ],
                '0' => [
                    'title' => __('False', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide Meta Comment in the Post?',
                ]
        );

        $this->add_group_control(
                'oxi-tabs-desc-recent-meta-typo', $this->style, [
            'label' => 'Meta Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-recent-body .oxi-tabs-recent-postmeta' => '',
                '{{WRAPPER}} .oxi-tabs-recent-postmeta .oxi-tabs-recent-date' => '',
                '{{WRAPPER}} .oxi-tabs-recent-postmeta .oxi-tabs-recent-comment' => '',
            ],
            'description' => 'Customize the Typography options for the Post’s Meta.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-recent-meta-color', $this->style, [
            'label' => __('Meta Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-recent-body .oxi-tabs-recent-postmeta' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-tabs-recent-postmeta .oxi-tabs-recent-date' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-tabs-recent-postmeta .oxi-tabs-recent-comment' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Meta Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-recent-meta-padding', $this->style, [
            'label' => __('Meta Padding', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} .oxi-tabs-recent-body .oxi-tabs-recent-postmeta' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Meta.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        //content Section
        $this->add_control(
                'oxi-tabs-desc-recent-content-lenth', $this->style, [
            'label' => esc_html__('Content Lenth', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 90,
            'description' => 'Set the Max Content Length.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-desc-recent-content-typo', $this->style, [
            'label' => 'Content Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-recent-body .oxi-tabs-recent-content' => '',
            ],
            'description' => 'Customize the Typography options for the Post’s Content.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-recent-content-color', $this->style, [
            'label' => __('Content Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-recent-body .oxi-tabs-recent-content' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Content Color.',
                ]
        );
        $this->add_responsive_control(
                'oxi-tabs-desc-recent-content-padding', $this->style, [
            'label' => __('Content Padding', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} .oxi-tabs-recent-body .oxi-tabs-recent-content' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Content.',
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'oxi-tabs-desc-recent-padding', $this->style, [
            'label' => __('Content Padding', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-recent-post' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Recent Post.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_desc_comment() {
        $this->start_controls_section(
                'oxi-tabs-desc-tags', [
            'label' => esc_html__('Comment Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => false,
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-comment-max', $this->style, [
            'label' => esc_html__('Max Comment', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 5,
            'description' => 'Set the maximum amount of Comments.',
                ]
        );

        $this->add_control(
                'oxi-tabs-desc-comment-show-avatar', $this->style, [
            'label' => __('Show Avatar', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 1,
            'options' => [
                '0' => [
                    'title' => __('False', OXI_TABS_TEXTDOMAIN),
                ],
                '1' => [
                    'title' => __('True', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide the Avatar of comments.',
                ]
        );

        $this->add_control(
                'oxi-tabs-desc-comment-avatar-size', $this->style, [
            'label' => esc_html__('Avatar Size', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 65,
            'condition' => [
                'oxi-tabs-desc-comment-show-avatar' => '1',
            ],
            'description' => 'Set the Avatar Size.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-comment-comment-lenth', $this->style, [
            'label' => esc_html__('Comment Lenth', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 90,
            'description' => 'Customize the Comment’s Length.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-desc-comment-typho', $this->style, [
            'label' => 'Title Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-ultimate-style span.oxi-tabs-comment-author' => '',
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-comment-meta a' => '',
                '{{WRAPPER}} .oxi-tabs-ultimate-style a span.oxi-tabs-comment-post' => '',
            ],
            'description' => ' Customize the Typography options for the Comment’s Title.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-desc-comment-excerpt-typo', $this->style, [
            'label' => 'Comment Typography',
            'type' => Controls::TYPOGRAPHY,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-comment-body .oxi-tabs-comment-content' => '',
            ],
            'description' => 'Customize the Typography options for the Comment’s Content.',
                ]
        );

        $this->start_controls_tabs(
                'oxi-tabs-head-number-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_TABS_TEXTDOMAIN),
                        'hover' => esc_html__('Hover', OXI_TABS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();
        $this->add_control(
                'oxi-tabs-desc-comment-title-color', $this->style, [
            'label' => __('Title Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-ultimate-style span.oxi-tabs-comment-author' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-comment-meta a' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-tabs-ultimate-style a span.oxi-tabs-comment-post' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Title Color.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-comment-comment-color', $this->style, [
            'label' => __('Comment Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-comment-body .oxi-tabs-comment-content' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Color to the Comment Content.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-tabs-desc-comment-title-hover-color', $this->style, [
            'label' => __('Title Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-ultimate-style span.oxi-tabs-comment-author:hover' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-comment-meta a:hover' => 'color:{{VALUE}};',
                '{{WRAPPER}} .oxi-tabs-ultimate-style a span.oxi-tabs-comment-post:hover' => 'color:{{VALUE}};',
            ],
            'description' => 'Add a custom Title Color while Hover.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-tabs-desc-comment-padding', $this->style, [
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
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-comment' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'description' => 'Generate some Space around the Comment.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_desc_tags() {
        $this->start_controls_section(
                'oxi-tabs-desc-tags', [
            'label' => esc_html__('Tags Settings', OXI_TABS_TEXTDOMAIN),
            'showing' => false,
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-tags-max', $this->style, [
            'label' => esc_html__('Max Tags', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 20,
            'description' => 'Set the maximum amount of Tags.',
                ]
        );

        $this->add_control(
                'oxi-tabs-desc-tags-show-count', $this->style, [
            'label' => __('Show Count', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::CHOOSE,
            'operator' => Controls::OPERATOR_TEXT,
            'default' => 1,
            'options' => [
                '0' => [
                    'title' => __('False', OXI_TABS_TEXTDOMAIN),
                ],
                '1' => [
                    'title' => __('True', OXI_TABS_TEXTDOMAIN),
                ],
            ],
            'description' => 'Show/Hide the tags count.',
                ]
        );

        $this->add_control(
                'oxi-tabs-desc-tags-small', $this->style, [
            'label' => esc_html__('Small Size', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 12,
            'description' => 'Set the Small Size of the Tags.',
                ]
        );
        $this->add_control(
                'oxi-tabs-desc-tags-big', $this->style, [
            'label' => esc_html__('Big Size', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 25,
            'description' => 'Set the Big Size of the Tags.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-desc-tags-typho', $this->style, [
            'type' => Controls::TYPOGRAPHY,
            Controls::TYPO_FONTSIZE => false,
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-body-tabs .tag-cloud-link' => '',
            ],
            'description' => 'Customize the Typography options for the Tag Text.',
                ]
        );

        $this->start_controls_tabs(
                'oxi-tabs-head-number-tabs',
                [
                    'options' => [
                        'normal' => esc_html__('Normal ', OXI_TABS_TEXTDOMAIN),
                        'hover' => esc_html__('Hover', OXI_TABS_TEXTDOMAIN),
                    ]
                ]
        );
        $this->start_controls_tab();
        $this->add_control(
                'oxi-tabs-desc-tags-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-body-tabs .tag-cloud-link' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Color of Tab’s Tag in Normal view.',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab();
        $this->add_control(
                'oxi-tabs-desc-tags-hover-color', $this->style, [
            'label' => __('Color', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::COLOR,
            'default' => '',
            'selector' => [
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-body-tabs .tag-cloud-link:hover' => 'color: {{VALUE}};',
            ],
            'description' => 'Set the Color of Tab’s Tag in Hover view.',
                ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'oxi-tabs-desc-tags-padding', $this->style, [
            'label' => __('Content Padding', OXI_TABS_TEXTDOMAIN),
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
                '{{WRAPPER}} .oxi-tabs-ultimate-style .oxi-tabs-body-tabs .tag-cloud-link' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};display: inline-block;',
            ],
            'description' => 'Generate some Space around the Tag’s Content including background color.',
                ]
        );
        $this->end_controls_section();
    }

    public function register_custom_parent() {
        ///Custom CSS
        $this->start_section_tabs(
                'oxi-tabs-start-tabs', [
            'condition' => [
                'oxi-tabs-start-tabs' => 'custom'
            ],
            'padding' => '10px'
                ]
        );

        $this->start_controls_section(
                'oxi-tabs-start-tabs-css', [
            'label' => esc_html__('Custom CSS', OXI_TABS_TEXTDOMAIN),
            'showing' => TRUE,
                ]
        );
        $this->add_control(
                'oxi-tabs-custom-css', $this->style, [
            'label' => __('', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::TEXTAREA,
            'default' => '',
            'description' => 'Custom CSS Section. You can add custom css into textarea.'
                ]
        );
        $this->end_controls_section();
        $this->end_section_tabs();
    }

    public function modal_form_data() {
        echo '<div class="modal-header">                    
                    <h4 class="modal-title">Tabs Modal Form</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">';
        $this->add_control(
                'oxi-tabs-modal-title', [], [
            'label' => esc_html__('Title', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::TEXT,
            'default' => 'Lorem Ipsum',
            'description' => 'Add Title of your Tabs else Make it Blank.',
                ]
        );
        $this->add_control(
                'oxi-tabs-modal-sub-title', [], [
            'label' => esc_html__('Sub Title', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::TEXT,
            'description' => 'Add Sub Title of your Tabs else Make it Blank.',
                ]
        );
        $this->add_control(
                'oxi-tabs-modal-title-additional', [], [
            'label' => __('Title Additional', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => __('None', OXI_TABS_TEXTDOMAIN),
                'icon' => __('Icon', OXI_TABS_TEXTDOMAIN),
                'number' => __('Number', OXI_TABS_TEXTDOMAIN),
                'image' => __('Image', OXI_TABS_TEXTDOMAIN),
            ],
            'description' => 'Add the Additional elements beside the Tab’s Title (Icon, Number or Image).',
                ]
        );

        $this->add_control(
                'oxi-tabs-modal-icon', [], [
            'label' => esc_html__('Icon', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::ICON,
            'default' => 'fab fa-facebook-f',
            'condition' => [
                'oxi-tabs-modal-title-additional' => 'icon',
            ],
            'description' => 'Select Icon from Font Awesome Icon list Panel.',
                ]
        );
        $this->add_control(
                'oxi-tabs-modal-number', [], [
            'label' => esc_html__('Number', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::NUMBER,
            'default' => 1,
            'condition' => [
                'oxi-tabs-modal-title-additional' => 'number',
            ],
            'description' => 'Write the Number as Title Additionals.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-modal-image', [],
                [
                    'label' => __('Image', OXI_TABS_TEXTDOMAIN),
                    'type' => Controls::MEDIA,
                    'condition' => [
                        'oxi-tabs-modal-title-additional' => 'image',
                    ],
                    'description' => 'Add an Image from Media Library or Input a custom Image URL.'
                ]
        );
        $this->add_control(
                'oxi-tabs-modal-components-type', [], [
            'label' => __('Choose Components', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'default' => 'wysiwyg',
            'options' => [
                'wysiwyg' => __('WYSIWYG Editor', OXI_TABS_TEXTDOMAIN),
                'nested-tabs' => __('Nested Tabs', OXI_TABS_TEXTDOMAIN),
                'link' => __('Custom Link', OXI_TABS_TEXTDOMAIN),
                'popular-post' => __('Polular Post', OXI_TABS_TEXTDOMAIN),
                'recent-post' => __('Recent Post', OXI_TABS_TEXTDOMAIN),
                'recent-comment' => __('Recent Comment', OXI_TABS_TEXTDOMAIN),
                'tag' => __('Post Tag', OXI_TABS_TEXTDOMAIN)
            ],
            'description' => 'Se the Tab’s Content type as Content or Custom Link.',
                ]
        );
        $this->add_group_control(
                'oxi-tabs-modal-link', [], [
            'label' => esc_html__('Link', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::URL,
            'condition' => [
                'oxi-tabs-modal-components-type' => 'link',
            ],
            'description' => 'Add Custom link with opening type.',
                ]
        );

        $this->add_control(
                'oxi-tabs-modal-desc', [], [
            'label' => __('Description', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::WYSIWYG,
            'default' => '',
            'condition' => [
                'oxi-tabs-modal-components-type' => 'wysiwyg',
            ],
            'description' => 'Add your Tab’s Description.',
                ]
        );
        $this->add_control(
                'oxi-tabs-modal-nested-tabs', [], [
            'label' => __('Select Tabs', OXI_TABS_TEXTDOMAIN),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => $this->Get_Nested_Tabs,
            'condition' => [
                'oxi-tabs-modal-components-type' => 'nested-tabs',
            ],
            'description' => 'Select Tabs to Create Nested.',
                ]
        );
        echo '</div>';
    }

    public function register_controls() {
        $this->get_initial_opening_list();
        $this->start_section_header(
                'shortcode-addons-start-tabs', [
            'options' => [
                'general-settings' => esc_html__('General Settings', OXI_TABS_TEXTDOMAIN),
                'heading-settings' => esc_html__('Heading Settings', OXI_TABS_TEXTDOMAIN),
                'description-settings' => esc_html__('Description Settings', OXI_TABS_TEXTDOMAIN),
                'custom' => esc_html__('Custom CSS', OXI_TABS_TEXTDOMAIN),
            ]
                ]
        );
        // General Section
        $this->register_general_parent();

        // Heading Section
        $this->register_heading_parent();

        //Description Section
        $this->register_description_parent();

        //Custom CSS
        $this->register_custom_parent();
    }

}
