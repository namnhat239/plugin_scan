<?php
/**
 * Admin Class
 *
 * Handles the admin functionality of plugin
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpnw_Admin {
	
	function __construct() {
		
		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wpnw_register_menu'), 12 );
		
		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'wpnw_post_sett_metabox') );

		// Init Processes
		add_action( 'admin_init', array($this, 'wpnw_admin_init_process') );

		// Admin for the Solutions & Features
		add_action( 'admin_init', array($this, 'wpnw_admin_init_sf_process') );

		// Filter to add row action in category table
		add_filter( WPNW_CAT.'_row_actions', array($this, 'wpnw_add_tax_row_data'), 10, 2 );

		// Filter to add display news tag
		add_filter( 'pre_get_posts', array($this, 'wpnw_display_news_tags') );

		//add_action( 'admin_footer', array( $this, 'wpnw_upgrade_page_link_blank' ) );
	}

	/**
	 * Function to add menu
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0.0
	 */
	function wpnw_register_menu() {

		// How it work page
		add_submenu_page( 'edit.php?post_type='.WPNW_POST_TYPE, __('How It Works - WP News and Scrolling Widgets', 'sp-news-and-widget'), __('How It Works', 'sp-news-and-widget'), 'edit_posts', 'wpnw-designs', array($this, 'wpnw_designs_page') );

		// Setting page
		add_submenu_page( 'edit.php?post_type='.WPNW_POST_TYPE, __('Solutions & Features - WP News and Scrolling Widgets', 'sp-news-and-widget'), '<span style="color:#2ECC71">'. __('Solutions & Features', 'sp-news-and-widget').'</span>', 'manage_options', 'wpnw-solutions-features', array($this, 'wpnw_solutions_features_page') );

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.WPNW_POST_TYPE, __('Upgrade To PRO - WP News and Scrolling Widgets', 'sp-news-and-widget'), '<span style="color:#ff2700">'.__('Upgrade To PRO', 'sp-news-and-widget').'</span>', 'manage_options', 'wpnw-premium', array($this, 'wpnw_premium_page') );

		//add_submenu_page( 'edit.php?post_type='.WPNW_POST_TYPE, __('Upgrade To PRO - WP News and Scrolling Widgets', 'sp-news-and-widget'), '<span class="wpos-upgrade-pro" style="color:#ff2700">' . __('Upgrade To Premium ', 'sp-news-and-widget') . '</span>', 'manage_options', 'wpnw-upgrade-pro', array($this, 'wpnw_redirect_page') );
		//add_submenu_page( 'edit.php?post_type='.WPNW_POST_TYPE, __('Bundle Deal - WP News and Scrolling Widgets', 'sp-news-and-widget'), '<span class="wpos-upgrade-pro" style="color:#ff2700">' . __('Bundle Deal', 'sp-news-and-widget') . '</span>', 'manage_options', 'wpnw-bundle-deal', array($this, 'wpnw_redirect_page') );
	}

	/**
	 * How it work Page Html
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0.0
	 */
	function wpnw_designs_page() {
		include_once( WPNW_DIR . '/includes/admin/wpnw-how-it-work.php' );
	}

	/**
	 * Redirect
	 * 
	 * @since 1.0
	 */
	// function wpnw_redirect_page() {
	// }

	function wpnw_solutions_features_page() {
		include_once( WPNW_DIR . '/includes/admin/settings/solutions-features.php' );
	}

	/**
	 * Premium Page Html
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0.0
	 */
	function wpnw_premium_page() {
		include_once( WPNW_DIR . '/includes/admin/settings/premium.php' );		
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 4.5
	 */
	function wpnw_post_sett_metabox() {
		add_meta_box( 'wpnw-post-metabox-pro', __('More Premium - Settings', 'sp-news-and-widget'), array($this, 'wpnw_post_sett_box_callback_pro'), WPNW_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * Function to handle 'premium ' metabox HTML
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 4.5
	 */
	function wpnw_post_sett_box_callback_pro( $post ) {		
		include_once( WPNW_DIR .'/includes/admin/metabox/wpnw-post-setting-metabox-pro.php');
	}

	/**
	 * Function to notification transient
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.4.3
	 */
	function wpnw_admin_init_process() {

		//global $typenow, $pagenow;

		//$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

		// If plugin notice is dismissed
	    if( isset($_GET['message']) && $_GET['message'] == 'wpnw-plugin-notice' ) {
	    	set_transient( 'wpnw_install_notice', true, 604800 );
	    }

	    // Redirect to external page for upgrade to menu
	    // if( $typenow == WPNW_POST_TYPE ) {

	    // 	if( $current_page == 'wpnw-upgrade-pro' ) {

	    // 		wp_redirect( WPNW_PLUGIN_LINK_UPGRADE );
	    // 		exit;
	    // 	}

	    // 	if( $current_page == 'wpnw-bundle-deal' ) {

	    // 		wp_redirect( WPNW_PLUGIN_BUNDLE_LINK );
	    // 		exit;
	    // 	}
	    // }
	}

	function wpnw_admin_init_sf_process() {

		if ( get_option( 'wpnw_sf_optin', false ) ) {

			delete_option( 'wpnw_sf_optin' );

			$redirect_link = add_query_arg( array('post_type' => WPNW_POST_TYPE, 'page' => 'wpnw-solutions-features' ), admin_url( 'edit.php' ) );

			wp_safe_redirect( $redirect_link );

			exit;
		}
	}

	/**
	 * Function to add category row action
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0
	 */
	function wpnw_add_tax_row_data( $actions, $tag ) {
		return array_merge( array( 'wpnw_id' => "<span style='color:#555'>ID: {$tag->term_id}</span>" ), $actions );
	}

	/**
	 * Function to display news tag filter
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0
	 */
	function wpnw_display_news_tags( $query ) {

		if( ! is_admin() && is_tag() && $query->is_main_query() ) {
			
			$post_types = array( 'post', WPNW_POST_TYPE );
			$query->set( 'post_type', $post_types );
		}
	}

	/**
	 * Add JS snippet to admin footer to add target _blank in upgrade link
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 2.0.5
	 */
	/*function wpnw_upgrade_page_link_blank() {

		global $wpos_upgrade_link_snippet;

		// Redirect to external page
		if( empty( $wpos_upgrade_link_snippet ) ) {

			$wpos_upgrade_link_snippet = 1;
	?>
		<script type="text/javascript">
			(function ($) {
				$('.wpos-upgrade-pro').parent().attr( { target: '_blank', rel: 'noopener noreferrer' } );
			})(jQuery);
		</script>
	<?php }
	} */

}

$wpnw_Admin = new Wpnw_Admin();