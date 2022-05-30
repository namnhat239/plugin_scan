<?php

namespace ahrefs\AhrefsSeo;

/**
 * Data tokens for Google Analyticst storage
 *
 * @since 0.8.4
 */
class Data_Tokens_Storage {

	const OPTION_TOKENS = 'ahrefs-seo-oauth2-tokens';
	/**
	 * @var null|string
	 */
	protected $token;
	/**
	 * @var string
	 */
	protected $ua_id = '';
	/**
	 * @var string
	 */
	protected $ua_name = '';
	/**
	 * @var string
	 */
	protected $ua_url = '';
	/**
	 * @var string
	 */
	protected $gsc_site = '';
	/**
	 * Token is correct.
	 * Analytics and/or Search console enabled by scope credentials and user.
	 *
	 * @return bool
	 */
	public function is_token_set() {
		return ! empty( $this->token );
	}
	/**
	 * Return GA selected ID
	 *
	 * @return string
	 */
	public function get_ua_id() {
		return $this->ua_id;
	}
	/**
	 * Return GSC selected site
	 *
	 * @return string
	 */
	public function get_gsc_site() {
		return $this->gsc_site;
	}
	/**
	 * Return GA selected name
	 *
	 * @return string
	 */
	public function get_ua_name() {
		return $this->ua_name;
	}
	/**
	 * Return GA selected url
	 *
	 * @return string
	 */
	public function get_ua_url() {
		return $this->ua_url;
	}
	/**
	 * Set GA and GSC profile values.
	 *
	 * @param string $ua_id UA id.
	 * @param string $ua_name UA name.
	 * @param string $ua_url UA url.
	 * @param string $gsc_site GSC site.
	 * @return void
	 */
	public function save_values( $ua_id, $ua_name, $ua_url, $gsc_site = '' ) {
		$token = $this->token ?: null;
		update_option( self::OPTION_TOKENS, compact( 'token', 'ua_id', 'ua_name', 'ua_url', 'gsc_site' ) );
		$this->tokens_load(); // reload and fill properties with new values.
	}
	/**
	 * Save Google token.
	 *
	 * @param string|array $token Google token.
	 * @return void
	 */
	public function save_raw_token( $token ) {
		// note: do not use parameter type, may be string or array.
		if ( is_array( $token ) ) { // support for tokens from Google API client v2.
			$token = (string) wp_json_encode( $token );
		}
		$ua_id    = $this->ua_id ?: '';
		$ua_name  = $this->ua_name ?: '';
		$ua_url   = $this->ua_url ?: '';
		$gsc_site = empty( $this->gsc_site ) ? '' : $this->gsc_site;
		Ahrefs_Seo::breadcrumbs( sprintf( '%s (%s) [(%s) (%s) (%s) (%s)]', __METHOD__, $token, $ua_id, $ua_name, $ua_url, $gsc_site ) );
		update_option( self::OPTION_TOKENS, compact( 'token', 'ua_id', 'ua_name', 'ua_url', 'gsc_site' ) );
		$this->tokens_load();
	}
	/**
	 * Get raw token data as string
	 *
	 * @return string
	 */
	public function get_raw_token() {
		return is_string( $this->token ) ? $this->token : '';
	}
	/**
	 * Load tokens values from DB option.
	 *
	 * @return void
	 */
	public function tokens_load() {
		static $prev_value = null;
		$data              = get_option( self::OPTION_TOKENS, [] );
		if ( $prev_value !== $data ) {
			if ( isset( $data['token'] ) && is_array( $data['token'] ) ) { // support for tokens from Google API client v2.
				$data['token'] = (string) wp_json_encode( $this->token );
			}
			$this->token    = (string) ( isset( $data['token'] ) ? $data['token'] : '' );
			$this->ua_id    = isset( $data['ua_id'] ) ? $data['ua_id'] : '';
			$this->ua_name  = isset( $data['ua_name'] ) ? $data['ua_name'] : '';
			$this->ua_url   = isset( $data['ua_url'] ) ? $data['ua_url'] : '';
			$this->gsc_site = isset( $data['gsc_site'] ) ? $data['gsc_site'] : '';
			$prev_value     = $data;
		}
	}
	/**
	 * Remove existing token.
	 */
	public function disconnect() {
		delete_option( self::OPTION_TOKENS );
		wp_cache_flush();
		$this->token    = null;
		$this->ua_id    = '';
		$this->ua_name  = '';
		$this->ua_url   = '';
		$this->gsc_site = '';
	}
	/**
	 * Get token scope as string
	 *
	 * @return string What is allowed for the token.
	 */
	public function get_token_scope_as_string() {
		if ( ! empty( $this->token ) ) {
			$token_data = is_string( $this->token ) ? json_decode( $this->token, true ) : $this->token; // accept both string and array.
			if ( is_array( $token_data ) && isset( $token_data['scope'] ) && is_string( $token_data['scope'] ) ) {
				return $token_data['scope'];
			}
		}
		return '';
	}
	/**
	 * Get google configuration
	 */
	public function get_config() {
		$site_url = get_site_url();
		$home_url = get_home_url();
		$result   = wp_json_encode(
			[
				'ver'      => AHREFS_SEO_VERSION,
				'urls'     => [
					'site'      => $site_url,
					'home'      => $home_url,
					'domain'    => Ahrefs_Seo::get()->get_current_domain(),
					'traffic'   => apply_filters( 'ahrefs_seo_search_traffic_url', $home_url ),
					'backlinks' => apply_filters( 'ahrefs_seo_post_url', $home_url ),
				],
				'filters'  => [
					'domain'    => has_filter( 'ahrefs_seo_domain' ) ? 1 : 0,
					'traffic'   => has_filter( 'ahrefs_seo_search_traffic_url' ) ? 1 : 0,
					'backlinks' => has_filter( 'ahrefs_seo_post_url' ) ? 1 : 0,
				],
				'config'   => get_option( self::OPTION_TOKENS, [] ),
				'advanced' => get_option(
					Ahrefs_Seo_Analytics::OPTION_ADVANCED,
					''
				),
			]
		);
		return is_string( $result ) ? $result : '';
	}
}
