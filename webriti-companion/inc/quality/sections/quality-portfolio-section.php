<?php
/**
 * Portfolio section for the homepage.
 */
if ( ! function_exists( 'quality_portfolio' ) ) :

	function quality_portfolio() {
		
		$current_options = get_option( 'quality_pro_options');
		$hide_section = isset($current_options['home_projects_enabled'])? $current_options['home_projects_enabled']:true;
		$quality_portfolio_title    = isset($current_options['project_heading_one'])? $current_options['project_heading_one'] : esc_html__('Featured portfolio project','webriti-companion');
		$quality_portfolio_subtitle = isset($current_options['project_tagline'])?$current_options['project_tagline']: esc_html__('Maecenas sit amet tincidunt elit. Pellentesque habitant morbi tristique senectus et netus et Nulla facilisi.','webriti-companion');
		if (  $hide_section == true ) {
		?>
		
	<div class="qua_portfolio_carusel">
	<div class="container">
		<div class="qua_port_title">
		<?php
		if ( ! empty( $quality_portfolio_title ) || is_customize_preview() ) {
			echo '<h1>' . esc_html( $quality_portfolio_title ) . '</h1>';
		}
		if ( ! empty( $quality_portfolio_subtitle ) || is_customize_preview() ) {
			echo '<p class="description">' . esc_html( $quality_portfolio_subtitle ) . '</p>';
		}
		?>	
		<div class="qua-separator" id=""></div>
		</div>
		<div class="row home_portfolio_row">
			
			<?php
			$j=1;
			$args = array( 'post_type' => 'quality_portfolio','posts_per_page' =>-1); 	
			$portfolio = new WP_Query( $args ); 
			if( $portfolio->have_posts() )
			{ while ( $portfolio->have_posts() ) : $portfolio->the_post();
						
			?>
			<div class="col-md-3 col-sm-6 qua_col_padding">
				<div class="qua_portfolio_image">
					<?php 
						if(has_post_thumbnail()):
						$class=array('class'=>'img-responsive');
						the_post_thumbnail('', $class);
						$post_thumbnail_id = get_post_thumbnail_id();
						$post_thumbnail_url = wp_get_attachment_url($post_thumbnail_id ); 
					?>
					
					<?php endif; ?>
				</div>
				<div class="qua_home_portfolio_caption">
					<a href="#" target="_blank"><?php the_title(); ?></a>			
				</div>
			</div>
			<?php if($j%4==0){ echo "<div class='clearfix'></div>"; } $j++; endwhile;
			} else { 
			for($i=1; $i<=4; $i++) {	?>
			<div class="col-md-3 col-sm-6 qua_col_padding">
				<div class="qua_portfolio_image">
					<img class="img-responsive" src="<?php echo WC__PLUGIN_URL; ?>/inc/quality/images/portfolio/home-port<?php echo $i; ?>.jpg" />
					
				</div>
			</div>
			<?php } } //end of default portfolio for loop  ?>
			
			<div class="clearfix"></div>
			
			<div class="qua_proejct_button">
			<a href="#"> <?php _e('View All Projects','webriti-companion'); ?> </a>
			</div>			
		</div>
	</div>
</div>
		<?php
}
	}

endif;

if(get_option('quality_pro_options')!=''){
	
	$status = get_option('webriti-migration-status','no');
	if($status == 'no'){
		$old_theme_project = get_option('quality_pro_options');
		if(isset($old_theme_project['project_one_title'])){
			$post_id = wp_insert_post(
			array (
		   'post_type' => 'quality_portfolio',
		   'post_title' => $old_theme_project['project_one_title'],
		   'post_status' => 'publish',
		));

		$filename = $old_theme_project['project_one_thumb'];

		$filetype = wp_check_filetype( basename( $filename ), null );

		$wp_upload_dir = wp_upload_dir();

		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
		set_post_thumbnail( $post_id, $attach_id );
		}
		if(isset($old_theme_project['project_two_title'])){
			$post_id = wp_insert_post(
			array (
		   'post_type' => 'quality_portfolio',
		   'post_title' => $old_theme_project['project_two_title'],
		   'post_status' => 'publish',
		));
		$filename = $old_theme_project['project_two_thumb'];
		$filetype = wp_check_filetype( basename( $filename ), null );
		$wp_upload_dir = wp_upload_dir();
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
		set_post_thumbnail( $post_id, $attach_id );
		}
		if(isset($old_theme_project['project_three_title'])){
			$post_id = wp_insert_post(
			array (
		   'post_type' => 'quality_portfolio',
		   'post_title' => $old_theme_project['project_three_title'],
		   'post_status' => 'publish',
		));
		$filename = $old_theme_project['project_three_thumb'];
		$filetype = wp_check_filetype( basename( $filename ), null );
		$wp_upload_dir = wp_upload_dir();
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
		set_post_thumbnail( $post_id, $attach_id );
		}
		if(isset($old_theme_project['project_four_title'])){
			$post_id = wp_insert_post(
			array (
		   'post_type' => 'quality_portfolio',
		   'post_title' => $old_theme_project['project_four_title'],
		   'post_status' => 'publish',
		));
		$filename = $old_theme_project['project_four_thumb'];
		$filetype = wp_check_filetype( basename( $filename ), null );
		$wp_upload_dir = wp_upload_dir();
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
		set_post_thumbnail( $post_id, $attach_id );
		}
	
	
	update_option('webriti-migration-status','yes');
		}
}


		if ( function_exists( 'quality_portfolio' ) ) {
		$section_priority = apply_filters( 'quality_section_priority', 11, 'quality_portfolio' );
		add_action( 'quality_sections', 'quality_portfolio', absint( $section_priority ) );

		}
