<?php
/**
 * Product thumbnail.
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider-pro/templates/loop/thumbnail.php
 *
 * @package    woo-product-slider-pro
 * @subpackage woo-product-slider-pro/Frontend
 */
?>
<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="wps-product-image">
	<?php
	if ( $product_image ) {
		if ( has_post_thumbnail( $shortcode_query->post->ID ) ) {
			$product_thumb          = $image_sizes;
			$wps_product_image_size = apply_filters( 'sp_wps_product_image_size', $product_thumb );
			echo get_the_post_thumbnail( $shortcode_query->post->ID, $wps_product_image_size, array( 'class' => 'wpsf-product-img' ) );
		} else {
			?>
		<img id="place_holder_thm" src="<?php echo esc_attr( wc_placeholder_img_src() ); ?>" alt="Placeholder" />
			<?php
		}
	}
	?>
</a>
