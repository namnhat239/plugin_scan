<?php

namespace ahrefs\AhrefsSeo;

use ahrefs\AhrefsSeo\Messages\Message;
/**
 * Google account settings.
 */
class Settings_Google extends Settings_Any {

	/**
	 * Updated message.
	 *
	 * @var string
	 */
	protected $updated = '';
	/**
	 * Error message, if any
	 *
	 * @var string
	 */
	protected $error = '';
	/**
	 * No GA & GSC accounts found.
	 *
	 * @var bool
	 */
	protected $google_no_accounts_error = false;
	/**
	 * Load options from request.
	 *
	 * @param Ahrefs_Seo_Screen $screen Screen instance.
	 * @return void
	 */
	public function apply_options( Ahrefs_Seo_Screen $screen ) {
        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- nonce already checked before this function call.
		$called_from_wizard = $screen instanceof Ahrefs_Seo_Screen_Wizard;
		$analytics          = Ahrefs_Seo_Analytics::get();
		$analytics_step     = isset( $_REQUEST['analytics_step'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['analytics_step'] ) ) : '';
		if ( $analytics->get_data_tokens()->is_token_set() ) {
			// reset step.
			if ( ! $analytics->is_analytics_enabled() || ! $analytics->is_gsc_enabled() ) {
				$analytics_step = '1';
			} else {
				$analytics_step = '2';
			}
		}
		if ( '1' === $analytics_step ) {
			// part 1: set code.
			$analytics_code = isset( $_REQUEST['analytics_code'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['analytics_code'] ) ) : '';
			if ( '' !== $analytics_code ) {
				if ( ! $analytics->check_token( $analytics_code ) ) {
					$this->error = $analytics->get_message(); // get error from current actions only.
					if ( '' === $this->error || strpos( $this->error, 'invalid_grant' ) ) {
						// replace empty or default message "Error fetching OAuth2 access token, message: 'invalid_grant: Malformed auth code.'".
						$this->error = __( 'The code is incorrect.', 'ahrefs-seo' );
					}
				}
			} else {
				$this->error = __( 'Please enter your authorization code', 'ahrefs-seo' );
			}
		} elseif ( '2' === $analytics_step ) {
			// part 2: set ua_id.
			$analytics_ua_id        = isset( $_REQUEST['ua_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ua_id'] ) ) : '';
			$analytics_ua_name      = isset( $_REQUEST['ua_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ua_name'] ) ) : '';
			$analytics_ua_url       = isset( $_REQUEST['ua_url'] ) ? sanitize_text_field( wp_unslash( isset( $_REQUEST['ua_url'] ) ? $_REQUEST['ua_url'] : '' ) ) : '';
			$analytics_gsc_site     = isset( $_REQUEST['gsc_site'] ) ? sanitize_text_field( wp_unslash( isset( $_REQUEST['gsc_site'] ) ? $_REQUEST['gsc_site'] : '' ) ) : '';
			$adv_gsc_uses_uppercase = ! empty( $_REQUEST['gsc_uses_uppercase'] );
			$adv_ga_not_urlencoded  = ! empty( $_REQUEST['ga_not_urlencoded'] );
			$adv_ga_uses_full_url   = ! empty( $_REQUEST['ga_uses_full_url'] );
			$updated                = $analytics_ua_id !== $analytics->get_data_tokens()->get_ua_id();
			$updated_gsc            = $analytics_gsc_site !== $analytics->get_data_tokens()->get_gsc_site();
			$analytics->set_adv_options( $adv_gsc_uses_uppercase, $adv_ga_not_urlencoded, $adv_ga_uses_full_url );
			if ( '' !== $analytics_ua_id && '' !== $analytics_ua_name && '' !== $analytics_gsc_site ) {
				$analytics->set_ua( $analytics_ua_id, $analytics_ua_name, $analytics_ua_url, $analytics_gsc_site );
				Ahrefs_Seo::get()->initialized_set( null, true );
			}
			if ( '' !== $analytics_ua_id && '' !== $analytics_ua_name || '' !== $analytics_gsc_site ) {
				$analytics->set_ua( $analytics_ua_id, $analytics_ua_name, $analytics_ua_url, $analytics_gsc_site );
				Ahrefs_Seo::get()->initialized_set( null, true );
				if ( $called_from_wizard && $screen instanceof Ahrefs_Seo_Screen_Wizard ) {
					$screen->set_step_and_reload( 3 );
				}
			}
			if ( ! $called_from_wizard ) {
				if ( $updated_gsc && $analytics->is_gsc_set() ) {
					// reset keywords and positions if snapshot with 'new' status exists.
					( new Snapshot() )->reset_keywords_and_position_for_new_snapshot();
				}
				// reanalyze everything if new UA ID value set.
				if ( $updated ) {
					( new Snapshot() )->reset_ga_for_new_snapshot();
				}
			}
		}
        // phpcs:enable WordPress.Security.NonceVerification.Recommended -- nonce already checked before this function call.
	}
	/**
	 * Show options block.
	 *
	 * @param Ahrefs_Seo_Screen $screen Screen instance.
	 * @param Ahrefs_Seo_View   $view View instance.
	 * @param Message           $error Message with already happened error if any.
	 * @return void
	 */
	public function show_options( Ahrefs_Seo_Screen $screen, Ahrefs_Seo_View $view, Message $error = null ) {
		$analytics = Ahrefs_Seo_Analytics::get();
		$token_set = $analytics->get_data_tokens()->is_token_set();
		$vars      = $screen->get_template_vars();
		// preselect accounts it the Wizard or just after new token set.
		$preselect                  = $screen instanceof Ahrefs_Seo_Screen_Wizard || $token_set && ( ! $analytics->is_ua_set() && ! $analytics->is_gsc_set() );
		$vars['updated']            = $this->updated;
		$vars['error']              = empty( $this->error ) && ! is_null( $error ) ? $error : $this->error;
		$vars['token_set']          = $token_set;
		$vars['no_ga']              = ! ( $token_set && $analytics->is_analytics_enabled() );
		$vars['no_gsc']             = ! ( $token_set && $analytics->is_gsc_enabled() );
		$vars['ga_has_account']     = $token_set && $analytics->is_analytics_has_accounts();
		$vars['gsc_uses_uppercase'] = $analytics->get_adv_gsc_uses_uppercase();
		$vars['ga_not_urlencoded']  = $analytics->get_adv_ga_not_urlencoded();
		$vars['ga_uses_full_url']   = $analytics->get_adv_ga_uses_full_url();
		$vars['preselect_accounts'] = $preselect;
		$template                   = $screen instanceof Ahrefs_Seo_Screen_Settings ? 'settings' : 'wizard';
		$vars['button_title']       = $screen instanceof Ahrefs_Seo_Screen_Settings ? __( 'Save', 'ahrefs-seo' ) : __( 'Continue', 'ahrefs-seo' );
		$vars['disconnect_link']    = $screen instanceof Ahrefs_Seo_Screen_Settings ? 'settings' : 'wizard';
		$view->show( 'settings-google', __( 'Connect Google Analytics & Search Console', 'ahrefs-seo' ), $vars, $screen, $template );
	}
}
