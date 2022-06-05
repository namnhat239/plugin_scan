<?php
/**
 * Services section for the homepage.
 */
if ( ! function_exists( 'quality_features' ) ) :

	function quality_features() {

		$current_options = get_option( 'quality_pro_options');
		$hide_section = isset($current_options['service_enable'])? $current_options['service_enable']:true;
	
		$quality_service_title    = isset($current_options['service_title'])? $current_options['service_title'] : esc_html__('Our nice services','webriti-companion');
		$quality_service_subtitle = isset($current_options['service_description'])?$current_options['service_description']: esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam scelerisque faucibus risus non iaculis.','webriti-companion');
		$quality_service_content  = ! empty($current_options['quality_service_content']) ? $current_options['quality_service_content'] : quality_get_service_default();
		$section_is_empty = empty( $quality_service_content ) && empty( $quality_service_subtitle ) && empty( $quality_service_title );

	if (  $hide_section == true ) {
		?>
		<div class="service_section" id="service">
			
			<div class="container">
				<div class="row">
					<div class="qua_heading_title">
						<?php
							if ( ! empty( $quality_service_title ) || is_customize_preview() ) {
								echo '<h1>' . esc_html( $quality_service_title ) . '</h1>';
							}
							if ( ! empty( $quality_service_subtitle ) || is_customize_preview() ) {
								echo '<p class="description">' . esc_html( $quality_service_subtitle ) . '</p>';
							}
							?>
					</div>	 
				</div>
				
					<?php
				
				quality_service_content( $quality_service_content );
				?>
			</div>
		</div>
		<?php
	}
	}

endif;


function quality_service_content( $quality_service_content, $is_callback = false ) {
	if ( ! $is_callback ) {
	?>
	
		<?php
	}
	if ( ! empty( $quality_service_content ) ) :

		$quality_service_content = json_decode( $quality_service_content );
		if ( ! empty( $quality_service_content ) ) {
			$i = 1;
			echo '<div class="row">';
			foreach ( $quality_service_content as $service_item ) :
				$icon = ! empty( $service_item->icon_value ) ?  $service_item->icon_value : '';
				$image = ! empty( $service_item->image_url ) ?  $service_item->image_url: '';
				$title = ! empty( $service_item->title ) ? $service_item->title : '';
				$text = ! empty( $service_item->text ) ?  $service_item->text : '';
				$link = ! empty( $service_item->link ) ? $service_item->link : '';
				$color = ! empty( $service_item->color ) ? $service_item->color : '';
				$choice = ! empty( $service_item->choice ) ? $service_item->choice : 'customizer_repeater_icon';
				?>
				<div class="col-md-3 col-sm-6 qua-service-area">
					<!--<div class="service_area">-->
					<div class="hexagon-box">
						<?php
						

						switch ( $choice ) {
							case 'customizer_repeater_image':
								if ( ! empty( $image ) ) {
									?>
									<div class="card card-plain">
										<img src="<?php echo esc_url( $image ); ?>"/>
										</div>
										<?php
								}
								break;
							case 'customizer_repeater_icon':
								if ( ! empty( $icon ) ) {
									?>
									<div class="icon" <?php echo ( ! empty( $color ) ? 'style="color:' . $color . '"' : '' ); ?>>
								<i class="fa <?php echo esc_html( $icon ); ?> "></i>
										</div>
										<?php
								}
								break;
						}
							?>
							</div>
							<?php if ( ! empty( $title ) ) : 
								if(empty($link)){ ?>
									<h2 ><?php echo esc_html( $title ); ?></h2><?php
								}else{
									?>
									<h2 ><a href="<?php echo $link; ?>" target="_blank" ><?php echo esc_html( $title ); ?></a></h2><?php
								}
							?>
							
							<?php endif; ?>
							
			<?php if ( ! empty( $text ) ) : ?>
							<p><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></p>
						<?php endif; ?>
				</div>
				<?php
				if ( $i % apply_filters( 'quality_service_per_row_no', 4 ) == 0 ) {
					echo '</div><!-- /.row -->';
					echo '<div class="row">';
				}
				$i++;

			endforeach;
			echo '</div>';
		}// End if().
		endif;
	if ( ! $is_callback ) {
	?>
		
		<?php
	}
}

/**
 * Get default values for service section.
 *
 * @since 1.1.31
 * @access public
 */
function quality_get_service_default() {
	
	$old_theme_servives = wp_parse_args(  get_option( 'quality_pro_options', array() ), plugin_data_setup() );
	
	//if(get_option( 'quality_pro_options')!=''){
	if($old_theme_servives['service_one_icon']!= '' || $old_theme_servives['service_one_title']!= '' || $old_theme_servives['service_one_text']!= '' 
			|| $old_theme_servives['service_two_icon']!= '' || $old_theme_servives['service_two_title']!= '' || $old_theme_servives['service_two_text']!= '' 
			|| $old_theme_servives['service_three_icon']!= '' || $old_theme_servives['service_three_title']!= '' || $old_theme_servives['service_three_text']!= ''
			 || $old_theme_servives['service_four_icon']!= '' || $old_theme_servives['service_four_title']!= '' || $old_theme_servives['service_four_text']!= '')
		 {
			 //$old_theme_servives = get_option( 'quality_pro_options');
			 
			 return apply_filters(
		'quality_service_default_content', json_encode(
			array(
				array(
						 'icon_value' => isset($old_theme_servives['service_one_icon'])? $old_theme_servives['service_one_icon']:'',
						 'title'      => isset($old_theme_servives['service_one_title'])? $old_theme_servives['service_one_title']:'',
						'text'       => isset($old_theme_servives['service_one_text'])? $old_theme_servives['service_one_text']:'',
						'link'       => '',
						 ),
						array(
						 'icon_value' => isset($old_theme_servives['service_two_icon'])? $old_theme_servives['service_two_icon']:'',
						 'title'      => isset($old_theme_servives['service_two_title'])? $old_theme_servives['service_two_title']:'',
						 'text'       => isset($old_theme_servives['service_two_text'])? $old_theme_servives['service_two_text']:'',
						 'link'       => '',
						 ),
						 array(
						 'icon_value' => isset($old_theme_servives['service_three_icon'])? $old_theme_servives['service_three_icon']:'',
						 'title'      => isset($old_theme_servives['service_three_title'])? $old_theme_servives['service_three_title']:'',
						 'text'       => isset($old_theme_servives['service_three_text'])? $old_theme_servives['service_three_text']:'',
						 'link'       => '',
						),
						
						 array(
						 'icon_value' => isset($old_theme_servives['service_four_icon'])? $old_theme_servives['service_four_icon']:'',
						 'title'      => isset($old_theme_servives['service_four_title'])? $old_theme_servives['service_four_title']:'',
						 'text'       => isset($old_theme_servives['service_four_text'])? $old_theme_servives['service_four_text']:'',
						 'link'       => '',
						),
			)
		)
	);	 
		 } 
		 //}
	else {
	return apply_filters(
		'quality_service_default_content', json_encode(
			array(
				array(
					'icon_value' => 'fa-mobile',
					'title'      => esc_html__( 'Fully Responsive', 'webriti-companion' ),
					'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'webriti-companion' ),
					'link'       => '#',
				),
				array(
					'icon_value' => 'fa-bar-chart',
					'title'      => esc_html__( 'SEO Friendly', 'webriti-companion' ),
					'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'webriti-companion' ),
					'link'       => '#',
				),
				array(
					'icon_value' => 'fa-edit',
					'title'      => esc_html__( 'Easy to Use', 'webriti-companion' ),
					'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'webriti-companion' ),
					'link'       => '#',
				),
				array(
					'icon_value' => 'fa-thumbs-o-up',
					'title'      => esc_html__( 'Well Documentation', 'webriti-companion' ),
					'text'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'webriti-companion' ),
					'link'       => '#',
				),
			)
		)
	);
	}
}

if ( function_exists( 'quality_features' ) ) {
	$section_priority = apply_filters( 'quality_section_priority', 10, 'quality_features' );
	add_action( 'quality_sections', 'quality_features', absint( $section_priority ) );
	
}

function plugin_data_setup()
{	

			return $theme_options=array(
			'service_one_title' => __('Fully responsive','webriti-companion'),
			'service_two_title' => __('SEO friendly','webriti-companion'),
			'service_three_title' => __('Easy customization','webriti-companion'),
			'service_four_title' => __('Well documentation','webriti-companion'),
			
			'service_one_icon' => 'fa-mobile',
			'service_two_icon' => 'fa-bar-chart',
			'service_three_icon' => 'fa-edit',
			'service_four_icon' => 'fa-thumbs-o-up',
			
			'service_one_text' => 'Lorem Ipsum which looks reason able. The generated Lorem Ipsum is ',
			'service_two_text' => 'Lorem Ipsum Lorem Ipsum which looks reason able. The generated Lorem Ipsum is',
			'service_three_text' => 'Lorem Ipsum which looks reason able. The generated Lorem Ipsum is ',
			'service_four_text' => 'Lorem Ipsum which looks reason able. The generated Lorem Ipsum is ',
			
		);
}
