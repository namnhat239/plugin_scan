<?php
namespace Product_Gallery_Sldier;

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Options {
	public function __construct() {

		$this->pluginOptions();
	}

	public function pluginOptions() {

		// Set a unique slug-like ID
		$prefix = 'wpgs_form';

		\CSF::createOptions( $prefix, array(
			'menu_title'      => 'Product Gallery',
			'menu_slug'       => 'cix-gallery-settings',
			'menu_type'       => 'submenu',
			'menu_parent'     => 'codeixer',
			'framework_title' => 'Product Gallery Slider for WooCommerce <small>by Codeixer</small>',
			'show_footer'     => true,
			'show_bar_menu'   => false,
			'save_defaults'   => true,
			'footer_credit'   => ' ',

		) );

//
		// Create a section
		\CSF::createSection( $prefix, array(
			'title'  => 'General Options',
			'icon'   => 'fas fa-sliders-h',
			'fields' => array(

				array(
					'id'      => 'slider_animation',
					'type'    => 'radio',
					'title'   => 'Slider Animation',
					'class' => 'cix-only-pro',
					'inline'  => true,
					'desc'    => 'Effect Between Product Images',
					'options' => array(
						'false'    => __( 'Slide', 'wpgs' ),
						'only_pro' => __( 'Fade (PRO)', 'wpgs' ),

					),
					'default' => 'false',

				),
				array(
					'id'      => 'slider_lazy_laod',
					'type'    => 'radio',
					'title'   => __( 'Slider Lazy Load', 'wpgs' ),
					'class' => 'cix-only-pro',
					'options' => array(
						'disable'     => __( 'Disable', 'wpgs' ),
						'ondemand'    => __( 'On Demand (PRO)', 'wpgs' ),
						'progressive' => __( 'Progressive (PRO)', 'wpgs' ),
					),
					'default' => 'disable',

					'desc'    => __( 'Useful for Page Loading Speed', 'wpgs' ),
				),
				array(
					'id'    => 'slider_infinity',
					'type'  => 'switcher',
					'title' => __( 'Slide Infinitely', 'wpgs' ),
					'desc'  => __( 'Sliding Infinite Loop', 'wpgs' ),
				),
				array(
					'id'      => 'slider_adaptiveHeight',
					'type'    => 'switcher',
					'title'   => __( 'Slide Adaptive Height', 'wpgs' ),
					'default' => true,
					'desc'    => __( 'Resize the Gallery Section Height to Match the Image Height', 'wpgs' ),
				),
				array(
					'id'      => 'slider_alt_text',
					'type'    => 'switcher',
					'default' => false,
					'class' => 'cix-only-pro',
					'title'   => __( 'Slide Image Caption (PRO)', 'wpgs' ),
					'desc'    => __( 'Display Image Caption / Title Text Under the Image.', 'wpgs' ),

				),

				array(
					'id'    => 'slider_dragging',
					'type'  => 'switcher',
					'title' => __( 'Mouse Dragging', 'wpgs' ),
					'desc'  => __( 'Move Slide on Mouse Dragging ', 'wpgs' ),
				),
				array(
					'id'    => 'slider_autoplay',
					'type'  => 'switcher',
					'title' => __( 'Slider Autoplay', 'wpgs' ),

				),
				array(
					'id'         => 'slider_autoplay_pause',
					'type'       => 'switcher',
					'title'      => __( 'Pause Autoplay', 'wpgs' ),
					'desc'       => __( 'Pause Autoplay when the Mouse Hovers Over the Product Image or Dots.', 'wpgs' ),
					'dependency' => array( 'slider_autoplay', '==', 'true' ),
					'default'    => true,
				),
				array(

					'id'         => 'autoplay_timeout',
					'type'       => 'slider',
					'title'      => 'Autoplay Speed',
					'min'        => 1000,
					'max'        => 10000,
					'step'       => 1000,
					'unit'       => 'ms',
					'default'    => 4000,
					'desc'       => __( '1000 ms = 1 second', 'wpgs' ),

					'dependency' => array( 'slider_autoplay', '==', 'true' ),
				),
				array(
					'id'    => 'dots',
					'type'  => 'switcher',
					'title' => __( 'Dots', 'wpgs' ),
					'desc'  => __( 'Enable Dots/Bullets for Product Image', 'wpgs' ),
				),
				array(
					'id'      => 'slider_nav',
					'type'    => 'switcher',
					'title'   => __( 'Navigation Arrows', 'wpgs' ),
					'desc'    => __( 'Enable Navigation Arrows for Product Image Slider', 'wpgs' ),
					'default' => true,
				),

				array(
					'id'         => 'slider_nav_animation',
					'type'       => 'switcher',
					'class' => 'cix-only-pro',
					'title'      => __( 'Arrows Animation (PRO)', 'wpgs' ),
					'desc'       => __( 'Enable Animation Slide effect for Appearing Arrows', 'wpgs' ),
					'default'    => false,
					'dependency' => array( 'slider_nav', '==', 'true' ),

				),
				array(
					'id'          => 'slider_nav_color',
					'type'        => 'color',
					'title'       => __( 'Arrows Color', 'wpgs' ),
					'desc'        => __( 'Set Arrows Color', 'wpgs' ),
					'default'     => '#000',
					'output_mode' => 'color',
					'output'      => '.wpgs-for .slick-arrow::before,.wpgs-nav .slick-prev::before, .wpgs-nav .slick-next::before',
					'dependency'  => array( 'slider_nav', '==', 'true' ),
				),

			),
		) );

//
		// Create a section
		\CSF::createSection( $prefix, array(
			'title'  => 'Lightbox Options',
			'icon'   => 'fas fa-expand',
			'fields' => array(

				array(
					'id'      => 'lightbox_picker',
					'type'    => 'switcher',
					'default' => true,
					'desc'    => esc_html__( 'Lightbox Feature on Product Image ', 'wpgs' ),
					'title'   => __( 'Image Lightbox', 'wpgs' ),
				),

				array(
					'id'         => 'lightbox_thumb_axis',
					'type'       => 'radio',
					'title'      => __( 'Lightbox Thumbnails Position', 'wpgs' ),
					'class' => 'cix-only-pro',
					'options'    => array(
						'y' => __( 'Vertical', 'wpgs' ),
						'x' => __( 'Horizontal (PRO)', 'wpgs' ),
					),

					'default'    => 'y',
					'dependency' => array( 'lightbox_picker', '==', 'true' ),
					'desc'       => __( 'Select Lightbox Thumbnails Position.', 'wpgs' ),

				),
				array(
					'id'         => 'lightbox_thumb_autoStart',
					'dependency' => array( 'lightbox_picker', '==', 'true' ),
					'type'       => 'switcher',
					'class' => 'cix-only-pro',
					'title'      => 'Lightbox Thumbnail Autostart (PRO)',

				),
				array(
					'id'          => 'lightbox_oc_effect',
					'type'        => 'select',
					'class' => 'cix-only-pro',
					'title'       => __( 'Lightbox Animation (PRO)', 'wpgs' ),
					'desc'        => __( 'Select Lightbox Open/close Animation Effect', 'wpgs' ),
					'placeholder' => 'Select an option',
					'dependency'  => array( 'lightbox_picker', '==', 'true' ),
					'options'     => array(
						'fade' => __( 'Fade', 'wpgs' ),
						'1'    => __( 'Slide (PRO)', 'wpgs' ),
						'2'    => __( 'Rotate (PRO)', 'wpgs' ),
						'3'    => __( 'Circular (PRO)', 'wpgs' ),
						'4'    => __( 'Tube (PRO)', 'wpgs' ),
						'5'    => __( 'Zoom In Out (PRO)', 'wpgs' ),
						''     => __( 'None', 'wpgs' ),
					),
					'default'     => 'fade',
				),
				array(
					'id'          => 'lightbox_slide_effect',
					'type'        => 'select',
					'class' => 'cix-only-pro',
					'title'       => __( 'Slide Animation (PRO)', 'wpgs' ),
					'desc'        => __( 'Select Lightbox Slide Animation Effect', 'wpgs' ),
					'placeholder' => 'Select an option',
					'dependency'  => array( 'lightbox_picker', '==', 'true' ),
					'options'     => array(
						'fade' => __( 'Fade', 'wpgs' ),
						'1'    => __( 'Slide (PRO)', 'wpgs' ),
						'2'    => __( 'Rotate (PRO)', 'wpgs' ),
						'3'    => __( 'Circular (PRO)', 'wpgs' ),
						'4'    => __( 'Tube (PRO)', 'wpgs' ),
						'5'    => __( 'Zoom In Out (PRO)', 'wpgs' ),
						''     => __( 'None', 'wpgs' ),
					),
					'default'     => 'fade',
				),
				array(
					'id'          => 'lightbox_bg',
					'type'        => 'color',
					'title'       => __( 'Lightbox Background', 'wpgs' ),
					'desc'        => __( 'Set Lightbox Background Color', 'wpgs' ),
					'default'     => 'rgba(10,0,0,0.75)',
					'output_mode' => 'background-color',
					'output'      => '.fancybox-bg',
					'dependency'  => array( 'lightbox_picker', '==', 'true' ),
				),
				array(
					'id'          => 'lightbox_txt_color',
					'type'        => 'color',
					'title'       => __( 'Lightbox Text Color', 'wpgs' ),
					'desc'        => __( 'Set Lightbox Text Color', 'wpgs' ),
					'default'     => '#fff',
					'output_mode' => 'color',
					'output'      => '.fancybox-caption,.fancybox-infobar',
					'dependency'  => array( 'lightbox_picker', '==', 'true' ),
				),
				array(
					'id'         => 'lightbox_img_count',
					'type'       => 'switcher',
					'default'    => true,
					'title'      => __( 'Display image count', 'wpgs' ),
					'desc'       => __( 'Display image count on top corner.', 'wpgs' ),
					'dependency' => array( 'lightbox_picker', '==', 'true' ),
				),

				array(
					'id'         => 'lightbox_icon_color',
					'type'       => 'color',
					'title'      => __( 'Icon Color', 'wpgs' ),
					'desc'       => __( 'Set lightbox icon color', 'wpgs' ),
					'default'    => '#fff',
					'dependency' => array( 'lightbox_icon|lightbox_picker', '!=|==', 'none|true' ),
				),
				array(
					'id'         => 'lightbox_icon_bg_color',
					'type'       => 'color',
					'title'      => __( 'Icon Background', 'wpgs' ),
					'desc'       => __( 'Set icon background color', 'wpgs' ),
					'default'    => '#000',
					'dependency' => array( 'lightbox_icon|lightbox_picker', '!=|==', 'none|true' ),
				),

			),
		) );
// Create a section
		\CSF::createSection( $prefix, array(
			'title'  => 'Zoom Options',
			'icon'   => 'fas fa-search-plus',
			'fields' => array(

				// A textarea field
				array(
					'id'      => 'image_zoom',
					'type'    => 'switcher',
					'default' => true,
					'title'   => __( 'Zoom', 'wpgs' ),
					'desc'    => __( 'Enable Zoom Feature for Product Image.', 'wpgs' ),

				),

			),
		) );
// Create a top-tab
		\CSF::createSection( $prefix, array(
			'id'    => 'thumbnail_tab', // Set a unique slug-like ID
			'title' => 'Thumbnails Options',
			'icon'  => 'fas fa-image',
		) );
// Create a section
		\CSF::createSection( $prefix, array(
			'parent' => 'thumbnail_tab', // The slug id of the parent section
			'title'  => 'Desktop',
			'fields' => array(

				array(
					'id'          => 'thumb_position',
					'type'        => 'select',
					'class' => 'cix-only-pro',
					'title'       => __( 'Thumbnails Position (PRO)', 'wpgs' ),
					'placeholder' => 'Select an option',
					'options'     => array(
						'bottom' => __( 'Bottom', 'wpgs' ),
						'left'   => __( 'Left (PRO)', 'wpgs' ),
						'right'  => __( 'Right (PRO)', 'wpgs' ),
					),
					'default'     => 'bottom',
					'desc'        => __( 'Select Thumbnails Position.', 'wpgs' ),

				),
				array(
					'id'    => 'thumbnails_lightbox',
					'type'  => 'switcher',
					'title' => __( 'LightBox For Thumbnails (PRO)', 'wpgs' ),
					'class' => 'cix-only-pro',
					'desc'  => __( 'Open Lightbox When click Thumbnails', 'wpgs' ),

				),
				array(
					'id'      => 'thumb_to_show',
					'type'    => 'number',
					'title'   => __( 'Thumbnails To Show', 'wpgs' ),
					'desc'    => __( 'Set the Number of Thumbnails to Display', 'wpgs' ),
					'default' => 4,

				),
				array(
					'id'      => 'thumb_scroll_by',
					'type'    => 'number',
					'title'   => __( 'Thumbnails Scroll By', 'wpgs' ),
					'desc'    => __( 'Set the Number of Thumbnails to Scroll when an Arrow is Clicked.', 'wpgs' ),
					'default' => 1,

				),

				array(
					'id'      => 'thumb_nav',
					'type'    => 'switcher',
					'default' => true,
					'title'   => __( 'Thumbnails Arrows', 'wpgs' ),

					'desc'    => __( 'Show Navigation Arrows for thumbnails.', 'wpgs' ),

				),
				array(
					'id'      => 'thumbnails_layout',
					'type'    => 'image_select',
					'title'   => 'Thumbnails Layout',
					'class'   => 'image_picker_image',
					'options' => array(

						'border' => WPGS_ROOT_URL . '/assets/img/border.png',

					),
					'default' => 'border',

				),

				array(
					'id'      => 'thumb_border_non_active_color',
					'type'    => 'color',
					'title'   => __( 'Non-Active Thumbnail Border', 'wpgs' ),
					'desc'    => __( 'Set Non-Active Thumbnail Border', 'wpgs' ),
					'default' => 'transparent',
					'output' => array( 'border-color' => '.wpgs-nav .slick-slide' )

				),
				array(
					'id'      => 'thumb_border_active_color',
					'type'    => 'color',
					'title'   => __( 'Active Thumbnail Border', 'wpgs' ),
					'desc'    => __( 'Set Active Thumbnails Border', 'wpgs' ),
					'default' => '#000',
					'output' => array( 'border-color' => '.wpgs-nav .slick-current' )

				),

			),
		) );
		\CSF::createSection( $prefix, array(
			'parent' => 'thumbnail_tab', // The slug id of the parent section
			'title'  => 'Tablet',
			'fields' => array(
				array(
					'type'    => 'heading',
					'content' => 'Tablet : Screen width from 768px to 1024px',
				),

				array(
					'id'      => 'thumbnails_tabs_thumb_to_show',
					'type'    => 'number',
					'title'   => __( 'Thumbnails To Show', 'wpgs' ),
					'desc'    => __( 'Set the Number of Thumbnails to Display', 'wpgs' ),
					'default' => 4,

				),
				array(
					'id'      => 'thumbnails_tabs_thumb_scroll_by',
					'type'    => 'number',
					'title'   => __( 'Thumbnails Scroll By', 'wpgs' ),
					'desc'    => __( 'Set the Number of Thumbnails to Scroll when an Arrow is Clicked.', 'wpgs' ),
					'default' => 1,

				),

			),
		) );
		\CSF::createSection( $prefix, array(
			'parent' => 'thumbnail_tab', // The slug id of the parent section
			'title'  => 'Smartphone',
			'fields' => array(
				array(
					'type'    => 'heading',
					'content' => 'SmartPhones : Screen width less than  768px',
				),

				array(
					'id'      => 'thumbnails_mobile_thumb_to_show',
					'type'    => 'number',
					'title'   => __( 'Thumbnails To Show', 'wpgs' ),
					'desc'    => __( 'Set the Number of Thumbnails to Display', 'wpgs' ),
					'default' => 4,

				),
				array(
					'id'      => 'thumbnails_mobile_thumb_scroll_by',
					'type'    => 'number',
					'title'   => __( 'Thumbnails Scroll By', 'wpgs' ),
					'desc'    => __( 'Set the Number of Thumbnails to Scroll when an Arrow is Clicked.', 'wpgs' ),
					'default' => 1,

				),

			),
		) );
// Create a section
		\CSF::createSection( $prefix, array(
			'title'  => 'Advanced Options',
			'icon'   => 'fas fa-cog',
			'fields' => array(

				array(
					'id'          => 'slider_image_size',
					'type'        => 'image_sizes',
					'title'       => __( 'Main Image Size', 'wpgs' ),
					'default'	=> 'shop_single',
				),
				array(
					'id'          => 'thumbnail_image_size',
					'type'        => 'image_sizes',
					'title'       => __( 'Thumbnail Image Size', 'wpgs' ),
					'default'	=> 'medium',
				),

				array(
					'id'       => 'custom_css',
					'type'     => 'code_editor',
					'title'    => 'Custom CSS',
					'desc'     => 'Add your custom CSS here',
					'settings' => array(
						'theme' => 'mbo',
						'mode'  => 'css',
					),

					'sanitize' => false,
				),

			),
		) );

		\CSF::createSection( $prefix, array(
			'title'  => 'Backup Settings',
			'icon'   => 'fas fa-sync',
			'fields' => array(

				array(
					'type' => 'backup',
				),

			),
		) );
	}
}
