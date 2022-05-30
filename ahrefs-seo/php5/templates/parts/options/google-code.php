<?php

namespace ahrefs\AhrefsSeo;

use ahrefs\AhrefsSeo\Disconnect_Reason\Disconnect_Reason_GSC;
use ahrefs\AhrefsSeo\Messages\Message;
$locals         = Ahrefs_Seo_View::get_template_variables();
$view           = Ahrefs_Seo::get()->get_view();
$analytics      = Ahrefs_Seo_Analytics::get();
$get_code_link  = $analytics->get_oauth2_url();
$analytics_code = isset( $_REQUEST['analytics_code'] ) && ! $locals['no_ga'] && ! $locals['no_gsc'] ? sanitize_text_field( wp_unslash( $_REQUEST['analytics_code'] ) ) : ''; // phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- load from request, should work without nonce too.
$message        = ( new Disconnect_Reason_GSC() )->get_reason();
if ( ! is_null( $message ) ) { // show only once and clear message.
	$message->show();
	( new Disconnect_Reason_GSC() )->save_reason( null );
	?>
	<script type="text/javascript">
		jQuery('h1').after( jQuery('.tip-google').detach() );
	</script>
	<?php
}
$messages = Ahrefs_Seo_Errors::get_current_messages();
if ( $messages ) {
	$view->show_part( 'notices/please-contact', $messages );
}
if ( ! current_user_can( Ahrefs_Seo::CAP_SETTINGS_ACCOUNTS_SAVE ) ) {
	Message::action_not_allowed( __( 'The account is not connected. Please, contact your site administrator to set it up.', 'ahrefs-seo' ) )->show();
	return;
}
?>

<form method="post" action="" class="ahrefs-seo-wizard ahrefs-analytics">
	<input type="hidden" name="analytics_step" value="1">
	<?php
	if ( isset( $locals['page_nonce'] ) ) {
		wp_nonce_field( $locals['page_nonce'] );
	}
	?>
	<div class="card-item">
		<div class="help">
			<div class="google-logos">
				<img src="<?php echo esc_attr( AHREFS_SEO_IMAGES_URL . 'google-analytics.svg' ); ?>">
				<img src="<?php echo esc_attr( AHREFS_SEO_IMAGES_URL . 'google-gsc.svg' ); ?>">
			</div>
			<?php
			esc_html_e( 'Connect your Google Analytics & Search Console accounts to see your pages’ rankings and traffic stats right in WP dashboard. The Content Audit and content suggestion are based on these data.', 'ahrefs-seo' );
			?>
		</div>
		<?php
		if ( $locals['token_set'] && ( $locals['no_ga'] || $locals['no_gsc'] ) ) {
			$view->show_part( 'options-tips/no-google', $locals );
		}
		?>
		<ol class="subitems">
			<li>
				<a href="<?php echo esc_attr( $get_code_link ); ?>" target="_blank" class="get-code-button">
					<span class="text">
					<?php
					esc_html_e( 'Get authorization code from Google', 'ahrefs-seo' );
					?>
					</span>
					<img src="<?php echo esc_attr( AHREFS_SEO_IMAGES_URL . 'link-open.svg' ); ?>" class="icon">
				</a>
			</li>
			<li>
				<div class="new-token-button">
					<label class="label">
					<?php
					esc_html_e( 'Enter the received code', 'ahrefs-seo' );
					?>
					</label>
					<div class="input_button">
						<input type="text" class="input-input-default-s-default
							<?php
							if ( '' !== $locals['error'] ) {
								?>
							error
								<?php
							} ?>" value="<?php echo esc_attr( $analytics_code ); ?>" name="analytics_code" id="analytics_code">
						<a href="#" class="button button-primary" id="step2_1_submit">
						<?php
						esc_html_e( 'Connect GA & GSC accounts', 'ahrefs-seo' );
						?>
						</a>
					</div>
					<div class="ahrefs-seo-error">
						<?php
						if ( '' !== $locals['error'] ) {
							echo esc_html( $locals['error'] );
						}
						?>
					</div>
				</div>
			</li>
		</ol>
	</div>
	<div class="help-ga-gsc">
		<?php
		esc_html_e( "GA and GSC data is stored in your website's database, and is never sent to Ahrefs.", 'ahrefs-seo' );
		?>
	</div>
</form>

<?php
