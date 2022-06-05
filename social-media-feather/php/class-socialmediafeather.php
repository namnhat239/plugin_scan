<?php
/**
 * Social Media Feather.
 *
 * @package SocialMediaFeather
 */

namespace SocialMediaFeather;

/**
 * Social Media Feather Class
 *
 * @package SocialMediaFeather
 */
class SocialMediaFeather {
	/**
	 * Plugin instance.
	 *
	 * @var object
	 */
	public $plugin;

	/**
	 * Menu slug.
	 *
	 * @var string
	 */
	public $menu_slug;

	/**
	 * Menu hook suffix.
	 *
	 * @var string
	 */
	private $hook_suffix;

	/**
	 * Holds the settings fields.
	 *
	 * @var string
	 */
	public $setting_fields;

	/**
	 * Class constructor.
	 *
	 * @param object $plugin Plugin class.
	 */
	public function __construct( $plugin ) {
		$this->plugin      = $plugin;
		$this->menu_slug   = 'social-media-feather';
		$this->hook_suffix = 'settings_page_social-media-feather-settings';
	}

	/**
	 * Enqueue react assets in admin.
	 *
	 * @action admin_enqueue_scripts
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_react_assets( $hook_suffix ) {
		// Only enqueue assets on this plugin admin menu.
		if ( $hook_suffix !== $this->hook_suffix ) {
			return;
		}

		// Load loader.js.
		if ( true === is_readable( "{$this->plugin->dir_path}build/loader.js" ) ) {
			wp_enqueue_script(
				"{$this->plugin->assets_prefix}-loader",
				"{$this->plugin->dir_url}build/loader.js",
				array(),
				filemtime( "{$this->plugin->dir_path}build/loader.js" ),
				false
			);
		}

		// Load index.js and dependencies.
		$dependencies_file = $this->plugin->dir_path . 'build/index.asset.php';

		$dependencies = array();

		$dependency_settings = array(
			'dependencies' => array(),
			'version'      => null,
		);

		if ( true === is_readable( $dependencies_file ) ) {
			$dependency_file_settings = require $dependencies_file;
			$dependency_settings      = wp_parse_args( $dependency_file_settings, $dependency_settings );
		}

		wp_enqueue_script(
			"{$this->plugin->assets_prefix}-app",
			"{$this->plugin->dir_url}build/index.js",
			$dependency_settings['dependencies'],
			$dependency_settings['version'],
			false
		);

		wp_localize_script(
			"{$this->plugin->assets_prefix}-app",
			'SocialMediaFeather',
			array(
				'nonce'     => wp_create_nonce( $this->plugin->meta_prefix ),
				'data'      => get_option( 'synved_social_settings' ),
				'review'    => get_option( 'smf-hide-review' ),
				'pluginDir' => SOCIAL_MEDIA_FEATHER_PLUGIN_DIRECTORY,
				'pluginURL' => SOCIAL_MEDIA_FEATHER_PLUGIN_URL,
			)
		);

		// Load index.css, if exists.
		if ( true === is_readable( $this->plugin->dir_path . 'build/index.css' ) ) {
			wp_enqueue_style(
				"{$this->plugin->assets_prefix}-app-style",
				$this->plugin->dir_url . 'build/index.css',
				array(),
				filemtime( $this->plugin->dir_path . 'build/index.css' )
			);
		}
	}

	/**
	 * Register REST routes.
	 *
	 * @action rest_api_init
	 */
	public function register_rest_routes() {
		register_rest_route(
			'social-media-feather/v2',
			'/settings',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'rest_settings_save' ),
				'permission_callback' => function ( \WP_REST_Request $request ) {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	/**
	 * Add settings page.
	 *
	 * @action admin_menu
	 */
	public function add_settings_page() {
		add_options_page(
			'Social Media Feather',
			'Social Media',
			'manage_options',
			'social-media-feather-settings',
			function () {
				echo '<div id="social-media-feather-app"></div>';
				echo wp_kses_post( synved_connect_support_social_follow_render() );
			},
			99
		);
	}

	/**
	 * Add plugin settings link.
	 *
	 * @param array  $plugin_actions Plugin actions array.
	 * @param string $plugin_file Plugin file.
	 *
	 * @action plugin_action_links
	 */
	public function add_plugin_settings_link( $plugin_actions, $plugin_file ) {
		$local_actions = array();

		if ( 'social-media-feather.php' === basename( $plugin_file ) ) {
			$local_actions['settings'] = '<a href="'
										. esc_url( admin_url( 'options-general.php?page=social-media-feather-settings' ) )
										. '">'
										. esc_html__( 'Settings', 'social-media-feather' )
										. '</a>';

			$plugin_actions = $plugin_actions + $local_actions;
		}

		return $plugin_actions;
	}

	/**
	 * Settings save endpoint.
	 *
	 * @param \WP_REST_Request $data Request data.
	 *
	 * @return void
	 */
	public function rest_settings_save( \WP_REST_Request $data ) {
		$settings_params_raw = $data->get_json_params();

		$settings_params_filtered = $this->sanitize_incoming_fields( $settings_params_raw );

		$current_settings = get_option( 'synved_social_settings' );

		$settings = wp_parse_args( $settings_params_filtered, $current_settings );

		update_option( 'synved_social_settings', $settings );

		wp_send_json_success( $settings_params_filtered );
	}

	/**
	 * Sanitize incoming fields.
	 *
	 * @param array $fields Array of fields.
	 *
	 * @return array Sanitized array of fields.
	 */
	public function sanitize_incoming_fields( array $fields ) {
		$whitelist = $this->get_field_whitelist();

		// Remove fields that aren't in our whitelist.
		$fields_filtered = array_intersect_key( $fields, $whitelist );

		// Sanitize field values against their sanitizer type.
		$fields_filtered = array_filter(
			$fields_filtered,
			function ( $raw_value, $field ) use ( $whitelist ) {
				if ( false === array_key_exists( $field, $whitelist ) ) {
					return false;
				}

				$type = $whitelist[ $field ]['type'];

				switch ( $type ) {
					case 'array':
						return true === is_array( $raw_value );
					case 'boolean':
						return true === is_bool( $raw_value );
					case 'string':
						return true === is_string( $raw_value );
					case 'number':
						return true === is_numeric( $raw_value );
					default:
						return true;
				}
			},
			ARRAY_FILTER_USE_BOTH
		);

		// Run through sanitizers.
		array_walk(
			$fields_filtered,
			function ( $raw_value, $field ) use ( &$fields_filtered, $whitelist ) {
				$sanitizer = $whitelist[ $field ]['sanitizer'];

				if ( true === is_null( $sanitizer ) ) {
					return;
				}

				switch ( $sanitizer ) {
					case 'alphanum':
						$fields_filtered[ $field ] =
							preg_replace( '/[^A-Za-z0-9 ]/', '', $raw_value );
						break;
					case 'esc_asc':
						$fields_filtered[ $field ] = esc_attr( $raw_value );
						break;
					case 'esc_html':
						$fields_filtered[ $field ] = wp_kses_decode_entities( esc_html( $raw_value ) );
						break;
					case 'esc_style':
						$fields_filtered[ $field ] = wp_kses(
							$raw_value,
							array(
								'style' => array(),
							)
						);
						break;
					case 'esc_url':
						$fields_filtered[ $field ] = rawurldecode( esc_url_raw( $raw_value ) );
						break;
					case 'kses':
						$fields_filtered[ $field ] = wp_kses(
							$raw_value,
							synved_get_allowed_html_array()
						);
						break;
					case 'kses_post':
						$fields_filtered[ $field ] = wp_kses_post( $raw_value );
						break;
					case 'in_array':
						$options = $whitelist[ $field ]['options'];

						if ( false === in_array( $raw_value, $options, true ) ) {
							$fields_filtered[ $field ] = $options[0];

							return;
						}
						break;
					case 'numeric':
						$fields_filtered[ $field ] =
							preg_replace( '/[^0-9 ]/', '', $raw_value );
						break;
					case 'array_intersect':
						$options = $whitelist[ $field ]['options'];

						$fields_filtered[ $field ] = array_intersect( $raw_value, $options );
						break;
				}
			}
		);

		return $fields_filtered;
	}

	/**
	 * Get field whitelist.
	 *
	 * @return array Hashed array with fields as key and sanitizer type as value.
	 */
	public function get_field_whitelist() {
		return array(
			'accepted_sharethis_terms'      => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'automatic_append_postfix'      => array(
				'type'      => 'string',
				'sanitizer' => 'kses_post',
			),
			'automatic_append_prefix'       => array(
				'type'      => 'string',
				'sanitizer' => 'kses_post',
			),
			'automatic_append_separator'    => array(
				'type'      => 'string',
				'sanitizer' => 'kses_post',
			),
			'automatic_follow'              => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'automatic_follow_before_share' => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'automatic_follow_position'     => array(
				'type'      => 'array',
				'sanitizer' => 'in_array',
				'options'   => array(
					'after_post',
					'before_post',
					'after_before_post',
				),
			),
			'automatic_follow_post_types'   => array(
				'type'      => 'array',
				'sanitizer' => 'array_intersect',
				'options'   => array(
					'post',
					'page',
					'attachment',
				),
			),
			'automatic_follow_postfix'      => array(
				'type'      => 'string',
				'sanitizer' => 'kses_post',
			),
			'automatic_follow_prefix'       => array(
				'type'      => 'string',
				'sanitizer' => 'kses_post',
			),
			'automatic_follow_single'       => array(
				'type'      => '',
				'sanitizer' => '',
			),
			'automatic_share'               => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'automatic_share_position'      => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'after_post',
					'before_post',
					'after_before_post',
				),
			),
			'automatic_share_post_types'    => array(
				'type'      => 'array',
				'sanitizer' => 'array_intersect',
				'options'   => array(
					'post',
					'page',
					'attachment',
				),
			),
			'automatic_share_postfix'       => array(
				'type'      => 'string',
				'sanitizer' => 'kses_post',
			),
			'automatic_share_prefix'        => array(
				'type'      => 'string',
				'sanitizer' => 'kses_post',
			),
			'automatic_share_single'        => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'custom_style'                  => array(
				'type'      => 'string',
				'sanitizer' => 'esc_style',
			),
			'buttons_alignment_follow'      => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'center',
					'left',
					'none',
					'right',
				),
			),
			'buttons_alignment_share'       => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'center',
					'left',
					'none',
					'right',
				),
			),
			'buttons_container'             => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'both',
					'follow',
					'none',
					'share',
				),
			),
			'buttons_container_type'        => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'basic',
					'block',
				),
			),
			'facebook_display'              => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'both',
					'follow',
					'none',
					'share',
				),
			),
			'facebook_follow_link'          => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'facebook_follow_title'         => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'facebook_share_link'           => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'facebook_share_title'          => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'fb_app_id'                     => array(
				'type'      => 'string',
				'sanitizer' => 'numeric',
			),
			'flickr_display'                => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'follow',
					'none',
				),
			),
			'flickr_follow_link'            => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'flickr_follow_title'           => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'foursquare_display'            => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'follow',
					'none',
				),
			),
			'foursquare_follow_link'        => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'foursquare_follow_title'       => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'hide_sharethis_terms'          => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'icon_resolution'               => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'double',
					'single',
				),
			),
			'icon_size'                     => array(
				'type'      => 'number',
				'sanitizer' => null,
			),
			'icon_skin'                     => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'balloon',
					'clearslate',
					'circle',
					'darkslate',
					'flag',
					'medal',
					'regular',
					'shed',
					'wheel',
				),
			),
			'icon_spacing'                  => array(
				'type'      => 'number',
				'sanitizer' => null,
			),
			'instagram_display'             => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'follow',
					'none',
				),
			),
			'instagram_follow_link'         => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'instagram_follow_title'        => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'layout_rtl'                    => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'linkedin_display'              => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'both',
					'follow',
					'none',
					'share',
				),
			),
			'linkedin_follow_link'          => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'linkedin_follow_title'         => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'linkedin_share_link'           => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'linkedin_share_title'          => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'mail_display'                  => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'both',
					'follow',
					'none',
					'share',
				),
			),
			'mail_follow_link'              => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'mail_follow_title'             => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'mail_share_link'               => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'mail_share_title'              => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'pinterest_display'             => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'both',
					'follow',
					'none',
					'share',
				),
			),
			'pinterest_follow_link'         => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'pinterest_follow_title'        => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'pinterest_share_link'          => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'pinterest_share_title'         => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'reddit_display'                => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'none',
					'share',
				),
			),
			'reddit_share_link'             => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'reddit_share_title'            => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'rss_display'                   => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'follow',
					'none',
				),
			),
			'rss_follow_link'               => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'rss_follow_title'              => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'share_full_url'                => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'share_message_default'         => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'shortcode_widgets'             => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'show_credit'                   => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'tumblr_display'                => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'both',
					'follow',
					'none',
					'share',
				),
			),
			'tumblr_follow_link'            => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'tumblr_follow_title'           => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'tumblr_share_link'             => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'tumblr_share_title'            => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'twitter_display'               => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'both',
					'follow',
					'none',
					'share',
				),
			),
			'twitter_follow_link'           => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'twitter_follow_title'          => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'twitter_share_link'            => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'twitter_share_title'           => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'use_shortlinks'                => array(
				'type'      => 'boolean',
				'sanitizer' => null,
			),
			'vimeo_display'                 => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'follow',
					'none',
				),
			),
			'vimeo_follow_link'             => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'vimeo_follow_title'            => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
			'youtube_display'               => array(
				'type'      => 'string',
				'sanitizer' => 'in_array',
				'options'   => array(
					'follow',
					'none',
				),
			),
			'youtube_follow_link'           => array(
				'type'      => 'string',
				'sanitizer' => 'esc_url',
			),
			'youtube_follow_title'          => array(
				'type'      => 'string',
				'sanitizer' => 'esc_html',
			),
		);
	}

	/**
	 * Show admin notices.
	 *
	 * @action admin_notices
	 */
	public function show_sharethis_terms() {
		$can_show_share_this_alert =
			false === self::get_social_option( 'accepted_sharethis_terms', false )
			&& false === self::get_social_option( 'hide_sharethis_terms', false );

		if ( true === $can_show_share_this_alert ) {
			self::show_terms_notice();
		}
	}

	/**
	 * Get Social Option by name with fallback default value.
	 *
	 * @param string $name    Option name.
	 * @param mixed  $default Option fallback value in case value not set.
	 *
	 * @return mixed|null
	 */
	public static function get_social_option( $name, $default = null ) {
		$social_settings = get_option( 'synved_social_settings' );

		if ( true === empty( $social_settings[ $name ] ) ) {
			return $default;
		}

		return $social_settings[ $name ];
	}

	/**
	 * Show terms notice.
	 *
	 * @param bool $render_html    Whether to render HTML.
	 * @param bool $is_dismissible Whether notice is dismissable.
	 *
	 * @return string|void
	 */
	public static function show_terms_notice( $render_html = true, $is_dismissible = true ) {
		$out = '<div id="sharethis_terms_notice" class="notice-warning notice ' . esc_attr( false === empty( $is_dismissible ) ? 'is-dismissible' : '' ) . '">
        <p>
            To get the most out of Social Media Feather and to help enable its continued development,
            please read the <a href="//sharethis.com/terms" target="_blank">ShareThis Terms of Service</a> and <a href="//sharethis.com/privacy" target="_blank">Privacy Notice</a>,
            then <a href="' . esc_url( admin_url( 'options-general.php?page=social-media-feather-settings&accept-terms=yes' ) ) . '">click here to accept the terms</a>.
        </p>
    </div>
    ';

		if ( $render_html ) {
			echo wp_kses( $out, synved_get_allowed_html_array() );
		} else {
			return wp_kses( $out, synved_get_allowed_html_array() );
		}
	}

	/**
	 * Print Styles.
	 *
	 * @action wp_head
	 */
	public function print_styles() {
		if ( true === is_admin() ) {
			return;
		}

		ob_start();

		// NOTE: Runs through escaping function on the way out!
		// phpcs:ignore
		echo <<<HEAD_STYLE
<style>
.synved-social-resolution-single {
display: inline-block;
}
.synved-social-resolution-normal {
display: inline-block;
}
.synved-social-resolution-hidef {
display: none;
}

@media only screen and (min--moz-device-pixel-ratio: 2),
only screen and (-o-min-device-pixel-ratio: 2/1),
only screen and (-webkit-min-device-pixel-ratio: 2),
only screen and (min-device-pixel-ratio: 2),
only screen and (min-resolution: 2dppx),
only screen and (min-resolution: 192dpi) {
	.synved-social-resolution-normal {
	display: none;
	}
	.synved-social-resolution-hidef {
	display: inline-block;
	}
}
</style>
HEAD_STYLE;

		$styles = ob_get_clean();

		echo wp_kses(
			$styles,
			array(
				'style' => array(),
			)
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_scripts() {
		if ( false === is_admin() && true === self::get_social_option( 'accepted_sharethis_terms', false ) ) {
			if ( true === is_ssl() ) {
				$st_insights = 'https://ws.sharethis.com/button/st_insights.js';
			} else {
				$st_insights = 'http://w.sharethis.com/button/st_insights.js';
			}

			$url = add_query_arg(
				array(
					'publisher' => 'eba0f3ba-f9ab-408c-bc68-c28af5afe749',
					'product'   => 'feather',
				),
				$st_insights
			);

			wp_enqueue_script(
				'feather-sharethis',
				$url,
				null,
				filemtime( SOCIAL_MEDIA_FEATHER_PLUGIN_MAIN_FILE ),
				false
			);

			add_filter( 'script_loader_tag', 'synved_social_wp_script_loader_tag', 10, 2 );
		}
	}
}
