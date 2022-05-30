<?php

namespace ahrefs\AhrefsSeo;

// link to Settings: Account.
$link = add_query_arg(
	[
		'page' => Ahrefs_Seo::SLUG_SETTINGS,
		'tab'  => Ahrefs_Seo_Screen_Settings::TAB_ACCOUNT,
	],
	admin_url( 'admin.php' )
);
?>
<div class="notice notice-warning">
	<p><strong>
	<?php
	esc_html_e( 'Ahrefs disconnected.', 'ahrefs-seo' );
	?>
	</strong></p>
	<p>
	<?php
	esc_html_e( 'Please insert your Ahrefs token on the settings page again.', 'ahrefs-seo' );
	?>
	</p>
</div>
<?php
