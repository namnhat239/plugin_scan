<?php
$widget_id  = $args['widget_id'];

if ( defined('DOING_AJAX') && DOING_AJAX ) {
    $section_id = '';
}
else {
	$section_id = $args['section_id'];
}

if ( !wcd_is_pro_activated() && !wcd_is_preview_mode() && !wcd_is_edit_mode() ) {
    $wishlist_show_hide = 'no';
}

$empty_message = __( 'No Product Found!', 'woolementor' );

$settings   = $args['settings'];

$products   = wcd_query_products( $settings );     
$user_id    = get_current_user_id();
$wishlist   = wcd_get_wishlist( $user_id );

extract( $settings );
?>

<div class="wl-ssl-product-style">
    <?php do_action( 'codesigner_before_shop_loop' ); ?>
	<div class="cx-container">
		<div class="wl-ssl-slider wl-ssl-slick-<?php echo $section_id; ?>">

			<?php
			if( $products->have_posts()) : 
				while( $products->have_posts()) : $products->the_post();
					$product_id = get_the_ID();
					$product    = wc_get_product( $product_id );
					$thumbnail  = get_the_post_thumbnail_url( $product_id, $image_thumbnail_size );
					$fav_product= in_array( $product_id, $wishlist );

                    if ( !empty( $fav_product ) ) {
                        $fav_item = 'fav-item';
                    }
                    else{
                        $fav_item = '';
                    }
					?>
					   <div class="wl-ssl-single-product <?php echo esc_attr( $alignment ); ?>">
                    		<?php do_action( 'codesigner_before_shop_loop_item' ); ?>
						  	<div class="wl-ssl-single-widget">

							 <?php if ( 'yes' == $sale_ribbon_show_hide && $product->is_on_sale() ): ?>
								<div class="wl-ssl-corner-ribbon">
									<?php
										printf( '<span>%1$s</span>',
	                                    esc_html( $settings['sale_ribbon_text' ] )
	                                );
									?>
								</div>
							 <?php endif; ?>

								<div class="wl-ssl-product-img">

									 <?php if ( 'none' == $image_on_click ): ?>
										 <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>"/>  
									 <?php elseif ( 'zoom' == $image_on_click ) : ?>
										 <a id="wl-ssl-product-image-zoom" href="<?php echo esc_url( $thumbnail ); ?>"><img src="<?php echo esc_url( $thumbnail ); ?>" alt=""/></a>
									 <?php elseif ( 'product_page' == $image_on_click ) : ?>
										 <a href="<?php the_permalink(); ?>">
											 <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>"/>                              
										 </a>
									 <?php endif; ?>

								</div>

								<?php if( 'outofstock' == $product->get_stock_status() && 'yes' == $stock_show_hide ): ?>
							    <div class="wl-ssl-stock">
							        <?php echo esc_html( $stock_ribbon_text ); ?>
							    </div>
								<?php endif; ?>

							 	<div class="wl-ssl-product-details">

									<?php if ( 'yes' == $wishlist_show_hide ): ?>
										<div class="wl-ssl-product-fav">
											<i class="<?php echo esc_attr( $wishlist_icon['value'] );?> <?php echo esc_attr( $fav_item ); ?> ajax_add_to_wish" data-product_id="<?php echo esc_attr( $product_id ); ?>"></i>
										</div>
									<?php endif; ?>

									<div class="wl-ssl-product-info">

                                		<?php do_action( 'codesigner_shop_loop_item_title' ); ?>
									   	<div class="wl-ssl-product-name"><a class="wl-gradient-heading" href="<?php the_permalink(); ?>"><?php echo esc_html( $product->get_name() ); ?></a></div>
                                		<?php do_action( 'codesigner_after_shop_loop_item_title' ); ?>

									   	<?php if( 'yes' == $settings['short_description_show_hide'] ): ?>
										   <div class="wl-ssl-product-desc">
											<p><?php echo wp_trim_words( esc_html( $product->get_short_description() ), $product_desc_words_count ); ?></p>
										   </div>
									   	<?php endif; ?>

									</div>
									<div class="wl-ssl-info-icons">
										<div class="wl-ssl-price">
											<h2><?php echo wp_kses_post( $product->get_price_html() ); ?></h2>
										</div>

										<?php 
										do_action( 'codesigner_before_cart_button', $product, $section_id, $settings );

										if ( 'yes' == $cart_show_hide ):
	                                        if( 'simple' == $product->get_type() ) : ?>
												<div class="wl-ssl-product-cart">
													<div class="wl-cart-area">
														<a href="?add-to-cart=<?php echo esc_attr( $product_id ); ?>" data-quantity="1" class="product_type_<?php echo esc_attr( $product->get_type() ); ?> add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product_id ); ?>" ><i class="<?php echo esc_attr( $cart_icon['value'] ); ?>"></i></a>
													</div>
												</div>
											<?php else: ?>
												<div class="wl-ssl-product-cart">
													<a href="<?php echo get_permalink( $product_id ); ?>"><i class="<?php echo esc_attr( $cart_icon['value'] ); ?>"></i></a>
												</div>
											<?php endif;
	                                    endif; 

	                                    do_action( 'codesigner_after_cart_button', $product, $section_id, $settings );
	                                    ?>
									</div>
							 	</div>
						  	</div>
                    		<?php do_action( 'codesigner_after_shop_loop_item' ); ?>
					   </div>

				<?php endwhile; wp_reset_query(); else: 

				echo '<p>' . __( 'No Product Found!', 'woolementor' ) . '</p>';

			endif; ?>
		</div>
	</div>
    <?php do_action( 'codesigner_after_shop_loop' ); ?>
</div>

<?php

$slick_config = [
	'autoplay'			=> $autoplay,
	'autoplay_speed'	=> $autoplay_speed,
	'animation_speed'	=> $animation_speed,
	'infinite_loop'		=> $infinite_loop,
	'navigation'		=> $navigation,
	'slides_show'		=> $slides_show,
	'slides_show_mobile'=> $slides_show_mobile,
	'slides_show_tablet'=> $slides_show_tablet,
	'slider_alignment'	=> $slider_alignment,
	'arrow_icon_left'	=> $arrow_icon_left,
	'arrow_icon_right'	=> $arrow_icon_right,
];
?>
<script type="text/javascript">
    jQuery(function($){
    	
    	var config 	= <?php echo json_encode( $slick_config ); ?>;
    	var $loop 	= config.infinite_loop ? true : false;
    	var $autoplay 	= config.autoplay ? true : false;
    	var $slider_alignment 	= config.slider_alignment == 'true' ? true : false;

    	if ( 'none' == config.navigation ) {
    		$arrows = false;
    		$dots 	= false;
    	}
    	else if( 'arrow' == config.navigation ) {
    		$arrows = true;
    		$dots 	= false;
    	}
    	else if( 'dots' == config.navigation ) {
    		$arrows = false;
    		$dots 	= true;
    	}
    	else {
    		$arrows = true;
    		$dots 	= true;
    	}

    	if ( config.arrow_icon_left ) {
    		var $prevArrow = '<button type="button" class="slick-prev"><i class="'+ config.arrow_icon_left.value +'"></i></button>'
    	} else {
    		var $prevArrow = false
    	}

    	if ( config.arrow_icon_right ) {
    		var $nextArrow = '<button type="button" class="slick-next"><i class="'+ config.arrow_icon_right.value +'"></i></button>'
    	} else {
    		var $nextArrow = false
    	}

    	$('.wl-ssl-slick-<?php echo $section_id; ?>' ).slick({
		  	infinite: $loop,
		  	autoplay: $autoplay,
		  	autoplaySpeed: config.autoplay_speed,
		  	speed: config.animation_speed,
		  	slidesToShow: parseInt(config.slides_show),
		  	slidesToScroll: parseInt(config.slides_show),
		  	arrows: $arrows,
		  	dots: $dots,
		  	vertical: $slider_alignment,
            prevArrow: $prevArrow,
            nextArrow: $nextArrow,

            responsive: [
		    {
		      breakpoint: 1024,
		      settings: {
		        slidesToShow: parseInt(config.slides_show),
		        slidesToScroll: parseInt(config.slides_show),
		      }
		    },
		    {
		      breakpoint: 769,
		      settings: {
		        slidesToShow: parseInt(config.slides_show_tablet),
		        slidesToScroll: parseInt(config.slides_show_tablet),
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		      slidesToShow: parseInt(config.slides_show_mobile),
		        slidesToScroll: parseInt(config.slides_show_mobile),
		      }
		    }
		  ]
		});
    })
</script>