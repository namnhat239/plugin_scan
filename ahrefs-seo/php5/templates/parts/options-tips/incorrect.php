<?php

namespace ahrefs\AhrefsSeo;

?>
<!-- not detected tip -->
<div class="ahrefs-content-tip tip-notice">
	<div class="caption">
	<?php
	/* translators: %s: current domain */
	printf( esc_html__( "Google profiles selected don't match %s", 'ahrefs-seo' ), esc_html( Ahrefs_Seo::get_current_domain() ) );
	?>
		</div>
	<div class="text">
	<?php
	esc_html_e( 'You might have authorized the wrong Google account which does not have access to the required traffic & search ranking data for this site.', 'ahrefs-seo' );
	?>
	</div>
	<div class="text">
		<a href="https://help.ahrefs.com/en/articles/4666920-how-do-i-connect-google-analytics-search-console-to-the-plugin" target="_blank" class="learn-more-link">
			<span class="text">
			<?php
			esc_html_e( 'How do I connect the right Google Analytics & Search Console accounts?', 'ahrefs-seo' );
			?>
			</span>
			<img src="<?php echo esc_attr( AHREFS_SEO_IMAGES_URL . 'link-open.svg' ); ?>" class="icon">
		</a>
	</div>
</div>
<?php
