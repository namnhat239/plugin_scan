<?php
/**
 * WAH custom css
 *
 * @package WAH
 */

$wah_hide_on_mobile = get_option( 'wah_hide_on_mobile' );
$wah_custom_css     = get_option( 'wah_custom_css' ) ? sanitize_textarea_field( get_option( 'wah_custom_css' ) ) : '';

if ( $wah_hide_on_mobile || $wah_custom_css ) : ?>
<style>
<?php endif; ?>
<?php if ( $wah_hide_on_mobile ) : ?>
	@media only screen and (max-width: 480px) {div#wp_access_helper_container {display: none;}}
<?php endif; ?>
	<?php if ( $wah_custom_css ) : ?>
		<?php echo esc_html( $wah_custom_css ); ?>
	<?php endif; ?>
<?php if ( $wah_hide_on_mobile || $wah_custom_css ) : ?>
	</style>
<?php endif; ?>
