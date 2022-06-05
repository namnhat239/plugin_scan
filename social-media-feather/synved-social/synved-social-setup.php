<?php
/**
 * Setup.
 *
 * @package SocialMediaFeather
 */

/**
 * Social Provider Settings.
 *
 * @return array
 */
function synved_social_provider_settings() {
	$share_providers    = synved_social_service_provider_list( 'share', true );
	$follow_providers   = synved_social_service_provider_list( 'follow', true );
	$provider_list      = array_merge( $share_providers, $follow_providers );
	$providers_settings = array();

	foreach ( $provider_list as $provider_name => $provider_item ) {
		$provider_label  = ucwords( str_replace( array( '-', '_' ), ' ', $provider_name ) );
		$display_set     = 'none=None';
		$display_default = 'none';

		if ( true === isset( $provider_item['label'] ) ) {
			$provider_label = $provider_item['label'];
		}

		if ( true === isset( $share_providers[ $provider_name ] ) ) {
			$display_set .= ',share=Share';

			if ( false === isset( $share_providers[ $provider_name ]['default-display'] ) || $share_providers[ $provider_name ]['default-display'] ) {
				$display_default = 'share';
			}
		}

		if ( true === isset( $follow_providers[ $provider_name ] ) ) {
			$display_set .= ',follow=Follow';

			if ( true === isset( $share_providers[ $provider_name ] ) ) {
				$display_set .= ',both=Share & Follow';
			}

			if ( false === isset( $follow_providers[ $provider_name ]['default-display'] ) || $follow_providers[ $provider_name ]['default-display'] ) {
				if ( 'share' === $display_default ) {
					$display_default = 'both';
				} else {
					$display_default = 'follow';
				}
			}
		}

		$providers_settings = array_merge(
			$providers_settings,
			array(
				$provider_name . '_display' => array(
					'default' => $display_default,
					'style'   => 'group',
					'set'     => $display_set,
					'label'   => sprintf(
					/* translators: %s is the Provider Label. */
						__( '%s Service', 'social-media-feather' ),
						$provider_label
					),
					'tip'     => sprintf(
					/* translators: %s is the Provider Label. */
						__(
							'Decides for what types of services %s will be used by default',
							'social-media-feather'
						),
						$provider_label
					),
				),
			)
		);

		if ( true === isset( $share_providers[ $provider_name ] ) ) {
			$share_item = $share_providers[ $provider_name ];

			$providers_settings = array_merge(
				$providers_settings,
				array(
					$provider_name . '_share_link'  => array(
						'label' => sprintf(
						/* translators: %s is the Provider Label. */
							__( '%s Share Link', 'social-media-feather' ),
							$provider_label
						),
						'tip'   => sprintf(
						/* translators: %s is the Provider Label. */
							__(
								'The link used by default for sharing content on %s. <b>Note: this generally doesn\'t need to be changed, only change it if you know what you\'re doing.</b>',
								'social-media-feather'
							),
							$provider_label
						),
						'hint'  => $share_item['link'],
					),
					$provider_name . '_share_title' => array(
						'label' => sprintf(
						/* translators: %s is the Provider Label. */
							__( '%s Share Title', 'social-media-feather' ),
							$provider_label
						),
						'tip'   => sprintf(
						/* translators: %s is the Provider Label. */
							__(
								'The title used by default for the %s share button (a standard one will be used if left empty)',
								'social-media-feather'
							),
							$provider_label
						),
						'hint'  => $share_item['title'],
					),
				)
			);
		}

		if ( true === isset( $follow_providers[ $provider_name ] ) ) {
			$follow_item = $follow_providers[ $provider_name ];

			$providers_settings = array_merge(
				$providers_settings,
				array(
					$provider_name . '_follow_link'  => array(
						'label' => sprintf(
						/* translators: %s is the Provider Label. */
							__( '%s Follow Link', 'social-media-feather' ),
							$provider_label
						),
						'tip'   => sprintf(
						/* translators: %1$s is the Provider Label. */
							__(
								'The link used by default for following you on %1$s. Change this to point to your own social profile page on %1$s.',
								'social-media-feather'
							),
							$provider_label
						),
						'hint'  => $follow_item['link'],
					),
					$provider_name . '_follow_title' => array(
						'label' => sprintf(
						/* translators: %1$s is the Provider Label. */
							__( '%s Follow Title', 'social-media-feather' ),
							$provider_label
						),
						'tip'   => sprintf(
						/* translators: %1$s is the Provider Label. */
							__(
								'The title used by default for the %s follow button (a standard one will be used if left empty)',
								'social-media-feather'
							),
							$provider_label
						),
						'hint'  => $follow_item['title'],
					),
				)
			);
		}
	}

	return $providers_settings;
}

$terms_accepted = synved_option_get( 'synved_social', 'accepted_sharethis_terms' );

$synved_social_options = array(
	'settings' => array(
		'label'           => 'Social Media',
		'title'           => 'Social Media Feather',
		'tip'             => synved_option_callback( 'synved_social_page_settings_tip' ),
		'link-target'     => plugin_basename( synved_plugout_module_path_get( 'synved-social', 'provider' ) ),
		'render-fragment' => 'synved_social_page_render_fragment',
		'sections'        => array(
			'section_general'           => array(
				'label'    => __( 'General Settings', 'social-media-feather' ),
				'tip'      => __( 'Settings affecting the general behaviour of the plugin', 'social-media-feather' ),
				'settings' => array(
					'use_shortlinks'           => array(
						'default' => false,
						'label'   => __( 'Use Shortlinks', 'social-media-feather' ),
						'tip'     => __(
							'Allows for shortened URLs to be used when sharing content if a shortening plugin is installed',
							'social-media-feather'
						),
					),
					'share_full_url'           => array(
						'default' => false,
						'label'   => __( 'Share Full URL', 'social-media-feather' ),
						'tip'     => __(
							'Determines whether to always share the full URL or just the post permalink. You can override this for individual posts by setting the "synved_social_share_full_url" custom field to either "yes" or "no", case sensitive',
							'social-media-feather'
						),
					),
					'layout_rtl'               => array(
						'default' => false,
						'label'   => __( 'Right To Left Layout', 'social-media-feather' ),
						'tip'     => __(
							'Check this option if you have a right-to-left site layout and icons show spacing issues',
							'social-media-feather'
						),
					),
					'shortcode_widgets'        => array(
						'default' => true,
						'label'   => __( 'Shortcodes In Widgets', 'social-media-feather' ),
						'tip'     => __( 'Allow shortcodes in Text widgets', 'social-media-feather' ),
					),
					'show_credit'              => array(
						'default' => false,
						'label'   => __( 'Show Credit', 'social-media-feather' ),
						'tip'     => __(
							'Display a small icon with a link to the Social Media Feather page',
							'social-media-feather'
						),
					),
					'share_message_default'    => array(
						'default' => __( 'Hey check this out', 'social-media-feather' ),
						'label'   => __( 'Default Message', 'social-media-feather' ),
						'tip'     => __(
							'Specify the default message to use when sharing content, this is what gets replaced into the %%message%% variable',
							'social-media-feather'
						),
					),
					'fb_app_id'                => array(
						'default' => '',
						'label'   => __( 'Facebook App ID', 'social-media-feather' ),
					),
					'accepted_sharethis_terms' => array(
						'type'   => 'custom',
						'label'  => __( 'Terms of Service', 'social-media-feather' ),
						'tip'    => __(
							'Accept or decline <a target="_blank" href="https://www.sharethis.com/terms/">ShareThis Terms of Service</a> and <a target="_blank" href="https://www.sharethis.com/privacy/">Privacy Notice</a>',
							'social-media-feather'
						),
						'render' => 'synved_social_accept_terms_switch',
					),
				),
			),

			'section_automatic_display' => array(
				'label'    => __( 'Automatic Display', 'social-media-feather' ),
				'tip'      => __(
					'Settings affecting automating appending of social buttons to post contents',
					'social-media-feather'
				),
				'settings' => array(
					'automatic_share'               => array(
						'default' => false,
						'label'   => __( 'Display Sharing Buttons', 'social-media-feather' ),
						'tip'     => __(
							'Tries to automatically append sharing buttons to your posts (disable for specific posts by setting custom field synved_social_exclude or synved_social_exclude_share to "yes", case sensitive)',
							'social-media-feather'
						),
					),
					'automatic_share_position'      => array(
						'default' => 'after_post',
						'set'     => 'after_post=After Post,before_post=Before Post,after_before_post=After and Before Post',
						'label'   => __( 'Share Buttons Position', 'social-media-feather' ),
						'tip'     => __(
							'Select where the sharing buttons should be placed. Note: placing buttons Before Post might not work in all themes.',
							'social-media-feather'
						),
					),
					'automatic_share_single'        => array(
						'default' => false,
						'label'   => __( 'Sharing Single Posts', 'social-media-feather' ),
						'tip'     => __(
							'Sharing buttons are only displayed on single posts/pages and not on archive pages like blog/category/tag/author pages',
							'social-media-feather'
						),
					),
					'automatic_share_post_types'    => array(
						'type'    => 'custom',
						'default' => 'post',
						'set'     => synved_option_callback(
							'synved_social_automatic_append_post_types_set',
							array( 'post', 'page' )
						),
						'label'   => __( 'Share Post Types', 'social-media-feather' ),
						'tip'     => __(
							'Post types for which automatic appending for share buttons should be attempted (CTRL + click to select multiple ones)',
							'social-media-feather'
						),
						'render'  => 'synved_social_automatic_append_post_types_render',
					),
					'automatic_share_prefix'        => array(
						'default' => '',
						'label'   => __( 'Share Prefix Markup', 'social-media-feather' ),
						'tip'     => __(
							'When automatically appending, place this markup before the share buttons markup',
							'social-media-feather'
						),
					),
					'automatic_share_postfix'       => array(
						'default' => '',
						'label'   => __( 'Share Postfix Markup', 'social-media-feather' ),
						'tip'     => __(
							'When automatically appending, place this markup after all of the share buttons markup',
							'social-media-feather'
						),
					),
					'automatic_follow'              => array(
						'default' => true,
						'label'   => __( 'Display Follow Buttons', 'social-media-feather' ),
						'tip'     => __(
							'Tries to automatically append follow buttons to your posts (disable for specific posts by setting custom field synved_social_exclude or synved_social_exclude_follow to "yes", case sensitive)',
							'social-media-feather'
						),
					),
					'automatic_follow_position'     => array(
						'default' => 'after_post',
						'set'     => 'after_post=After Post,before_post=Before Post,after_before_post=After and Before Post',
						'label'   => __( 'Follow Buttons Position', 'social-media-feather' ),
						'tip'     => __(
							'Select where the follow buttons should be placed. Note: placing buttons Before Post might not work in all themes.',
							'social-media-feather'
						),
					),
					'automatic_follow_single'       => array(
						'default' => false,
						'label'   => __( 'Follow Single Posts', 'social-media-feather' ),
						'tip'     => __(
							'Follow buttons are only displayed on single posts/pages and not on archive pages like blog/category/tag/author pages',
							'social-media-feather'
						),
					),
					'automatic_follow_post_types'   => array(
						'type'    => 'custom',
						'default' => 'post',
						'set'     => synved_option_callback(
							'synved_social_automatic_append_post_types_set',
							array( 'post', 'page' )
						),
						'label'   => __( 'Follow Post Types', 'social-media-feather' ),
						'tip'     => __(
							'Post types for which automatic appending for follow buttons should be attempted (CTRL + click to select multiple ones)',
							'social-media-feather'
						),
						'render'  => 'synved_social_automatic_append_post_types_render',
					),
					'automatic_follow_before_share' => array(
						'default' => false,
						'label'   => __( 'Follow Before Share', 'social-media-feather' ),
						'tip'     => __(
							'When automatically appending, place follow buttons before share buttons. Only valid when share and follow buttons positions are the same.',
							'social-media-feather'
						),
					),
					'automatic_follow_prefix'       => array(
						'default' => '',
						'label'   => __( 'Follow Prefix Markup', 'social-media-feather' ),
						'tip'     => __(
							'When automatically appending, place this markup before the follow buttons markup',
							'social-media-feather'
						),
					),
					'automatic_follow_postfix'      => array(
						'default' => '',
						'label'   => __( 'Follow Postfix Markup', 'social-media-feather' ),
						'tip'     => __(
							'When automatically appending, place this markup after all of the follow buttons markup',
							'social-media-feather'
						),
					),
					'automatic_append_prefix'       => array(
						'default' => '',
						'label'   => __( 'Prefix Markup', 'social-media-feather' ),
						'tip'     => __(
							'When automatically appending, place this markup before the buttons markup',
							'social-media-feather'
						),
					),
					'automatic_append_separator'    => array(
						'default' => '<br/>',
						'label'   => __( 'Separator Markup', 'social-media-feather' ),
						'tip'     => __(
							'When automatically appending both, use this markup as separator between the set of share buttons and the set of follow buttons. Only valid when share and follow buttons positions are the same.',
							'social-media-feather'
						),
					),
					'automatic_append_postfix'      => array(
						'default' => '',
						'label'   => __( 'Postfix Markup', 'social-media-feather' ),
						'tip'     => __(
							'When automatically appending, place this markup after all of the buttons markup',
							'social-media-feather'
						),
					),
				),
			),
			'section_customize_look'    => array(
				'label'    => __( 'Customize Look', 'social-media-feather' ),
				'tip'      => synved_option_callback(
					'synved_social_section_customize_look_tip',
					__( 'Customize the look & feel of Social Media Feather', 'social-media-feather' )
				),
				'settings' => array(
					'icon_skin'                => array(
						'default' => 'regular',
						'set'     => synved_option_callback( 'synved_social_cb_icon_skin_set', 'regular=Regular' ),
						'label'   => __( 'Icon Skin', 'social-media-feather' ),
						'tip'     => '',
						'render'  => 'synved_social_icon_skin_render',
					),
					'icon_size'                => array(
						'default' => 48,
						'set'     => '16=16x16,24=24x24,32=32x32,48=48x48,64=64x64,96=96x96',
						'label'   => __( 'Icon Size', 'social-media-feather' ),
						'tip'     => __(
							'Select the size in pixels for the icons. Note: for high resolution displays like Retina the maximum size is 64x64.',
							'social-media-feather'
						),
					),
					'icon_resolution'          => array(
						'default' => 'single',
						'set'     => 'single=Single,double=Double',
						'label'   => __( 'Icon Resolution', 'social-media-feather' ),
						'tip'     => __(
							'Select what icon resolutions will be used. Single might make the icons slightly blurry on low resolution displays. Double will always look the best but will consume more bandwidth.',
							'social-media-feather'
						),
					),
					'icon_spacing'             => array(
						'default' => 5,
						'label'   => __( 'Icon Spacing', 'social-media-feather' ),
						'tip'     => __( 'Select the spacing in pixels between the icons', 'social-media-feather' ),
					),
					'buttons_container'        => array(
						'default' => 'none',
						'set'     => 'none=None,share=Sharing Buttons,follow=Following Buttons,both=Both',
						'label'   => __( 'Buttons in Container', 'social-media-feather' ),
						'tip'     => __(
							'Determines whether or not to wrap the buttons in a container, which will affect how the buttons are rendered, based on the "Buttons Container Type" setting.',
							'social-media-feather'
						),
					),
					'buttons_container_type'   => array(
						'default' => 'basic',
						'set'     => 'basic=Basic,block=Block',
						'label'   => __( 'Buttons Container Type', 'social-media-feather' ),
						'tip'     => __(
							'"Basic" should not affect rendering, while "Block" should display the buttons in their own row. <b>Note</b>: selecting "Block" might not look the way you want if you\'re using Prefix or Postfix markup.',
							'social-media-feather'
						),
					),
					'buttons_alignment_share'  => array(
						'default' => 'none',
						'set'     => 'none=Theme Default,left=Align Left,right=Align Right,center=Align Center',
						'label'   => __( 'Share Buttons Alignment', 'social-media-feather' ),
						'tip'     => __(
							'Will attempt at aligning the share buttons accordingly. <strong>Note:</strong> this will enforce "Buttons Container Type" of "Block" and might not work reliably on all themes',
							'social-media-feather'
						),
					),
					'buttons_alignment_follow' => array(
						'default' => 'none',
						'set'     => 'none=Theme Default,left=Align Left,right=Align Right,center=Align Center',
						'label'   => __( 'Follow Buttons Alignment', 'social-media-feather' ),
						'tip'     => __(
							'Will attempt at aligning the follow buttons accordingly. <strong>Note:</strong> this will enforce "Buttons Container Type" of "Block" and might not work reliably on all themes',
							'social-media-feather'
						),
					),
					'custom_style'             => array(
						'type'  => 'style',
						'label' => __( 'Extra Styles', 'social-media-feather' ),
						'tip'   => __(
							'Any CSS styling code you type in here will be loaded after all of the Social Media Feather styles.',
							'social-media-feather'
						),
					),
				),
			),
			'section_service_providers' => array(
				'label'    => __( 'Service Providers', 'social-media-feather' ),
				'tip'      => __( 'Customize social sharing and following providers', 'social-media-feather' ),
				'settings' => synved_social_provider_settings(),
			),
		),
	),
);

synved_option_register( 'synved_social', $synved_social_options );

synved_option_include_module_addon_list( 'synved-social' );

/**
 * Accept terms switch.
 *
 * @return string
 */
function synved_social_accept_terms_switch() {
	$out = '<label class="synved-switch">
			<input id="synved-disable" name="synved_social_settings[accepted_sharethis_terms]"
				type="checkbox" value="' . esc_attr( true === synved_social_are_terms_accepted() ? 1 : 0 ) . '">
			<div id="synved-slider" class="synved-slider round"></div>
		</label>';

	return wp_kses( $out, synved_get_allowed_html_array() );
}

/**
 * Synved Social Page Settings tip.
 *
 * @param string $tip  Tip string.
 * @param array  $item Item array.
 *
 * @return string
 */
function synved_social_page_settings_tip( $tip, $item ) {
	$tip = '';

	$request_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING );

	if ( preg_match( '/page=social-media-feather-settings/i', $request_uri ) ) {
		$accept_terms = filter_input( INPUT_GET, 'accept-terms', FILTER_SANITIZE_STRING );

		// If terms have just been accepted.
		if ( 'yes' === $accept_terms ) {
			$tip .= '<div class="alert alert-success text-center"><p>Thank you, the update has been enabled!</p></div>';
		} elseif ( false === synved_option_get( 'synved_social', 'accepted_sharethis_terms', false ) ) {
			$tip .= synved_social_sharethis_terms_notice( false, false );
		}

		if ( true === function_exists( 'synved_connect_support_social_follow_render' ) ) {
			$tip .= synved_connect_support_social_follow_render();
		}
	}

	return $tip;
}

/**
 * Render fragment.
 *
 * @param string $fragment Fragment string.
 * @param string $out      Out string.
 * @param array  $params   Parameter array.
 *
 * @return mixed|string
 */
function synved_social_page_render_fragment( $fragment, $out, $params ) {
	if ( 'page-submit-tail' === $fragment ) {
		$out .= '<div style="clear:both; margin-top: -12px;"><a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/social-media-feather?rate=5#postform" style="font-size:120%"><b>We need your help!</b> If you like the plugin, you can help us by leaving a 5-stars review! It only takes a minute and it\'s free!</a></div>';
		$out .= '<p>By using this plugin you are agreeing to the <a href="https://sharethis.com/terms/" target="_blank">Terms of service</a> and <a href="https://sharethis.com/privacy/" target="_blank">Privacy Policy</a>.</p>';
	}

	$out .= '<div id="social-media-feather-app"></div>';

	return wp_kses( $out, synved_get_allowed_html_array() );
}

/**
 * Customize look tip.
 *
 * @param string $tip  Tip string.
 * @param array  $item Item array.
 *
 * @return mixed
 */
function synved_social_section_customize_look_tip( $tip, $item ) {
	return wp_kses( $tip, synved_get_allowed_html_array() );
}

/**
 * Icon skin set callback.
 *
 * @param array|null $set  Set array.
 * @param array      $item Item array.
 *
 * @return array
 */
function synved_social_cb_icon_skin_set( $set, $item ) {
	if ( false === empty( $set ) && false === is_array( $set ) ) {
		$set = synved_option_item_set_parse( $item, $set );
	}

	$set   = array();
	$icons = synved_social_icon_skin_list();

	foreach ( $icons as $icon_name => $icon_meta ) {
		$set[][ $icon_name ] = $icon_meta['label'];
	}

	return $set;
}

/**
 * Icon skin render.
 *
 * @param string $value  Value string.
 * @param array  $params Parameter array.
 * @param string $id     ID string.
 * @param string $name   Name string.
 * @param array  $item   Item array.
 *
 * @return string
 */
function synved_social_icon_skin_render( $value, $params, $id, $name, $item ) {
	$uri   = synved_social_path_uri();
	$icons = synved_social_icon_skin_list();

	$out      = null;
	$out_name = $params['output_name'];
	$set      = $params['set'];

	$out .= '<div>';

	$sharethis_terms_agree = synved_option_get( 'synved_social', 'accepted_sharethis_terms' );

	foreach ( $set as $set_it ) {
		$set_it_keys = array_keys( $set_it );
		$img_src     = '';

		if ( isset( $icons[ $set_it_keys[0] ] ) ) {
			$img_src = $icons[ $set_it_keys[0] ]['image'];
		}

		$disabled = disabled(
			false === $sharethis_terms_agree
			&& false === empty( $icons[ $set_it_keys[0] ]['extra'] ),
			true,
			false
		);

		$out .= '<div style="text-align:center; width:260px; float:left; margin-right:20px; margin-bottom: 15px;">
					<label title="Use skin=&quot;'
				. esc_attr( $set_it_keys[0] )
				. '&quot; in shortcodes"><img src="'
				. esc_url( $img_src )
				. '" style="border:solid 1px #bbb" /><p><input type="radio" name="'
				. esc_attr( $out_name )
				. '" value="'
				. esc_attr( $set_it_keys[0] )
				. '"'
				. checked( $set_it_keys[0] === $value, true, false )
				. ' '
				. $disabled . '/> '
				. esc_html( $set_it[ $set_it_keys[0] ] )
				. '</p></label></div>';
	}

	$out .= '</div>';

	return wp_kses( $out, synved_get_allowed_html_array() );
}

/**
 * Automatic append post types set.
 *
 * @param array $set  Set array.
 * @param array $item Item array.
 *
 * @return array
 */
function synved_social_automatic_append_post_types_set( $set, $item ) {
	if ( false === empty( $set ) && false === is_array( $set ) ) {
		$set = synved_option_item_set_parse( $item, $set );
	}

	$set   = array();
	$types = get_post_types( array( 'public' => true ) );

	foreach ( $types as $type_name ) {
		$set[][ $type_name ] = $type_name;
	}

	return $set;
}

/**
 * Automatic append post types render.
 *
 * @param array  $value  Value array.
 * @param array  $params Parameter array.
 * @param string $id     ID string.
 * @param string $name   Name string.
 * @param array  $item   Item array.
 *
 * @return string
 */
function synved_social_automatic_append_post_types_render( $value, $params, $id, $name, $item ) {
	$uri   = synved_social_path_uri();
	$icons = synved_social_icon_skin_list();

	if ( false === is_array( $value ) ) {
		if ( false === empty( $value ) ) {
			$value = array( $value );
		} else {
			$value = array();
		}
	}

	$out      = null;
	$out_name = $params['output_name'];
	$set      = $params['set'];

	$out .= '<select multiple="multiple" name="' . esc_attr( $out_name . '[]' ) . '">';

	foreach ( $set as $set_it ) {
		$set_it_keys = array_keys( $set_it );

		$out .= '<option value="'
				. esc_attr( $set_it_keys[0] )
				. '"'
				. selected( in_array( $set_it_keys[0], $value, true ), true, false )
				. '>'
				. esc_html( $set_it[ $set_it_keys[0] ] )
				. '</option>';
	}

	$out .= '</select>';

	return wp_kses( $out, synved_get_allowed_html_array() );
}

/**
 * Social path.
 *
 * @param string|null $path Path string.
 *
 * @return array|string|string[]
 */
function synved_social_path( $path = null ) {
	$root = dirname( __FILE__ );

	if ( false === empty( $root ) ) {
		if ( '/' !== substr( $root, - 1 ) && true === isset( $path, $path[0] ) && '/' !== $path[0] ) {
			$root .= '/';
		}

		$root .= $path;
	}

	$root = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, $root );

	return $root;
}

/**
 * Path URI.
 *
 * @param string|null $path Path string.
 *
 * @return string
 */
function synved_social_path_uri( $path = null ) {
	$uri = plugins_url( '/social-media-feather' ) . '/synved-social';

	if ( function_exists( 'synved_plugout_module_uri_get' ) ) {
		$mod_uri = synved_plugout_module_uri_get( 'synved-social' );

		if ( false === empty( $mod_uri ) ) {
			$uri = $mod_uri;
		}
	}

	if ( false === empty( $path ) ) {
		if ( '/' !== substr( $uri, - 1 ) && '/' !== $path[0] ) {
			$uri .= '/';
		}

		$uri .= $path;
	}

	return $uri;
}

/**
 * WP Script Loader Tag.
 *
 * @param string $tag    Tag string.
 * @param string $handle Handle string.
 *
 * @return array|mixed|string|string[]
 */
function synved_social_wp_script_loader_tag( $tag, $handle ) {
	if ( 'feather-sharethis' === $handle ) {
		return str_replace( '<script', '<script id=\'st_insights_js\'', $tag );
	}

	return $tag;
}

/**
 * Are terms accepted?
 *
 * @return bool
 */
function synved_social_are_terms_accepted() {
	return synved_option_get( 'synved_social', 'accepted_sharethis_terms', false );
}

/**
 * Admin enqueue scripts for Social.
 *
 * @return void
 */
function synved_social_admin_enqueue_scripts() {
	$uri = synved_social_path_uri();
	$dir = plugin_dir_path( __FILE__ );

	wp_enqueue_script(
		'synved-option-script-custom',
		$uri . '/script/custom.js',
		array(
			'jquery',
		),
		filemtime( $dir . '/script/custom.js' ),
		false
	);
}

/**
 * Register widgets.
 *
 * @return void
 */
function synved_social_register_widgets() {
	register_widget( 'SynvedSocialShareWidget' );
	register_widget( 'SynvedSocialFollowWidget' );
}

/**
 * WP: the_content.
 *
 * @param string      $content Post content string.
 * @param string|null $id      ID string.
 *
 * @return mixed|string
 */
function synved_social_wp_the_content( $content, $id = null ) {
	$exclude        = false;
	$exclude_share  = false;
	$exclude_follow = false;

	$extra_after      = null;
	$extra_before     = null;
	$separator_after  = null;
	$separator_before = null;

	if ( true === empty( $id ) ) {
		$id = get_the_ID();

		if ( true === empty( $id ) ) {
			global $post;

			$id = $post->ID;
		}
	}

	if ( false === empty( $id ) ) {
		$automatic_share_single = boolval( synved_option_get( 'synved_social', 'automatic_share_single', false ) );

		$exclude        = 'yes' === get_post_meta( $id, 'synved_social_exclude', true );
		$exclude_share  = 'yes' === get_post_meta( $id, 'synved_social_exclude_share', true );
		$exclude_follow = 'yes' === get_post_meta( $id, 'synved_social_exclude_follow', true );

		if ( false === $exclude_share && false !== $automatic_share_single ) {
			$exclude_share = false === (
					true === is_singular( synved_option_get( 'synved_social', 'automatic_share_post_types' ) )
					&& (
						true === is_single( $id ) || true === is_page( $id )
					)
				);
		}

		if ( false === $exclude_follow &&
			false !== boolval( synved_option_get( 'synved_social', 'automatic_follow_single', false ) )
		) {
			$exclude_follow = false === (
					true === is_singular( synved_option_get( 'synved_social', 'automatic_follow_post_types' ) )
					&& (
						true === is_single( $id ) || true === is_page( $id )
					)
				);
		}
	}

	if ( false === $exclude ) {
		if ( false === $exclude_share
			&& false !== boolval( synved_option_get( 'synved_social', 'automatic_share', false ) )
		) {
			$post_type = get_post_type();
			$type_list = synved_option_get( 'synved_social', 'automatic_share_post_types' );

			if ( true === is_string( $type_list ) ) {
				$type_list = array( $type_list );
			}

			if ( true === in_array( $post_type, $type_list, true ) ) {
				$position        = synved_option_get( 'synved_social', 'automatic_share_position' );
				$position_before = true === in_array( $position, array( 'before_post', 'after_before_post' ), true );
				$position_after  = true === in_array( $position, array( 'after_post', 'after_before_post' ), true );
				$prefix          = synved_option_get( 'synved_social', 'automatic_share_prefix' );
				$postfix         = synved_option_get( 'synved_social', 'automatic_share_postfix' );

				if ( $position_after ) {
					$markup = synved_social_share_markup();

					if ( false === empty( trim( $markup ) ) ) {
						$markup = $prefix . $markup . $postfix;

						$extra_after .= $markup;
					}
				}

				if ( true === $position_before ) {
					$markup = synved_social_share_markup();

					if ( false === empty( trim( $markup ) ) ) {
						$markup = $prefix . $markup . $postfix;

						$extra_before .= $markup;
					}
				}
			}
		}

		$separator = synved_option_get( 'synved_social', 'automatic_append_separator' );

		if ( false === empty( $extra_after ) ) {
			$separator_after = $separator;
		}

		if ( false === empty( $extra_before ) ) {
			$separator_before = $separator;
		}

		$synved_social_settings = get_option( 'synved_social_settings' );

		$automatic_follow = boolval( $synved_social_settings['automatic_follow'] );

		if ( false === $exclude_follow && false !== $automatic_follow ) {
			$post_type = get_post_type();
			$type_list = synved_option_get( 'synved_social', 'automatic_follow_post_types', array() );

			if ( false === is_array( $type_list ) ) {
				$type_list = array();
			}

			if ( true === in_array( $post_type, $type_list, true ) ) {
				$position        = synved_option_get( 'synved_social', 'automatic_follow_position' );
				$position_before = true === in_array( $position, array( 'before_post', 'after_before_post' ), true );
				$position_after  = true === in_array( $position, array( 'after_post', 'after_before_post' ), true );
				$prefix          = synved_option_get( 'synved_social', 'automatic_follow_prefix' );
				$postfix         = synved_option_get( 'synved_social', 'automatic_follow_postfix' );

				if ( true === $position_after ) {
					$markup = synved_social_follow_markup();

					if ( false === empty( trim( $markup ) ) ) {
						$markup = $prefix . $markup . $postfix;

						if ( false !== synved_option_get( 'synved_social', 'automatic_follow_before_share', false ) ) {
							$extra_after = $markup . $separator_after . $extra_after;
						} else {
							$extra_after .= $separator_after . $markup;
						}
					}
				}

				if ( $position_before ) {
					$markup = synved_social_follow_markup();

					if ( false === empty( trim( $markup ) ) ) {
						$markup = $prefix . $markup . $postfix;

						if ( false !== synved_option_get( 'synved_social', 'automatic_follow_before_share', false ) ) {
							$extra_before = $markup . $separator_before . $extra_before;
						} else {
							$extra_before .= $separator_before . $markup;
						}
					}
				}
			}
		}

		$prefix  = synved_option_get( 'synved_social', 'automatic_append_prefix' );
		$postfix = synved_option_get( 'synved_social', 'automatic_append_postfix' );

		if ( false === empty( $extra_after ) ) {
			$content .= $prefix . $extra_after . $postfix;
		}

		if ( false === empty( $extra_before ) ) {
			$content = $prefix . $extra_before . $postfix . $content;
		}
	}

	return $content;
}

/**
 * Renders ShareThis Terms of Service alert.
 *
 * @param bool $render_html    Render HTML.
 * @param bool $is_dismissible Whether the notice is dismissable.
 *
 * @return string
 */
function synved_social_sharethis_terms_notice( $render_html = true, $is_dismissible = true ) {
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
 * Wrapper to be able to call this method within parameters by add_action
 */
function synved_social_sharethis_terms_notice_callback_admin_notice_wrapper() {
	synved_social_sharethis_terms_notice( true, true );
}

/**
 * Hide the terms agreement at user's request?
 *
 * @return void
 */
function synved_social_admin_hide_callback() {
	synved_option_set( 'synved_social', 'hide_sharethis_terms', true );
}

/**
 * Init.
 *
 * @return void
 */
function synved_social_init() {
	$priority = defined( 'SHORTCODE_PRIORITY' ) ? SHORTCODE_PRIORITY : 11;

	if ( true === boolval( synved_option_get( 'synved_social', 'shortcode_widgets' ) ) ) {
		remove_filter( 'widget_text', 'do_shortcode', $priority );
		add_filter( 'widget_text', 'do_shortcode', $priority );
	}

	if ( true === function_exists( 'synved_shortcode_add' ) ) {
		synved_shortcode_add( 'feather_share', 'synved_social_share_shortcode' );
		synved_shortcode_add( 'feather_follow', 'synved_social_follow_shortcode' );

		$size_set  = '16,24,32,48,64,96';
		$size_item = synved_option_item( 'synved_social', 'icon_size' );

		if ( false === empty( $size_item ) ) {
			$item_set = synved_option_item_set( $size_item );

			if ( false === empty( $item_set ) ) {
				$set_items = array();

				foreach ( $item_set as $set_item ) {
					$item_keys = array_keys( $set_item );

					$set_items[] = $item_keys[0];
				}

				$size_set = implode( ',', $set_items );
			}
		}

		$providers_share  = array_keys( synved_social_service_provider_list( 'share' ) );
		$providers_follow = array_keys( synved_social_service_provider_list( 'follow' ) );

		$providers_params = array(
			/* translators: %1$s is the provider. %2$s are the possible values. */
			'show' => __(
				'Specify a comma-separated list of %1$s providers to show and their order, possible values are %2$s',
				'social-media-feather'
			),
			/* translators: %1$s is the provider. %2$s are the possible values. */
			'hide' => __(
				'Specify a comma-separated list of %1$s providers to hide, possible values are %2$s',
				'social-media-feather'
			),
		);

		$common_params = array(
			'skin'           => __( 'Specify which skin to use for the icons', 'social-media-feather' ),
			'size'           => sprintf(
				/* translators: %s are the possible values of icons. */
				__(
					'Specify the size for the icons, possible values are %s',
					'social-media-feather'
				),
				$size_set
			),
			'spacing'        => __(
				'Determines how much blank space there will be between the buttons, in pixels',
				'social-media-feather'
			),
			'container'      => __( 'Determines whether to wrap the buttons in a container', 'social-media-feather' ),
			'container_type' => sprintf(
				/* translators: %s are the possible values of container types. */
				__(
					'Determines what type of container to use, possible values are %1$s',
					'social-media-feather'
				),
				'basic, block'
			),
			'class'          => __(
				'Select additional CSS classes for the buttons, separated by spaces',
				'social-media-feather'
			),
		);

		$share_params = array(
			'url'     => __(
				'URL to use for the sharing buttons, default is the current post URL',
				'social-media-feather'
			),
			'title'   => __(
				'Title to use for the sharing buttons, default is the current post title',
				'social-media-feather'
			),
			'message' => __(
				'Message to use when sharing content, replaced into the %%message%% variable',
				'social-media-feather'
			),
		);

		$follow_params = array();

		$share_params  = array_merge( $common_params, $share_params );
		$follow_params = array_merge( $common_params, $follow_params );

		foreach ( $providers_params as $param_name => $param_value ) {
			$share_params[ $param_name ]  = sprintf( $param_value, 'share', implode( ', ', $providers_share ) );
			$follow_params[ $param_name ] = sprintf( $param_value, 'follow', implode( ', ', $providers_follow ) );
		}

		if ( true === function_exists( 'synved_shortcode_item_help_set' ) ) {
			synved_shortcode_item_help_set(
				'feather_share',
				array(
					'tip'        => __(
						'Creates a list of buttons for social sharing as selected in the Social Media options',
						'social-media-feather'
					),
					'parameters' => $share_params,
				)
			);

			synved_shortcode_item_help_set(
				'feather_follow',
				array(
					'tip'        => __(
						'Creates a list of buttons for social following as selected in the Social Media options',
						'social-media-feather'
					),
					'parameters' => $follow_params,
				)
			);
		}
	} else {
		add_shortcode( 'feather_share', 'synved_social_share_shortcode' );
		add_shortcode( 'synved_feather_share', 'synved_social_share_shortcode' );
		add_shortcode( 'feather_follow', 'synved_social_follow_shortcode' );
		add_shortcode( 'synved_feather_follow', 'synved_social_follow_shortcode' );
	}

	$automatic_share  = boolval( synved_option_get( 'synved_social', 'automatic_share', false ) );
	$automatic_follow = boolval( synved_option_get( 'synved_social', 'automatic_follow', false ) );

	if ( false !== $automatic_share || false !== $automatic_follow ) {
		add_filter( 'the_content', 'synved_social_wp_the_content', 10, 2 );
	}

	if ( true === is_admin() ) {
		if ( true === current_user_can( 'manage_options' ) ) {
			$accept_terms = filter_input( INPUT_GET, 'accept-terms', FILTER_SANITIZE_STRING );

			if ( false === empty( $accept_terms ) ) {
				synved_option_set( 'synved_social', 'accepted_sharethis_terms', ( 'yes' === $accept_terms ) );
				wp_safe_redirect( admin_url( 'options-general.php?page=social-media-feather-settings' ) );
				exit;
			}
		}

		$request_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING );

		$can_show_share_this_alert =
			false !== synved_option_get( 'synved_social', 'accepted_sharethis_terms', false )
			&& false !== synved_option_get( 'synved_social', 'hide_sharethis_terms', false )
			&& false === preg_match( '/page=social-media-feather-settings/i', $request_uri );

		if ( true === $can_show_share_this_alert ) {
			add_action( 'admin_notices', 'synved_social_sharethis_terms_notice_callback_admin_notice_wrapper' );
			add_action( 'wp_ajax_feather_hide_terms', 'synved_social_admin_hide_callback' );
		}
	}
}

add_action( 'init', 'synved_social_init' );

add_action( 'widgets_init', 'synved_social_register_widgets' );

if ( true === is_admin() ) {
	add_action( 'admin_enqueue_scripts', 'synved_social_admin_enqueue_scripts' );
}
