<?php  namespace StopBadBots_feedback{
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	if ( is_multisite() ) {
		return;
	}

	if ( __NAMESPACE__ == 'StopBadBots_feedback' ) {

		define( __NAMESPACE__ . '\PRODCLASS', 'stop_bad_bots' );
		define( __NAMESPACE__ . '\VERSION', STOPBADBOTSVERSION );
		define( __NAMESPACE__ . '\PLUGINHOME', 'https://StopBadBots.com' );
		define( __NAMESPACE__ . '\PRODUCTNAME', 'Stop Bad Bots Plugin' );
		define( __NAMESPACE__ . '\LANGUAGE', 'stopbadbots' );
		define( __NAMESPACE__ . '\PAGE', 'settings' );
		// define(__NAMESPACE__ .'\OPTIN', "stop_bad_bots_optin" );
		define( __NAMESPACE__ . '\URL', STOPBADBOTSURL );
		// define(__NAMESPACE__ .'\DINSTALL', "bill_installed_stopbadbots" );
	}

	// somente um, por enquanto
	define( __NAMESPACE__ . '\OPTIN', 'bill-vote' );
	define( __NAMESPACE__ . '\DINSTALL', 'bill_installed' );


	class Bill_Vote {

		protected static $namespace         = __NAMESPACE__;
		protected static $bill_plugin_url   = URL;
		protected static $bill_class        = PRODCLASS;
		protected static $bill_prod_version = VERSION;

		function __construct() {
			add_action( 'load-plugins.php', array( __CLASS__, 'init' ) );
			add_action( 'wp_ajax_vote', array( __CLASS__, 'vote' ) );
		}
		public static function init() {

			$vote = get_option( OPTIN );
			// echo $vote;
			// $vote = '';

			$timeinstall = get_option( DINSTALL, '' );

			if ( $timeinstall == '' ) {
				 $w = update_option( DINSTALL, time() );
				if ( ! $w ) {
					add_option( DINSTALL, time() );
				}

				$timeinstall = time();

			}

			$timeout = time() > ( $timeinstall + 60 * 60 * 24 * 3 );

			if ( in_array( $vote, array( 'yes', 'no' ) ) || ! $timeout ) {
				return;
			}
			add_action( 'in_admin_footer', array( __CLASS__, 'message' ) );
			add_action( 'admin_head', array( __CLASS__, 'register' ) );
			add_action( 'admin_footer', array( __CLASS__, 'enqueue' ) );
		}
		public static function register() {

			  wp_enqueue_style( PRODCLASS, URL . 'includes/feedback/feedback-plugin.css' );
			  wp_register_script( PRODCLASS, URL . 'includes/feedback/feedback.js', array( 'jquery' ), VERSION, true );
		}
		public static function enqueue() {
			  wp_enqueue_style( PRODCLASS );
			  wp_enqueue_script( PRODCLASS );
		}

		public static function vote() {
			  $vote = sanitize_key( $_GET['vote'] );

			  // http://boatplugin.com/wp-admin/admin-ajax.php?action=vote&vote=no
			if ( ! is_user_logged_in() || ! in_array( $vote, array( 'yes', 'no', 'later' ) ) ) {
				die( 'error' );
			}
			$r = update_option( OPTIN, $vote );
			if ( ! $r ) {
				 add_option( OPTIN, $vote );
			}

			if ( $vote === 'later' ) {
				update_option( DINSTALL, time() );
			}
			wp_die( 'OK: ' . $vote );
		}
		public static function message() {
			?>
		<div class="<?php echo esc_attr( PRODCLASS ); ?>-wrap-vote" style="display:none">
			<div class="bill-vote-wrap">
				<div class="bill-vote-gravatar"><a href="https://profiles.wordpress.org/sminozzi" target="_blank"><img src="https://en.gravatar.com/userimage/94727241/31b8438335a13018a1f52661de469b60.jpg?size=100" alt="<?php _e( 'Bill Minozzi', 'stopbadbots' ); ?>" width="70" height="70"></a></div>
				<div class="bill-vote-message">
					<p>
				  <?php
					 _e( 'Hello, my name is Bill Minozzi, and I am developer of', 'stopbadbots' );
					 echo ' ' . esc_attr( PRODUCTNAME );
					 echo '. ';
					 _e( 'If you like this product, please write a few words about it. It will help other people find this useful plugin more quickly.<br><b>Thank you!</b>', 'stopbadbots' );
					?>
					   </p>
					<p>
						<a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=vote&amp;vote=yes" class="bill-vote-action button button-medium button-primary" data-action="<?php echo esc_attr( PLUGINHOME ); ?>/share/"><?php _e( 'Rate or Share', 'stopbadbots' ); ?></a>
						<a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=vote&amp;vote=no" class="bill-vote-action button button-medium"><?php _e( 'No, dismiss', 'stopbadbots' ); ?></a>
<span><?php _e( 'or', 'stopbadbots' ); ?></span>
						<a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=vote&amp;vote=later" class="bill-vote-action button button-medium"><?php _e( 'Remind me later', 'stopbadbots' ); ?></a>
						<input type="hidden" id="billclassvote" name="billclassvote" value="<?php echo esc_attr( PRODCLASS ); ?>" />
						<input type="hidden" id="billclassvote" name="billclassvote" value="<?php echo esc_attr( PRODCLASS ); ?>" />

					</p>
				</div>
				<div class="bill-vote-clear"></div>
		</div>
				<?php
		}
	}
	new Bill_Vote();
}
