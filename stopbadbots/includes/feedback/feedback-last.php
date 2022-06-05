<?php  namespace StopBadBots_last_feedback{
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	if ( is_multisite() ) {
		return;
	}
	if ( __NAMESPACE__ == 'StopBadBots_last_feedback' ) {
		define( __NAMESPACE__ . '\PRODCLASS', 'stop_bad_bots' );
		define( __NAMESPACE__ . '\VERSION', STOPBADBOTSVERSION );
		define( __NAMESPACE__ . '\PLUGINHOME', 'https://StopBadBots.com' );
		define( __NAMESPACE__ . '\PRODUCTNAME', 'Stop Bad Bots Plugin' );
		define( __NAMESPACE__ . '\LANGUAGE', 'stopbadbots' );
		define( __NAMESPACE__ . '\PAGE', 'settings' );
		define( __NAMESPACE__ . '\OPTIN', 'stop_bad_bots_optin' );
		define( __NAMESPACE__ . '\LAST', 'stop_bad_bots_last_feedback' );
		define( __NAMESPACE__ . '\URL', STOPBADBOTSURL );
	}

	
	$last_feedback = (int) sanitize_text_field( get_site_option( LAST, '0' ) );
	if ( $last_feedback == '0' ) {
		$delta = 0;
	} else {
		$delta = 1 * 24 * 3600;
	}

// debug
// $delta = time();
//error_log(var_export($last_feedback,true));


	if ( $last_feedback + $delta > time() ) {
		// return;
		define( __NAMESPACE__ . '\SBBSHOW', true );
	}
	else
	    define( __NAMESPACE__ . '\SBBSHOW', false );

	


	class Bill_Config {
		protected static $namespace          = __NAMESPACE__;
		protected static $bill_plugin_url    = URL;
		protected static $bill_class         = PRODCLASS;
		protected static $bill_prod_veersion = VERSION;
		//protected static $sbb_show_or_not = SBBNOTSHOW;

		function __construct() {
			add_action( 'load-plugins.php', array( __CLASS__, 'init' ) );
			add_action( 'wp_ajax_bill_feedback', array( __CLASS__, 'feedback' ) );
		}
		public static function init() {
			add_action( 'in_admin_footer', array( __CLASS__, 'message' ) );
			add_action( 'admin_head', array( __CLASS__, 'register' ) );
			add_action( 'admin_footer', array( __CLASS__, 'enqueue' ) );
		}
		public static function register() {
			  wp_enqueue_style( PRODCLASS, URL . 'includes/feedback/feedback-plugin.css' );
			  if(SBBSHOW)
			    wp_register_script( PRODCLASS, URL . 'includes/feedback/feedback-last.js', array( 'jquery' ), VERSION, true );
		}
		public static function enqueue() {
			  wp_enqueue_style( PRODCLASS );
			  wp_enqueue_script( PRODCLASS );
		}
		public static function message() {

			if ( ! update_option( LAST, time() ) ) {
				add_option( LAST, time() );
			}

			$wpversion = get_bloginfo( 'version' );

			if ( ! function_exists( 'wp_get_current_user' ) ) {
				require_once ABSPATH . 'wp-includes/pluggable.php';
			}

			$current_user = wp_get_current_user();
			$plugin       = plugin_basename( __FILE__ );
			$email        = $current_user->user_email;
			$username     = trim( $current_user->user_firstname );
			$user         = $current_user->user_login;
			$user_display = trim( $current_user->display_name );
			if ( empty( $username ) ) {
				$username = $user;
			}
			if ( empty( $username ) ) {
				$username = $user_display;
			}
			$memory['limit'] = (int) ini_get( 'memory_limit' );
			$memory['usage'] = function_exists( 'memory_get_usage' ) ? round( memory_get_usage() / 1024 / 1024, 0 ) : 0;
			if ( defined( 'WP_MEMORY_LIMIT' ) ) {
				$memory['wplimit'] = WP_MEMORY_LIMIT;
			} else {
				$memory['wplimit'] = '';
			}
			?>  
		   <div class="<?php echo esc_attr( PRODCLASS ); ?>-wrap-deactivate" style="display:none">
			  <div class="bill-vote-gravatar"><a href="https://profiles.wordpress.org/sminozzi" target="_blank"><img src="https://en.gravatar.com/userimage/94727241/31b8438335a13018a1f52661de469b60.jpg?size=100" alt="Bill Minozzi" width="70" height="70"></a></div>
				<div class="bill-vote-message">
				 <h4><?php _e( 'If you have a moment, Please, let us know you and why you are deactivating.', 'stopbadbots' ); ?></h4>
			   <?php
				 _e( 'Hi, my name is Bill Minozzi, and I am developer of', 'stopbadbots' );
					   echo ' ' . esc_attr( PRODUCTNAME );
					   echo '. ';
				?>
				 <br />
				 <?php _e( 'If you Kindly tell us the reason so we can improve it and maybe give some support by email to you.', 'stopbadbots' ); ?>
				 <br /><br />             
				 <strong><?php _e( 'Thank You!', 'stopbadbots' ); ?></strong>
				 <br /><br /> 
				 <textarea rows="4" cols="50" id="<?php echo esc_attr( PRODCLASS ); ?>-explanation" name="explanation" placeholder="<?php _e( 'type here yours sugestions or questions...', 'stopbadbots' ); ?>" ></textarea>
				 <br /><br /> 
				 <input type="checkbox" class="anonymous" value="anonymous" /><small>Participate anonymous <?php _e( '(In this case, we are unable to email you)', 'stopbadbots' ); ?></small>
				 <br /><br /> 			
					   <img src="/wp-admin/images/wpspin_light-2x.gif" id="imagewaitfbl" style="display:none" />
						<a href="https://BillMinozzi.com/dove/" class="button button-primary <?php echo PRODCLASS; ?>-close-dialog"><?php _e( 'Support Page', 'stopbadbots' ); ?></a>
						<a href="#" class="button button-primary <?php echo esc_attr( PRODCLASS ); ?>-close-submit"><?php _e( 'Submit and Deactivate', 'stopbadbots' ); ?></a>
 
						<a href="#" class="button <?php echo esc_attr( PRODCLASS ); ?>-close-dialog"><?php _e( 'Cancel', 'stopbadbots' ); ?></a>
						<a href="#" class="button <?php echo esc_attr( PRODCLASS ); ?>-deactivate"><?php _e( 'Just Deactivate', 'stopbadbots' ); ?></a>
						<input type="hidden" id="<?php echo esc_attr( PRODCLASS ); ?>-version" name="version" value="<?php echo esc_attr( VERSION ); ?>" />
						<input type="hidden" id="email" name="email" value="<?php echo esc_attr( $email ); ?>" />
						<input type="hidden" id="username" name="username" value="<?php echo esc_attr( $username ); ?>" />
						<input type="hidden" id="wpversion" name="wpversion" value="<?php echo esc_attr( $wpversion ); ?>" />
						<input type="hidden" id="limit" name="limit" value="<?php echo esc_attr( $memory['limit'] ); ?>" />
						<input type="hidden" id="wplimit" name="wplimit" value="<?php echo esc_attr( $memory['wplimit'] ); ?>" />
						   <input type="hidden" id="usage" name="usage" value="<?php echo esc_attr( $memory['usage'] ); ?>" />
						<input type="hidden" id="billclass" name="billclass" value="<?php echo esc_attr( PRODCLASS ); ?>" />
						<input type="hidden" id="billlanguage" name="billlanguage" value="<?php echo esc_attr( LANGUAGE ); ?>" />
				 <br /><br />
			   </div>
		 </div> 
				<?php
		}
	}
	new Bill_Config();
	/*
	if ( ! update_option( 'bill_last_feedback', '1' ) ) {
		add_option( 'bill_last_feedback', '1' );
	}
	*/
	if ( ! update_option( LAST, '1' ) ) {
		add_option( LAST, '1' );
	}
} // End Namespace ...

?>
