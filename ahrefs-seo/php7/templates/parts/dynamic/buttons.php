<?php
/**
 * Buttons template
 */

declare(strict_types=1);
namespace ahrefs\AhrefsSeo;

// which buttons to show?
/** @var array $locals Defined in parent template. */
$buttons = $locals['buttons'] ?? [];

$button_plugins = in_array( 'plugins', $buttons, true );
$button_themes  = in_array( 'themes', $buttons, true );
$button_ahrefs  = in_array( 'ahrefs', $buttons, true );
$button_google  = in_array( 'google', $buttons, true );
$how_to_google  = in_array( 'how_to_google', $buttons, true );
$refresh_page   = in_array( 'refresh_page', $buttons, true );
$close_button   = in_array( 'close', $buttons, true );

$show_buttons = $button_plugins || $button_themes || $button_ahrefs || $button_google || $how_to_google || $refresh_page || $close_button; // show buttons block?
if ( $show_buttons ) {
	?>
	<div class="buttons">
		<?php
		if ( $button_plugins ) {
			?>
		<a id="open_plugins_button" class="button button-primary" href="<?php echo esc_attr( admin_url( 'plugins.php' ) ); ?>"><?php esc_html_e( 'Go to plugins page', 'ahrefs-seo' ); ?></a>
			<?php
		}
		if ( $button_themes ) {
			?>
		<a id="open_plugins_button" class="button button-primary" href="<?php echo esc_attr( admin_url( 'themes.php' ) ); ?>"><?php esc_html_e( 'Go to themes page', 'ahrefs-seo' ); ?></a>
			<?php
		}
		if ( ( $button_ahrefs || $button_google ) && current_user_can( Ahrefs_Seo::CAP_SETTINGS_ACCOUNTS_VIEW ) ) {
			$link = add_query_arg(
				[
					'page' => Ahrefs_Seo::SLUG_SETTINGS,
					'tab'  => $button_ahrefs ? Ahrefs_Seo_Screen_Settings::TAB_ACCOUNT : Ahrefs_Seo_Screen_Settings::TAB_ANALYTICS,
					[ 'return' => rawurlencode( add_query_arg( 'page', Ahrefs_Seo::SLUG_CONTENT, admin_url( 'admin.php' ) ) ) ],
				],
				admin_url( 'admin.php' )
			);
			?>
		<a id="open_account_setting_button" class="uirole-target-settings-account button button-primary" href="<?php echo esc_attr( $link ); ?>"><?php esc_html_e( 'Set up connections', 'ahrefs-seo' ); ?></a>
			<?php
		}
		if ( $how_to_google ) {
			?>
			<a href="https://help.ahrefs.com/en/articles/4666920-how-do-i-connect-google-analytics-search-console-to-the-plugin" target="_blank" class="learn-more-link">
				<span class="text"><?php esc_html_e( 'How do I connect the right account?', 'ahrefs-seo' ); ?></span>
				<img src="<?php echo esc_attr( AHREFS_SEO_IMAGES_URL . 'link-open.svg' ); ?>" class="icon">
			</a>
			<?php
		}
		if ( $refresh_page ) {
			?>
			<a class="button button-primary refresh-page-button" href="javascript:document.location.reload();"><?php esc_html_e( 'Refresh the page', 'ahrefs-seo' ); ?></a>
			<?php
		}
		if ( $close_button ) {
			?>
			<button type="button" class="notice-dismiss close-current-message"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'ahrefs-seo' ); ?></span></button>
			<?php
		}
		?>
	</div>
	<?php
}
