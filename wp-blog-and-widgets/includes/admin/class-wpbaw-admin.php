<?php
/**
 * Admin Class
 *
 * Handles the admin functionality of plugin
 *
 * @package WP Blog and Widget
 * @since 1.3.2
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpbaw_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wpbaw_register_menu'), 12 );

		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'wpbaw_post_sett_metabox') );

		// Init Processes
		add_action( 'admin_init', array($this, 'wpbaw_admin_init_process') );

		// Admin for the Solutions & Features
		add_action( 'admin_init', array($this, 'wpbaw_admin_init_sf_process') );

		// Get Posts Filter
		add_filter( 'pre_get_posts', array($this, 'wpbaw_blog_display_tags') );

		// Manage Category Shortcode Columns
		add_filter("manage_edit-".WPBAW_CAT."_columns", array($this, 'blog_category_manage_columns')); 
		add_filter("manage_".WPBAW_CAT."_custom_column", array($this, 'blog_category_columns'), 10, 3);

		// Action to add little JS code in admin footer
		//add_action( 'admin_footer', array($this, 'wpbaw_upgrade_page_link_blank') );
	}

	/**
	 * Function to add menu
	 * 
	 * @package WP Blog and Widget
	 * @since 1.3.2
	 */
	function wpbaw_register_menu() {

		// How it work page
		add_submenu_page( 'edit.php?post_type='.WPBAW_POST_TYPE, __('How it works, our plugins and offers', 'wp-blog-and-widgets'), __('How It Works', 'wp-blog-and-widgets'), 'manage_options', 'wpbawh-designs', array($this, 'wpbawh_designs_page') );

		// Setting page
		add_submenu_page( 'edit.php?post_type='.WPBAW_POST_TYPE, __('Solutions & Features - WP Blog and Widget', 'wp-blog-and-widgets'), '<span style="color:#2ECC71">'. __('Solutions & Features', 'wp-blog-and-widgets').'</span>', 'manage_options', 'wpbaw-solutions-features', array($this, 'wpbaw_solutions_features_page') );

		// Plugin features menu
		add_submenu_page( 'edit.php?post_type='.WPBAW_POST_TYPE, __('Upgrade To PRO - WP Blog and Widget', 'wp-blog-and-widgets'), '<span style="color:#ff2700">'.__('Upgrade To PRO', 'wp-blog-and-widgets').'</span>', 'edit_posts', 'wpbawh-premium', array($this, 'wpbaw_premium_page') );
		//add_submenu_page( 'edit.php?post_type='.WPBAW_POST_TYPE, __('Upgrade To PRO - WP Blog and Widget', 'wp-blog-and-widgets'), '<span class="wpos-upgrade-pro" style="color:#ff2700">' . __('Upgrade To Premium ', 'wp-blog-and-widgets') . '</span>', 'manage_options', 'wpbawh-upgrade-pro', array($this, 'wpbaw_redirect_page') );
		//add_submenu_page( 'edit.php?post_type='.WPBAW_POST_TYPE, __('Bundle Deal - WP Blog and Widget', 'wp-blog-and-widgets'), '<span class="wpos-upgrade-pro" style="color:#ff2700">' . __('Bundle Deal', 'wp-blog-and-widgets') . '</span>', 'manage_options', 'wpbawh-bundle-deal', array($this, 'wpbaw_redirect_page') );
	}

	/**
	 * Function to display plugin design HTML
	 * 
	 * @package WP Blog and Widget
	 * @since 2.0
	 */
	function wpbaw_solutions_features_page() {
		include_once( WPBAW_DIR . '/includes/admin/settings/solutions-features.php' );
	}

	/**
	 * Function to display plugin design HTML
	 * 
	 * @package WP Blog and Widget
	 * @since 2.0
	 */
	function wpbawh_designs_page() {
		include_once( WPBAW_DIR . '/includes/admin/wpbaw-how-it-work.php' );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package WP Blog and Widget
	 * @since 1.3.2
	 */
	function wpbaw_premium_page() {
		include_once( WPBAW_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Redirect
	 * 
	 * @since 1.0
	 */
	// function wpbaw_redirect_page() {
	// }

	/**
	 * Post Settings Metabox
	 * 
	 * @package WP Blog and Widget
	 * @since 2.1
	 */
	function wpbaw_post_sett_metabox() {
		add_meta_box( 'wpbaw-post-metabox-pro', __('More Premium - Settings', 'wp-slick-slider-and-image-carousel'), array($this, 'wpbaw_post_sett_box_callback_pro'), WPBAW_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * Function to handle 'premium ' metabox HTML
	 * 
	 * @package WP Blog and Widget
	 * @since 2.1
	 */
	function wpbaw_post_sett_box_callback_pro( $post ) {		
		include_once( WPBAW_DIR .'/includes/admin/metabox/wpbaw-post-setting-metabox-pro.php');
	}

	/**
	 * Function to notification transient
	 * 
	 * @package WP Blog and Widget
	 * @since 1.3.2
	 */
	function wpbaw_admin_init_process() {

		//global $typenow, $pagenow;

		//$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

		// If plugin notice is dismissed
	    if( isset($_GET['message']) && $_GET['message'] == 'wpbawh-plugin-notice' ) {
	    	set_transient( 'wpbawh_install_notice', true, 604800 );
	    }

	    // Redirect to external page for upgrade to menu
	    // if( $typenow == WPBAW_POST_TYPE ) {

	    // 	if( $current_page == 'wpbawh-upgrade-pro' ) {

	    // 		wp_redirect( WPBAW_PLUGIN_LINK_UPGRADE );
	    // 		exit;
	    // 	}

	    // 	if( $current_page == 'wpbawh-bundle-deal' ) {

	    // 		wp_redirect( WPBAW_PLUGIN_BUNDLE_LINK );
	    // 		exit;
	    // 	}
	    // }
	}

	function wpbaw_admin_init_sf_process() {

		if ( get_option( 'wpbaw_sf_optin', false ) ) {

			delete_option( 'wpbaw_sf_optin' );

			$redirect_link = add_query_arg( array('post_type' => WPBAW_POST_TYPE, 'page' => 'wpbaw-solutions-features' ), admin_url( 'edit.php' ) );

			wp_safe_redirect( $redirect_link );

			exit;
		}
	}

	/**
	 * Function to blog display tags
	 * 
	 * @package WP Blog and Widget
	 * @since 1.3.2
	 */
	function wpbaw_blog_display_tags( $query ) {

		if( is_tag() && $query->is_main_query() ) {       
		   $post_types = array( 'post', 'blog_post' );
			$query->set( 'post_type', $post_types );
		}
	}

	/**
	 * Function to add category manage column
	 * 
	 * @package WP Blog and Widgets
	 * @since 1.0
	 */
	function blog_category_manage_columns($theme_columns) {
		$new_columns = array(
				'cb' => '<input type="checkbox" />',
				'name' => __('Name'),
				'blog_shortcode' => __( 'Blog Category Shortcode', 'wp-blog-and-widgets' ),
				'slug' => __('Slug'),
				'posts' => __('Posts')
				);
		return $new_columns;
	}

	/**
	 * Function to add category column
	 * 
	 * @package WP Blog and Widgets
	 * @since 1.0
	 */
	function blog_category_columns($out, $column_name, $theme_id) {
		$theme = get_term($theme_id, 'blog-category');
		switch ($column_name) {      

			case 'title':
				echo get_the_title();
			break;
			case 'blog_shortcode':

				 echo '[blog category="' . $theme_id. '"]';
				  echo '[recent_blog_post category="' . $theme_id. '"]';
			break;

			default:
				break;
		}
		return $out;
	}

	/**
	 * Add JS snippet to admin footer to add target _blank in upgrade link
	 * 
	 * @package WP Blog and Widgets
	 * @since 1.0.0
	 */
	/*function wpbaw_upgrade_page_link_blank() {

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

$wpbaw_Admin = new Wpbaw_Admin();