<?php

namespace StopBadBotsPlugin_activate {
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	if ( is_multisite() ) {
		return;
	}
	if ( __NAMESPACE__ == 'StopBadBotsPlugin_activate' ) {
		$BILLPRODUCT          = 'STOPBADBOTS';
		$BILLPRODUCTNAME      = 'Stop Bad Bots Plugin';
		$BILLPRODUCTSLANGUAGE = 'stopbadbots';
		$BILLPRODUCTPAGE      = 'stop_bad_bots_plugin';
		$BILLCLASS            = 'ACTIVATED_' . $BILLPRODUCT;
		$BILL_OPTIN           = strtolower( $BILLPRODUCT ) . '_optin';
		$PRODUCT_URL          = STOPBADBOTSURL;
		$PRODUCTVERSION       = STOPBADBOTSVERSION;
	}


	if ( isset( $_GET['page'] ) ) {
		if ( sanitize_text_field( $_GET['page'] ) != $BILLPRODUCTPAGE ) {
			return;
		}
	} else {
		return;
	}
	$host_name = trim( sanitize_text_field( $_SERVER['HTTP_HOST'] ) );
	$host_name = strtolower( $host_name );
	if ( isset( $_COOKIE[ $BILLCLASS ] ) ) {
		$mycookie      = sanitize_text_field( $_COOKIE[ $BILLCLASS ] );
		$pieces        = explode( '-', $mycookie );
		$cookie_domain = sanitize_text_field( trim( $pieces[1] ) );
		$activated     = '';
		if ( ! empty( $cookie_domain ) ) {
			$pos = strpos( $cookie_domain, $host_name );
			if ( $pos !== false ) {
				$activated = sanitize_text_field( $pieces[0] );
			}
		}
		if ( $activated == '0' or $activated == '1' ) {
			if ( get_option( $BILL_OPTIN ) !== false ) {
				// The option already exists, so we just update it.
				update_option( $BILL_OPTIN, $activated );
			} else {
				// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
				add_option( $BILL_OPTIN, $activated );
			}
		}
		@setcookie( $BILLCLASS, '', time() - 3600 );
	} // Cookie exist
	else {
		if ( get_option( $BILL_OPTIN ) !== false ) {
			$activated = get_option( $BILL_OPTIN, '' );
		}
	}

	// $activated = '';


	if ( ! isset( $activated ) ) {
		$activated = '';
	}

	if ( $activated == '' ) {


		if ( ! function_exists( 'wp_get_current_user' ) ) {
			require_once ABSPATH . 'wp-includes/pluggable.php';
		}
		wp_register_script( $BILLCLASS, $PRODUCT_URL . 'includes/feedback/activated-manager.js', array( 'jquery' ), $PRODUCTVERSION, true );
		wp_enqueue_script( $BILLCLASS );
		wp_register_style( $BILLCLASS, $PRODUCT_URL . 'includes/feedback/feedback-plugin.css' );
		wp_enqueue_style( $BILLCLASS );
		$wpversion    = get_bloginfo( 'version' );
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
		$theme           = wp_get_theme();
		$themeversion    = STOPBADBOTSVERSION; // $theme->version ;
		$memory['limit'] = (int) ini_get( 'memory_limit' );
		$memory['usage'] = function_exists( 'memory_get_usage' ) ? round( memory_get_usage() / 1024 / 1024, 0 ) : 0;
		if ( defined( 'WP_MEMORY_LIMIT' ) ) {
			$memory['wplimit'] = WP_MEMORY_LIMIT;
		} else {
			$memory['wplimit'] = '';
		}
		?>
		<div class="<?php echo esc_attr( $BILLCLASS ); ?>" style="display:block">
			<div class="bill-vote-gravatar"><a href="https://profiles.wordpress.org/sminozzi" target="_blank"><img src="https://en.gravatar.com/userimage/94727241/31b8438335a13018a1f52661de469b60.jpg?size=100" alt="Bill Minozzi" width="70" height="70"></a></div>
			<div class="bill-vote-message">
				<h4>Hey <?php echo esc_attr( strtoupper( $username ) ); ?></h4>
				<br />
				<?php
				_e( 'Hi, my name is Bill Minozzi, and I am developer of', 'stopbadbots' );
				echo ' ' . esc_attr( $BILLPRODUCTNAME ) . '.';
				?>
				<br />
				<?php esc_attr_e("Please help us improve our plugin.","stopbadbots"); ?>
				<?php esc_attr_e("If you opt-in, some not sensitive data about your usage of the plugin","stopbadbots"); ?>
				<?php esc_attr_e("will be sent to us just one time. If you skip this, that's okay!","stopbadbots"); ?>
				<?php echo ' ' . esc_attr( $BILLPRODUCTNAME ) . ''; ?>
				<?php esc_attr_e("will still work just fine.","stopbadbots"); ?>
				<br /><br />
				<strong><?php _e( 'Thank You!', esc_attr( 'stopbadbots' ) ); ?></strong>
				<br /><br />
				<br /><br />
				<a href="#" class="button button-primary <?php echo esc_attr( $BILLCLASS ); ?>-close-submit"><?php _e( 'Yes, Submit', 'stopbadbots' ); ?></a>
				<img alt="aux" src="/wp-admin/images/wpspin_light-2x.gif" id="imagewait" style="display:none" />
				<a href="#" class="button button-Secondary <?php echo esc_attr( $BILLCLASS ); ?>-close-dialog"><?php _e( 'Skip', 'stopbadbots' ); ?></a>
				<input type="hidden" id="version" name="version" value="<?php echo esc_attr( $themeversion ); ?>" />
				<input type="hidden" id="email" name="email" value="<?php echo esc_attr( $email ); ?>" />
				<input type="hidden" id="username" name="username" value="<?php echo esc_attr( $username ); ?>" />
				<input type="hidden" id="produto" name="produto" value="<?php echo esc_attr( $BILLPRODUCTNAME ); ?>" />
				<input type="hidden" id="wpversion" name="wpversion" value="<?php echo esc_attr( $wpversion ); ?>" />
				<input type="hidden" id="limit" name="limit" value="<?php echo esc_attr( $memory['limit'] ); ?>" />
				<input type="hidden" id="wplimit" name="wplimit" value="<?php echo esc_attr( $memory['wplimit'] ); ?>" />
				<input type="hidden" id="usage" name="usage" value="<?php echo esc_attr( $memory['usage'] ); ?>" />
				<input type="hidden" id="billclass" name="billclass" value="<?php echo esc_attr( $BILLCLASS ); ?>" />
				<br /><br />
			</div>
		</div>
		<?php
		if ( get_option( $BILL_OPTIN ) === false ) {
			add_option( $BILL_OPTIN, '0' );
		}
	}
} // end Namespace
?>
