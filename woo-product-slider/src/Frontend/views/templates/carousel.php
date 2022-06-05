<?php
/**
 * Carousel
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider/templates/carousel.php
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend
 */

?>
<div id="wps-slider-section" class="wps-slider-section wps-slider-section-<?php echo esc_attr( $post_id ); ?>">
	<?php
	require self::wps_locate_template( 'slider-title.php' );
	require self::wps_locate_template( 'preloader.php' );
	?>
	<div id="sp-woo-product-slider-<?php echo esc_attr( $post_id ); ?>" class="wps-product-section sp-wps-<?php echo esc_attr( $theme_style ); ?>" <?php echo wp_kses_post( $slider_data . ' ' . $the_rtl ); ?>>
		<?php
		if ( $shortcode_query->have_posts() ) {
			while ( $shortcode_query->have_posts() ) :
				$shortcode_query->the_post();
				global $product;
				?>
		<div class="wpsf-product">
			<div class="sp-wps-product-image-area">
				<?php
					require self::wps_locate_template( 'loop/thumbnail.php' );
				?>
				<div class="sp-wps-product-details">
					<div class="sp-wps-product-details-inner">
						<?php
						require self::wps_locate_template( 'loop/title.php' );
						require self::wps_locate_template( 'loop/price.php' );
						require self::wps_locate_template( 'loop/rating.php' );
						require self::wps_locate_template( 'loop/add_to_cart.php' );
						?>
					</div> <!-- sp-wps-product-details-inner. -->
				</div> <!--  sp-wps-product-details. -->
			</div> <!-- sp-wps-product-image-area.  -->
		</div> <!-- wpsf-product. -->
				<?php
		endwhile;
		} else {
			?>
		<h2 class="sp-not-found-any-product-f"><?php echo esc_html__( 'No products found', 'woo-product-slider' ); ?></h2>
			<?php
		}
		wp_reset_postdata();
		?>
	</div>
</div>
