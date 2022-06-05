<?php
/**
 * The Helper class to manage all public facing stuffs.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package woo-product-slider-pro.
 * @subpackage woo-product-slider-pro/Frontend.
 */

namespace ShapedPlugin\WooProductSlider\Frontend;

/**
 * Helper
 */
class Helper {

	/**
	 * Custom Template locator.
	 *
	 * @param  mixed $template_name template name.
	 * @param  mixed $template_path template path.
	 * @param  mixed $default_path default path.
	 * @return string
	 */
	public static function wps_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = 'woo-product-slider/templates';
		}
		if ( ! $default_path ) {
			$default_path = SP_WPS_PATH . 'Frontend/views/templates/';
		}
		$template = locate_template( trailingslashit( $template_path ) . $template_name );
		// Get default template.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}
		// Return what we found.
		return $template;
	}

	/**
	 * Minify output
	 *
	 * @param  string $html output minifier.
	 * @return statement
	 */
	public static function minify_output( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}
	/**
	 * Minify output
	 *
	 * @param  string $html output minifier.
	 * @return statement
	 */
	public static function minify_with_space( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), ' ', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}
	/**
	 * Product custom query
	 *
	 * @param  mixed $product_order_by product order by.
	 * @param  mixed $product_type product type.
	 * @param  mixed $number_of_total_products how many product to show.
	 * @param  mixed $hide_out_of_stock_product hide out of stock product from query.
	 * @param  mixed $product_order product ordering.
	 * @return object
	 */
	public static function spwps_product_query( $product_order_by, $product_type, $number_of_total_products, $hide_out_of_stock_product, $product_order ) {
		$arg = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'orderby'        => $product_order_by,
			'order'          => 'DESC',
			'fields'         => 'ids',
			'posts_per_page' => $number_of_total_products,
		);
		if ( 'featured_products' === $product_type ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			$arg['tax_query'][]          = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['featured'],
			);
		}
		if ( $hide_out_of_stock_product ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			$arg['tax_query'][]          = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['outofstock'],
				'operator' => 'NOT IN',
			);
		}

		$viewed_products = get_posts(
			$arg
		);

		$args = array();
		if ( $viewed_products ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'orderby'        => $product_order_by,
				'order'          => $product_order,
				'post__in'       => $viewed_products,
				'posts_per_page' => $number_of_total_products,
			);
		}

		return new \WP_Query( $args );
	}

	/**
	 * Full html show.
	 *
	 * @param array $post_id Shortcode ID.
	 * @param array $shortcode_data get all meta options.
	 * @param array $main_section_title shows section title.
	 */
	public static function spwps_html_show( $post_id, $shortcode_data, $main_section_title ) {
		$setting_options = get_option( 'sp_woo_product_slider_options' );
		// General Settings.
		$theme_style               = isset( $shortcode_data['theme_style'] ) ? $shortcode_data['theme_style'] : 'theme_one';
		$product_type              = isset( $shortcode_data['product_type'] ) ? $shortcode_data['product_type'] : 'latest_products';
		$number_of_total_products  = isset( $shortcode_data['number_of_total_products'] ) ? $shortcode_data['number_of_total_products'] : 16;
		$hide_out_of_stock_product = isset( $shortcode_data['hide_out_of_stock_product'] ) ? $shortcode_data['hide_out_of_stock_product'] : false;
		$number_of_column          = isset( $shortcode_data['number_of_column'] ) ? $shortcode_data['number_of_column'] : array(
			'number1' => '4',
			'number2' => '3',
			'number3' => '2',
			'number4' => '1',
		);
		$product_order_by          = isset( $shortcode_data['product_order_by'] ) ? $shortcode_data['product_order_by'] : 'date';
		$product_order             = isset( $shortcode_data['product_order'] ) ? $shortcode_data['product_order'] : 'DESC';
		$preloader                 = isset( $shortcode_data['preloader'] ) ? $shortcode_data['preloader'] : false;

		// Slider Controls.
		$auto_play         = isset( $shortcode_data['carousel_auto_play'] ) && $shortcode_data['carousel_auto_play'] ? 'true' : 'false';
		$auto_play_speed   = isset( $shortcode_data['carousel_auto_play_speed'] ) ? $shortcode_data['carousel_auto_play_speed'] : 3000;
		$scroll_speed      = isset( $shortcode_data['carousel_scroll_speed'] ) ? $shortcode_data['carousel_scroll_speed'] : 600;
		$pause_on_hover    = isset( $shortcode_data['carousel_pause_on_hover'] ) && $shortcode_data['carousel_pause_on_hover'] ? 'true' : 'false';
		$carousel_infinite = isset( $shortcode_data['carousel_infinite'] ) && $shortcode_data['carousel_infinite'] ? 'true' : 'false';
		$rtl_mode          = isset( $shortcode_data['rtl_mode'] ) && $shortcode_data['rtl_mode'] ? 'true' : 'false';
		$the_rtl           = ( 'true' === $rtl_mode ) ? ' dir="rtl"' : ' dir="ltr"';
		$navigation_data   = isset( $shortcode_data['navigation_arrow'] ) ? $shortcode_data['navigation_arrow'] : '';
		switch ( $navigation_data ) {
			case 'true':
				$navigation        = 'true';
				$navigation_mobile = 'true';
				break;
			case 'hide_on_mobile':
				$navigation        = 'true';
				$navigation_mobile = 'false';
				break;
			default:
				$navigation        = 'false';
				$navigation_mobile = 'false';
		}
		$pagination_data = isset( $shortcode_data['pagination'] ) ? $shortcode_data['pagination'] : '';
		switch ( $pagination_data ) {
			case 'true':
				$pagination        = 'true';
				$pagination_mobile = 'true';
				break;
			case 'hide_on_mobile':
				$pagination        = 'true';
				$pagination_mobile = 'false';
				break;
			default:
				$pagination        = 'false';
				$pagination_mobile = 'false';
		}

		$carousel_swipe     = isset( $shortcode_data['carousel_swipe'] ) && $shortcode_data['carousel_swipe'] ? 'true' : 'false';
		$carousel_draggable = isset( $shortcode_data['carousel_draggable'] ) && $shortcode_data['carousel_draggable'] ? 'true' : 'false';

		// Display Options.
		$slider_title       = isset( $shortcode_data['slider_title'] ) ? $shortcode_data['slider_title'] : false;
		$product_name       = isset( $shortcode_data['product_name'] ) ? $shortcode_data['product_name'] : true;
		$product_price      = isset( $shortcode_data['product_price'] ) ? $shortcode_data['product_price'] : true;
		$product_rating     = isset( $shortcode_data['product_rating'] ) ? $shortcode_data['product_rating'] : true;
		$add_to_cart_button = isset( $shortcode_data['add_to_cart_button'] ) ? $shortcode_data['add_to_cart_button'] : true;

		// Image Settings.
		$product_image   = isset( $shortcode_data['product_image'] ) ? $shortcode_data['product_image'] : '';
		$image_sizes     = isset( $shortcode_data['image_sizes'] ) ? $shortcode_data['image_sizes'] : 'full';
		$shortcode_query = self::spwps_product_query( $product_order_by, $product_type, $number_of_total_products, $hide_out_of_stock_product, $product_order );

		$slider_data  = 'data-slick=\'{"dots": ' . $pagination . ', "pauseOnHover": ' . $pause_on_hover . ', "infinite": ' . $carousel_infinite . ', "slidesToShow": ' . $number_of_column['number1'] . ', "speed": ' . $scroll_speed . ', "arrows": ' . $navigation . ', "autoplay": ' . $auto_play . ', "autoplaySpeed": ' . $auto_play_speed . ', "swipe": ' . $carousel_swipe . ', "draggable": ' . $carousel_draggable . ', "rtl": ' . $rtl_mode . ',  "responsive": [ {"breakpoint": 1100, "settings": { "slidesToShow": ' . $number_of_column['number2'] . ' } }, {"breakpoint": 990, "settings": { "slidesToShow": ' . $number_of_column['number3'] . ' } }, {"breakpoint": 650, "settings": { "slidesToShow": ' . $number_of_column['number4'] . ', "dots": ' . $pagination_mobile . ', "arrows": ' . $navigation_mobile . ' } } ] }\'';
		$slider_data .= ' data-preloader="' . $preloader . '"';
		include self::wps_locate_template( 'carousel.php' );
	}
}
