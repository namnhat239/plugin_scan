<?php
/**
 * The plugin gutenberg block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      2.5.4
 *
 * @package    woo-product-slider-free
 * @subpackage woo-product-slider-free/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WooProductSlider\Admin\GutenbergBlock;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gutenberg_Init class.
 */
class Gutenberg_Init {
	/**
	 * Script and style suffix
	 *
	 * @since 2.5.4
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * Custom Gutenberg Block Initializer.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'woo_product_slider_free_gutenberg_shortcode_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'woo_product_slider_free_block_editor_assets' ) );
	}

	/**
	 * Register block editor script for backend.
	 */
	public function woo_product_slider_free_block_editor_assets() {
		wp_enqueue_script(
			'woo-product-slider-free-shortcode-block',
			plugins_url( '/GutenbergBlock/build/index.js', dirname( __FILE__ ) ),
			array( 'jquery' ),
			SP_WPS_VERSION,
			true
		);

		/**
		* Register block editor css file enqueue for backend.
		*/
		wp_enqueue_style( 'sp-wps-slick', esc_url( SP_WPS_URL . 'Frontend/assets/css/slick.min.css' ), array(), SP_WPS_VERSION );
		wp_enqueue_style( 'sp-wps-font-awesome', esc_url( SP_WPS_URL . 'Frontend/assets/css/font-awesome.min.css' ), array(), SP_WPS_VERSION );
		wp_enqueue_style( 'sp-wps-style', esc_url( SP_WPS_URL . 'Frontend/assets/css/style.min.css' ), array(), SP_WPS_VERSION );
		wp_enqueue_style( 'sp-wps-style-dep', esc_url( SP_WPS_URL . 'Frontend/assets/css/style-deprecated.min.css' ), array(), SP_WPS_VERSION );
	}

	/**
	 * Shortcode list.
	 *
	 * @return array
	 */
	public function woo_product_slider_free_post_list() {
		$shortcodes = get_posts(
			array(
				'post_type'      => 'sp_wps_shortcodes',
				'post_status'    => 'publish',
				'posts_per_page' => 9999,
			)
		);

		if ( count( $shortcodes ) < 1 ) {
			return array();
		}

		return array_map(
			function ( $shortcode ) {
					return (object) array(
						'id'    => absint( $shortcode->ID ),
						'title' => esc_html( $shortcode->post_title ),
					);
			},
			$shortcodes
		);
	}

	/**
	 * Register Gutenberg shortcode block.
	 */
	public function woo_product_slider_free_gutenberg_shortcode_block() {
		/**
		 * Register block editor js file enqueue for backend.
		 */
		wp_register_script( 'sp-wps-slick-min-js', esc_url( SP_WPS_URL . 'Frontend/assets/js/slick.min.js' ), array( 'jquery' ), SP_WPS_VERSION, false );
		wp_register_script( 'sp-wps-slick-config-js', esc_url( SP_WPS_URL . 'Frontend/assets/js/slick-config.min.js' ), array( 'jquery' ), SP_WPS_VERSION, false );

		wp_localize_script(
			'sp-wps-slick-config-js',
			'sp_wps_load_script',
			array(
				'path'          => SP_WPS_URL,
				'loadScript'    => SP_WPS_URL . 'Frontend/assets/js/slick-config.min.js',
				'url'           => admin_url( 'post-new.php?post_type=sp_wps_shortcodes' ),
				'shortCodeList' => $this->woo_product_slider_free_post_list(),
			)
		);

		/**
		 * Register Gutenberg block on server-side.
		 */
		register_block_type(
			'woo-product-slider-pro/shortcode',
			array(
				'attributes'      => array(
					'shortcode'          => array(
						'type'    => 'string',
						'default' => '',
					),
					'showInputShortcode' => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'preview'            => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'is_admin'           => array(
						'type'    => 'boolean',
						'default' => is_admin(),
					),
				),
				'example'         => array(
					'attributes' => array(
						'preview' => true,
					),
				),
				// Enqueue blocks.editor.build.js in the editor only.
				'editor_script'   => array(
					'sp-wps-slick-min-js',
					'sp-wps-slick-config-js',
					'sp-wpsp-infinite-scroll-js',
					'sp-wpsp-bxslider-min-js',
					'sp-wpsp-magnific-popup-min-js',
					'jquery-masonry',
				),
				// Enqueue blocks.editor.build.css in the editor only.
				'editor_style'    => array(),
				'render_callback' => array( $this, 'woo_product_slider_free_render_shortcode' ),
			)
		);
	}

	/**
	 * Render callback.
	 *
	 * @param string $attributes ShortCode.
	 * @return string
	 */
	public function woo_product_slider_free_render_shortcode( $attributes ) {

		if ( ! $attributes['is_admin'] ) {
			return do_shortcode( '[woo_product_slider id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' );
		}

		$post_id        = $attributes['shortcode'];
		$dynamic_style  = '';
		$shortcode_data = get_post_meta( $post_id, 'sp_wps_shortcode_options', true );
		require SP_WPS_PATH . 'Frontend/views/partials/dynamic-style.php';

		$style = '<style>' . $dynamic_style . '</style>';
		return $style . ' <div id="' . uniqid() . '">' . do_shortcode( '[woo_product_slider id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
	}
}
