<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SportsPress Admin.
 *
 * @class       SP_Admin
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.5.1
 */
class SP_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'current_screen', array( $this, 'conditonal_includes' ) );
		add_action( 'admin_init', array( $this, 'prevent_admin_access' ) );

		// Action buttons
		add_action( 'admin_print_footer_scripts', array( $this, 'action_links' ) );

		// Review link
		add_action( 'sportspress_settings_page', 'sp_review_link' );
		add_action( 'sportspress_config_page', 'sp_review_link' );
		add_action( 'sportspress_overview_page', 'sp_review_link' );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		// Functions
		include_once 'sp-admin-functions.php';

		// Classes
		include_once 'class-sp-admin-post-types.php';
		include_once 'class-sp-admin-taxonomies.php';
		include_once 'class-sp-admin-ajax.php';

		// Classes we only need if the ajax is not-ajax
		if ( ! is_ajax() ) {
			include 'class-sp-admin-menus.php';
			include 'class-sp-admin-welcome.php';
			include 'class-sp-admin-notices.php';
			include 'class-sp-admin-assets.php';
			include 'class-sp-admin-permalink-settings.php';

			if ( 'yes' == get_option( 'sportspress_rich_editing', 'yes' ) ) :
				include 'class-sp-admin-editor.php';
			endif;
		}

		// Setup/welcome
		if ( ! empty( $_GET['page'] ) ) {
			switch ( $_GET['page'] ) {
				case 'sp-setup':
					include_once 'class-sp-admin-setup-wizard.php';
					break;
			}
		}
	}

	/**
	 * Include admin files conditionally
	 */
	public function conditonal_includes() {
		$screen = get_current_screen();

		switch ( $screen->id ) {
			case 'dashboard':
				include 'class-sp-admin-dashboard.php';
				break;
		}
	}

	/**
	 * Prevent any user who cannot 'edit_posts' (subscribers, fans etc) from accessing admin
	 */
	public function prevent_admin_access() {
		$prevent_access = false;

		if ( 'yes' == get_option( 'sportspress_lock_down_admin' ) && ! is_ajax() && ! ( current_user_can( 'edit_posts' ) || current_user_can( 'manage_sportspress' ) ) && basename( $_SERVER['SCRIPT_FILENAME'] ) !== 'admin-post.php' ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$prevent_access = true;
		}

		$prevent_access = apply_filters( 'sportspress_prevent_admin_access', $prevent_access );

		if ( $prevent_access ) {
			wp_safe_redirect( get_permalink( sp_get_page_id( 'myaccount' ) ) );
			exit;
		}
	}

	/**
	 * Add action link after post list title
	 */
	public function action_links() {
		global $pagenow, $typenow;
		if ( in_array( $typenow, sp_importable_post_types() ) ) {
			if ( 'sp_event' === $typenow ) {
				if ( 'edit.php' === $pagenow ) {
					?>
					<script type="text/javascript">
					(function($) {
						$(".wrap .page-title-action").first().after(
							$("<a class=\"add-new-h2\" href=\"<?php echo esc_url( admin_url( add_query_arg( array( 'import' => 'sp_fixture_csv' ), 'admin.php' ) ) ); ?>\"><?php esc_html_e( 'Import Fixtures', 'sportspress' ); ?></a>")
						).after(
							$("<a class=\"add-new-h2\" href=\"<?php echo esc_url( admin_url( add_query_arg( array( 'import' => 'sp_event_csv' ), 'admin.php' ) ) ); ?>\"><?php esc_html_e( 'Import Events', 'sportspress' ); ?></a>")
						);
					})(jQuery);
					</script>
					<?php
				}
			} else {
				if ( 'edit.php' === $pagenow ) {
					?>
					<script type="text/javascript">
					(function($) {
						$(".wrap .page-title-action").first().after(
							$("<a class=\"add-new-h2\" href=\"<?php echo esc_url( admin_url( add_query_arg( array( 'import' => $typenow . '_csv' ), 'admin.php' ) ) ); ?>\"><?php esc_html_e( 'Import', 'sportspress' ); ?></a>")
						);
					})(jQuery);
					</script>
					<?php
				}
			}
		}
	}
}

return new SP_Admin();
