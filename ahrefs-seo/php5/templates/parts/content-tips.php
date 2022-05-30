<?php

namespace ahrefs\AhrefsSeo;

use ahrefs\AhrefsSeo\Content_Tips\Tips;
$locals = Ahrefs_Seo_View::get_template_variables();
$view   = Ahrefs_Seo::get()->get_view();
?>
<div id="content_tips_block" class="content-tips-block">
	<div class="ahrefs-content-tip tip-multi">
		<div class="caption">
		<?php
		esc_html_e( 'Tips', 'ahrefs-seo' );
		?>
		</div>
		<div class="subitems"></div>
		<button type="button" class="notice-dismiss sugggested-tip-close-button"><span class="screen-reader-text">
		<?php
		esc_html_e( 'Dismiss this notice.', 'ahrefs-seo' );
		?>
		</span></button>
	</div>
	<div class="tip-single">
		<?php
		$tips       = Tips::at_content_screen();
		$visibility = [];
		foreach ( $tips as $tip ) {
			$tip->show( true );
			$visibility[ $tip::ID ] = $tip->need_to_show();
		}
		// add visibility details.
		wp_localize_script( 'ahrefs-seo-content', 'content_tips_data', [ 'tips' => $visibility ] );
		?>
	</div>
</div>
<?php
