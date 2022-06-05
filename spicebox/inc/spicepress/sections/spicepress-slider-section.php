<?php
/**
 * Slider section for the homepage.
 */
if ( ! function_exists( 'spiceb_spicepress_slider' ) ) :

	function spiceb_spicepress_slider() {
		
		$home_slider_image = get_theme_mod('home_slider_image',SPICEB_PLUGIN_URL .'inc/spicepress/images/slider/slider.jpg');
		$home_slider_title = get_theme_mod('home_slider_title','Welcome to SpicePress Theme');
		
		$home_slider_discription = get_theme_mod('home_slider_discription','Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim mei ludus efficiendi ei sea summo mazim ex.');
		$home_slider_btn_txt = get_theme_mod('home_slider_btn_txt','Read More');
		$home_slider_btn_link = get_theme_mod('home_slider_btn_link',esc_url('#'));
		$home_slider_btn_target = get_theme_mod('home_slider_btn_target',false);
		
		$home_page_slider_enabled = get_theme_mod('home_page_slider_enabled','on');
		if($home_page_slider_enabled !='off') {
		?>
	<section class="slider" style="position:relative;">
		<div class="item" style="background-image:url(<?php echo $home_slider_image; ?>); width: 100%; height: 100vh; background-position: center center; background-size: cover; z-index: 0;" >
			<div class="container">
					<div class="format-standard">
						<?php if ( ! empty( $home_slider_title ) || is_customize_preview() ) { ?>
						<div class="slide-text-bg1">
						<h1><?php echo $home_slider_title;  ?></h1>
						<?php } 
						if ( ! empty( $home_slider_discription ) || is_customize_preview() ) {
						?>
						<p><?php echo $home_slider_discription; ?></p>
						<?php } ?>
						</div>
						<?php if($home_slider_btn_txt) { ?>
						<div class="slide-btn-area-sm">						
						<a <?php if($home_slider_btn_link) { ?> href="<?php echo $home_slider_btn_link; } ?>" 
						<?php if($home_slider_btn_target) { ?> target="_blank" <?php } ?> class="slide-btn-sm">
						<?php if($home_slider_btn_txt) { echo $home_slider_btn_txt; } ?></a>
						</div>
						<?php } ?>						
					</div>	
				</div>	
			</div>
	</section>
		<?php
}
	}

endif;

if ( function_exists( 'spiceb_spicepress_slider' ) ) {
$section_priority = apply_filters( 'spicepress_section_priority', 11, 'spiceb_spicepress_slider' );
add_action( 'spiceb_spicepress_sections', 'spiceb_spicepress_slider', absint( $section_priority ) );

}