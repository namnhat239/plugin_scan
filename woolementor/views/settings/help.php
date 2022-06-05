<?php

$base_url 	= wcd_home_link();
$buttons 	= [
	'roadmap' 	=> [
		'url' 	=> "{$base_url}/roadmap/",
		'label' => __( 'Roadmap', 'woolementor' ) 
	],
	'changelog' => [
		'url' 	=> "{$base_url}/roadmap/",
		'label' => __( 'Changelog', 'woolementor' ) 
	],
	'ideas' 	=> [
		'url' 	=> "{$base_url}/roadmap/",
		'label' => __( 'Ideas', 'woolementor' ) 
	],
	'support' 	=> [
		'url' 	=> wcd_help_link(),
		'label' => __( 'Ask Support', 'woolementor' ) 
	],
];
$buttons 	= apply_filters( 'wcd_help_tab_link', $buttons );
?>

<div class="wcd-help-tab">
	<div class="wcd-documentation">
		 <div class='wrap'>
		 	<div id='woolementor-helps'>
		    <?php

		    $helps = get_option( 'woolementor-docs_json', [] );
			$utm = [ 'utm_source' => 'dashboard', 'utm_medium' => 'settings', 'utm_campaign' => 'faq' ];
		    if( is_array( $helps ) && count( $helps ) > 0 ) :
		    foreach ( $helps as $help ) {
		    	$help_link = add_query_arg( $utm, $help['link'] );
		        ?>
		        <div id='woolementor-help-<?php echo esc_attr( $help['id'] ); ?>' class='woolementor-help'>
		            <h2 class='woolementor-help-heading' data-target='#woolementor-help-text-<?php echo esc_attr( $help['id'] ); ?>'>
		                <a href='<?php echo esc_url( $help_link ); ?>' target='_blank'>
		                <span class='dashicons dashicons-admin-links'></span></a>
		                <span class="heading-text"><?php echo esc_html( $help['title']['rendered'] ); ?></span>
		            </h2>
		            <div id='woolementor-help-text-<?php echo esc_attr( $help['id'] ); ?>' class='woolementor-help-text' style='display:none'>
		                <?php echo wpautop( esc_html( wp_trim_words( $help['content']['rendered'], 55 ) ) . " <a class='sc-more' href='" . esc_url( $help_link ) . "' target='_blank'>[more..]</a>" ); ?>
		            </div>
		        </div>
		        <?php

		    }
		    else:
		        echo '<p>' . __( 'Something is wrong! Refreshing the page might help.', 'woolementor' ) . '</p>';
		    endif;
		    ?>
		    </div>
		</div>
	</div>
	<div class="wcd-help-links">
		<?php 
		foreach ( $buttons as $key => $button ) {
			echo "<a target='_blank' href='" . esc_url( $button['url'] ) . "' class='wcd-help-link'>" . esc_html( $button['label'] ) . "</a>";
		}
		?>
	</div>
</div>

<script type="text/javascript">
	jQuery(function($){ $.get( ajaxurl, { action : 'woolementor-docs_json' }); });
</script>

<?php do_action( 'wcd_help_tab_content' ); ?>