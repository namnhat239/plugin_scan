<?php
/**
 * The Frontend class to manage all public-facing functionality of the plugin.
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WooProductSlider\Frontend;

use ShapedPlugin\WooProductSlider\Frontend\Helper;

/**
 * The Frontend class to manage all public facing stuffs.
 */
class Frontend {

	/**
	 * Class Construct
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_shortcode( 'woo_product_slider', array( $this, 'wps_shortcode' ) );
		// Deprecated old shortcode support.
		add_shortcode( 'woo-product-slider', array( $this, 'sp_woo_product_slider_old_shortcode' ) );
	}

	/**
	 * Plugin Scripts and Styles
	 */
	public function front_scripts() {
		// CSS Files.
		wp_register_style( 'sp-wps-slick', esc_url( SP_WPS_URL . 'Frontend/assets/css/slick.min.css' ), array(), SP_WPS_VERSION );
		wp_register_style( 'sp-wps-font-awesome', esc_url( SP_WPS_URL . 'Frontend/assets/css/font-awesome.min.css' ), array(), SP_WPS_VERSION );
		wp_register_style( 'sp-wps-style', esc_url( SP_WPS_URL . 'Frontend/assets/css/style.min.css' ), array(), SP_WPS_VERSION );
		wp_register_style( 'sp-wps-style-dep', esc_url( SP_WPS_URL . 'Frontend/assets/css/style-deprecated.min.css' ), array(), SP_WPS_VERSION );
		$custom_css = trim( html_entity_decode( get_option( 'sp_woo_product_slider_options' )['custom_css'] ) );
		wp_add_inline_style( 'sp-wps-style', $custom_css );

		// JS Files.
		wp_register_script( 'sp-wps-slick-min-js', esc_url( SP_WPS_URL . 'Frontend/assets/js/slick.min.js' ), array( 'jquery' ), SP_WPS_VERSION, false );
		wp_register_script( 'sp-wps-slick-config-js', esc_url( SP_WPS_URL . 'Frontend/assets/js/slick-config.min.js' ), array( 'jquery' ), SP_WPS_VERSION, false );
		$style_load_in_header = apply_filters( 'sp_product_slider_style_load_in_header', true );
		$setting_options      = get_option( 'sp_woo_product_slider_options' );
		/**
		 * Enqueue style.
		 */
		if ( $style_load_in_header ) {
			if ( $setting_options['enqueue_font_awesome'] ) {
				wp_enqueue_style( 'sp-wps-font-awesome' );
			}
			if ( $setting_options['enqueue_slick_css'] ) {
				wp_enqueue_style( 'sp-wps-slick' );
			}
			wp_enqueue_style( 'sp-wps-style' );
			$wpsp_posts    = new \WP_Query(
				array(
					'post_type'      => 'sp_wps_shortcodes',
					'post_status'    => 'publish',
					'posts_per_page' => 1000,
				)
			);
			$post_ids      = wp_list_pluck( $wpsp_posts->posts, 'ID' );
			$dynamic_style = '';
			foreach ( $post_ids as $post_id ) {
				$shortcode_data = get_post_meta( $post_id, 'sp_wps_shortcode_options', true );
				require SP_WPS_PATH . 'Frontend/views/partials/dynamic-style.php';
			}
			wp_add_inline_style( 'sp-wps-style', Helper::minify_output( $dynamic_style ) );
		}

	}

	/**
	 * Live preview Scripts and Styles
	 */
	public function admin_scripts() {
		$current_screen            = get_current_screen();
			$the_current_post_type = $current_screen->post_type;
		if ( 'sp_wps_shortcodes' === $the_current_post_type ) {
			// CSS Files.
			wp_enqueue_style( 'sp-wps-slick', esc_url( SP_WPS_URL . 'Frontend/assets/css/slick.min.css' ), array(), SP_WPS_VERSION );
			wp_enqueue_style( 'sp-wps-font-awesome', esc_url( SP_WPS_URL . 'Frontend/assets/css/font-awesome.min.css' ), array(), SP_WPS_VERSION );
			wp_enqueue_style( 'sp-wps-style', esc_url( SP_WPS_URL . 'Frontend/assets/css/style.min.css' ), array(), SP_WPS_VERSION );
			wp_enqueue_style( 'sp-wps-style-dep', esc_url( SP_WPS_URL . 'Frontend/assets/css/style-deprecated.min.css' ), array(), SP_WPS_VERSION );
			$custom_css = trim( html_entity_decode( get_option( 'sp_woo_product_slider_options' )['custom_css'] ) );
			wp_add_inline_style( 'sp-wps-style', $custom_css );

			// JS Files.
			wp_enqueue_script( 'sp-wps-slick-min-js', esc_url( SP_WPS_URL . 'Frontend/assets/js/slick.min.js' ), array( 'jquery' ), SP_WPS_VERSION, false );
		}
	}

	/**
	 * Shortcode
	 *
	 * @param array $attributes shortcode attributes.
	 *
	 * @return string
	 */
	public function wps_shortcode( $attributes ) {
		shortcode_atts(
			array(
				'id' => '',
			),
			$attributes,
			'woo_product_slider'
		);
		$style_load_in_header = apply_filters( 'sp_product_slider_style_load_in_header', true );
		$setting_options      = get_option( 'sp_woo_product_slider_options' );
		$post_id              = $attributes['id'];
		$shortcode_data       = get_post_meta( $post_id, 'sp_wps_shortcode_options', true );
		$main_section_title   = get_the_title( $post_id );
		ob_start();
		if ( ! $style_load_in_header ) {
			if ( $setting_options['enqueue_font_awesome'] ) {
				wp_enqueue_style( 'sp-wps-font-awesome' );
			}
			if ( $setting_options['enqueue_slick_css'] ) {
				wp_enqueue_style( 'sp-wps-slick' );
			}
			wp_enqueue_style( 'sp-wps-style' );
			/**
			 * Dynamic style.
			 */
			$dynamic_style = '';
			require SP_WPS_PATH . 'Frontend/views/partials/dynamic-style.php';
			wp_add_inline_style( 'sp-wps-style', Helper::minify_output( $dynamic_style ) );
		}
		if ( $setting_options['enqueue_slick_js'] ) {
			wp_enqueue_script( 'sp-wps-slick-min-js' );
		}
		wp_enqueue_script( 'sp-wps-slick-config-js' );
		Helper::spwps_html_show( $post_id, $shortcode_data, $main_section_title );
		return ob_get_clean();
	}

	/**
	 * Old Shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public function sp_woo_product_slider_old_shortcode( $atts ) {
		$shortcode_atts = shortcode_atts(
			array(
				'id'            => '01',
				'title'         => '',
				'color'         => '#e74c3c',
				'pagination'    => 'true',
				'nav'           => 'true',
				'auto_play'     => 'true',
				'items'         => '4',
				'stop_on_hover' => 'true',
				'rating'        => 'true',
			),
			$atts,
			'woo-product-slider'
		);

		$id            = $shortcode_atts['id'];
		$title         = $shortcode_atts['title'];
		$color         = $shortcode_atts['color'];
		$pagination    = $shortcode_atts['pagination'];
		$nav           = $shortcode_atts['nav'];
		$auto_play     = $shortcode_atts['auto_play'];
		$items         = $shortcode_atts['items'];
		$stop_on_hover = $shortcode_atts['stop_on_hover'];
		$rating        = $shortcode_atts['rating'];

		$args            = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
		);
		$shortcode_query = new \WP_Query( $args );

		$outline = '';

		$outline .= '<style>
			div#sp-woo-product-slider-free' . $id . '.wpsf-product-section .slick-arrow:hover,
			div.wpsf-slider-section .wpsf-cart-button a:hover,
			div#sp-woo-product-slider-free' . $id . '.wpsf-product-section ul.slick-dots li button{background-color: ' . $color . '; border-color: ' . $color . '; color: #fff; }
			div.wpsf-slider-section .wpsf-product-title a:hover{
				color: ' . $color . ';
			}
			</style>';
		$outline .= '
			<script type="text/javascript">
					jQuery(document).ready(function() {
					jQuery("#sp-woo-product-slider-free' . $id . '").slick({
						infinite: true,
						dots: ' . $pagination . ',
						pauseOnHover: ' . $stop_on_hover . ',
						slidesToShow: ' . $items . ',
						slidesToScroll: 1,
						autoplay: ' . $auto_play . ',
						arrows: ' . $nav . ',
						prevArrow: "<div class=\'slick-prev\'><i class=\'fa fa-angle-left\'></i></div>",
						nextArrow: "<div class=\'slick-next\'><i class=\'fa fa-angle-right\'></i></div>",
						responsive: [
								{
								breakpoint: 1000,
								settings: {
									slidesToShow: 3
								}
								},
								{
								breakpoint: 700,
								settings: {
									slidesToShow: 2
								}
								},
								{
								breakpoint: 460,
								settings: {
									slidesToShow: 1
								}
								}
							]
					});

				});
			</script>';

		wp_enqueue_style( 'sp-wps-slick' );
		wp_enqueue_style( 'sp-wps-font-awesome' );
		wp_enqueue_style( 'sp-wps-style-dep' );

		wp_enqueue_script( 'sp-wps-slick-min-js' );

		$outline .= '<div class="wpsf-slider-section">';
		if ( '' !== $title ) {
			$outline .= '<h2 class="wpsf-section-title">' . $title . '</h2>';
		}

		$outline .= '<div id="sp-woo-product-slider-free' . $id . '" class="wpsf-product-section">';

		if ( $shortcode_query->have_posts() ) {
			while ( $shortcode_query->have_posts() ) :
				$shortcode_query->the_post();
				global $product;

				$outline .= '<div class="wpsf-product">';
				$outline .= '<a href="' . esc_url( get_the_permalink() ) . '">';
				if ( has_post_thumbnail( $shortcode_query->post->ID ) ) {
					$outline .= get_the_post_thumbnail( $shortcode_query->post->ID, 'shop_catalog_image_size', array( 'class' => 'wpsf-product-img' ) );
				} else {
					$outline .= '<img id="place_holder_thm" src="' . wc_placeholder_img_src() . '" alt="Placeholder" />';
				}
				$outline .= '</a>';
				$outline .= '<div class="wpsf-product-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></div>';

				$price_html = $product->get_price_html();
				if ( class_exists( 'WooCommerce' ) && $price_html ) {
					$outline .= '<div class="wpsf-product-price">' . $price_html . '</div>';
				}

				if ( class_exists( 'WooCommerce' ) && true === $rating ) {
					$average = $product->get_average_rating();
					if ( $average > 0 ) {
						$outline .= '<div class="star-rating" title="' . esc_html__( 'Rated', 'woo-product-slider' ) . ' ' . $average . '' . esc_html__( ' out of 5', 'woo-product-slider' ) . '"><span style="width:' . ( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">' . $average . '</strong> ' . esc_html__( 'out of 5', 'woo-product-slider' ) . '</span></div>';
					}
				}

				$outline .= '<div class="wpsf-cart-button">' . do_shortcode( '[add_to_cart id="' . get_the_ID() . '"]' ) . '</div>';
				$outline .= '</div>';

				endwhile;
		} else {
			$outline .= '<h2 class="sp-not-found-any-product-f">' . __( 'No products found', 'woo-product-slider' ) . '</h2>';
		}
		$outline .= '</div>';
		$outline .= '</div>';

		wp_reset_postdata();

		return $outline;

	}

}
