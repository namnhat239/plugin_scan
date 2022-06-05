<?php
/**
 * Elementor Settings Class.
 *
 * This class contains all the controls for Settings tab.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets\Elementor\Sections;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

/**
 * Elementor Settings Class.
 */
class Settings {

	/**
	 * Tab name.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $tab = \Elementor\Controls_Manager::TAB_SETTINGS;

	/**
	 * Slider section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function slider( $obj ) {
		$obj->startSection( 'slider_section', esc_html__( 'Slider Settings', 'tlp-team' ), self::$tab );
		$obj->elHeading( $obj->elPrefix . 'slider_control_note', __( 'Controls', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'slider_loop',
			'label'       => __( 'Infinite Loop', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'description' => __( 'Switch on to enable slider infinite loop.', 'tlp-team' ),
			'condition'   => [ $obj->elPrefix . 'layout!' => [ 'carousel10' ] ],
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'slider_nav',
			'label'       => __( 'Navigation Arrows', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'description' => __( 'Switch on to enable slider navigation arrows.', 'tlp-team' ),
			'default'     => 'yes',
		];

		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'slider_nav_position',
			'label'       => __( 'Navigation Arrows <br>Position', 'tlp-team' ),
			'options'     => [
				'top'      => __( 'Top', 'tlp-team' ),
				'standard' => __( 'Middle', 'tlp-team' ),
				'bottom'   => __( 'Bottom', 'tlp-team' ),
			],
			'description' => __( 'Please select the slider arrows position.', 'tlp-team' ),
			'default'     => 'top',
			'condition'   => [
				$obj->elPrefix . 'slider_nav' => [ 'yes' ],
				$obj->elPrefix . 'layout!'    => [ 'carousel10' ],
			],
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'slider_pagi',
			'label'       => __( 'Dot Pagination', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'description' => __( 'Switch on to enable slider dot pagination.', 'tlp-team' ),
			'default'     => 'yes',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'slider_auto_height',
			'label'       => __( 'Auto Height', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'description' => __( 'Switch on to enable slider dynamic height.', 'tlp-team' ),
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'slider_lazy_load',
			'label'       => __( 'Image Lazy Load', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'description' => __( 'Switch on to enable slider image lazy load.', 'tlp-team' ),
		];

		$obj->elControls[] = [
			'type'        => 'number',
			'id'          => $obj->elPrefix . 'slide_speed',
			'label'       => __( 'Slide Speed (in ms)', 'tlp-team' ),
			'description' => __( 'Please enter the duration of transition between slides (in ms).', 'tlp-team' ),
			'default'     => 2000,
			'separator'   => 'after',
		];

		$obj->elHeading( $obj->elPrefix . 'slider_autoplay_note', __( 'Autoplay', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'slide_autoplay',
			'label'       => __( 'Enable Autoplay?', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'description' => __( 'Switch on to enable slider autoplay.', 'tlp-team' ),
			'default'     => 'yes',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'pause_hover',
			'label'       => __( 'Pause on Mouse Hover?', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'description' => __( 'Switch on to enable slider autoplay pause on mouse hover.', 'tlp-team' ),
			'default'     => 'yes',
			'condition'   => [ $obj->elPrefix . 'slide_autoplay' => 'yes' ],
		];

		$obj->elControls[] = [
			'type'        => 'number',
			'id'          => $obj->elPrefix . 'autoplay_timeout',
			'label'       => esc_html__( 'Autoplay Delay (in ms)', 'tlp-team' ),
			'options'     => Fns::getTTPShortcodeList(),
			'default'     => 5000,
			'description' => __( 'Please select autoplay interval delay (in ms).', 'tlp-team' ),
			'condition'   => [ $obj->elPrefix . 'slide_autoplay' => 'yes' ],
		];

		// $obj->elControls = Fns::filter( $obj->elPrefix . 'end_of_slider_section', $obj );

		$obj->endSection();

		return new static();
	}

	/**
	 * Filter section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function filter( $obj ) {
		$obj->startSection(
			'filter_section',
			esc_html__( 'Filters (Front-End)', 'tlp-team' ),
			self::$tab,
			[],
			[ $obj->elPrefix . 'layout!' => [ 'special01' ] ]
		);

		$obj->elControls = Fns::filter( $obj->elPrefix . 'filter_section', $obj );

		$obj->endSection();

		return new static();
	}

	/**
	 * Filter section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function isotope( $obj ) {
		$obj->startSection( 'filter_section', esc_html__( 'Isotope Filters', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'enable_isotope_button',
			'label'       => __( 'Enable Isotope Filters Button?', 'tlp-team' ),
			'description' => __( 'Switch on to enable isotope filters button.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'separator'   => 'after',
			'default'     => 'yes',
		];

		$obj->elControls = Fns::filter( $obj->elPrefix . 'isotope_section', $obj );

		$obj->endSection();

		return new static();
	}

	/**
	 * Content limit section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function contentLimit( $obj ) {
		$obj->startSection( 'content_limit_section', esc_html__( 'Content Limit', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'        => 'number',
			'id'          => $obj->elPrefix . 'content_limit',
			'label'       => __( 'Short Biography limit', 'tlp-team' ),
			'description' => __( 'Limits the short biography text (letter limit). Leave it blank for full text.', 'tlp-team' ),
		];

		$obj->elControls[] = [
			'type' => 'html',
			'id'   => $obj->elPrefix . 'content_limit_note',
			'raw'  => __( '<span style="display: block; margin-top: 0; font-weight: 500; line-height: 1.4;">Please note that, HTML tags will not work if content limit is applied.</span>', 'tlp-team' ),
		];

		$obj->elControls[] = [
			'type'        => 'text',
			'id'          => $obj->elPrefix . 'after_content',
			'label'       => __( 'Text After Short Biography', 'tlp-team' ),
			'description' => __( 'Adds text after short biography.', 'tlp-team' ),
			'separator'   => 'before',
			'label_block' => true,
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Pagination section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function links( $obj ) {
		$obj->startSection( 'links_section', esc_html__( 'Detail Page', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'detail_page_link',
			'label'       => __( 'Link to Detail Page?', 'tlp-team' ),
			'description' => __( 'Switch on to enable linking to detail page.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'default'     => 'yes',
			'separator'   => 'after',
		];

		$obj->elControls = Fns::filter( $obj->elPrefix . 'end_of_links_section', $obj );

		$obj->endSection();

		return new static();
	}

	/**
	 * Visibility section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function ContentVisibility( $obj ) {
		$layoutCondition = [ $obj->elPrefix . 'layout!' => [ 'layout-el-4', 'layout7', 'layout-el-8', 'layout9', 'layout-el-10', 'layout11', 'layout12', 'layout13', 'layout14', 'layout15', 'carousel-el-2', 'carousel3', 'carousel4', 'carousel5', 'carousel6', 'carousel7', 'carousel8', 'carousel9', 'carousel11', 'isotope1', 'isotope3', 'isotope4', 'isotope5', 'isotope6', 'isotope7', 'isotope8', 'isotope9', 'isotope10' ] ];

		$obj->startSection( 'visibility_section', esc_html__( 'Content Visibility', 'tlp-team' ), self::$tab );

		$obj->startTabGroup( 'visibility_tab' );
		$obj->startTab( 'visibility_details_tab', __( 'Details', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_name',
			'label'       => __( 'Show Name?', 'tlp-team' ),
			'description' => __( 'Switch on to show team member name.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'default'     => 'yes',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_designation',
			'label'       => __( 'Show Designation?', 'tlp-team' ),
			'description' => __( 'Switch on to show team member designation.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'default'     => 'yes',
			// 'condition'   => [ $obj->elPrefix . 'layout!' => [ 'layout11' ] ],
			'separator'   => 'before',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_department',
			'label'       => __( 'Show Department?', 'tlp-team' ),
			'description' => __( 'Switch on to show team member department.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'condition'   => [ $obj->elPrefix . 'layout!' => [ 'layout9', 'layout10', 'layout11', 'layout12', 'layout13', 'layout14', 'layout5', 'layout15', 'carousel2', 'carousel3', 'carousel3', 'carousel6', 'carousel7', 'carousel8', 'carousel9', 'carousel11', 'isotope1', 'isotope2', 'isotope3', 'isotope4', 'isotope5', 'isotope6', 'isotope7', 'isotope8', 'isotope9', 'isotope10', 'carousel4', 'carousel5' ] ],
			'separator'   => 'before',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_short_bio',
			'label'       => __( 'Show Short Biography?', 'tlp-team' ),
			'description' => __( 'Switch on to show short biography.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'default'     => 'yes',
			'separator'   => 'before',
			'condition'   => [ $obj->elPrefix . 'layout!' => [ 'layout-el-8', 'layout11', 'layout14', 'layout5', 'layout15', 'carousel3', 'carousel6', 'carousel8', 'carousel9', 'carousel11', 'isotope5', 'isotope7', 'isotope8', 'isotope9', 'isotope10' ] ],
		];

		$obj->elControls = Fns::filter( $obj->elPrefix . 'end_of_details_tab', $obj );

		$obj->endTab();
		$obj->startTab( 'visibility_contact_tab', __( 'Contact', 'tlp-team' ), [], $layoutCondition );

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_email',
			'label'       => __( 'Show Email Address?', 'tlp-team' ),
			'description' => __( 'Switch on to show email address.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'default'     => 'yes',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_phone',
			'label'       => __( 'Show Telephone Number?', 'tlp-team' ),
			'description' => __( 'Switch on to show telephone number.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'default'     => 'yes',
			'separator'   => 'before',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_mobile',
			'label'       => __( 'Show Mobile Number?', 'tlp-team' ),
			'description' => __( 'Switch on to show mobile number.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'default'     => 'yes',
			'separator'   => 'before',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_fax',
			'label'       => __( 'Show Fax?', 'tlp-team' ),
			'description' => __( 'Switch on to show fax.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'separator'   => 'before',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_location',
			'label'       => __( 'Show Location?', 'tlp-team' ),
			'description' => __( 'Switch on to show location.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'separator'   => 'before',
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'team_website',
			'label'       => __( 'Show Website URL?', 'tlp-team' ),
			'description' => __( 'Switch on to show website URL.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'separator'   => 'before',
		];

		$obj->endTab();
		$obj->startTab(
			'visibility_social_tab',
			__( 'Social', 'tlp-team' ),
			[],
			[ $obj->elPrefix . 'layout!' => [ 'carousel3' ] ]
		);

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'show_social_media',
			'label'       => __( 'Show Social Media?', 'tlp-team' ),
			'description' => __( 'Switch on to show social media.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'default'     => 'yes',
			'separator'   => 'default',
		];

		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'team_social_media',
			'label'       => __( 'Which icons to show?', 'tlp-team' ),
			'description' => __( 'Please select the social media icons you want to show. Leave it blank to show all social profiles.', 'tlp-team' ),
			'options'     => Options::socialLink(),
			'multiple'    => true,
			'label_block' => true,
			'condition'   => [ $obj->elPrefix . 'show_social_media' => [ 'yes' ] ],
		];

		$obj->endTab();
		$obj->endTabGroup();

		$obj->endSection();

		return new static();
	}
}
