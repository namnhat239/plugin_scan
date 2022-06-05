<?php
/**
 * Elementor Layout Class.
 *
 * This class contains all the controls for Layout tab.
 *
 * @package RT_Team
 */

namespace RT\Team\Widgets\Elementor\Sections;

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;

/**
 * Elementor Layout Class.
 */
class Layout {

	/**
	 * Tab name.
	 *
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $tab = \Elementor\Controls_Manager::TAB_LAYOUT;

	/**
	 * Shortcode List section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function shortcodeList( $obj ) {
		$obj->startSection( 'content_section', esc_html__( 'Team Shortcodes List', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => 'short_code_id',
			'label'       => esc_html__( 'Select Shortcode', 'tlp-team' ),
			'label_block' => true,
			'options'     => Fns::getTTPShortcodeList(),
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Layout section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function gridLayout( $obj ) {
		$obj->startSection( 'layout_section', esc_html__( 'Layouts', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'    => 'rttm-image-selector',
			'id'      => $obj->elPrefix . 'layout',
			'options' => Options::elGridLayouts(),
			'default' => 'layout1',
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Layout section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function listLayout( $obj ) {
		$obj->startSection( 'layout_section', esc_html__( 'Layouts', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'    => 'rttm-image-selector',
			'id'      => $obj->elPrefix . 'layout',
			'options' => Options::elListLayouts(),
			'default' => 'layout2',
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Layout section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function sliderLayout( $obj ) {
		$obj->startSection( 'layout_section', esc_html__( 'Layouts', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'    => 'rttm-image-selector',
			'id'      => $obj->elPrefix . 'layout',
			'options' => Options::elSliderLayouts(),
			'default' => 'carousel-el-1',
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Layout section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function isotopeLayout( $obj ) {
		$obj->startSection( 'layout_section', esc_html__( 'Layouts', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'    => 'rttm-image-selector',
			'id'      => $obj->elPrefix . 'layout',
			'options' => Options::elIsotopeLayouts(),
			'default' => 'isotope-free',
		];

		$obj->endSection();

		return new static();
	}

	/**
	 * Layout section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function columns( $obj ) {
		$obj->startSection( 'columns_section', esc_html__( 'Columns', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'           => 'select2',
			'id'             => $obj->elPrefix . 'cols',
			'mode'           => 'responsive',
			'label'          => __( 'Number of Columns', 'tlp-team' ),
			'description'    => __( 'Please select the number of columns to show per row.', 'tlp-team' ),
			'options'        => Options::scElColumns(),
			'default'        => '0',
			'tablet_default' => '0',
			'mobile_default' => '0',
			'required'       => true,
			'separator'      => 'after',
			'condition'      => [ $obj->elPrefix . 'layout!' => [ 'layout5' ] ],
		];

		$obj->elControls[] = [
			'type'           => 'select2',
			'id'             => $obj->elPrefix . 'image_cols',
			'mode'           => 'responsive',
			'label'          => __( 'Number of <br>Image Columns', 'tlp-team' ),
			'description'    => __( 'Please select the number of image columns to show per row. Content column will be calculated automatically.', 'tlp-team' ),
			'options'        => Options::scElColumns(),
			'default'        => '0',
			'tablet_default' => '0',
			'mobile_default' => '0',
			'required'       => true,
			'separator'      => 'after',
			'condition'      => [ $obj->elPrefix . 'layout' => [ 'layout2' ] ],
		];

		$obj->elControls = Fns::filter( $obj->elPrefix . 'end_of_columns_section', $obj );

		$obj->endSection();

		return new static();
	}

	/**
	 * Layout section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function sliderColumns( $obj ) {
		$obj->startSection( 'columns_section', esc_html__( 'Columns', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'           => 'select2',
			'id'             => $obj->elPrefix . 'cols',
			'mode'           => 'responsive',
			'label'          => __( 'Number of Slides Per View', 'tlp-team' ),
			'description'    => __( 'Please select the number of slides per view.', 'tlp-team' ),
			'options'        => Options::scElColumns(),
			'default'        => '0',
			'tablet_default' => '0',
			'mobile_default' => '0',
			'required'       => true,
			'label_block'    => true,
			'separator'      => 'after',
		];

		$obj->elControls = Fns::filter( $obj->elPrefix . 'after_slide_items', $obj );

		$obj->endSection();

		return new static();
	}

	/**
	 * Filtering section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function query( $obj ) {
		$obj->startSection( 'query_section', esc_html__( 'Query', 'tlp-team' ), self::$tab );
		$obj->elHeading( $obj->elPrefix . 'query_note', __( 'Filtering', 'tlp-team' ) );

		$obj->startTabGroup( 'query_tab' );
		$obj->startTab( 'query_include_tab', __( 'Include', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'include_posts',
			'label'       => __( 'Include Team Members', 'tlp-team' ),
			'options'     => Fns::getMemberList(),
			'description' => __( 'Please select the team members to show. Leave it blank to include all posts.', 'tlp-team' ),
			'multiple'    => true,
			'label_block' => true,
		];

		$obj->endTab();
		$obj->startTab( 'query_exclude_tab', __( 'Exclude', 'tlp-team' ) );

		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'exclude_posts',
			'label'       => __( 'Exclude Team Members', 'tlp-team' ),
			'options'     => Fns::getMemberList(),
			'description' => __( 'Please select the team members to exclude. Leave it blank to exclude none.', 'tlp-team' ),
			'multiple'    => true,
			'label_block' => true,
		];

		$obj->endTab();
		$obj->endTabGroup();

		$obj->elControls[] = [
			'type'        => 'number',
			'id'          => $obj->elPrefix . 'posts_limit',
			'label'       => __( 'Posts Limit', 'tlp-team' ),
			'description' => __( 'The number of team members to show. Set empty to show all team members.', 'tlp-team' ),
			'default'     => 12,
		];

		$obj->elHeading( $obj->elPrefix . 'category_note', __( 'Categories', 'tlp-team' ), 'before' );

		$obj->elControls = Fns::filter( $obj->elPrefix . 'query_tax_filter', $obj );

		$obj->elHeading( $obj->elPrefix . 'sorting_note', __( 'Sorting', 'tlp-team' ), 'before' );

		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'posts_order_by',
			'label'       => __( 'Order By', 'tlp-team' ),
			'description' => __( 'Please choose to reorder team members.', 'tlp-team' ),
			'options'     => Options::scOrderBy(),
			'default'     => 'date',
		];

		$obj->elControls[] = [
			'type'        => 'select2',
			'id'          => $obj->elPrefix . 'posts_order',
			'label'       => __( 'Order', 'tlp-team' ),
			'description' => __( 'Please choose to reorder team members.', 'tlp-team' ),
			'options'     => Options::scOrder(),
			'default'     => 'DESC',
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
	public static function pagination( $obj ) {
		$condition = [
			$obj->elPrefix . 'show_pagination' => [ 'yes' ],
			$obj->elPrefix . 'pagination_type' => [ 'pagination' ],
		];

		if ( rttlp_team()->has_pro() ) {
			$condition[ $obj->elPrefix . 'tax_filter!' ]   = [ 'yes' ];
			$condition[ $obj->elPrefix . 'tax_order_by!' ] = [ 'yes' ];
			$condition[ $obj->elPrefix . 'tax_order!' ]    = [ 'yes' ];
		}

		$obj->startSection(
			'pagination_section',
			esc_html__( 'Pagination', 'tlp-team' ),
			self::$tab,
			[],
			[ $obj->elPrefix . 'layout!' => [ 'special01' ] ]
		);

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'show_pagination',
			'label'       => __( 'Enable Pagination?', 'tlp-team' ),
			'description' => __( 'Switch on to enable pagination.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
		];

		$obj->elControls = Fns::filter( $obj->elPrefix . 'after_show_pagination', $obj );

		$obj->elControls[] = [
			'type'        => 'number',
			'id'          => $obj->elPrefix . 'pagination_per_page',
			'label'       => __( 'Number of Posts Per Page', 'tlp-team' ),
			'default'     => 8,
			'description' => __( 'Please enter the number of team members per page to show.', 'tlp-team' ),
			'separator'   => 'before',
			'condition'   => [ $obj->elPrefix . 'show_pagination' => [ 'yes' ] ],
		];

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'show_page_number',
			'label'       => __( 'Show Page Number Text?', 'tlp-team' ),
			'description' => __( 'Switch on to show page number text.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'separator'   => 'before',
			'default'     => 'yes',
			'condition'   => $condition,
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
	public static function isotopPagination( $obj ) {
		$obj->startSection( 'pagination_section', esc_html__( 'Pagination', 'tlp-team' ), self::$tab );

		$obj->elControls = Fns::filter( $obj->elPrefix . 'after_show_isotope_pagination', $obj );

		$obj->endSection();

		return new static();
	}

	/**
	 * Image section
	 *
	 * @param object $obj Reference object.
	 * @return static
	 */
	public static function image( $obj ) {
		$obj->startSection( 'image_section', esc_html__( 'Image', 'tlp-team' ), self::$tab );

		$obj->elControls[] = [
			'type'        => 'switch',
			'id'          => $obj->elPrefix . 'show_featured_image',
			'label'       => __( 'Display Featured Image?', 'tlp-team' ),
			'description' => __( 'Switch on to display featured image.', 'tlp-team' ),
			'label_on'    => __( 'On', 'tlp-team' ),
			'label_off'   => __( 'Off', 'tlp-team' ),
			'default'     => 'yes',
			'separator'   => 'after',
		];

		$obj->elControls[] = [
			'type'            => 'select2',
			'id'              => $obj->elPrefix . 'image',
			'label'           => __( 'Select Image Size', 'tlp-team' ),
			'options'         => Fns::get_image_sizes(),
			'default'         => 'team-thumb',
			'label_block'     => true,
			'separator'       => 'after',
			'content_classes' => 'elementor-descriptor',
			'condition'       => [ $obj->elPrefix . 'show_featured_image' => [ 'yes' ] ],
		];

		$obj->elControls[] = [
			'type'        => 'image-dimensions',
			'id'          => $obj->elPrefix . 'image_custom_dimension',
			'label'       => __( 'Enter Custom Image Size', 'tlp-team' ),
			'label_block' => true,
			'show_label'  => true,
			'condition'   => [
				$obj->elPrefix . 'show_featured_image' => [ 'yes' ],
				$obj->elPrefix . 'image'               => [ 'ttp_custom' ],
			],
		];

		$obj->elControls[] = [
			'type'      => 'select2',
			'id'        => $obj->elPrefix . 'image_crop',
			'label'     => __( 'Image Crop', 'tlp-team' ),
			'options'   => [
				'soft' => esc_html__( 'Soft Crop', 'tlp-team' ),
				'hard' => esc_html__( 'Hard Crop', 'tlp-team' ),
			],
			'default'   => 'hard',
			'condition' => [
				$obj->elPrefix . 'show_featured_image' => [ 'yes' ],
				$obj->elPrefix . 'image'               => [ 'ttp_custom' ],
			],
		];

		$obj->elControls[] = [
			'type'      => 'html',
			'id'        => $obj->elPrefix . 'image_custom_dimension_note',
			'raw'       => __( '<span style="display: block; margin-top: 10px; font-weight: 500; line-height: 1.4;">Please note that, if you enter image size larger than the actual image iteself, the image sizes will fallback to the full image dimension.</span>', 'tlp-team' ),
			'condition' => [
				$obj->elPrefix . 'show_featured_image' => [ 'yes' ],
				$obj->elPrefix . 'image'               => [ 'ttp_custom' ],
			],
			'separator' => 'after',
		];

		$obj->elControls = Fns::filter( $obj->elPrefix . 'end_of_image_section', $obj );

		$obj->endSection();

		return new static();
	}
}
