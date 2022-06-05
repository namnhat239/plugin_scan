<?php
/**
 * WAH JS vars
 *
 * @package WAH
 */

$role_links_setup         = get_option( 'wah_role_links_setup' );
$remove_link_titles       = get_option( 'wah_remove_link_titles' );
$header_element_selector  = get_option( 'wah_header_element_selector' );
$sidebar_element_selector = get_option( 'wah_sidebar_element_selector' );
$footer_element_selector  = get_option( 'wah_footer_element_selector' );
$main_element_selector    = get_option( 'wah_main_element_selector' );
$nav_element_selector     = get_option( 'wah_nav_element_selector' );
$lights_off_selector      = get_option( 'wah_lights_selector' );

if ( $role_links_setup || $remove_link_titles || $header_element_selector || $sidebar_element_selector || $footer_element_selector || $main_element_selector || $nav_element_selector || $lights_off_selector ) : ?>
<script type="text/javascript">
	<?php if ( $role_links_setup ) : ?>
		var roleLink = 1;
	<?php endif; ?>
	<?php if ( $remove_link_titles ) : ?>
		var removeLinkTitles = 1;
	<?php endif; ?>
	<?php if ( $header_element_selector ) : ?>
		var headerElementSelector = '<?php echo esc_html( $header_element_selector ); ?>';
	<?php endif; ?>
	<?php if ( $sidebar_element_selector ) : ?>
		var sidebarElementSelector = '<?php echo esc_html( $sidebar_element_selector ); ?>';
	<?php endif; ?>
	<?php if ( $footer_element_selector ) : ?>
		var footerElementSelector = '<?php echo esc_html( $footer_element_selector ); ?>';
	<?php endif; ?>
	<?php if ( $main_element_selector ) : ?>
		var mainElementSelector = '<?php echo esc_html( $main_element_selector ); ?>';
	<?php endif; ?>
	<?php if ( $nav_element_selector ) : ?>
		var navElementSelector = '<?php echo esc_html( $nav_element_selector ); ?>';
	<?php endif; ?>
	<?php if ( $lights_off_selector ) : ?>
		var wah_lights_off_selector = '<?php echo esc_html( $lights_off_selector ); ?>';
	<?php endif; ?>
</script>
<?php endif; ?>
