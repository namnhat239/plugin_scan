<?php
declare(strict_types=1);

namespace ahrefs\AhrefsSeo;

use ahrefs\AhrefsSeo\Disconnect_Reason\Disconnect_Reason_GA;
use ahrefs\AhrefsSeo\Disconnect_Reason\Disconnect_Reason_GSC;
use ahrefs\AhrefsSeo\Keywords\Data_Keyword;
use ahrefs\AhrefsSeo\Keywords\Data_Clicks_Info;
use ahrefs\AhrefsSeo\Messages\Message;
use ahrefs\AhrefsSeo\Third_Party\Sources;
use ahrefs\AhrefsSeo\Workers\Worker_Position;
use ahrefs\AhrefsSeo\Workers\Worker_Traffic;
use InvalidArgumentException;
use ahrefs\AhrefsSeo_Vendor\Google_Client;
use ahrefs\AhrefsSeo_Vendor\Google_Service_Analytics;
use ahrefs\AhrefsSeo_Vendor\Google\Service\SearchConsole as Google_Service_SearchConsole;
use ahrefs\AhrefsSeo_Vendor\Google\Service\SearchConsole\SearchAnalyticsQueryRequest as Google_Service_SearchConsole_SearchAnalyticsQueryRequest;
use ahrefs\AhrefsSeo_Vendor\Google\Service\SearchConsole\SearchAnalyticsQueryResponse as Google_Service_SearchConsole_SearchAnalyticsQueryResponse;
use ahrefs\AhrefsSeo_Vendor\Google\Service\SearchConsole\ApiDataRow as Google_Service_SearchConsole_ApiDataRow;
use ahrefs\AhrefsSeo_Vendor\Google_Service_Exception;
use ahrefs\AhrefsSeo_Vendor\Google\Service\GoogleAnalyticsAdmin;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsData;
use ahrefs\AhrefsSeo_Vendor\Google\Service\AnalyticsData\RunReportRequest as Google_Service_AnalyticsData_RunReportRequest;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsData_DateRange;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsData_Metric;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsData_Dimension;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsData_Entity;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsData_Filter;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsData_FilterExpression;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsData_InListFilter;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsReporting;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsReporting_DateRange;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsReporting_Metric;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsReporting_Dimension;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsReporting_ReportRequest;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsReporting_DimensionFilter;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsReporting_DimensionFilterClause;
use ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsReporting_GetReportsRequest as Google_Service_AnalyticsReporting_GetReportsRequest;
use ahrefs\AhrefsSeo_Vendor\Google_Task_Runner as Runner;
use ahrefs\AhrefsSeo_Vendor\Google_Http_Batch;
use Composer\CaBundle\CaBundle as CaBundle;
use ahrefs\AhrefsSeo_Vendor\GuzzleHttp\Client as GuzzleClient;
use ahrefs\AhrefsSeo_Vendor\GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use ahrefs\AhrefsSeo_Vendor\GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use ahrefs\AhrefsSeo_Vendor\GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use ahrefs\AhrefsSeo_Vendor\GuzzleHttp\ClientInterface as GuzzleClientInterface;
use ahrefs\AhrefsSeo_Vendor\GuzzleHttp\Exception\ClientException as GuzzleClientException;

/**
 * Class for interacting with Google Analytics and Google Search Console API.
 */
class Ahrefs_Seo_Analytics extends Ahrefs_Seo_Abstract_Api {

	private const OPTION_LAST_ERROR = 'ahrefs-seo-analytics-last-error';
	public const OPTION_ADVANCED    = 'ahrefs-seo-analytics-advanced';

	private const OPTION_HAS_ACCOUNT_GA     = 'ahrefs-seo-has-analytics-account'; // has GA or GA4 profiles to select from.
	private const OPTION_HAS_ACCOUNT_GA_RAW = 'ahrefs-seo-has-analytics-account-raw'; // has account, note: account may not have any profile.
	private const OPTION_HAS_ACCOUNT_GSC    = 'ahrefs-seo-has-gsc-account';
	private const OPTION_GSC_SITES          = 'ahrefs-seo-has-gsc-sites';

	/** Allow send queries once per second. */
	const API_MIN_DELAY = 2.5;

	const SCOPE_ANALYTICS      = 'https://www.googleapis.com/auth/analytics.readonly';
	const SCOPE_SEARCH_CONSOLE = 'https://www.googleapis.com/auth/webmasters.readonly';

	const GSC_KEYWORDS_LIMIT = 10;

	/**
	 * Load page size for traffic requests.
	 */
	private const QUERY_TRAFFIC_PER_PAGE = 20;
	/**
	 * Load first 100 results (pages) and search existing page slugs here.
	 */
	private const QUERY_DETECT_GA_LIMIT = 100;
	/**
	 * Load first 1000 results (search phrases) and search existing page slugs here.
	 */
	private const QUERY_DETECT_GSC_LIMIT = 1000;
	/**
	 * Page size for account details loading.
	 */
	private const QUERY_LIST_GA_ACCOUNTS_PAGE_SIZE = 100;

	/** @var float[] Time when last visitors query to GA, GA4 or GSC run. */
	private $last_query_time = [];

	/**
	 * Error message.
	 *
	 * @var string
	 */
	protected $message = '';
	/**
	 * @var array
	 */
	protected $service_error = [];
	/**
	 * @var string
	 */
	private $api_user = '';
	/**
	 * @var null|\ahrefs\AhrefsSeo_Vendor\Psr\Log\AbstractLogger
	 */
	private $logger;

	/**
	 * @var Google_Client|null
	 */
	private $client = null;

	/**
	 * @var string
	 */
	private $last_token = '';

	/**
	 * @var array
	 */
	private $default_config = [
		// OAuth2 Settings, you can get these keys at https://code.google.com/apis/console .
		'oauth2_client_id'     => '616074445976-gce92a0p1ptkrgj6rl0jdpk7povts56a.apps.googleusercontent.com',
		'oauth2_client_secret' => 'JpBej-3XMNqXhGdRpgpSc7Y4',
	];

	/**
	 * @var Data_Tokens_Storage
	 */
	public $data_tokens;
	/**
	 * User's account (profiles) list for GA is not empty.
	 * Null if unknown.
	 *
	 * @var null|bool
	 */
	protected $has_ga_account;
	/**
	 * User has at least single GA account. This is not mean, that user has any accessible profile.
	 * Null if unknown.
	 *
	 * @var null|bool
	 */
	protected $has_ga_account_raw;
	/**
	 * User's account (profiles) list for GSC is not empty.
	 * Null if unknown.
	 *
	 * @var null|bool
	 */
	protected $has_gsc_account;

	/**
	 * Cached accounts (profiles) list for GA.
	 * Used for choice at Google accounts page.
	 *
	 * @var array|null
	 */
	protected $accounts_ga;
	/**
	 * Cached accounts (profiles) list for GA4.
	 * Used for choice at Google accounts page.
	 *
	 * @var array|null
	 */
	protected $accounts_ga4;
	/**
	 * Cached accounts list for GA.
	 * Used for choice at Google accounts page.
	 *
	 * @var array|null
	 */
	protected $accounts_ga_raw;
	/**
	 * Cached accounts list for GA4.
	 * Used for choice at Google accounts page.
	 *
	 * @var array|null
	 */
	protected $accounts_ga4_raw;
	/**
	 * Cached accounts list for GSC.
	 *
	 * @var array|null
	 */
	protected $accounts_gsc;

	/**
	 * Paused because last request returned rate error.
	 *
	 * @var bool
	 */
	private $gsc_paused = false;

	/** @var Ahrefs_Seo_Analytics */
	private static $instance = null;

	/**
	 * Return the instance
	 *
	 * @return Ahrefs_Seo_Analytics
	 */
	public static function get() : Ahrefs_Seo_Analytics {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->api_user           = substr( 'w' . implode( '-', [ get_current_user_id(), get_current_blog_id(), wp_parse_url( get_home_url(), PHP_URL_HOST ) ?? '' ] ), 0, 40 );
		$this->has_ga_account     = get_option( self::OPTION_HAS_ACCOUNT_GA, null );
		$this->has_ga_account_raw = get_option( self::OPTION_HAS_ACCOUNT_GA_RAW, null );
		$this->has_gsc_account    = get_option( self::OPTION_HAS_ACCOUNT_GSC, null );
		$this->get_data_tokens()->tokens_load(); // this will create and initialize data_tokens property.
	}


	/**
	 * Get API user string for request to API
	 *
	 * @return string
	 */
	public function get_api_user() : string {
		return (string) $this->api_user;
	}

	/**
	 * Access to Google Analytics allowed and accounts (profiles) list is not empty
	 *
	 * @param bool $force_detection Force account detection.
	 *
	 * @return bool
	 */
	public function is_analytics_enabled( bool $force_detection = false ) : bool {
		if ( ( $force_detection || is_null( $this->has_ga_account ) ) && false !== strpos( $this->data_tokens->get_token_scope_as_string(), self::SCOPE_ANALYTICS ) ) {
			$accounts_ga_all          = $this->load_accounts_list();
			$this->has_ga_account     = ! empty( $accounts_ga_all );
			$this->has_ga_account_raw = ! empty( $this->accounts_ga_raw ) || ! empty( $this->accounts_ga4_raw );
			update_option( self::OPTION_HAS_ACCOUNT_GA, $this->has_ga_account );
			update_option( self::OPTION_HAS_ACCOUNT_GA_RAW, $this->has_ga_account_raw );
		}
		return false !== strpos( $this->data_tokens->get_token_scope_as_string(), self::SCOPE_ANALYTICS ) && $this->has_ga_account || defined( 'AHREFS_SEO_NO_GA' ) && AHREFS_SEO_NO_GA;
	}

	/**
	 * User has at least single GA account.
	 * Cached result.
	 *
	 * @since 0.7.1
	 *
	 * @return bool
	 */
	public function is_analytics_has_accounts() : bool {
		return ! empty( $this->has_ga_account_raw );
	}

	/**
	 * Access to Google Search Console allowed and accounts list is not empty
	 *
	 * @param bool $force_detection Force account detection.
	 *
	 * @return bool
	 */
	public function is_gsc_enabled( bool $force_detection = false ) : bool {
		if ( ( $force_detection || is_null( $this->has_gsc_account ) ) && false !== strpos( $this->data_tokens->get_token_scope_as_string(), self::SCOPE_SEARCH_CONSOLE ) ) {
			if ( is_null( $this->accounts_gsc ) ) { // no existing value from another service call.
				$this->accounts_gsc = $this->load_gsc_accounts_list();
			}
			$this->has_gsc_account = ! empty( $this->accounts_gsc );
			update_option( self::OPTION_HAS_ACCOUNT_GSC, $this->has_gsc_account );
		}
		return false !== strpos( $this->data_tokens->get_token_scope_as_string(), self::SCOPE_SEARCH_CONSOLE ) && $this->has_gsc_account;
	}

	/**
	 * Access to GA enabled and account set in plugin options.
	 *
	 * @return bool
	 */
	public function is_ua_set() : bool {
		return '' !== $this->data_tokens->get_ua_id() && $this->is_analytics_enabled();
	}

	/**
	 * Access to GSC enabled and site set in plugin options.
	 *
	 * @return bool
	 */
	public function is_gsc_set() : bool {
		if ( '' !== $this->data_tokens->get_gsc_site() && $this->is_gsc_enabled() ) {
			return true;
		}
		return false;
	}

	/**
	 * Check that GSC used correct domain and set disconnect reason.
	 * If GSC site URL selected is not the same as WordPress site or GA profile  - should be treated as GSC not connected.
	 *
	 * @return bool False on error.
	 */
	public function gsc_check_domain() : bool {
		$site_domain = $this->get_clean_domain();
		// single or multiple domains.
		$analytics_domains = $this->is_ua_set() ? array_map( [ $this, 'get_clean_domain' ], explode( '|', $this->data_tokens->get_ua_url() ) ) : [];
		$gsc_domain        = $this->get_clean_domain( $this->data_tokens->get_gsc_site() );
		$result            = ! empty( $analytics_domains ) && in_array( $gsc_domain, $analytics_domains, true ) || $site_domain === $gsc_domain;
		if ( ! $result ) {
			/* translators: 1: domain name, 2: domain name */
			$this->set_gsc_disconnect_reason( sprintf( __( 'Google Search Console has an invalid domain (current domain: %1$s, selected: %2$s).', 'ahrefs-seo' ), $site_domain, $gsc_domain ), false );
			return false;
		} else {
			// check credentials is not "siteUnverifiedUser".
			if ( '' !== $gsc_domain ) {
				$list = $this->load_gsc_accounts_list();
				foreach ( $list as $item ) {
					if ( $this->data_tokens->get_gsc_site() === (string) $item['site'] && 'siteUnverifiedUser' === $item['level'] ) {
						/* Translators: %s: current permission level string */
						$this->set_gsc_disconnect_reason( sprintf( __( 'Google Search Console has an invalid permission level (%s).', 'ahrefs-seo' ), $item['level'] ), false );
						return false;
					}
				}
			}
		}
		return true;
	}

	/**
	 * Set GA and GSC accounts
	 *
	 * @param string $ua_id UA id.
	 * @param string $ua_name UA name.
	 * @param string $ua_url UA url.
	 * @param string $gsc_site GSC site.
	 * @return void
	 */
	public function set_ua( string $ua_id, string $ua_name, string $ua_url, string $gsc_site = '' ) : void {
		Ahrefs_Seo::breadcrumbs( sprintf( '%s (%s) (%s) (%s) (%s)', __METHOD__, $ua_id, $ua_name, $ua_url, $gsc_site ) );
		$is_gsc_updated = $this->data_tokens->get_gsc_site() !== $gsc_site;
		$is_ga_updated  = $this->data_tokens->get_ua_id() !== $ua_id;
		$this->reset_pause( $is_ga_updated, $is_gsc_updated );

		$this->data_tokens->save_values( $ua_id, $ua_name, $ua_url, $gsc_site );
		if ( $is_gsc_updated ) {
			$this->set_gsc_disconnect_reason( null ); // reset any error.
			if ( '' !== $gsc_site ) { // do not check if site is empty.
				if ( ! $this->gsc_check_domain() ) { // set error if domain is incorrect.
					$gsc_site = ''; // ... and reset gsc account.
					$this->data_tokens->save_values( $ua_id, $ua_name, $ua_url, $gsc_site );
				}
			}
		}
		if ( $is_ga_updated ) {
			$this->set_ga_disconnect_reason( null ); // reset any error.
		}
	}

	/**
	 * Check that currently selected GA account has same domain in website property as current site has.
	 * Ignore empty value.
	 *
	 * @param string|null $ua_url Check current GA account if null.
	 * @return bool|null Null if nothing to check
	 */
	public function is_ga_account_correct( ?string $ua_url = null ) : ?bool {
		if ( is_null( $ua_url ) ) {
			$ua_url = $this->data_tokens->get_ua_url(); // use current account.
		}
		if ( '' === $ua_url ) {
			return null; // nothing to check.
		}
		$domain = strtolower( Ahrefs_Seo::get_current_domain() );
		if ( 0 === strpos( $domain, 'www.' ) ) { // remove www. prefix from domain.
			$domain = substr( $domain, 4 );
		}

		$sites = explode( '|', $ua_url );
		foreach ( $sites as $site_url ) {
			$_website = strtolower( (string) wp_parse_url( $site_url, PHP_URL_HOST ) );
			if ( '' === $_website ) { // incorrect URL, maybe the domain name used here?
				$_website = strtolower( $site_url );
			}
			if ( 0 === strpos( $_website, 'www.' ) ) { // remove www. prefix from domain.
				$_website = substr( $_website, 4 );
			}
			if ( $_website === $domain ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check that currently selected GSC account has same domain as current site has.
	 *
	 * @return bool|null Null if nothing to check
	 */
	public function is_gsc_account_correct() : ?bool {
		if ( empty( $this->data_tokens->get_gsc_site() ) ) {
			return null;
		}
		$domain = $this->get_clean_domain();

		$_website = $this->get_clean_domain( $this->data_tokens->get_gsc_site() );
		return $_website === $domain;
	}

	/**
	 * Return Google Client static instance, until token is same.
	 *
	 * @return Google_Client
	 */
	private function create_client() : Google_Client {
		// load fresh tokens.
		$this->data_tokens->tokens_load();
		if ( is_null( $this->client ) || ( $this->last_token !== $this->data_tokens->get_raw_token() ) ) {
			$client_id     = $this->default_config['oauth2_client_id'];
			$client_secret = $this->default_config['oauth2_client_secret'];
			$redirect_uri  = 'urn:ietf:wg:oauth:2.0:oob';
			$scopes        = [ self::SCOPE_ANALYTICS, self::SCOPE_SEARCH_CONSOLE ];
			$config        = [
				'retry'     => [
					'retries'       => 3,
					'initial_delay' => 0,
				],
				'retry_map' => array(
					'500'                   => Runner::TASK_RETRY_ALWAYS,
					'503'                   => Runner::TASK_RETRY_ALWAYS,
					'rateLimitExceeded'     => Runner::TASK_RETRY_ALWAYS,
					'userRateLimitExceeded' => Runner::TASK_RETRY_ALWAYS,
					6                       => Runner::TASK_RETRY_ALWAYS,  // CURLE_COULDNT_RESOLVE_HOST.
					7                       => Runner::TASK_RETRY_ALWAYS,  // CURLE_COULDNT_CONNECT.
					28                      => Runner::TASK_RETRY_ALWAYS,  // CURLE_OPERATION_TIMEOUTED.
					35                      => Runner::TASK_RETRY_ALWAYS,  // CURLE_SSL_CONNECT_ERROR.
					52                      => Runner::TASK_RETRY_ALWAYS,  // CURLE_GOT_NOTHING.
					'quotaExceeded'         => Runner::TASK_RETRY_NEVER,
					'internalServerError'   => Runner::TASK_RETRY_NEVER,
					'backendError'          => Runner::TASK_RETRY_NEVER,
				),
			];

			$this->client = new Google_Client( $config );
			// request offline access token.
			$this->client->setAccessType( 'offline' );
			$this->client->setClientSecret( $client_secret );
			$this->client->setScopes( $scopes );
			$this->client->setRedirectUri( $redirect_uri );
			$this->client->setClientId( $client_id );
			$this->client->setTokenCallback( [ $this, 'token_callback' ] );
			$this->client->setApplicationName( 'ahrefs-seo/' . AHREFS_SEO_VERSION . '-' . AHREFS_SEO_RELEASE );

			$path = $this::get_cert_path();
			if ( ! empty( $path ) ) { // recreate http client with updated verify path in config.
				$http_client                              = $this->client->getHttpClient();
				$options                                  = $http_client->getConfig();
				$options[ GuzzleRequestOptions::VERIFY ]  = $path;
				$options[ GuzzleRequestOptions::TIMEOUT ] = 120;
				$options[ GuzzleRequestOptions::CONNECT_TIMEOUT ] = 15;
				$this->client->setHttpClient( $this->get_http_client( $options ) );
			}

			Ahrefs_Seo::breadcrumbs( sprintf( '%s Google Client version: %s, current token: %s', __METHOD__, $this->client->getLibraryVersion(), (string) wp_json_encode( $this->data_tokens->get_raw_token() ) ) );
			if ( ! empty( $this->data_tokens->get_raw_token() ) ) {
				$this->client->setAccessToken( $this->data_tokens->get_raw_token() );
			}
			$this->last_token = $this->data_tokens->get_raw_token();
		}
		// clean old logged data each time when new client required. Add logger for google api client v2.
		$this->logger = new Logger();
		$this->client->setLogger( $this->logger );
		return $this->client;
	}

	/**
	 * Set Google client
	 *
	 * @since 0.8.4
	 * @param Google_Client $client Google client instance.
	 * @return void
	 */
	public function set_client( Google_Client $client ) : void {
		$this->client = $client;
	}

	/**
	 * Return new Guzzle HTTP client instance
	 *
	 * @since 0.7.3
	 *
	 * @param array<string, mixed> $options Guzzle client options.
	 * @return GuzzleClientInterface
	 */
	protected function get_http_client( array $options ) : GuzzleClientInterface {
		return new GuzzleClient( $options );
	}

	/**
	 * Called on token update. Callback.
	 * Update the in-memory access token and save it.
	 *
	 * @since 0.7.2
	 *
	 * @param string $cache_key Unused parameter.
	 * @param string $access_token Google access token (without refresh token).
	 * @return void
	 */
	public function token_callback( $cache_key, $access_token ) : void {
		// Note: callback, do not use parameter types.
		Ahrefs_Seo::breadcrumbs( __METHOD__ . (string) wp_json_encode( func_get_args() ) );

		if ( ! is_null( $this->client ) ) {
			$token = $this->client->getAccessToken();
			// similar as default handler, but do not overwrite refresh token and scope.
			$token['access_token'] = (string) $access_token;
			$token['expires_in']   = 3600; // Google default.
			$token['created']      = time();
			$this->client->setAccessToken( $token );
			$this->data_tokens->save_raw_token( $token );
		}
	}

	/**
	 * Get the ca bundle path if one exists.
	 *
	 * @since 0.7.2
	 *
	 * @return string|null
	 */
	public static function get_cert_path(): ?string {
		if ( version_compare( PHP_VERSION, '5.3.2' ) < 0 ) {
			return null;
		}

		return realpath( CaBundle::getSystemCaRootBundlePath() ) ?: null;
	}

	/**
	 * Return url for OAuth2, where user will see a code
	 *
	 * @return string
	 */
	public function get_oauth2_url() : string {
		try {
			$client = $this->create_client();
			return $client->createAuthUrl();
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			$this->set_message( $message );
		}
		return '#error-happened';
	}

	/**
	 * Check received code.
	 * Update options if it is ok.
	 *
	 * @param string $code Code.
	 * @return bool
	 */
	public function check_token( string $code ) : bool {
		Ahrefs_Seo::breadcrumbs( sprintf( '%s (%s)', __METHOD__, (string) wp_json_encode( $code ) ) );
		try {
			$client = $this->create_client();
			if ( $this->data_tokens->is_token_set() ) {
				// another token exists? Disconnect it.
				$this->disconnect();
				$this->set_message( '' );
				// recreate client.
				$client = $this->create_client();
			}

			$client->authenticate( $code );
			$token = $client->getAccessToken();

			if ( ! empty( $token ) ) {
				Ahrefs_Seo::breadcrumbs( sprintf( '%s: (%s)', __METHOD__, (string) wp_json_encode( $token ) ) );
				$this->data_tokens->save_raw_token( $token );
			} else { // no error, but code was wrong.
				return false;
			}
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			$this->set_message( $message );
		} catch ( InvalidArgumentException $e ) {
			$this->set_message( $this->extract_message( $e ), $e );
			return false;
		} catch ( \Exception $e ) {
			$this->set_message( $this->extract_message( $e ), $e );
			return false;
		}
		return true;
	}

	/**
	 * Get Data token
	 *
	 * @since 0.8.4
	 *
	 * @return Data_Tokens_Storage
	 */
	public function get_data_tokens() : Data_Tokens_Storage {
		if ( is_null( $this->data_tokens ) ) { // @phpstan-ignore-line
			$this->data_tokens = new Data_Tokens_Storage();
		}
		return $this->data_tokens;
	}

	/**
	 * Set Data token
	 *
	 * @since 0.8.4
	 *
	 * @param Data_Tokens_Storage $data_tokens Data tokens instance.
	 */
	public function set_data_token( Data_Tokens_Storage $data_tokens ) : void {
		$this->data_tokens = $data_tokens;
	}

	/**
	 * Return array with ua accounts list
	 *
	 * @return array<array>
	 */
	public function load_accounts_list() : array {
		$result = [];
		try {
			// mix ga4 with ga.
			$ga4  = $this->load_accounts_list_ga4();
			$ga   = $this->load_accounts_list_ga();
			$data = array_merge( $ga, $ga4 );
			// sort results.
			usort(
				$data,
				function( $a, $b ) {
					// order by account name.
					$diff = strcasecmp( $a['account_name'], $b['account_name'] );
					if ( 0 !== $diff ) {
						return $diff;
					}
					// then order by name.
					return strcasecmp( $a['name'], $b['name'] );
				}
			);
			// split by account, profile.
			foreach ( $data as $item ) {
				$account      = $item['account'];
				$account_name = $item['account_name'];
				$ua_id        = $item['ua_id'];
				$name         = $item['name'];
				$website      = $item['website'];
				if ( ! isset( $result[ $account ] ) ) {
					$result[ $account ] = [
						'account' => $account,
						'label'   => $account_name,
						'values'  => [],
					];
				}
				if ( ! isset( $result[ $account ]['values'][ $name ] ) ) {
					$result[ $account ]['values'][ $name ] = [];
				}
				$new_item = [
					'ua_id'   => $ua_id,
					'website' => $website,
				];
				$type     = null;
				if ( isset( $item['view'] ) ) {
					$type             = 'views';
					$new_item['view'] = $item['view'];
				} elseif ( isset( $item['stream'] ) ) {
					$type               = 'streams';
					$new_item['stream'] = $item['stream'];
				}
				if ( ! is_null( $type ) ) {
					if ( ! isset( $result[ $account ]['values'][ $name ][ $type ] ) ) {
						$result[ $account ]['values'][ $name ][ $type ] = [];
					}
					$result[ $account ]['values'][ $name ][ $type ][] = $new_item;
				}
			}
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__, __( 'Google Analytics API: failed to get the list of accounts.', 'ahrefs-seo' ) );
			$this->set_message( $message );
		}
		return $result;
	}

	/**
	 * Return array with ua accounts list from Google Analytics Admin API
	 *
	 * @since 0.7.3
	 * @throws \Exception The thrown exceptions are handled and never returned.
	 *
	 * @return array<array>
	 */
	protected function load_accounts_list_ga4() : array {
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( is_array( $this->accounts_ga4 ) ) { // cached results from last call.
			return $this->accounts_ga4;
		}
		if ( defined( 'AHREFS_SEO_NO_GA' ) && AHREFS_SEO_NO_GA ) {
			return [];
		}
		$result     = [];
		$accounts   = [];
		$properties = [];
		try {
			$client = $this->create_client();
			$admin  = new GoogleAnalyticsAdmin( $client );

			// accounts and properties list.
			$next_list = '';
			do {
				$params = [
					'pageSize' => self::QUERY_LIST_GA_ACCOUNTS_PAGE_SIZE,
				];
				if ( ! empty( $next_list ) ) {
					$params['pageToken'] = $next_list;
				}
				$account_summaries = $admin->accountSummaries->listAccountSummaries( $params );
				$_accounts         = $account_summaries->getAccountSummaries();
				if ( count( $_accounts ) ) {
					foreach ( $_accounts as $_account ) {
						$account_name              = $_account->getAccount();
						$accounts[ $account_name ] = $_account->getDisplayName();

						$_properties = $_account->getPropertySummaries();
						if ( count( $_properties ) ) {
							foreach ( $_properties as $_property ) {
								$properties[ $_property['property'] ] = [
									'account' => $account_name,
									'label'   => $_property['displayName'],
								];
							}
						}
					}
				}
				$next_list = $account_summaries->getNextPageToken();
			} while ( ! empty( $next_list ) );
			$this->accounts_ga4_raw = $accounts;

			// get web streams for each property: need website urls.
			$streams  = []; // index is property id, value is array with data url.
			$requests = []; // Pending requests, [ property_id => next page token ].
			try {
				$client->setUseBatch( true );
				// prepare all initial requests.
				foreach ( $properties as $_property_id => $_values ) {
					$requests[ $_property_id ] = '';
				}

				$error_set = false;
				while ( ! empty( $requests ) ) {
					$pieces = array_splice( $requests, 0, 5 ); // execute up to 5 requests at once.
					$batch  = $admin->createBatch();
					foreach ( $pieces as $_property_id => $next_page ) {
						$params = [
							'pageSize' => self::QUERY_LIST_GA_ACCOUNTS_PAGE_SIZE,
						];
						if ( ! empty( $next_page ) ) {
							$params['pageToken'] = $next_page;
						}
						$request = $admin->properties_dataStreams->listPropertiesDataStreams( "{$_property_id}", $params );
						$batch->add( $request, $_property_id );
					}

					$responses = [];
					try {
						$responses = $batch->execute();
						do_action_ref_array( 'ahrefs_seo_api_list_ga4', [ &$responses ] );
					} catch ( \Exception $e ) { // catch all errors.
						$this->set_message( $this->extract_message( $e ), $e );
						$this->on_error_received( $e );
						throw $e;
					}

					foreach ( $responses as $_property_id => $streams_list ) {
						if ( $streams_list instanceof \Exception ) {
							if ( ! $error_set ) {
								$this->set_message( __( 'Could not receive a list of Google accounts. Google Analytics API returned an error. Please try again later or contact Ahrefs support to get it resolved.', 'ahrefs-seo' ) );
								$this->set_message( $this->extract_message( $streams_list ), $streams_list );
								$this->on_error_received( $streams_list );
								$error_set = true;
							}
							continue;
						}
						$_property_id = str_replace( 'response-', '', $_property_id );
						$_streams     = $streams_list->getDataStreams();
						if ( count( $_streams ) ) {
							foreach ( $_streams as $_stream ) {
								$web_data = $_stream->webStreamData;
								if ( $web_data ) {
									if ( ! isset( $streams[ "$_property_id" ] ) ) {
										$streams[ "$_property_id" ] = [];
									}
									$streams[ "$_property_id" ][] = [
										'uri'   => $web_data->defaultUri,
										'label' => $_stream->displayName,
									];
								}
							}
						}
						$next_list = $streams_list->getNextPageToken();
						if ( ! empty( $next_list ) ) {
							$requests[ "$_property_id" ] = $next_list;
						}
					}
				}
			} finally {
				$client->setUseBatch( false );
			}

			if ( ! empty( $accounts ) && ! empty( $properties ) ) {
				foreach ( $properties as $property_id => $value ) {
					$account_id     = (string) $value['account'];
					$account_number = explode( '/', $account_id, 2 )[1] ?? '';
					$property_label = $value['label'];
					$account_label  = isset( $accounts[ $account_id ] ) ? $accounts[ $account_id ] : '---';
					if ( ! empty( $streams[ $property_id ] ) ) {
						foreach ( $streams[ $property_id ] as $stream ) {
							$uri          = $stream['uri'];
							$stream_label = $stream['label'];
							$result[]     = [
								'ua_id'        => $property_id,
								'account'      => $account_number,
								'account_name' => $account_label,
								'name'         => $property_label,
								'stream'       => $stream_label,
								'website'      => $uri,
							];
						}
					}
				}
			}
			$this->accounts_ga4 = $result;
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			$this->set_message( $message );
		} catch ( Google_Service_Exception $e ) {
			Ahrefs_Seo::breadcrumbs( 'Events ' . (string) wp_json_encode( $this->get_logged_events() ) );
			Ahrefs_Seo::notify( $e );
			$this->set_message( $this->extract_message( $e, __( 'Google Analytics Admin API: failed to get the list of accounts.', 'ahrefs-seo' ) ) );
		} catch ( \Exception $e ) {
			$this->set_message( $this->extract_message( $e, __( 'Google Analytics Admin API: failed to get the list of accounts.', 'ahrefs-seo' ) ), $e );
		}

		return $result;
		// phpcs:enable WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	}

	/**
	 * Return array with ua accounts list from Google Analytics Management API
	 *
	 * @since 0.7.3
	 *
	 * @return array<array>
	 */
	protected function load_accounts_list_ga() : array {
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( is_array( $this->accounts_ga ) ) { // cached results from last call.
			return $this->accounts_ga;
		}
		if ( defined( 'AHREFS_SEO_NO_GA' ) && AHREFS_SEO_NO_GA ) {
			return [
				[
					'ua_id'        => 'AHREFS_SEO_NO_GA',
					'account'      => 'AHREFS_SEO_NO_GA',
					'account_name' => 'AHREFS_SEO_NO_GA',
					'name'         => 'AHREFS_SEO_NO_GA',
					'view'         => __( 'default', 'ahrefs-seo' ),
					'website'      => 'https://' . Ahrefs_Seo::get_current_domain(),
				],
			];
		}
		$result = [];
		// do this call earlier, maybe it is no sence to make another calls if no accounts.
		try {
			$client    = $this->create_client();
			$analytics = new Google_Service_Analytics( $client );

			$ua_list = $analytics->management_webproperties->listManagementWebproperties( '~all' );
			do_action_ref_array( 'ahrefs_seo_api_list_ga_webproperties', [ &$ua_list ] );
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );

			return [];
		} catch ( \Exception $e ) {
			$this->handle_exception( $e, false, true, false ); // do not save message.
			$this->set_message( $this->extract_message( $e, __( 'Google Analytics Management API: failed to get the list of accounts.', 'ahrefs-seo' ) ) );
			return [];
		}

		if ( empty( $ua_list ) ) {
			return [];
		}
		$data = $ua_list->getItems();

		try {
			$accounts_list = $analytics->management_accounts->listManagementAccounts();
			do_action_ref_array( 'ahrefs_seo_api_list_ga_accounts', [ &$accounts_list ] );
		} catch ( \Exception $e ) {
			$this->handle_exception( $e );
			$accounts_list = null;
		}

		$accounts = [];
		if ( ! empty( $accounts_list ) ) {
			foreach ( $accounts_list->getItems() as $account ) {
				$accounts[ $account->getId() ] = $account->getName();
			}
			$this->accounts_ga_raw = array_values( $accounts );
		}

		/*
		Workaround to extract defaultProfileId, which some of the older GA accounts lack
		*/
		try {
			$profiles_list = $analytics->management_profiles->listManagementProfiles( '~all', '~all' );
			do_action_ref_array( 'ahrefs_seo_api_list_ga_profiles', [ &$profiles_list ] );
		} catch ( \Exception $e ) {
			$this->handle_exception( $e );
			$profiles_list = null;
		}

		$profiles_groups = [];
		if ( ! empty( $profiles_list ) ) {
			foreach ( $profiles_list->getItems() as $profile ) {
				$_web_property_id = $profile->getWebPropertyId();
				if ( ! isset( $profiles_groups[ $_web_property_id ] ) ) {
					$profiles_groups[ $_web_property_id ] = [];
				}
				$profiles_groups[ $_web_property_id ][] = [
					'id'      => $profile->getId(),
					'name'    => $profile->getName(),
					'website' => $profile->getWebsiteUrl(),
				];
			}
		}

		if ( ! empty( $data ) ) {
			/** @var \ahrefs\AhrefsSeo_Vendor\Google_Service_Analytics_Webproperty $item */
			foreach ( $data as $item ) {
				if ( isset( $profiles_groups[ $item->id ] ) ) {
					foreach ( $profiles_groups[ $item->id ] as $_profile ) {
						$result[] = [
							'ua_id'        => $_profile['id'],
							'account'      => $item->accountId,
							'account_name' => isset( $accounts[ $item->accountId ] ) ? $accounts[ $item->accountId ] : '---',
							'name'         => $item->name,
							'view'         => $_profile['name'],
							'website'      => $_profile['website'],
						];
					}
				} else {
					// fill default choice.
					$result[] = [
						'ua_id'        => $item->defaultProfileId,
						'account'      => $item->accountId,
						'account_name' => isset( $accounts[ $item->accountId ] ) ? $accounts[ $item->accountId ] : '---',
						'name'         => $item->name,
						/* Translators: part of "default view" */
						'view'         => __( 'default', 'ahrefs-seo' ),
						'website'      => $item->websiteUrl,
					];
				}
			}
		}
		$this->accounts_ga = $result;
		return $result;
		// phpcs:enable WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	}

	/**
	 * GA account: find items with the current domain and do some queries here
	 * Set found account as selected.
	 *
	 * @return string|null
	 */
	public function find_recommended_ga_id() : ?string {
		if ( defined( 'AHREFS_SEO_NO_GA' ) && AHREFS_SEO_NO_GA ) {
			return 'AHREFS_SEO_NO_GA';
		}
		$this->reset_pause( true, false );
		$list = $this->load_accounts_list();
		// recommended results, with the same domain in websiteUrl.
		$recommended = [];
		$details     = [];
		$domain      = strtolower( Ahrefs_Seo::get_current_domain() );
		if ( 0 === strpos( $domain, 'www.' ) ) { // remove www. prefix from domain.
			$domain = substr( $domain, 4 );
		}

		foreach ( $list as $account ) {
			if ( ! empty( $account['values'] ) ) {
				foreach ( $account['values'] as $property_name => $item ) {
					if ( isset( $item['views'] ) && count( $item['views'] ) ) {
						foreach ( $item['views'] as $view ) {
							if ( $this->is_ga_account_correct( $view['website'] ) ) {
								$recommended[]             = $view['ua_id'];
								$details[ $view['ua_id'] ] = [
									'name'    => $view['view'],
									'website' => $view['website'],
								];
							}
						}
					}
					if ( isset( $item['streams'] ) && count( $item['streams'] ) ) {
						$ua_id    = $item['streams'][0]['ua_id'];
						$websites = implode(
							'|',
							array_map(
								function( $stream ) {
									return $stream['website'];
								},
								$item['streams']
							)
						);
						if ( $this->is_ga_account_correct( $websites ) ) {
							$recommended[]     = $ua_id;
							$details[ $ua_id ] = [
								'name'    => $property_name,
								'website' => $websites,
							];
						}
					}
				}
			}
		}
		if ( ! count( $recommended ) ) {
			return null;
		}
		$counts = $this->check_ga_using_top_traffic_pages( $recommended );
		if ( is_null( $counts ) ) {
			return null;
		}
		arsort( $counts );
		reset( $counts );
		$ua_id = key( $counts );
		// set this account.
		if ( isset( $details[ $ua_id ] ) ) {
			$value = $details[ $ua_id ];
			wp_cache_flush();
			$this->data_tokens->tokens_load();
			$this->set_ua( "$ua_id", $value['name'], $value['website'], $this->data_tokens->get_gsc_site() );
		}
		return (string) $ua_id;
	}

	/**
	 * GSC account: find items with the current domain and do some queries here.
	 * Set found account as selected.
	 *
	 * @return string|null
	 */
	public function find_recommended_gsc_id() : ?string {
		$this->set_gsc_disconnect_reason( null ); // clean any previous error.
		$this->reset_pause( false, true );
		$list = $this->load_gsc_accounts_list();
		// recommended results, with the same domain in websiteUrl.
		$recommended = [];
		$domain      = $this->get_clean_domain();

		foreach ( $list as $item ) {
			$_website = $this->get_clean_domain( (string) $item['site'] );
			if ( $_website === $domain && 'siteUnverifiedUser' !== $item['level'] ) {
				$recommended[] = $item['site'];
			}
		}
		if ( ! count( $recommended ) ) {
			return null;
		}
		$counts = [];
		foreach ( $recommended as $site ) {
			$counts[ $site ] = $this->check_gsc_using_bulk_results( $site );
		}
		arsort( $counts );
		reset( $counts );
		$site = key( $counts );
		// set this account.
		wp_cache_flush();
		$this->data_tokens->tokens_load();
		$this->set_ua( $this->data_tokens->get_ua_id(), $this->data_tokens->get_ua_name(), $this->data_tokens->get_ua_url(), "$site" );
		return (string) $site;
	}

	/**
	 * Return number of pages found in GA or GA4 account.
	 *
	 * @param string[] $ua_ids UA ids list.
	 * @return null|array<string, int|null> Index is ua_id, value is number of found pages.
	 */
	private function check_ga_using_top_traffic_pages( array $ua_ids ) : ?array {
		if ( ! $this->is_analytics_enabled() ) {
			return null;
		}
		$results    = [];
		$start_date = date( 'Y-m-d', time() - 3 * MONTH_IN_SECONDS );
		$end_date   = date( 'Y-m-d' );

		$ua_ids_ga  = [];
		$ua_ids_ga4 = [];

		foreach ( $ua_ids as $ua_id ) {
			if ( 0 === strpos( $ua_id, 'properties/' ) ) {
				$ua_ids_ga4[] = $ua_id;
			} else {
				$ua_ids_ga[] = $ua_id;
			}
		}
		if ( count( $ua_ids_ga ) ) {
			$results = $this->get_found_pages_by_ua_id_ga( $ua_ids_ga, $start_date, $end_date );
		}
		if ( count( $ua_ids_ga4 ) ) {
			$results = $results + $this->get_found_pages_by_ua_id_ga4( $ua_ids_ga4, $start_date, $end_date ); // save indexes.
		}
		return $results;
	}

	/**
	 * Check GSC accounts, return number of pages with results.
	 *
	 * @param string $gsc_site GSC site.
	 * @return int|null
	 */
	private function check_gsc_using_bulk_results( string $gsc_site ) : ?int {
		$result = [];
		$urls   = $this->check_gsc_using_bulk_results_strings( $gsc_site ); // page urls, received from GSC.
		if ( is_null( $urls ) ) {
			return null;
		}
		foreach ( $urls as $url ) {
			$result[] = wp_parse_url( $url, PHP_URL_PATH ) ?? '';
		}
		$result = array_unique( $result );
		$count  = 0;
		if ( count( $result ) ) {
			array_walk(
				$result,
				function( $slug ) use ( &$count ) {
					$post = get_page_by_path( $slug, OBJECT, [ 'post', 'page' ] );
					if ( $post instanceof \WP_Post ) {
						$count++;
					}
				}
			);
		}
		return $count;
	}

	/**
	 * Check GSC accounts, return pages with results.
	 *
	 * @param string $gsc_site GSC site.
	 * @param bool   $with_clicks_only Return only URLs with non-empty clicks value.
	 * @return string[]|null URLs list or null.
	 */
	private function check_gsc_using_bulk_results_strings( string $gsc_site, bool $with_clicks_only = true ) : ?array {
		if ( ! $this->is_gsc_enabled() ) {
			return null;
		}
		$result     = [];
		$start_date = date( 'Y-m-d', time() - 3 * MONTH_IN_SECONDS );
		$end_date   = date( 'Y-m-d' );

		$parameters = [
			'startDate'  => $start_date,
			'endDate'    => $end_date,
			'dimensions' => [
				'page',
			],
			'rowLimit'   => self::QUERY_DETECT_GSC_LIMIT,
			'startRow'   => 0,
		];
		try {
			$client = $this->create_client();

			$service_searchconsole = new Google_Service_SearchConsole( $client );
			// https://developers.google.com/webmaster-tools/search-console-api-original/v3/searchanalytics/query .
			$post_body = new Google_Service_SearchConsole_SearchAnalyticsQueryRequest( $parameters );
			$this->maybe_do_a_pause( 'gsc' );
			/**
			* @var \ahrefs\AhrefsSeo_Vendor\Google\Service\SearchConsole\SearchAnalyticsQueryResponse $response_total
			*/
			$response_total = $service_searchconsole->searchanalytics->query( $gsc_site, $post_body );
			$this->maybe_do_a_pause( 'gsc', true );
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			return null;
		} catch ( \Exception $e ) {
			$this->maybe_do_a_pause( 'gsc', true );
			// do not handle error, no need to show it or disconnect an account.
			return null;
		}
		$result = []; // page urls, received from GSC.
		/**
		* @var \ahrefs\AhrefsSeo_Vendor\Google\Service\SearchConsole\ApiDataRow $row
		*/
		foreach ( $response_total->getRows() as $row ) {
			if ( $row instanceof Google_Service_SearchConsole_ApiDataRow ) {
				if ( ! $with_clicks_only || ( $row->getClicks() > 0 ) ) { // use only pages with traffic > 0.
					// key[0] is a page url.
					$result[] = $row->getKeys()[0] ?? '';
				}
			}
		}
		return $result;
	}

	/**
	 * Return array with Google Search Console accounts list
	 *
	 * @param bool $cached_only Return only cached value.
	 * @return array<array>
	 */
	public function load_gsc_accounts_list( bool $cached_only = false ) : array {
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar,WordPress.NamingConventions.ValidVariableName.NotSnakeCase
		if ( is_array( $this->accounts_gsc ) ) { // cached results from last call.
			return $this->accounts_gsc;
		}
		if ( $cached_only ) {
			return (array) json_decode( '' . get_option( self::OPTION_GSC_SITES, '' ), true );
		}
		$result = [];
		try {
			$client = $this->create_client();

			$service_searchconsole = new Google_Service_SearchConsole( $client );
			/**
			* @var \ahrefs\AhrefsSeo_Vendor\Google\Service\SearchConsole\SitesListResponse
			*/
			$sites_list = $service_searchconsole->sites->listSites();
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			return [];
		} catch ( \Exception $e ) {
			$this->handle_exception( $e, false, true, false ); // do not save message.
			$this->set_message( $this->extract_message( $e, __( 'Google Search Console API: failed to get the list of accounts.', 'ahrefs-seo' ) ) );
			return [];
		}

		/**
		* @var \ahrefs\AhrefsSeo_Vendor\Google\Service\SearchConsole\WmxSite $account
		*/
		foreach ( $sites_list->getSiteEntry() as $account ) {
			$url = $account->getSiteUrl();
			if ( ! is_null( $url ) ) {
				$result[] = [
					'site'   => $url ? $url : '---',
					'domain' => wp_parse_url( $url, PHP_URL_HOST ) ? strtolower( wp_parse_url( $url, PHP_URL_HOST ) ) : '---',
					'scheme' => wp_parse_url( $url, PHP_URL_SCHEME ) ? strtolower( wp_parse_url( $url, PHP_URL_SCHEME ) ) : '---',
					'level'  => $account->getPermissionLevel(),
				];
			}
		}

		// sort results.
		usort(
			$result,
			function( $a, $b ) {
				// order by account name.
				$diff = $a['domain'] <=> $b['domain'];
				if ( 0 !== $diff ) {
					return $diff;
				}
				// then order by name.
				return $a['scheme'] <=> $b['scheme'];
			}
		);
		$result             = apply_filters( 'ahrefs_seo_accounts_gsc', $result );
		$this->accounts_gsc = (array) $result;
		update_option( self::OPTION_GSC_SITES, (string) wp_json_encode( $this->accounts_gsc ) );
		return $result;
		// phpcs:enable WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar,WordPress.NamingConventions.ValidVariableName.NotSnakeCase
	}

	/**
	 * Get last error message from Analytics API.
	 *
	 * @param bool $return_and_clear_saved_message true - return and clear message from option, false - return current message.
	 * @return string Error message or empty string.
	 */
	public function get_message( bool $return_and_clear_saved_message = false ) : string {
		if ( $return_and_clear_saved_message ) {
			$error = '' . get_option( self::OPTION_LAST_ERROR, '' );
			if ( '' !== $error ) {
				$this->set_message( '' );
			}
			return $error;
		}
		return $this->message;
	}

	/**
	 * Set error message. Submit report if Exception parameter is set.
	 * Save 'google notice' message.
	 *
	 * @param string|null     $message Message, null if no need to save.
	 * @param \Exception|null $e Exception.
	 * @param string|null     $request Request string, saved to breadcrumbs.
	 * @param string          $type 'notice', 'error', 'error-single'.
	 * @return void
	 */
	public function set_message( ?string $message, ?\Exception $e = null, ?string $request = null, string $type = 'error' ) : void {
		if ( ! is_null( $message ) ) {
			if ( '' !== $message ) {
				Ahrefs_Seo_Errors::save_message( 'google', $message, $type );
			} else { // clean messages.
				Ahrefs_Seo_Errors::clean_messages( 'google' );
			}

			$this->message = $message;
			update_option( self::OPTION_LAST_ERROR, $message );
		}
		if ( ! is_null( $e ) ) {
			Ahrefs_Seo::breadcrumbs( 'Events ' . (string) wp_json_encode( $this->get_logged_events() ) . ( ! empty( $request ) ? "\nRequest: " . $request : '' ) );
			Ahrefs_Seo::notify( $e );
		}
	}

	/**
	 * Return service error or empty array
	 *
	 * @return array<array>
	 */
	public function get_service_error() : array {
		return (array) $this->service_error;
	}

	/**
	 * Remove existing token.
	 */
	public function disconnect() : void {
		Ahrefs_Seo::breadcrumbs( sprintf( '%s', __METHOD__ ) );

		$this->data_tokens->tokens_load();
		if ( ! empty( $this->data_tokens->get_raw_token() ) && ! defined( 'AHREFS_SEO_PRESERVE_TOKEN' ) ) {
			try {
				$client = $this->create_client();
				$client->revokeToken( $client->getAccessToken() );
			} catch ( \Error $e ) {
				$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
				$this->set_message( $message );
			} catch ( \Exception $e ) {
				Ahrefs_Seo::breadcrumbs( 'Events ' . (string) wp_json_encode( $this->get_logged_events() ) );
				Ahrefs_Seo_Errors::save_message( 'google', $e->getMessage() );
			}
			$this->set_message( __( 'Google account disconnected.', 'ahrefs-seo' ) );
		}

		$this->data_tokens->disconnect();
		delete_option( self::OPTION_HAS_ACCOUNT_GA );
		delete_option( self::OPTION_HAS_ACCOUNT_GA_RAW );
		delete_option( self::OPTION_HAS_ACCOUNT_GSC );
		delete_option( self::OPTION_GSC_SITES );
		( new Disconnect_Reason_GSC() )->clean_reason();
		wp_cache_flush();
		$this->accounts_ga_raw    = null;
		$this->accounts_ga        = null;
		$this->accounts_ga4       = null;
		$this->accounts_ga4_raw   = null;
		$this->accounts_gsc       = null;
		$this->has_ga_account     = null;
		$this->has_ga_account_raw = null;
		$this->has_gsc_account    = null;
	}

	/**
	 * Get visitors traffic by type for page
	 *
	 * @param array<int|string, string>|null $page_slugs Page url starting with '/'.
	 * @param string                         $start_date Start date.
	 * @param string                         $end_date End date.
	 * @param null|int                       $max_results Max results.
	 * @param null|string                    $ua_id UA id.
	 *
	 * @return array<int|string, array<string, mixed>>|null Array, 'slug' => [ traffic type => visitors number].
	 */
	public function get_visitors_by_page( ?array $page_slugs, string $start_date, string $end_date, ?int $max_results = null, ?string $ua_id = null ) : ?array {
		Ahrefs_Seo::breadcrumbs( __METHOD__ . (string) wp_json_encode( func_get_args() ) );
		// is Analytics enabled?
		if ( ! $this->is_analytics_enabled() ) {
			$this->set_message( __( 'Analytics disconnected.', 'ahrefs-seo' ) );
			$this->service_error = [ [ 'reason' => 'internal-no-token' ] ];
			return null;
		}
		if ( is_null( $ua_id ) && ! $this->is_ua_set() ) {
			$this->set_message( __( 'Please choose Analytics profile.', 'ahrefs-seo' ) );
			$this->service_error = [ [ 'reason' => 'internal-no-profile' ] ];
			return null;
		}
		if ( is_array( $page_slugs ) && ! count( $page_slugs ) ) {
			return [];
		}

		$revert = [];
		if ( is_array( $page_slugs ) ) {
			foreach ( $page_slugs as $key => $value ) {
				$new_url            = $this->url_for_ga( $value );
				$revert[ $new_url ] = $value;
				$page_slugs[ $key ] = $new_url;
			}
		}

		if ( defined( 'AHREFS_SEO_NO_GA' ) && AHREFS_SEO_NO_GA ) {
			if ( is_null( $page_slugs ) ) {
				return [];
			} else {
				$result = [];
				foreach ( $page_slugs as $page_slug ) {
					/**
					 * Modify traffic values.
					 *
					 * @param array<string, int> $traffic    The traffic values.
					 * @param string             $page_slug  Page slug.
					 * @param string             $start_date Start date.
					 * @param string             $end_date   End date.
					 */
					$result[ $page_slug ] = apply_filters(
						'ahrefs_seo_no_ga_visitors_by_page',
						[
							'total'          => 10,
							'Organic Search' => 5,
						],
						$page_slug,
						$start_date,
						$end_date
					);
				}
				return $result;
			}
		}

		$result = ( 0 === strpos( is_null( $ua_id ) ? $this->data_tokens->get_ua_id() : $ua_id, 'properties/' ) )
			? $this->get_visitors_by_page_ga4( $page_slugs, $start_date, $end_date, $max_results, $ua_id )
			: $this->get_visitors_by_page_ga( $page_slugs, $start_date, $end_date, $max_results, $ua_id );

		// add total => 0 to each missing slug.
		if ( ! is_null( $result ) && is_array( $page_slugs ) ) {
			foreach ( $page_slugs as $_slug ) {
				if ( ! isset( $result[ $_slug ] ) ) {
					$result[ $_slug ] = [ 'total' => 0 ];
				}
			}
		}

		// set back to original URLs.
		$result2 = [];
		foreach ( $result as $slug => $value ) {
			if ( isset( $revert[ $slug ] ) ) { // sometimes returned value has chars in different case.
				$result2[ $revert[ $slug ] ] = $value;
			} else {
				$result2[ $slug ] = $value;
			}
		}

		Ahrefs_Seo::breadcrumbs( 'get_visitors_by_page: ' . (string) wp_json_encode( $page_slugs ) . ' results: ' . (string) wp_json_encode( $result2 ) );
		return $result2;
	}

	// phpcs:disable Squiz.Commenting.FunctionCommentThrowTag.Missing,WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	/**
	 * Get visitors traffic by type for page for GA property, use Google Analytics Reporting API version 4.
	 *
	 * @since 0.7.3
	 *
	 * @param array<int|string, string>|null $page_slugs_list Page url starting with '/'.
	 * @param string                         $start_date Start date.
	 * @param string                         $end_date End date.
	 * @param null|int                       $max_results Max results count.
	 * @param null|string                    $ua_id UA id or null if default UA id used.
	 *
	 * @return array<int|string, array<string, mixed>> Array, 'slug' => [ traffic type => visitors number].
	 */
	public function get_visitors_by_page_ga( ?array $page_slugs_list, string $start_date, string $end_date, ?int $max_results = null, ?string $ua_id = null ) : ?array {
		$result = [];
		$data   = [];
		try {
			$client             = $this->create_client();
			$analyticsreporting = new Google_Service_AnalyticsReporting( $client );
			$continue           = true;
			if ( is_null( $ua_id ) ) {
				$ua_id = $this->data_tokens->get_ua_id();
			}
			$page_slugs = is_null( $page_slugs_list ) ? [ null ] : $page_slugs_list; // receive pages info without slug filter.
			$per_page   = is_null( $max_results ) ? self::QUERY_TRAFFIC_PER_PAGE : $max_results;

			$pages_to_load = array_map(
				function( $slug ) {
					return [
						'slug'       => $slug,
						'next_token' => null,
					]; // later we will add next_token or remove item from the list.
				},
				$page_slugs
			);

			do {
				try {
					$requests = []; // up to 5 requests allowed.
					$data     = null;
					// analytics parameters.
					$params = [
						'quotaUser' => $this->get_api_user(),
					];

					// get results from google analytics.
					try {
						$this->maybe_do_a_pause( 'ga' );

						foreach ( $pages_to_load as $page_to_load ) {
							$page_slug  = $page_to_load['slug'];
							$next_token = $page_to_load['next_token'] ?? null;

							// Create the DateRange object.
							$dateRange = new Google_Service_AnalyticsReporting_DateRange();
							$dateRange->setStartDate( $start_date );
							$dateRange->setEndDate( $end_date );

							// Create the Metrics object.
							$metric1 = new Google_Service_AnalyticsReporting_Metric();
							$metric1->setExpression( 'ga:uniquePageviews' );

							// Create the Dimensions object.
							$dimension1 = new Google_Service_AnalyticsReporting_Dimension();
							$dimension1->setName( 'ga:pagePath' );

							/** @link https://ga-dev-tools.appspot.com/dimensions-metrics-explorer/#ga:channelGrouping */
							$dimension2 = new Google_Service_AnalyticsReporting_Dimension();
							$dimension2->setName( 'ga:channelGrouping' );

							// Create the ReportRequest object.
							$request = new Google_Service_AnalyticsReporting_ReportRequest();

							if ( ! is_null( $page_slug ) ) {
								// Create the DimensionFilter.
								$dimensionFilter = new Google_Service_AnalyticsReporting_DimensionFilter();
								$dimensionFilter->setDimensionName( 'ga:pagePath' );
								$dimensionFilter->setOperator( 'EXACT' );
								$dimensionFilter->setExpressions( array( $page_slug ) );

								// Create the DimensionFilterClauses.
								$dimensionFilterClause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
								$dimensionFilterClause->setFilters( array( $dimensionFilter ) );
								$request->setDimensionFilterClauses( array( $dimensionFilterClause ) );
							}

							$request->setViewId( $ua_id );
							$request->setDateRanges( $dateRange );
							$request->setDimensions( array( $dimension1, $dimension2 ) );
							$request->setMetrics( array( $metric1 ) );
							$request->setPageSize( $per_page );
							if ( ! empty( $next_token ) ) {
								$request->setPageToken( $next_token );
							}

							$requests[] = $request;
						}

						$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
						$body->setReportRequests( $requests );
						$data = $analyticsreporting->reports->batchGet( $body, $params );
						do_action_ref_array( 'ahrefs_seo_api_visitors_by_page_ga', [ &$data ] );
						$this->maybe_do_a_pause( 'ga', true );
					} catch ( Google_Service_Exception $e ) { // catch recoverable errors.
						$this->maybe_do_a_pause( 'ga', true );
						$this->service_error = $e->getErrors();
						$this->handle_exception( $e );
						$this->on_error_received( $e, $page_slugs_list );
						throw $e;
					} catch ( GuzzleRequestException $e ) { // catch recoverable errors.
						$this->maybe_do_a_pause( 'ga', true );
						$this->handle_exception( $e );
						$this->on_error_received( $e, $page_slugs_list );
						throw $e;
					}

					$continue = false;
					if ( ! is_null( $data ) ) {
						$reports = $data->getReports();
						if ( ! empty( $reports ) ) {
							foreach ( $reports as $index => $report ) {
								$data_items                            = $report->getData();
								$pages_to_load[ $index ]['next_token'] = $report->getNextPageToken();

								// load details from rows.
								$rows = $data_items->getRows();
								if ( ! empty( $rows ) ) {
									foreach ( $rows as $row ) {
										list($_slug, $_type) = $row->getDimensions(); // page slug + traffic type.

										$_metrics       = $row->getMetrics();
										$_traffic_count = ( $_metrics[0]->getValues() )[0] ?? 0;

										if ( ! isset( $result[ $_slug ] ) ) {
											$result[ $_slug ] = [];
										}
										if ( ! isset( $result[ $_slug ][ "$_type" ] ) ) {
											$result[ $_slug ][ "$_type" ] = (int) $_traffic_count;
											$result[ $_slug ]['total']    = (int) $_traffic_count + ( $result[ $_slug ]['total'] ?? 0 );
										} else {
											$result[ $_slug ][ "$_type" ] += (int) $_traffic_count;
											$result[ $_slug ]['total']    += (int) $_traffic_count;
										}
									}
								}
								if ( ! is_null( $max_results ) && ( count( $rows ) >= $max_results || count( $result ) >= $max_results ) ) {
									$pages_to_load[ $index ]['next_token'] = null; // do not load more.
								}
							}
						} else {
							$pages_to_load = [];
						}
					} else {
						$pages_to_load = [];
					}
					// remove finished pages (without next_token) from load list.
					$pages_to_load = array_values(
						array_filter(
							$pages_to_load,
							function( $value ) {
								return ! empty( $value['next_token'] );
							}
						)
					);
				} catch ( \Error $e ) {
					$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
					$this->set_message( $message );
				} catch ( \Exception $e ) {
					$this->handle_exception( $e, true );
					return $this->prepare_answer( $page_slugs_list, __( 'Connection error', 'ahrefs-seo' ) );
				}
				// load until any next page exists, but load only first page with results for the generic request without page ($page_slugs_list is null).
			} while ( ! empty( $pages_to_load ) && ! is_null( $page_slugs_list ) && ! is_null( $data ) );
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			$this->set_message( $message );
		}
		return $result;
	}
	// phpcs:enable Squiz.Commenting.FunctionCommentThrowTag.Missing,WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase

	// phpcs:disable Squiz.Commenting.FunctionCommentThrowTag.Missing,WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	/**
	 * Get visitors traffic by type for page for GA4 property, use Google Analytics Data API
	 *
	 * @since 0.7.3
	 *
	 * @param null|array<int|string, string> $page_slugs_list Page url starting with '/'.
	 * @param string                         $start_date Start date.
	 * @param string                         $end_date End date.
	 * @param null|int                       $max_results Max results count.
	 * @param null|string                    $ua_id UA id or null if default UA id used.
	 *
	 * @return array<int|string, array<string, mixed>> Array, 'slug' => [ traffic type => visitors number].
	 */
	protected function get_visitors_by_page_ga4( ?array $page_slugs_list, string $start_date, string $end_date, ?int $max_results = null, ?string $ua_id = null ) : ?array {
		$result = [];
		try {
			$client     = $this->create_client();
			$data       = [];
			$analytics4 = new Google_Service_AnalyticsData( $client );

			$continue = true;
			if ( is_null( $ua_id ) ) {
				$ua_id = $this->data_tokens->get_ua_id();
			}
			// numeric part only.
			$property_id = str_replace( 'properties/', '', $ua_id );
			$page_slugs  = empty( $page_slugs_list ) ? null : $page_slugs_list; // receive pages info without slug filter.
			$count       = is_array( $page_slugs ) ? count( $page_slugs ) : 1;
			$per_page    = ( is_null( $max_results ) ? self::QUERY_TRAFFIC_PER_PAGE : $max_results ) * $count;
			$offset      = 0;

			do {
				try {
					$rows = [];
					$data = null;
					// analytics additional parameters.
					$params = [
						'quotaUser' => $this->get_api_user(),
					];
					// get results from GA4.
					try {
						$this->maybe_do_a_pause( 'ga4' );

							// Create the DateRange object.
							$dateRange = new Google_Service_AnalyticsData_DateRange();
							$dateRange->setStartDate( $start_date );
							$dateRange->setEndDate( $end_date );

							// Create the Metrics object.
							/** @link https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema#metrics */
							$metric = new Google_Service_AnalyticsData_Metric();
							$metric->setName( 'screenPageViews' ); // "ga:uniquePageviews"

							// Create the Dimension object.
							/** @link https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema#dimensions */
							$dimension1 = new Google_Service_AnalyticsData_Dimension();
							$dimension1->setName( 'pagePathPlusQueryString' ); // "ga:pagePath".

							$dimension2 = new Google_Service_AnalyticsData_Dimension();
							$dimension2->setName( 'sessionDefaultChannelGrouping' ); // "ga:channelGrouping".

							// Create the ReportRequest object.
							$request = new Google_Service_AnalyticsData_RunReportRequest();
							$request->setDateRanges( $dateRange );
							$request->setMetrics( array( $metric ) );
							$request->setDimensions( array( $dimension1, $dimension2 ) );
							$request->setLimit( $per_page );
							$request->setOffset( $offset );
						if ( ! $this->set_ga4_property( $request, $property_id ) ) {
							/* Translators: 1: version string, 2: function name, 3: line number */
							throw new Ahrefs_Seo_Compatibility_Exception( sprintf( __( 'Unsupported Google Analytics Data API version %1$s at %2$s line %3$d', 'ahrefs-seo' ), $analytics4->version, __METHOD__, __LINE__ ) );
						}

						if ( ! is_null( $page_slugs ) ) { // request for specified urls list.

							$in_list_filter = new Google_Service_AnalyticsData_InListFilter();
							$in_list_filter->setValues( $page_slugs );

							$filter = new Google_Service_AnalyticsData_Filter();
							$filter->setFieldName( 'pagePathPlusQueryString' );
							$filter->setInListFilter( $in_list_filter );

							$dimension_filter = new Google_Service_AnalyticsData_FilterExpression();
							$dimension_filter->setFilter( $filter );

							$request->setDimensionFilter( $dimension_filter );
						}

						if ( property_exists( $analytics4, 'v1alpha' ) ) {
							$report = $analytics4->v1alpha->runReport( $request, $params );
						} elseif ( property_exists( $analytics4, 'properties' ) && is_object( $analytics4->properties ) && method_exists( $analytics4->properties, 'runReport' ) ) {
							$report = $analytics4->properties->runReport( 'properties/' . $property_id, $request, $params );
						} else {
							/* Translators: 1: version string, 2: function name, 3: line number */
							throw new Ahrefs_Seo_Compatibility_Exception( sprintf( __( 'Unsupported Google Analytics Data API version %1$s at %2$s line %3$d', 'ahrefs-seo' ), $analytics4->version, __METHOD__, __LINE__ ) );
						}
						do_action_ref_array( 'ahrefs_seo_api_visitors_by_page_ga4', [ &$report ] );
						$this->maybe_do_a_pause( 'ga4', true );
					} catch ( Google_Service_Exception $e ) { // catch recoverable errors.
						$this->maybe_do_a_pause( 'ga4', true );
						$this->service_error = $e->getErrors();
						$this->handle_exception( $e );
						$this->on_error_received( $e, $page_slugs_list );
						throw $e;
					} catch ( GuzzleConnectException $e ) { // catch recoverable errors.
						$this->maybe_do_a_pause( 'ga4', true );
						$this->set_message( $this->extract_message( $e ), $e, (string) wp_json_encode( $request ?? null ) );
						$this->on_error_received( $e, $page_slugs_list );
						throw $e;
					}

					if ( ! empty( $report ) ) {
								$continue = false;
								$rows     = $report->getRows();
								$totals   = $report->getRowCount() ?? 0;
						if ( ! empty( $rows ) ) {
							foreach ( $rows as $row ) {
								$dimensions     = $row->getDimensionValues(); // page slug + traffic type.
								$_slug          = $dimensions[0]->getValue();
								$_type          = $dimensions[1]->getValue();
								$_metrics       = $row->getMetricValues();
								$_traffic_count = (int) ( $_metrics[0]->getValue() ?? 0 );

								if ( ! isset( $result[ $_slug ] ) ) {
									$result[ $_slug ] = [];
								}
								if ( ! isset( $result[ $_slug ][ "$_type" ] ) ) {
									$result[ $_slug ][ "$_type" ] = $_traffic_count;
									$result[ $_slug ]['total']    = $_traffic_count + ( $result[ $_slug ]['total'] ?? 0 );
								} else {
									$result[ $_slug ][ "$_type" ] += $_traffic_count;
									$result[ $_slug ]['total']    += $_traffic_count;
								}
							}
							$continue = count( $rows ) === $per_page && ( is_null( $max_results ) || count( $rows ) < $max_results ) && ( $offset + count( $rows ) < $totals );
						}
						if ( ! is_null( $max_results ) && ( count( $rows ) >= $max_results || count( $result ) >= $max_results ) && $continue ) {
							$offset += count( $rows );
						} else {
							$offset = null; // do not load more.
						}
					} else {
						$offset = null;
					}
				} catch ( \Error $e ) {
					$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
					$this->set_message( $message );
				} catch ( \Exception $e ) {
					$this->handle_exception( $e, true );
					return $this->prepare_answer( $page_slugs_list, __( 'Connection error', 'ahrefs-seo' ) );
				}
				// load until any next page exists, but load only first page with results for the generic request without page ($page_slugs_list is null).
			} while ( ! empty( $offset ) && ! is_null( $page_slugs_list ) );
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			$this->set_message( $message );
		}
		return $result;
	}
	// phpcs:enable Squiz.Commenting.FunctionCommentThrowTag.Missing,WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase

	// phpcs:disable Squiz.Commenting.FunctionCommentThrowTag.Missing,WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	/**
	 * Get visitors traffic by type for page for GA property.
	 *
	 * @since 0.7.3
	 *
	 * @param string[] $ua_ids UA ids list to check.
	 * @param string   $start_date Start date.
	 * @param string   $end_date End date.
	 * @param bool     $return_count Return count of found pages or pages slugs list.
	 *
	 * @return array<string, null|int>|array<string, null|string[]> Array, [ ua_id => pages_found ].
	 */
	private function get_found_pages_by_ua_id_ga( array $ua_ids, string $start_date, string $end_date, bool $return_count = true ) : array {
		$results = [];
		try {
			$client = $this->create_client();
			$client->setUseBatch( true );

			$analyticsreporting = new Google_Service_AnalyticsReporting( $client );

			$per_page = $return_count ? self::QUERY_DETECT_GA_LIMIT : 1000; // used as per page parameter, but really we load first page only.
			do { // for ua_ids parts.
				$ua_id_list = array_splice( $ua_ids, 0, 5 ); // max 5 requests per batch.
				try {
					$data = null;
					// analytics parameters.
					$params = [
						'quotaUser' => $this->get_api_user(),
					];

					// get results from google analytics.
					try {
						$this->maybe_do_a_pause( 'ga' );
						$batch = new Google_Http_Batch(
							$client,
							false,
							$analyticsreporting->rootUrl,
							$analyticsreporting->batchPath
						);
						$this->maybe_do_a_pause( 'ga', true );

						foreach ( $ua_id_list as $ua_id ) {
							// Create the DateRange object.
							$dateRange = new Google_Service_AnalyticsReporting_DateRange();
							$dateRange->setStartDate( $start_date );
							$dateRange->setEndDate( $end_date );

							// Create the Metrics object.
							$metric1 = new Google_Service_AnalyticsReporting_Metric();
							$metric1->setExpression( 'ga:uniquePageviews' );

							// Create the Dimensions object.
							$dimension1 = new Google_Service_AnalyticsReporting_Dimension();
							$dimension1->setName( 'ga:pagePath' );

							/** @link https://ga-dev-tools.appspot.com/dimensions-metrics-explorer/#ga:channelGrouping */
							$dimension2 = new Google_Service_AnalyticsReporting_Dimension();
							$dimension2->setName( 'ga:channelGrouping' );

							// Create the ReportRequest object.
							$request = new Google_Service_AnalyticsReporting_ReportRequest();

							$request->setViewId( $ua_id );
							$request->setDateRanges( $dateRange );
							$request->setDimensions( array( $dimension1, $dimension2 ) );
							$request->setMetrics( array( $metric1 ) );
							$request->setPageSize( $per_page );

							$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
							$body->setReportRequests( [ $request ] );
							$prepared_queries = $analyticsreporting->reports->batchGet( $body, $params );
							$batch->add( $prepared_queries, $ua_id );
						}
						$data = $batch->execute();
					} catch ( Google_Service_Exception $e ) { // try to continue, but report error.
						Ahrefs_Seo_Errors::save_message( 'google', $e->getMessage(), Message::TYPE_NOTICE );
						Ahrefs_Seo::notify( $e, 'autodetect ga' );
					} catch ( GuzzleConnectException $e ) { // try to continue, but report error.
						Ahrefs_Seo_Errors::save_message( 'google', $e->getMessage(), Message::TYPE_NOTICE );
						Ahrefs_Seo::notify( $e, 'autodetect ga' );
					}
					$continue = false;
					if ( ! is_null( $data ) ) {
						foreach ( $data as $index => $values ) {
							$result      = [];
							$result_list = [];
							$index       = str_replace( 'response-', '', $index );
							if ( $values instanceof \Exception ) {
								$results[ "$index" ] = null;
								continue;
							}
							$reports = $values->getReports();
							if ( ! empty( $reports ) ) {
								foreach ( $reports as $key => $report ) {
									$data_items = $report->getData();
									// load details from rows.
									$rows = $data_items->getRows();
									if ( ! empty( $rows ) ) {
										foreach ( $rows as $row ) {
											// if we here - the traffic at page is not empty.
											list($_slug, $_type) = $row->getDimensions(); // page slug + traffic type.
											if ( ! isset( $result[ $_slug ] ) ) {
												$result[ $_slug ] = true;
												$result_list[]    = $_slug;
											}
										}
									}

									if ( $return_count ) {
										$count = 0;
										if ( ! empty( $result ) ) {
											$result = array_keys( $result );
											array_walk(
												$result,
												function( $slug ) use ( &$count ) {
													$post = get_page_by_path( "$slug", OBJECT, [ 'post', 'page' ] );
													if ( $post instanceof \WP_Post ) {
														$count++;
													}
												}
											);
										}
										$results[ "$index" ] = $count;
									} else {
										$results[ "$index" ] = $result_list;
									}
								}
							}
						}
					}
				} catch ( \Error $e ) {
					$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
					$this->set_message( $message );
				} catch ( \Exception $e ) {
					$this->handle_exception( $e, true );
					return $results;
				}
				// load until any next page exists, but load only first page with results for the generic request without page ($page_slugs_list is null).
			} while ( ! empty( $ua_ids ) );
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			$this->set_message( $message );
		} finally {
			if ( ! empty( $client ) ) {
				$client->setUseBatch( false );
			}
		}
		return $results;
	}
	// phpcs:enable Squiz.Commenting.FunctionCommentThrowTag.Missing,WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase


	// phpcs:disable Squiz.Commenting.FunctionCommentThrowTag.Missing,WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	/**
	 * Get visitors traffic by type for page for GA4 property.
	 *
	 * @since 0.7.3
	 *
	 * @param string[] $ua_ids UA ids list to check.
	 * @param string   $start_date Start date.
	 * @param string   $end_date End date.
	 * @param bool     $return_count Return count of found pages or pages slugs list.
	 *
	 * @return array<string, null|int>|array<string, null|string[]> Array, [ ua_id => pages_found ].
	 */
	public function get_found_pages_by_ua_id_ga4( array $ua_ids, string $start_date, string $end_date, bool $return_count = true ) : array {
		$results = [];
		try {
			$client = $this->create_client();
			$client->setUseBatch( true );

			$analytics4 = new Google_Service_AnalyticsData( $client );

			$per_page = $return_count ? self::QUERY_DETECT_GA_LIMIT : 1000;
			do { // for ua_ids parts.
				$ua_id_list = array_splice( $ua_ids, 0, 5 ); // max 5 requests per batch.

				$result = [];
				$data   = [];

				try {
					$data = null;
					// analytics parameters.
					$params = [
						'quotaUser' => $this->get_api_user(),
					];

					// get results from google analytics.
					try {
						$this->maybe_do_a_pause( 'ga4' );

						$batch = new Google_Http_Batch(
							$client,
							false,
							$analytics4->rootUrl,
							$analytics4->batchPath
						);
						$this->maybe_do_a_pause( 'ga4', true );

						foreach ( $ua_id_list as $ua_id ) {
							$property_id = str_replace( 'properties/', '', $ua_id );
							// Create the DateRange object.
							$dateRange = new Google_Service_AnalyticsData_DateRange();
							$dateRange->setStartDate( $start_date );
							$dateRange->setEndDate( $end_date );

							// Create the Metrics object.
							/** @link https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema#metrics */
							$metric = new Google_Service_AnalyticsData_Metric();
							$metric->setName( 'screenPageViews' ); // "ga:uniquePageviews"

							// Create the Dimension object.
							/** @link https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema#dimensions */
							$dimension1 = new Google_Service_AnalyticsData_Dimension();
							$dimension1->setName( 'pagePathPlusQueryString' ); // "ga:pagePath".

							$dimension2 = new Google_Service_AnalyticsData_Dimension();
							$dimension2->setName( 'sessionDefaultChannelGrouping' ); // "ga:channelGrouping".

							// Create the ReportRequest object.
							$request = new Google_Service_AnalyticsData_RunReportRequest();
							$request->setDateRanges( $dateRange );
							$request->setMetrics( array( $metric ) );
							$request->setDimensions( array( $dimension1, $dimension2 ) );
							$request->setLimit( $per_page );
							$request->setOffset( 1 );
							if ( ! $this->set_ga4_property( $request, $property_id ) ) {
								throw new Ahrefs_Seo_Compatibility_Exception( sprintf( 'Unsupported Google Analytics Data API version %s at %s line %d', $analytics4->version, __METHOD__, __LINE__ ) );
							}

							if ( property_exists( $analytics4, 'v1alpha' ) ) {
								$query = $analytics4->v1alpha->runReport( $request, $params );
								$batch->add( $query, $property_id );
							} elseif ( property_exists( $analytics4, 'properties' ) && is_object( $analytics4->properties ) && method_exists( $analytics4->properties, 'runReport' ) ) {
								$query = $analytics4->properties->runReport( 'properties/' . $property_id, $request, $params );
								$batch->add( $query, 'properties/' . $property_id );
							} else {
								throw new Ahrefs_Seo_Compatibility_Exception( sprintf( 'Unsupported Google Analytics Data API version %s at %s line %d', $analytics4->version, __METHOD__, __LINE__ ) );
							}
						}
						$data = $batch->execute();
					} catch ( Google_Service_Exception $e ) { // try to continue, but report error.
						Ahrefs_Seo_Errors::save_message( 'google', $e->getMessage(), Message::TYPE_NOTICE );
						Ahrefs_Seo::notify( $e, 'autodetect ga4' );
					} catch ( GuzzleConnectException $e ) { // try to continue, but report error.
						Ahrefs_Seo_Errors::save_message( 'google', $e->getMessage(), Message::TYPE_NOTICE );
						Ahrefs_Seo::notify( $e, 'autodetect ga4' );
					}
					$continue = false;
					if ( ! is_null( $data ) ) {
						foreach ( $data as $index => $report ) {
							$index = str_replace( 'response-', '', $index );
							if ( $report instanceof \Exception ) {
								$results[ "$index" ] = null;
								continue;
							}
							$result      = [];
							$result_list = [];
							$rows        = $report->getRows();
							$count       = 0;
							if ( ! empty( $rows ) ) {
								foreach ( $rows as $row ) {
									$dimensions = $row->getDimensionValues(); // page slug + traffic type.
									$_slug      = $dimensions[0]->getValue();
									if ( ! isset( $result[ $_slug ] ) ) {
										$result[ $_slug ] = true;
										$result_list[]    = $_slug;
									}
								}

								if ( ! empty( $result ) && $return_count ) {
									$result = array_keys( $result );
									array_walk(
										$result,
										function( $slug ) use ( &$count ) {
											$post = get_page_by_path( "$slug", OBJECT, [ 'post', 'page' ] );
											if ( $post instanceof \WP_Post ) {
												$count++;
											}
										}
									);
								}
							}
							$results[ "$index" ] = $return_count ? $count : $result_list;
						}
					}
				} catch ( \Error $e ) {
					$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
					$this->set_message( $message );
				} catch ( \Exception $e ) {
					$this->handle_exception( $e, true );
					return $results;
				}
				// load until any next page exists, but load only first page with results for the generic request without page ($page_slugs_list is null).
			} while ( ! empty( $ua_ids ) );
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			$this->set_message( $message );
		} finally {
			if ( ! empty( $client ) ) {
				$client->setUseBatch( false );
			}
		}
		return $results;
	}
	// phpcs:enable Squiz.Commenting.FunctionCommentThrowTag.Missing,WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase,WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

	/**
	 * Set property id for GA4 request using API version v1alpha or v1beta.
	 *
	 * @since 0.8.2
	 *
	 * @param \ahrefs\AhrefsSeo_Vendor\Google\Service\AnalyticsData\RunReportRequest $request Request.
	 * @param string                                                                 $property_id Property id.
	 * @return bool False on error.
	 */
	protected function set_ga4_property( \ahrefs\AhrefsSeo_Vendor\Google\Service\AnalyticsData\RunReportRequest &$request, string $property_id ) : bool {
		if ( class_exists( '\\ahrefs\\AhrefsSeo_Vendor\\Google_Service_AnalyticsData_Entity' ) && method_exists( $request, 'setEntity' ) ) { // v1alpha.
			$entity = new \ahrefs\AhrefsSeo_Vendor\Google_Service_AnalyticsData_Entity();
			$entity->setPropertyId( $property_id ); // only numeric part.
			$request->setEntity( $entity );
		} elseif ( method_exists( $request, 'setProperty' ) ) { // v1beta.
			$request->setProperty( 'properties/' . $property_id );
		} else {
			return false;
		}
		return true;
	}

	/**
	 * Fill answers with error message
	 *
	 * @since 0.7.3
	 *
	 * @param int[]|string[]|null $page_slugs_list Page slugs list.
	 * @param string              $error_message Error message.
	 * @return array Index is slug, value is ['error' => $error_message].
	 */
	protected function prepare_answer( ?array $page_slugs_list, string $error_message ) : ?array {
		return is_null( $page_slugs_list ) ? null : array_map(
			function( $slug ) use ( $error_message ) {
				return [ 'error' => $error_message ];
			},
			array_flip( $page_slugs_list )
		);
	}

	/**
	 * Maybe disconnect Google using 'disconnect' link.
	 * Static function.
	 *
	 * @param Ahrefs_Seo_Screen $screen Screen instance.
	 * @return void
	 */
	public static function maybe_disconnect( Ahrefs_Seo_Screen $screen ) : void {
		if ( isset( $_GET['disconnect-analytics'] ) && check_admin_referer( $screen->get_nonce_name(), 'disconnect-analytics' ) && current_user_can( Ahrefs_Seo::CAP_SETTINGS_ACCOUNTS_SAVE ) ) {
			// disconnect Analytics.
			self::get()->disconnect();
			Ahrefs_Seo::get()->initialized_set( null, false );
			// show notice if any of Analytics settings changed.
			$params = [
				'page' => isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : Ahrefs_Seo::SLUG,
				'tab'  => isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : null,
				'step' => isset( $_GET['step'] ) ? sanitize_text_field( wp_unslash( $_GET['step'] ) ) : null,
			];
			Helper_Content::wp_redirect( remove_query_arg( [ 'disconnect-analytics' ], add_query_arg( $params, admin_url( 'admin.php' ) ) ) );
			die();
		}
	}

	/**
	 * Try to refresh current access token using refresh token.
	 * Disconnect Analytics on invalid_grant error or if no refresh token exists.
	 *
	 * @return bool Was the token updated
	 */
	private function try_to_refresh_token() : bool {
		// try to update current token.
		try {
			$client = $this->create_client();
			Ahrefs_Seo::breadcrumbs( sprintf( '%s: %s', __METHOD__, wp_json_encode( $client->getAccessToken() ) ) );
			$refresh = $client->getRefreshToken();
			if ( $refresh ) {
				$_token           = $client->getAccessToken();
				$created_time_old = $_token['created'] ?? 0;

				$client->refreshToken( $refresh );

				$_token           = $client->getAccessToken();
				$created_time_new = $_token['created'] ?? 0;

				if ( $created_time_new === $created_time_old ) {
					$this->disconnect();
					$this->set_message( __( 'Google account disconnected due to invalid token.', 'ahrefs-seo' ) );
				}
				return true;
			} else {
				$this->disconnect();
			}
		} catch ( \LogicException $e ) {
			$this->disconnect();
			Ahrefs_Seo_Errors::save_message( 'google', $e->getMessage(), Message::TYPE_NOTICE );
			Ahrefs_Seo::notify( $e, 'token refresh' );
		} catch ( GuzzleClientException $e ) {
			$this->disconnect();
			Ahrefs_Seo_Errors::save_message( 'google', $e->getMessage(), Message::TYPE_NOTICE );
			Ahrefs_Seo::notify( $e, 'token refresh' );
		} catch ( Google_Service_Exception $e ) {
			$errors = $e->getErrors();
			$this->disconnect();
			if ( is_array( $errors ) && count( $errors ) && ( 401 === $e->getCode() && isset( $errors[0]['reason'] ) && 'authError' === $errors[0]['reason'] ) ) {
				$message = __( 'Google account disconnected due to invalid token.', 'ahrefs-seo' );
				$this->set_message( $message, $e );
			} else {
				/* Translators: 1: error code, 2: error message */
				$message = sprintf( __( 'There was an additional Google Auth error while refresh token %1$d: %2$s', 'ahrefs-seo' ), $e->getCode(), $e->getMessage() );
				$this->set_message( $this->get_message() . ' ' . $message, $e );
			}
		} catch ( \Exception $e ) {
			$this->disconnect();
			/* Translators: 1: error code, 2: error message */
			$message = sprintf( __( 'There was an additional error while refresh token %1$d: %2$s', 'ahrefs-seo' ), $e->getCode(), $e->getMessage() );
			$this->set_message( $this->get_message() . ' ' . $message, $e );
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			$this->set_message( $message );
		}
		return false;
	}

	/**
	 * Handle exception, set error message, maybe refresh token or disconnect on invalid token
	 *
	 * @param \Exception $e Exception.
	 * @param bool       $set_service_error Set internal variable with error message.
	 * @param bool       $is_gsc Is exception coming from GSC.
	 * @param bool       $save_message Save message.
	 *
	 * @return void
	 */
	private function handle_exception( \Exception $e, bool $set_service_error = false, bool $is_gsc = false, bool $save_message = true ) : void {
		Ahrefs_Seo::breadcrumbs( __METHOD__ . (string) wp_json_encode( [ (string) $e, $set_service_error, $is_gsc ] ) );
		if ( $e instanceof Google_Service_Exception ) {
			$no_report = false;
			if ( $set_service_error ) {
				$this->service_error = $e->getErrors();
			}

			$error = json_decode( $e->getMessage(), true );
			if ( is_array( $error ) && isset( $error['error'] ) && in_array( $error['error'], [ 'invalid_grant', 'unauthorized_client' ], true ) ) {
				// tokens are invalid.
				do_action( 'ahrefs_seo_analylics_token_disconnect' );
				$this->disconnect();
				$this->set_message( '' ); // clean possible error message.
				$this->set_gsc_disconnect_reason( __( 'Your Google account has been disconnected because token has been expired or revoked.', 'ahrefs-seo' ) );
			} elseif ( 403 === $e->getCode() ) {
				$errors = $e->getErrors();

				if ( is_array( $errors ) && ( 0 < count( $errors ) ) && isset( $errors[0]['reason'] ) ) {
					$reason = $errors[0]['reason'];
					if ( 'forbidden' === $reason ) {
						if ( $is_gsc && $this->data_tokens->get_gsc_site() ) { // if was not disconnected before.
							$site    = preg_match( "/site '([^']+)'/", $errors[0]['message'] ?? $e->getMessage(), $m ) ? $m[1] : $this->data_tokens->get_gsc_site(); // get site from message.
							$message = ! empty( $site ) ?
								/* Translators: %s: site url */
								sprintf( __( 'Your Google account has been disconnected because you don’t have the required permission for %s site.', 'ahrefs-seo' ), $site )
								:
								__( 'Your Google account has been disconnected because you don’t have the required permission for this site.', 'ahrefs-seo' );

							$this->set_gsc_disconnect_reason( $message );

							$this->set_message( $message, $e ); // this will submit error.
							$no_report = true;
						} else {
							$ga      = $this->data_tokens->get_ua_id();
							$message = ! empty( $ga ) ?
							/* Translators: %s: profile name */
							sprintf( __( 'Your Google account has been disconnected because you don’t have the sufficient permissions for %s profile.', 'ahrefs-seo' ), $ga )
							:
							__( 'Your Google account has been disconnected because you don’t have the sufficient permissions for this profile.', 'ahrefs-seo' );

							$this->set_ga_disconnect_reason( $message );
						}
					} elseif ( 'insufficientPermissions' === $reason && isset( $errors[0]['message'] ) && 'User does not have any Google Analytics account.' === $errors[0]['message'] ) { // do not translate 'User does not....'.
						$message = __( 'Google Search Console account does not exists.', 'ahrefs-seo' );
						$this->set_message( $message );
						$no_report = true;
					}
				}
			} elseif ( 401 === $e->getCode() ) {
				$this->try_to_refresh_token();
			} elseif ( 400 === $e->getCode() ) {
				$errors = $e->getErrors();
				if ( is_array( $errors ) && ( 0 < count( $errors ) ) && isset( $errors[0]['reason'] ) ) {
					$err = $errors[0];
					if ( ( 'invalidParameter' === $err['reason'] ) && ( 'siteUrl' === ( $err['location'] ?? '' ) ) ) {
						/* Translators: %s: original error message */
						$message = sprintf( __( 'Your Google account has been disconnected because of error: %s', 'ahrefs-seo' ), $err['message'] ?? $err['reason'] );
						$this->set_gsc_disconnect_reason( $message );
						$this->set_message( $message, $e ); // this will submit error.
						$no_report = true;
					}
				}
			}

			if ( ! $no_report ) {
				$this->set_message( $this->extract_message( $e ), $e );
			}
		} elseif ( $e instanceof GuzzleRequestException ) { // GuzzleConnectException and GuzzleClientException.
			if ( strpos( $e->getMessage(), '"error"' ) && ( strpos( $e->getMessage(), '"invalid_grant"' ) || strpos( $e->getMessage(), '"invalid_token"' ) ) ) {
				do_action( 'ahrefs_seo_analylics_token_disconnect' );
				$this->disconnect();
				$this->set_gsc_disconnect_reason( __( 'Your Google account has been disconnected because token has been expired or revoked.', 'ahrefs-seo' ) );
			}
			Ahrefs_Seo::notify( $e );
		} elseif ( $e instanceof Ahrefs_Seo_Compatibility_Exception ) {
			Content_Audit::audit_stop( [ Message::google_api_error( $e->getMessage() ) ] );
			Ahrefs_Seo::notify( $e );
		} else { // \Exception.
			Ahrefs_Seo::breadcrumbs( 'Events ' . (string) wp_json_encode( $this->get_logged_events() ) );
			if ( $save_message ) {
				$this->set_message( $this->extract_message( $e ), $e );
			}
			Ahrefs_Seo::notify( $e );
		}
	}

	/**
	 * Do a minimal delay between requests.
	 * Used to prevent API rate errors.
	 *
	 * @param string $what_api What API used: 'ga', 'ga4' or 'gsc'.
	 * @param bool   $request_just_finished Do not pause, just set request time.
	 * @return void
	 */
	protected function maybe_do_a_pause( string $what_api, bool $request_just_finished = false ) : void {
		if ( ! $request_just_finished ) {
			$time_since = microtime( true ) - ( $this->last_query_time[ $what_api ] ?? 0 );
			if ( $time_since < self::API_MIN_DELAY && ! defined( 'AHREFS_SEO_IGNORE_DELAY' ) ) {
				$pause = intval( ceil( ( self::API_MIN_DELAY - $time_since ) * 1000000 ) );
				Ahrefs_Seo::breadcrumbs( sprintf( '%s(%s): %d', __METHOD__, $what_api, $pause ) );
				Ahrefs_Seo::usleep( $pause );
			}
		}
		$this->last_query_time[ $what_api ] = microtime( true );
	}

	/**
	 * Prepare query to GSC.
	 *
	 * @since 0.7.3
	 *
	 * @param string                       $key Key for responses array.
	 * @param Google_Http_Batch            $batch Batch instance.
	 * @param Google_Service_SearchConsole $service_searchconsole Google Service SearchConsole instance.
	 * @param string                       $gsc_site Site for query.
	 * @param array                        $parameters Other parameters for query.
	 *
	 * @return void
	 */
	private function prepare_gsc_query( string $key, Google_Http_Batch &$batch, Google_Service_SearchConsole $service_searchconsole, string $gsc_site, array $parameters ) : void {
		$result = [];
		try {
			$post_body = new Google_Service_SearchConsole_SearchAnalyticsQueryRequest( $parameters );
			$request   = $service_searchconsole->searchanalytics->query( $gsc_site, $post_body, [ 'quotaUser' => $this->get_api_user() ] );
			$batch->add( $request, $key );
		} catch ( \Exception $e ) {
			$this->handle_exception( $e, false, true );
			return;
		}
	}

	/**
	 * Parse results of request.
	 *
	 * @since 0.7.3
	 *
	 * @param \ahrefs\AhrefsSeo_Vendor\Google\Service\SearchConsole\SearchAnalyticsQueryResponse|null $response Response.
	 * @return array<array{query:string, clicks:int, pos:float, impr:int}>
	 */
	protected function parse_gsc_response( ?Google_Service_SearchConsole_SearchAnalyticsQueryResponse $response ) : array {
		$result = [];
		if ( ! empty( $response ) ) {
			/**
			* @var \ahrefs\AhrefsSeo_Vendor\Google\Service\SearchConsole\ApiDataRow $row
			*/
			foreach ( $response->getRows() as $row ) {
				if ( $row instanceof Google_Service_SearchConsole_ApiDataRow ) {
					$keys     = $row->getKeys();
					$clicks   = $row->getClicks();
					$impr     = $row->getImpressions();
					$position = $row->getPosition();
					$result[] = [
						'query'  => $keys[0],
						'impr'   => $impr,
						'clicks' => $clicks,
						'pos'    => $position,
					];
				}
			}
		}
		return $result;
	}

	/**
	 * Load keywords for url from GSC
	 *
	 * @param array<string, Data_Keyword> $data_url_and_country_list [ post_tax_string => Data_Keyword ] pairs with url and country code filled.
	 * @param string                      $start_date Start date.
	 * @param string                      $end_date End date.
	 * @param int|null                    $limit Keywords limit.
	 * @param bool                        $without_totals Do not make additional query for total values.
	 * @param Data_Keyword[]              $data_keyword_list Current or imported keyword of post, if is set.
	 *
	 * @return array<string, array{total_clicks:int, total_impr:int, result:array<array{query:string, clicks:int, pos:float, impr:int}>, kw_pos:array<array{query:string, clicks:int, pos:float, impr:int}>|null, error?:\Exception}|array{error:\Exception|\Error}>|null
	 *                                   Array with details [post_tax_string (same index as was in $urls) => results] or null on error.
	 *                                   Each value has indexes:
	 *                                   @type int $total_clicks
	 *                                   @type int $total_impr
	 *                                   @type array $result
	 *                                   @type array $kw_pos
	 *                                   @type string $error Error text if any
	 */
	public function get_clicks_and_impressions_by_urls( array $data_url_and_country_list, string $start_date = null, string $end_date = null, ?int $limit = null, bool $without_totals = false, array $data_keyword_list = [] ) : ?array {
		// is GSC enabled?
		if ( ! $this->is_gsc_enabled() ) {
			$this->set_message( __( 'Google Search Console disconnected.', 'ahrefs-seo' ) );
			$this->service_error = [ [ 'reason' => 'internal-no-token' ] ];
			return null;
		}
		if ( ! $this->is_gsc_set() ) {
			$this->set_message( __( 'Please choose Google Search Console site.', 'ahrefs-seo' ) );
			$this->service_error = [ [ 'reason' => 'internal-no-profile' ] ];
			return null;
		}

		Ahrefs_Seo::breadcrumbs( sprintf( '%s %s', __METHOD__, (string) wp_json_encode( func_get_args() ) ) );
		$results      = [];
		$url_to_key   = [];
		$time_wait    = 0;
		$time_query_1 = 0;
		$limit        = $limit ?? self::GSC_KEYWORDS_LIMIT;
		$responses    = null;
		$urls         = array_map(
			function( Data_Keyword $data_keyword ) {
				return $data_keyword->get_url();
			},
			$data_url_and_country_list
		);
		try {
			$client                = $this->create_client();
			$service_searchconsole = new Google_Service_SearchConsole( $client );
			$batch                 = $service_searchconsole->createBatch();
			$client->setUseBatch( true );
			/** @var Data_Keyword $data_keyword */
			foreach ( $data_url_and_country_list as $key => $data_keyword ) {
				$url                = (string) $data_keyword->get_url(); // we already filtered out empty urls.
				$country_code       = $data_keyword->get_country_code();
				$url_to_key[ $url ] = $key;
				// request must use same scheme, as site parameter has.
				if ( false === strpos( $this->data_tokens->get_gsc_site(), 'sc-domain:' ) && false === strpos( $url, $this->data_tokens->get_gsc_site() ) ) {
					$scheme_current  = explode( '://', $url, 2 );
					$scheme_required = explode( '://', $this->data_tokens->get_gsc_site(), 2 );
					if ( 2 === count( $scheme_current ) && 2 === count( $scheme_required ) ) {
						$url = $scheme_required[0] . '://' . $scheme_current[1];
					}
				}

				$filters = [
					[
						'dimension'  => 'page',
						'expression' => $this->url_for_gsc( $url ),
					],
				];
				if ( '' !== $country_code ) {
					$filters[] = [
						'dimension'  => 'country',
						'expression' => $country_code,
					];
				}
				$parameters = [
					'startDate'             => $start_date,
					'endDate'               => $end_date,
					'dimensions'            => [], // without any values.
					'dimensionFilterGroups' => [
						[
							'filters' => $filters,
						],
					],
					'rowLimit'              => $limit,
					'startRow'              => 0,
				];

				// Total clicks, positions, impressions.
				if ( ! $without_totals ) {
					$this->prepare_gsc_query(
						"{$key}-total",
						$batch,
						$service_searchconsole,
						$this->data_tokens->get_gsc_site(),
						array_merge(
							$parameters,
							[
								'dimensions' => [
									'page',
								],
							]
						)
					);
				}

				// Top 10 clicks, positions, impressions.
				$this->prepare_gsc_query(
					"{$key}-q",
					$batch,
					$service_searchconsole,
					$this->data_tokens->get_gsc_site(),
					array_merge(
						$parameters,
						[
							'dimensions' => [
								'query',
								'page',
							],
						]
					)
				);
			}

			$e = null;
			try {
				// execute requests.
				$time0 = microtime( true );
				$this->maybe_do_a_pause( 'gsc' );
				$time_wait += microtime( true ) - $time0;
				$time0      = microtime( true );

				$responses = $batch->execute();
				do_action_ref_array( 'ahrefs_seo_api_clicks_and_impressions', [ &$responses, $urls ] );

				$time_query_1 += microtime( true ) - $time0;
			} catch ( Google_Service_Exception $e ) { // catch forbidden error.
				$this->handle_exception( $e, false, true );
				$this->on_error_received( $e, $urls );
			} catch ( \Error $e ) {
				$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
				$this->set_message( $message );
				$this->on_error_received( $e, $urls );
			} catch ( \Exception $e ) { // catch any errors.
				$this->set_message( $this->extract_message( $e ), $e );
				$this->on_error_received( $e, $urls );
			}
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			$this->set_message( $message );
			$e = new Ahrefs_Seo_Exception( $message, 0, $e );
			$this->on_error_received( $e, $urls );
		} finally {
			if ( ! empty( $client ) ) {
				$client->setUseBatch( false );
			}
		}

		if ( is_null( $responses ) ) {
			if ( empty( $e ) ) {
				$e = new Ahrefs_Seo_Exception( 'GSC returned empty response.' );
			}
			// Nothing received - exit earlier.
			return array_map(
				function( $value ) use ( $e ) {
					return [ 'error' => $e ];
				},
				$urls
			);
		}

		// parse requests.
		foreach ( $data_url_and_country_list as $key => $data_keyword ) {
			$url          = $data_keyword->get_url();
			$result       = [];
			$total_clicks = 0;
			$total_impr   = 0;
			$total_filled = false;

			if ( ! $without_totals ) {
				$answer = $responses[ "response-{$key}-total" ] ?? null;
				if ( $answer instanceof Google_Service_Exception ) { // catch forbidden error.
					$message = $this->extract_message( $answer );
					$this->handle_exception( $answer, false, true );
					$this->on_error_received( $answer, [ $url ] );
					$this->gsc_paused = true; // do not make additional requests.
					continue;
				} elseif ( $answer instanceof \Exception ) {
					$results[ $key ] = [ 'error' => $answer ];
					$this->on_error_received( $answer, [ $url ] );
					Ahrefs_Seo::notify( $answer, 'gsc get_clicks_and_impressions single' );
					Ahrefs_Seo_Errors::save_message( 'google', $this->extract_message( $answer ), Message::TYPE_ERROR );
					$this->gsc_paused = true; // do not make additional requests.
					continue;
				}
				$response_total = $this->parse_gsc_response( $answer );
				if ( ! empty( $response_total ) ) {
					foreach ( $response_total as $row ) {
						// save total clicks & impressions.
						$total_clicks = $row['clicks'];
						$total_impr   = $row['impr'];
						$total_filled = true;
						break;
					}
				}
			}
			$answer = $responses[ "response-{$key}-q" ] ?? null;

			if ( $answer instanceof Google_Service_Exception ) { // catch forbidden error.
				$message = $this->extract_message( $answer );
				$this->handle_exception( $answer, false, true );
				$this->on_error_received( $answer, [ $url ] );
				$this->gsc_paused = true; // do not make additional requests.
				continue;
			} elseif ( $answer instanceof \Exception ) {
				$results[ $key ] = [ 'error' => $answer ];
				$this->on_error_received( $answer, [ $url ] );
				Ahrefs_Seo::notify( $answer, 'gsc get_clicks_and_impressions single' );
				Ahrefs_Seo_Errors::save_message( 'google', $this->extract_message( $answer ), Message::TYPE_ERROR );
				$this->gsc_paused = true; // do not make additional requests.
				continue;
			}
			$response = $this->parse_gsc_response( $answer );

			$kw_pos = null;
			if ( ! empty( $response ) ) {
				foreach ( $response as $row ) {
					$result[] = $row;
					$keyword  = $row['query'];
					// exclude some of $data_keyword_list items if already exists in results list.
					if ( count( $data_keyword_list ) ) {
						foreach ( $data_keyword_list as $k => $data_keyword ) {
							if ( ( $url === $data_keyword->get_url() ) && $data_keyword->is_same_keyword( $keyword ) ) {
								// we can lost data for kw_pos here... so save it.
								if ( ! is_array( $kw_pos ) ) {
									$kw_pos = [];
								}
								$kw_pos[] = $row;
								unset( $data_keyword_list[ $k ] );
							}
						}
					}

					if ( ! $total_filled ) {
						// count total clickes & impressions.
						$total_clicks += $row['clicks'];
						$total_impr   += $row['impr'];
					}
				}
			}

			$results[ $key ] = [
				'total_clicks' => $total_clicks,
				'total_impr'   => $total_impr,
				'result'       => $result,
				'kw_pos'       => $kw_pos,
			];
		}

		if ( count( $data_keyword_list ) ) {
			// remove empty values.
			/** @var Data_Keyword[] $data_keyword_list_  */
			$data_keyword_list_ = array_filter(
				$data_keyword_list,
				function( Data_Keyword $item ) {
					return ! is_null( $item->get_keyword() ) && ( '' !== $item->get_keyword() );
				}
			);
			// remove non unique items.
			$data_keyword_list = [];
			foreach ( $data_keyword_list_ as $item ) {
				$data_keyword_list[ "{$item->get_url()}|" . strtolower( $item->get_keyword() ?? '' ) . '|' . $item->get_country_code() ] = $item;
			}
			$data_keyword_list = array_values( $data_keyword_list );
			unset( $data_keyword_list_ );
		}

		if ( count( $data_keyword_list ) && ! $this->gsc_paused ) {
			// make additional request and load details for current keywords.
			$additional = $this->get_position_fast( $data_keyword_list );
			if ( ! empty( $additional ) ) {
				foreach ( $additional as $key => $data_keyword ) {
					if ( ! is_null( $data_keyword->get_clicks_info() ) ) {
						$key                           = $url_to_key[ $data_keyword->get_url() ];
						$results[ "$key" ]['result'][] = $data_keyword->as_gsc_array();
						if ( ! isset( $results[ "$key" ]['kw_pos'] ) ) {
							$results[ "$key" ]['kw_pos'] = [];
						}
						$results[ "$key" ]['kw_pos'][] = $data_keyword->as_gsc_array();
					}
				}
			}
		}
		$this->gsc_paused = false; // unblock next requests to API.

		$total_clicks = array_map(
			function( $values ) {
				return $values['total_clicks'] ?? null;
			},
			$results
		);
		Ahrefs_Seo::breadcrumbs( sprintf( '%s(%s) (%s) (%s) (%d): wait: %1.3fsec, query:  %1.3fsec. Total clicks: %s', __METHOD__, (string) wp_json_encode( $data_url_and_country_list ), $start_date, $end_date, $limit, $time_wait, $time_query_1, (string) wp_json_encode( $total_clicks ) ) );
		return $results;
	}

	/**
	 * Load metrics (position, clicks, impressions) of keyword.
	 *
	 * @param Data_Keyword[] $list_url_keyword List with url and keyword fields filled.
	 * @return Data_Keyword[]|null Null if error, a list of results otherwise
	 */
	public function get_position_fast( array $list_url_keyword ) : ?array {
		Ahrefs_Seo::breadcrumbs(
			__METHOD__ . (string) wp_json_encode(
				array_map(
					function( $item ) {
						return $item->as_array();
					},
					$list_url_keyword
				)
			)
		);
		$start_date = date( 'Y-m-d', strtotime( sprintf( '- 3 month' ) ) );
		$end_date   = date( 'Y-m-d' );

		// is GSC enabled?
		if ( ! $this->is_gsc_enabled() ) {
			$this->set_message( __( 'Google Search Console disconnected.', 'ahrefs-seo' ) );
			$this->service_error = [ [ 'reason' => 'internal-no-token' ] ];
			return null;
		}
		if ( ! $this->is_gsc_set() ) {
			$this->set_message( __( 'Please choose Google Search Console site.', 'ahrefs-seo' ) );
			$this->service_error = [ [ 'reason' => 'internal-no-profile' ] ];
			return null;
		}

		$results      = [];
		$time_wait    = 0;
		$time_query_3 = 0;
		try {
			$client                = $this->create_client();
			$service_searchconsole = new Google_Service_SearchConsole( $client );

			$batch = $service_searchconsole->createBatch();
			$client->setUseBatch( true );
			$key = 0;
			foreach ( $list_url_keyword as $key => $row ) {
				$url = $this->url_for_gsc( $row->get_url() ?? '' );

				$current_keyword = $row->get_keyword();
				$country_code    = $row->get_country_code();
				// request must use same scheme, as site parameter has.
				if ( false === strpos( $this->data_tokens->get_gsc_site(), 'sc-domain:' ) && false === strpos( $url, $this->data_tokens->get_gsc_site() ) ) {
					$scheme_current  = explode( '://', $url, 2 );
					$scheme_required = explode( '://', $this->data_tokens->get_gsc_site(), 2 );
					if ( 2 === count( $scheme_current ) && 2 === count( $scheme_required ) ) {
						$url = $scheme_required[0] . '://' . $scheme_current[1];
					}
				}
				$filters = [
					[
						'dimension'  => 'page',
						'expression' => $url,
					],
					[
						'dimension'  => 'query',
						'expression' => $current_keyword,
					],
				];
				if ( '' !== $country_code ) {
					$filters[] = [
						'dimension'  => 'country',
						'expression' => $country_code,
					];
				}
				$parameters = [
					'startDate'             => $start_date,
					'endDate'               => $end_date,
					'dimensions'            => [
						'query',
						'page',
					],
					'dimensionFilterGroups' => [
						[
							'filters' => $filters,
						],
					],
					'rowLimit'              => 1,
					'startRow'              => 0,
				];

				// prepare request and load details for current keyword.
				$this->prepare_gsc_query(
					"{$key}-f",
					$batch,
					$service_searchconsole,
					$this->data_tokens->get_gsc_site(),
					$parameters
				);
			}

			try {
				// execute requests.
				$time0 = microtime( true );
				$this->maybe_do_a_pause( 'gsc' );
				$time_wait += microtime( true ) - $time0;
				$time0      = microtime( true );
				$responses  = $batch->execute();
				do_action_ref_array( 'ahrefs_seo_api_position_fast', [ &$responses ] );
				$time_query_3 += microtime( true ) - $time0;
			} catch ( \Exception $e ) { // catch all errors.
				$this->on_error_received(
					$e,
					array_filter(
						array_map(
							function( Data_Keyword $item ) {
								return $item->get_url();
							},
							$list_url_keyword
						)
					)
				);
				$this->handle_exception( $e );
				return null; // exit without success.
			}
		} catch ( \Error $e ) {
			$message = Ahrefs_Seo_Compatibility::on_type_error( $e, __METHOD__, __FILE__ );
			$this->set_message( $message );
			return null;
		} finally {
			if ( ! empty( $client ) ) {
				$client->setUseBatch( false );
			}
		}

		foreach ( $list_url_keyword as $key => $row ) {
			$results[ $key ] = new Data_Keyword( $row->get_keyword(), Sources::SOURCE_GSC, null, $row->get_url(), $row->get_country_code() ); // assign same url, keyword and country code.
			$url             = $row->get_url() ?? '';
			$answer          = $responses[ "response-{$key}-f" ] ?? null;
			if ( $answer instanceof Google_Service_Exception ) { // catch forbidden error.
				$message = $this->extract_message( $answer );
				$this->handle_exception( $answer, false, true );
				$this->on_error_received( $answer, [ $url ] );
				$this->gsc_paused = true; // do not make additional requests.
				continue;
			} elseif ( $answer instanceof \Exception ) {
				$results[ $key ]->set_error( $this->extract_message( $answer ) );
				$this->on_error_received( $answer, [ $url ] );
				Ahrefs_Seo::notify( $answer, 'get_position_fast single' );
				Ahrefs_Seo_Errors::save_message( 'google', $this->extract_message( $answer ), Message::TYPE_ERROR );
				continue;
			}
			$response = $this->parse_gsc_response( $answer );
			if ( ! empty( $response ) ) {
				foreach ( $response as $row ) {
					$results[ $key ]->set_clicks_info( new Data_Clicks_Info( $row['clicks'], $row['pos'], $row['impr'] ) ); // only 1 row was loaded.
					break;
				}
			}
		}
		return $results;
	}

	/**
	 * Return lowercase domain name without 'www.'.
	 *
	 * @param null|string $url If null - return domain of current site.
	 *        Examples: http://www.example.com/ (for a URL-prefix property) or sc-domain:example.com (for a Domain property).
	 * @return string
	 */
	private function get_clean_domain( ?string $url = null ) : string {
		$result = '';
		if ( is_null( $url ) ) {
			$result = strtolower( Ahrefs_Seo::get_current_domain() );
		} else {
			$result = 0 !== strpos( $url, 'sc-domain:' ) ? wp_parse_url( $url, PHP_URL_HOST ) : substr( $url, strlen( 'sc-domain:' ) ); // url or string "sc-domain:".
			$result = is_string( $result ) ? strtolower( $result ) : ''; // wp_parse_url may return null.
		}
		if ( 0 === strpos( $result, 'www.' ) ) {
			$result = substr( $result, 4 );
		};
		return $result;
	}

	/**
	 * Set disconnect reason for GCS if any.
	 *
	 * @param string|null $string Null if not disconnected.
	 * @param bool        $reset_gsc_account Reset GSC account.
	 * @return void
	 */
	public function set_gsc_disconnect_reason( ?string $string = null, bool $reset_gsc_account = true ) : void {
		if ( $reset_gsc_account && ! is_null( $string ) ) {
			$this->set_ua( $this->data_tokens->get_ua_id(), $this->data_tokens->get_ua_name(), $this->data_tokens->get_ua_url(), '' );
		}
		( new Disconnect_Reason_GSC() )->save_reason( $string );
	}

	/**
	 * Set disconnect reason for GA if any.
	 *
	 * @since 0.7.5
	 *
	 * @param string|null  $string Null if not disconnected.
	 * @param Message|null $message Message instance.
	 * @return void
	 */
	public function set_ga_disconnect_reason( ?string $string = null, ?Message $message = null ) : void {
		if ( ! is_null( $string ) || ! is_null( $message ) ) {
			$this->set_ua( '', '', '', $this->data_tokens->get_gsc_site() );
		}
		( new Disconnect_Reason_GA() )->save_reason( $string );
	}

	/**
	 * Get logged events from API requests.
	 *
	 * @since 0.7.1
	 *
	 * @return array<array>|null Null if no logging method available.
	 */
	protected function get_logged_events() : ?array {
		return ! is_null( $this->logger ) && ( $this->logger instanceof Logger ) ? $this->logger->get_events() : null;
	}

	/**
	 * Requests to GSC API are paused
	 *
	 * @since 0.7.4
	 *
	 * @param bool $is_paused Is audit paused.
	 * @return void
	 */
	public function set_gsc_paused( bool $is_paused ) : void {
		$this->gsc_paused = $is_paused;
	}

	/**
	 * Is request to GSC API paused?
	 *
	 * @since 0.7.4
	 *
	 * @return bool
	 */
	public function is_gsc_paused() : bool {
		return $this->gsc_paused;
	}

	/**
	 * Extract human readable message from exception
	 *
	 * @since 0.7.4
	 *
	 * @param \Exception  $e Exception.
	 * @param string|null $default_message Default message, translated.
	 * @param bool        $skip_disconnected_message Do not add disconnected account message, if account is disconnecting due a error.
	 * @return string|null
	 */
	protected function extract_message( \Exception $e, ?string $default_message = null, bool $skip_disconnected_message = true ) : ?string {
		$result = $default_message ?? $e->getMessage();
		if ( $e instanceof Google_Service_Exception ) {
			$errors = $e->getErrors();
			if ( is_array( $errors ) && count( $errors ) && isset( $errors[0]['message'] ) && isset( $errors[0]['reason'] ) ) {
				if ( $skip_disconnected_message && in_array( $errors[0]['reason'], [ 'userRateLimitExceeded', 'rateLimitExceeded', 'quotaExceeded', 'internalError', 'forbidden' ], true ) ) { /** @see Worker::on_rate_error() */
					$result = null; // no need to save and show this error, because other tip displayed.
				} else {
					$reason = preg_replace( '/(?<! )[A-Z]/', ' $0', $errors[0]['reason'] ); // camel case to words with a space as separator.
					$result = sprintf( '%s. %s', ucfirst( $reason ), $errors[0]['message'] );
				}
			} else {
				if ( false !== stripos( $e->getMessage(), 'The server encountered a temporary error' ) || false !== stripos( $e->getMessage(), 'Error 404' ) ) {
					$result = null; // no need to save and show this error, because other tip displayed.
				}
			}
		} elseif ( $e instanceof GuzzleConnectException ) {
			$error = $e->getMessage();
			if ( false !== stripos( $error, 'could not resolve' ) ) { // "cURL error 6: Could not resolve host: www.googleapis.com (see https://curl.haxx.se/libcurl/c/libcurl-errors.html)".
				$result = sprintf( '%s. %s', __( 'Connection error', 'ahrefs-seo' ), __( 'Could not resolve host.', 'ahrefs-seo' ) );
			} elseif ( false !== stripos( $error, 'connection timed out' ) ) { // "cURL error 7: Failed to connect to analyticsreporting.googleapis.com port 443: Connection timed out (see https://curl.haxx.se/libcurl/c/libcurl-errors.html)".
				$result = sprintf( '%s. %s', __( 'Connection error', 'ahrefs-seo' ), __( 'Connection timed out.', 'ahrefs-seo' ) );
			} elseif ( false !== stripos( $error, 'operation timed out' ) ) { // "cURL error 28: Operation timed out after 120001 milliseconds with 0 bytes received (see https://curl.haxx.se/libcurl/c/libcurl-errors.html)".
				$result = sprintf( '%s. %s', __( 'Connection error', 'ahrefs-seo' ), __( 'Operation timed out.', 'ahrefs-seo' ) );
			} elseif ( false !== stripos( $error, 'Failed to connect' ) ) { // "cURL error 28: Operation timed out after 120001 milliseconds with 0 bytes received (see https://curl.haxx.se/libcurl/c/libcurl-errors.html)".
				$result = sprintf( '%s. %s', __( 'Connection error', 'ahrefs-seo' ), __( 'Failed to connect.', 'ahrefs-seo' ) );
			}
		} elseif ( $e instanceof GuzzleRequestException ) {
			$error = $e->getMessage();
		}
		return $result;
	}

	/**
	 * Reset pause for GA or GSC.
	 *
	 * @since 0.8.4
	 *
	 * @param bool $reset_ga Reset GA pause.
	 * @param bool $reset_gsc Reset GSC pause.
	 * @return void
	 */
	public function reset_pause( bool $reset_ga, bool $reset_gsc ) : void {
		if ( $reset_ga ) {
			( new Worker_Traffic() )->reset_pause();
		}
		if ( $reset_gsc ) {
			( new Worker_Position() )->reset_pause();
		}
	}

	/**
	 * Get top pages for current GA profile.
	 *
	 * @since 0.9.4
	 *
	 * @return string[]|null
	 */
	public function get_top_ga_results() : ?array {
		if ( ! $this->is_analytics_enabled() ) {
			return null;
		}
		$start_date = date( 'Y-m-d', time() - 3 * MONTH_IN_SECONDS );
		$end_date   = date( 'Y-m-d' );
		$ua_id      = $this->get_data_tokens()->get_ua_id();
		if ( 0 === strpos( $ua_id, 'properties/' ) ) {
			$result = $this->get_found_pages_by_ua_id_ga4( [ $ua_id ], $start_date, $end_date, false );
		} else {
			$result = $this->get_found_pages_by_ua_id_ga( [ $ua_id ], $start_date, $end_date, false );
		}

		return is_array( $result ) ? array_shift( $result ) : null;
	}

	/**
	 * Get top pages for current GSC profile.
	 *
	 * @since 0.9.4
	 *
	 * @return string[]|null
	 */
	public function get_top_gsc_results() : ?array {
		return $this->check_gsc_using_bulk_results_strings( $this->get_data_tokens()->get_gsc_site() );
	}

	/**
	 * Set advanced options
	 *
	 * @since 0.9.4
	 *
	 * @param bool $gsc_uses_uppercase My GSC uses uppercase URL encoded characters.
	 * @param bool $ga_not_urlencoded My GA does not use URL encoding.
	 * @param bool $ga_uses_full_url My GA reports full page URLs that include the domain name.
	 * @return void
	 */
	public function set_adv_options( bool $gsc_uses_uppercase, bool $ga_not_urlencoded, bool $ga_uses_full_url ) : void {
		update_option( self::OPTION_ADVANCED, (string) wp_json_encode( compact( 'gsc_uses_uppercase', 'ga_not_urlencoded', 'ga_uses_full_url' ) ) );
	}

	/**
	 * Get "My GSC uses uppercase URL encoded characters" advanced option value.
	 *
	 * @since 0.9.4
	 *
	 * @return bool
	 */
	public function get_adv_gsc_uses_uppercase() : bool {
		return (bool) ( ( $this->get_adv_options_raw() )['gsc_uses_uppercase'] ?? false );
	}

	/**
	 * Get "My GA does not use URL encoding" advanced option value.
	 *
	 * @since 0.9.4
	 *
	 * @return bool
	 */
	public function get_adv_ga_not_urlencoded() : bool {
		return (bool) ( ( $this->get_adv_options_raw() )['ga_not_urlencoded'] ?? false );
	}

	/**
	 * Get "My GA uses full URL (with site domain)" advanced option value.
	 *
	 * @since 0.9.8
	 *
	 * @return bool
	 */
	public function get_adv_ga_uses_full_url() : bool {
		return (bool) ( ( $this->get_adv_options_raw() )['ga_uses_full_url'] ?? false );
	}

	/**
	 * Get all advanced options as associative array.
	 *
	 * @since 0.9.4
	 *
	 * @return array
	 */
	public function get_adv_options_raw() : array {
		$values = json_decode( (string) get_option( self::OPTION_ADVANCED ), true );
		return is_array( $values ) ? $values : [];
	}

	/**
	 * Prepare URL for GSC request.
	 *
	 * @since 0.9.4
	 *
	 * @param string $url Original URL.
	 * @return string
	 */
	private function url_for_gsc( string $url ) : string {
		return $this->get_adv_gsc_uses_uppercase() ? (string) preg_replace_callback(
			'/%[0-9a-f]{2}/',
			function( array $matches ) {
				return strtoupper( $matches[0] );
			},
			$url
		) : $url;
	}

	/**
	 * Prepare URL for GA request.
	 *
	 * @since 0.9.4
	 *
	 * @param string $url Original URL.
	 * @return string
	 */
	private function url_for_ga( string $url ) : string {
		if ( $this->get_adv_ga_uses_full_url() ) {
			$url = Ahrefs_Seo::get_current_domain() . $url;
		}
		return $this->get_adv_ga_not_urlencoded() ? urldecode( $url ) : $url;
	}

}
