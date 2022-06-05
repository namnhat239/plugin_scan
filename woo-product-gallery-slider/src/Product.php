<?php

namespace Product_Gallery_Sldier;

class Product {

	/**
	 * @var mixed
	 */
	public $gallery_options;

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [$this, 'frontend_scripts'], 90 );
		add_action( 'after_setup_theme', [$this, 'remove_default_gallery_support'], 100 );
		$this->hooks();
		$this->gallery_options = get_option( 'wpgs_form' );
	}
	public function remove_default_gallery_support() {

		if ( $this->gallery_options['image_zoom'] != '1' ) {
			remove_theme_support( 'wc-product-gallery-zoom' );
		} else {
			add_theme_support( 'wc-product-gallery-zoom' );
		}

		remove_theme_support( 'wc-product-gallery-lightbox' );
		remove_theme_support( 'wc-product-gallery-slider' );
	}

	public function hooks() {
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
		add_action( 'woocommerce_before_single_product_summary', [$this, 'wpgs_product_image'], 20 );
		add_action( 'woocommerce_product_thumbnails', [$this, 'wpgs_product_thumbnails'], 20 );
	}
	// Output the product image before the single product summary.
	public function wpgs_product_thumbnails() {
		require_once CIPG_PATH . '/inc/product-thumbnails.php';

	}
	// Output the product image before the single product summary.
	public function wpgs_product_image() {
		require_once CIPG_PATH . '/inc/product-image.php';

	}

	/**
	 * Frontend styles/scripts
	 *
	 * @return void
	 */
	public function frontend_scripts() {
		wp_dequeue_script( 'photoswipe-ui-default' );
		wp_dequeue_script( 'photoswipe' );
		wp_dequeue_style( 'photoswipe' );
		wp_dequeue_style( 'photoswipe-default-skin' );
		
		wp_enqueue_script( 'slick', CIPG_ASSETS . '/js/slick.min.js', array( 'jquery' ), CIPG_VERSION, true );
		wp_enqueue_script( 'fancybox', CIPG_ASSETS . '/js/jquery.fancybox.min.js', array( 'jquery' ), CIPG_VERSION, true );

		wp_enqueue_script( 'wpgs-public', CIPG_ASSETS . '/js/wpgs.js', array( 'jquery', 'fancybox', 'slick' ), CIPG_VERSION, true );

		$warrows          = ( $this->gallery_options['slider_nav'] == '1' ) ? 'true' : 'false';
		$w_thumb_arrows   = ( $this->gallery_options['thumb_nav'] == '1' ) ? 'true' : 'false';
		$wzoom            = $this->gallery_options['image_zoom'];
		$slider_dots      = ( $this->gallery_options['dots'] == 1 ) ? 'true' : 'false';
		$wautoPlay        = ( $this->gallery_options['slider_autoplay'] == '1' ) ? 'true' : 'false';
		$autoplay_timeout = $this->gallery_options['autoplay_timeout'];

		$slider_autoplay_pause = ( $this->gallery_options['slider_autoplay_pause'] == '1' ) ? 'true' : 'false';
		$wslider_thubms        = $this->gallery_options['thumb_to_show'];
		$thumb_scroll_by       = $this->gallery_options['thumb_scroll_by'];
		$slider_adaptiveHeight = ( $this->gallery_options['slider_adaptiveHeight'] == '1' ) ? 'true' : 'false';
		$wslider_mouse_draging = ( $this->gallery_options['slider_dragging'] == '1' ) ? 'true' : 'false';
		$wslider_infinity_loop = ( $this->gallery_options['slider_infinity'] == '1' ) ? 'true' : 'false';

		$wzoom_script = '';
		$slider_rtl   = ( is_rtl() ) ? 'true' : 'false';
		$nextArrow    = ( $slider_rtl == 'false' ) ? "nextArrow:'<i class=\"flaticon-right-arrow\"></i>'," : '';
		$prevArrow    = ( $slider_rtl == 'false' ) ? " prevArrow:'<i class=\"flaticon-back\"></i>'," : '';

		$thumbnails_mobile_thumb_to_show   = $this->gallery_options['thumbnails_mobile_thumb_to_show'];
		$thumbnails_mobile_thumb_scroll_by = $this->gallery_options['thumbnails_mobile_thumb_scroll_by'];

		$thumbnails_tabs_thumb_to_show   = $this->gallery_options['thumbnails_tabs_thumb_to_show'];
		$thumbnails_tabs_thumb_scroll_by = $this->gallery_options['thumbnails_tabs_thumb_scroll_by'];

		if ( $wzoom == '1' ) {
			$wzoom_script = "
            jQuery('.wpgs-for img.attachment-shop_single').each(function () {
				var newImgSrc = jQuery(this).attr('data-zoom_src');
				jQuery(this)
                .wrap('<span style=\"display:inline-block\"></span>')
                .css('display', 'block')
                .parent()
                .zoom({url: newImgSrc});

			});
            jQuery('.woocommerce-product-gallery__image img').load(function () {
                var imageObj = jQuery('.woocommerce-product-gallery__image img');
                if (!(imageObj.width() == 1 && imageObj.height() == 1)) {
                    jQuery(this).parent().find('.zoomImg').remove();
                }
            });

            ";
		}

		$wpgs_sliderJs = "jQuery(document).ready(function(){
        jQuery('.wpgs-for').slick({
            slidesToShow:1,
            slidesToScroll:1,
            arrows:{$warrows},
            fade:false,
            rtl: $slider_rtl,
			dots: $slider_dots,
			pauseOnHover: $slider_autoplay_pause,
			pauseOnDotsHover: $slider_autoplay_pause,
			dotsClass:'slick-dots wpgs-dots',
            infinite:{$wslider_infinity_loop},
            adaptiveHeight:{$slider_adaptiveHeight},
            autoplay:{$wautoPlay},
			autoplaySpeed: $autoplay_timeout,
            draggable:{$wslider_mouse_draging},
           {$nextArrow}
           {$prevArrow}
            asNavFor:'.wpgs-nav',

        });
        jQuery('.wpgs-nav').slick({
            slidesToShow:{$wslider_thubms},
            slidesToScroll: $thumb_scroll_by,
            asNavFor:'.wpgs-for',
            arrows:{$w_thumb_arrows},
            rtl: $slider_rtl,
            infinite:{$wslider_infinity_loop},
            focusOnSelect:true,
            responsive: [

					{
					breakpoint: 1025,
					settings: {
						variableWidth: false,

						slidesToShow: $thumbnails_tabs_thumb_to_show,
						slidesToScroll: $thumbnails_tabs_thumb_scroll_by,
						swipeToSlide :true,

					}
					},

					{
					breakpoint: 767,
					settings: {
						variableWidth: false,

						slidesToShow: $thumbnails_mobile_thumb_to_show,
						slidesToScroll: $thumbnails_mobile_thumb_scroll_by,
						swipeToSlide :true,
					}
					}

				],
        });

        {$wzoom_script}


      });";
		wp_add_inline_script( 'wpgs-public', $wpgs_sliderJs );

		wp_enqueue_style( 'slick', CIPG_ASSETS . '/css/slick.css', null, CIPG_VERSION );
		wp_enqueue_style( 'slick-theme', CIPG_ASSETS . '/css/slick-theme.css', null, CIPG_VERSION );
		wp_enqueue_style( 'fancybox', CIPG_ASSETS . '/css/jquery.fancybox.min.css', null, CIPG_VERSION );

		$custom_css = $this->gallery_options['custom_css'];

		if ( is_product() ) {
			$twist_product  = new \WC_Product( get_the_ID() );
			$attachment_ids = $twist_product->get_gallery_image_ids();

			if ( count( $attachment_ids ) + 1 <= $wslider_thubms ) {
				$custom_css .= "
					.wpgs-nav .slick-track {
						transform: inherit !important;
					}
				";
			}
		}
		if ( $this->gallery_options['lightbox_picker'] == '1' ) {
			$custom_css .= ".wpgs-for .slick-slide{cursor:pointer;}";
		} else {
			$custom_css .= ".wpgs-for .slick-slide{cursor: default;}";
		}

		wp_add_inline_style( 'fancybox', $custom_css );

		wp_enqueue_style( 'flaticon-wpgs', CIPG_ASSETS . '/css/font/flaticon.css', null, CIPG_VERSION );
	}
}
