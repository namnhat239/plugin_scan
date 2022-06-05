<?php
/**
 * Additional functionalities for block themes.
 *
 * @package twentig
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue styles for block themes: spacing, layout, fonts.
 */
function twentig_block_theme_enqueue_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Match styles from wp_get_layout_style().
	$block_gap             = wp_get_global_settings( array( 'spacing', 'blockGap' ) );
	$has_block_gap_support = isset( $block_gap ) ? null !== $block_gap : false;

	$layout_styles = '
	.layout-default > :where(:not(.alignleft):not(.alignright)) { max-width: var(--layout--content-size); margin-left: auto !important; margin-right: auto !important; }		
	.layout-default > .alignwide { max-width: var(--layout--wide-size); }
	.layout-default .alignfull { max-width: none; }
	.layout-default .alignleft { float: left; margin-inline-start: 0; margin-inline-end: 2em; }
	.layout-default .alignright { float: right; margin-inline-start: 2em; margin-inline-end: 0; }
	.layout-default .aligncenter { margin-left: auto !important; margin-right: auto !important; }';

	if ( $has_block_gap_support ) {
		$layout_styles .= '
		:where(.layout-default):not(.has-gap) > * { margin-block-start: 0; margin-block-end: 0; }
		:where(.layout-default):not(.has-gap) > * + * {	margin-block-start: var( --wp--style--block-gap ); margin-block-end: 0; }';
	}
	
	$default_layout = wp_get_global_settings( array( 'layout' ) );	
	$content_width  = get_option( 'twentig_content_size' );
	$wide_width     = get_option( 'twentig_wide_size' );
	$content_width  = $content_width ? $content_width : $default_layout['contentSize'];		
	$wide_width     = $wide_width ? $wide_width : $default_layout['wideSize'];
			
	$layout_styles .= 'body {';		
	if ( $content_width ) {
		$layout_styles .= '--layout--content-size:' . esc_html( $content_width ) . ';';
	}
	if ( $wide_width ) {
		$layout_styles .= '--layout--wide-size:' . esc_html( $wide_width ) . ';';
	}
	$layout_styles .= '}';
	$layout_styles = preg_replace( '/[\r\n\t ]+/', ' ', $layout_styles );
		
	wp_add_inline_style( 'global-styles', $layout_styles );

	if ( get_option( 'twentig_global_spacing', twentig_default_global_spacing() ) ) {
		wp_enqueue_style(
			'twentig-global-spacing',
			TWENTIG_ASSETS_URI . "/blocks/tw-spacing{$min}.css",
			array(),
			TWENTIG_VERSION
		);
	}

	global $template_html;

	$fonts            = twentig_get_additional_fonts();	
	$stylesheet       = wp_get_global_stylesheet();	
	$heading_font     = get_option( 'twentig_heading_font' );
	$enqueue_fonts    = [];
	$font_vars        = '';
	$font_classes     = '';	
	$content_to_check = $stylesheet . $template_html;

	if ( $heading_font ) {
		$heading_style = 'h1,h2,h3,h4,h5,h6{font-family:' . $heading_font . ';}';
		wp_add_inline_style( 'global-styles', $heading_style );
	}

	foreach( $fonts as $font ) {	
		$family_slug = sanitize_title( $font['slug'] );
		$family      = $font['fontFamily'];			
		if ( false !== strpos( $content_to_check, 'var(--wp--preset--font-family--' . $family_slug . ')' ) ||
			 false !== strpos( $content_to_check, 'has-' . $family_slug . '-font-family' ) ||
			 false !== strpos( $heading_font, $family ) ) {
				$enqueue_fonts[] = $font['src'];						
				$font_vars      .= "--wp--preset--font-family--{$family_slug}:{$family};";
				$font_classes   .= ".has-{$family_slug}-font-family{font-family:var(--wp--preset--font-family--{$family_slug})!important;}";	
		}
	}

	if ( ! empty( $enqueue_fonts ) ) {
		wp_enqueue_style( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			'twentig-theme-fonts',
			esc_url_raw( 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', array_unique( array_values( $enqueue_fonts ) ) ) . '&display=swap' ),
			array(),
			null
		);

		wp_add_inline_style( 'global-styles', 'body{' . $font_vars . '}' . $font_classes );
	}

	if ( false !== strpos( $template_html, 'tw-bottom-shape' ) || false !== strpos( $template_html, 'tw-top-shape' ) ) {
		$styles_file = TWENTIG_PATH . "dist/blocks/group/shape.min.css";
		$styles = file_get_contents( $styles_file );
		wp_add_inline_style( "wp-block-group", $styles );
	}
}
add_action( 'wp_enqueue_scripts', 'twentig_block_theme_enqueue_scripts', 11 );

/**
 * Enqueue styles inside the editor.
 */
function twentig_block_theme_editor_styles() {
	
	if ( get_option( 'twentig_global_spacing', twentig_default_global_spacing() ) ) {
		if ( ! is_wp_version_compatible( '6.0' ) ) {
			add_editor_style( TWENTIG_ASSETS_URI . '/blocks/tw-spacing-editor-5-9.css' );
		} else {
			add_editor_style( TWENTIG_ASSETS_URI . '/blocks/tw-spacing-editor.css' );
		}
	}

	add_editor_style( TWENTIG_ASSETS_URI . '/blocks/editor.css' );
	$fse_blocks = [
		'post-template',
		'search',
		'tag-cloud',
		'post-terms',
	];

	foreach ( $fse_blocks as $block_name ) {
		$style_path = TWENTIG_PATH . "dist/blocks/$block_name/style.min.css";
		if ( file_exists( $style_path ) ) {
			add_editor_style( TWENTIG_ASSETS_URI . "/blocks/{$block_name}/style.min.css" );
		}
	}
	
	$fonts_inline = twentig_get_font_face_styles();
	$fonts_inline = preg_replace( '/[\r\n\t ]+/', ' ', $fonts_inline );

	wp_add_inline_style( 'wp-block-library', $fonts_inline );

	$fonts        = twentig_get_additional_fonts();
	$heading_font = get_option( 'twentig_heading_font' );
	$css          = '';
	$css_vars     = '';

	foreach( $fonts as $font ) {
		$family       = $font['fontFamily'];
		$family_slug  = sanitize_title( $font['slug'] );		
		$css_vars    .= "--wp--preset--font-family--{$family_slug}:{$family};";
		$css         .= ".has-{$family_slug}-font-family{font-family:var(--wp--preset--font-family--{$family_slug})!important;}";
	}
	
	if ( $heading_font ) {
		$css .= '.editor-styles-wrapper.editor-styles-wrapper :where(h1,h2,h3,h4,h5,h6) { font-family:' . $heading_font . ';}';
	}
	
	wp_add_inline_style( 'wp-block-library', '.editor-styles-wrapper {' . $css_vars . '}' . $css );
}
add_action( 'admin_init', 'twentig_block_theme_editor_styles' );

/**
 * Returns additional fonts.
 */
function twentig_get_additional_fonts() {
	$fonts = array(
		array(
			'fontFamily' => "\"Alegreya\", serif",
			"slug"       => "alegreya",
			"name"       => "Alegreya",
			'src'        => "Alegreya:ital,wght@0,400..900;1,400..900",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Cabin\", sans-serif",
			"slug"       => "cabin",
			"name"       => "Cabin",
			'src'        => "Cabin:ital,wght@0,400..700;1,400..700",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Crimson Pro\", serif",
			"slug"       => "crimson-pro",
			"name"       => "Crimson Pro",
			'src'        => "Crimson+Pro:ital,wght@0,200..900;1,200..900",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"EB Garamond\", serif",
			"slug"       => "eb-garamond",
			"name"       => "EB Garamond",
			'src'        => "EB+Garamond:ital,wght@0,400..800;1,400..800",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Inter\", sans-serif",
			"slug"       => "inter",
			"name"       => "Inter",
			'src'        => "Inter:wght@100..900",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Libre Franklin\", sans-serif",
			"slug"       => "libre-franklin",
			"name"       => "Libre Franklin",
			'src'        => "Libre+Franklin:ital,wght@0,100..900;1,100..900",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Lora\", serif",
			"slug"       => "lora",
			"name"       => "Lora",
			'src'        => "Lora:ital,wght@0,400..700;1,400..700",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Open Sans\", sans-serif",
			"slug"       => "open-sans",
			"name"       => "Open Sans",
			'src'        => "Open+Sans:ital,wght@0,300..800;1,300..800",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Playfair Display\", serif",
			"slug"       => "playfair-display",
			"name"       => "Playfair Display",
			'src'        => "Playfair+Display:ital,wght@0,400..900;1,400..900",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Raleway\", sans-serif",
			"slug"       => "raleway",
			"name"       => "Raleway",
			'src'        => "Raleway:ital,wght@0,100..900;1,100..900",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Roboto Flex\", sans-serif",
			"slug"       => "roboto-flex",
			"name"       => "Roboto Flex",
			'src'        => "Roboto+Flex:wght@100..900",
			'provider'   => 'google',
		),				
		array(
			'fontFamily' => "\"Roboto Mono\", monospace",
			"slug"       => "roboto-mono",
			"name"       => "Roboto Mono",
			'src'        => "Roboto+Mono:ital,wght@0,100..700;1,100..700",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Roboto Serif\", serif",
			"slug"       => "roboto-serif",
			"name"       => "Roboto Serif",
			'src'        => "Roboto+Serif:ital,wght@0,100..900;1,100..900",
			'provider'   => 'google',
		),	
		array(
			'fontFamily' => "\"Roboto Slab\", serif",
			"slug"       => "roboto-slab",
			"name"       => "Roboto Slab",
			'src'        => "Roboto+Slab:wght@100..900",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Rubik\", sans-serif",
			"slug"       => "rubik",
			"name"       => "Rubik",
			'src'        => "Rubik:ital,wght@0,300..900;1,300..900",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Source Sans 3\", sans-serif",
			"slug"       => "source-sans-3",
			"name"       => "Source Sans 3",
			'src'        => "Source+Sans+3:ital,wght@0,200..900;1,200..900",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Source Serif 4\", serif",
			"slug"       => "source-serif-4",
			"name"       => "Source Serif 4",
			'src'        => "Source+Serif+4:ital,wght@0,200..900;1,200..900",
			'provider'   => 'google',
		),
		array(
			'fontFamily' => "\"Work Sans\", sans-serif",
			"slug"       => "work-sans",
			"name"       => "Work Sans",
			'src'        => "Work+Sans:ital,wght@0,100..900;1,100..900",
			'provider'   => 'google',
		),
	);
	return $fonts;
}

/**
 * Returns inline CSS for our additional fonts.
 */
function twentig_get_font_face_styles() {
	$fonts_file = TWENTIG_PATH . 'inc/block-themes/google-fonts-inline.php';
	return require $fonts_file;
}

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 */
function twentig_block_theme_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'twentig-theme-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'twentig_block_theme_resource_hints', 10, 2 );

/**
 * Updates post editor settings to add fonts and width settings.
 *
 * @param array  $settings Default editor settings.
 *
 * @return array Filtered editor settings.
 */
function twentig_filter_global_styles_settings( $settings ) {	

	if ( isset( $settings['__experimentalFeatures'] ) ) {
		$default_layout = wp_get_global_settings( array( 'layout' ) );
		$content_width  = get_option( 'twentig_content_size' );
		$content_width  = $content_width ? $content_width : $default_layout['contentSize'];
		$wide_width     = get_option( 'twentig_wide_size' );
		$wide_width     = $wide_width ? $wide_width : $default_layout['wideSize'];

		$settings['__experimentalFeatures']['layout']['contentSize'] = $content_width;
		$settings['__experimentalFeatures']['layout']['wideSize'] = $wide_width;
	
		// Make sure the path to __experimentalFeatures.typography.fontFamilies.theme exists before updating fonts.
		if ( empty( $settings['__experimentalFeatures']['typography'] ) ) {
			$settings['__experimentalFeatures']['typography'] = array();
		}
		if ( empty( $settings['__experimentalFeatures']['typography']['fontFamilies'] ) ) {
			$settings['__experimentalFeatures']['typography']['fontFamilies'] = array();
		}
		if ( empty( $settings['__experimentalFeatures']['typography']['fontFamilies']['theme'] ) ) {
			$settings['__experimentalFeatures']['typography']['fontFamilies']['theme'] = array();
		}

		$fonts = $settings['__experimentalFeatures']['typography']['fontFamilies']['theme'];			
		$settings['__experimentalFeatures']['typography']['fontFamilies']['theme'] = twentig_merge_fonts_to_theme_fonts( $fonts );
	}

	return $settings;
}
add_filter( 'block_editor_settings_all', 'twentig_filter_global_styles_settings' );

/**
 * Merge our additional fonts with the theme fonts and make sure the values are unique.
 *
 * @param array $theme_fonts The theme fonts.
 * @return array             The merged fonts.
 */
function twentig_merge_fonts_to_theme_fonts( $theme_fonts ) {
	$new_fonts = twentig_get_additional_fonts();	
	$fonts     = $theme_fonts ? $theme_fonts : array();
	
	foreach( $new_fonts as $font ) {
		if ( in_array( $font['fontFamily'], array_column( $fonts, 'fontFamily' ) ) ) {
			continue;
		}
		$fonts[] = $font;
	}
	return $fonts;	
}

/**
 * Updates the Global Styles controller route.
 *
 * @see WP_REST_Global_Styles_Controller.
 */
function twentig_register_global_styles_rest_route() {
	
	$controller = new WP_REST_Global_Styles_Controller();
	register_rest_route(
		'wp/v2',
		sprintf(
			'/%s/themes/(?P<stylesheet>%s)',
			'global-styles',
			'[^\/:<>\*\?"\|]+(?:\/[^\/:<>\*\?"\|]+)?'
		),
		array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => 'twentig_get_theme_item_global_styles',
				'permission_callback' => array( $controller, 'get_theme_item_permissions_check'),
				'args'                => array(
					'stylesheet' => array(
						'description'       => __( 'The theme identifier' ),
						'type'              => 'string',
						'sanitize_callback' => array( $controller, '_sanitize_global_styles_callback' ),
					),
				),
			),
		),
		true
	);
}
add_action( 'rest_api_init', 'twentig_register_global_styles_rest_route', 20 );

/**
 * Returns the given theme global styles config.
 * 
 * @param WP_REST_Request $request The request instance.
 * @return WP_REST_Response|WP_Error
*/
function twentig_get_theme_item_global_styles( $request ) {

	$controller = new WP_REST_Global_Styles_Controller();
	$response = $controller->get_theme_item( $request );

	if ( $response->data['settings'] ) {
		$settings       = $response->data['settings'];
		$default_layout = wp_get_global_settings( array( 'layout' ) );	
		$content_width  = get_option( 'twentig_content_size' );
		$content_width  = $content_width ? $content_width : $default_layout['contentSize'];
		$wide_width     = get_option( 'twentig_wide_size' );
		$wide_width     = $wide_width ? $wide_width : $default_layout['wideSize'];

		$settings['layout']['contentSize'] = $content_width;
		$settings['layout']['wideSize'] = $wide_width;

		// Make sure the path to settings.typography.fontFamilies.theme exists before updating fonts.
		if ( empty( $settings['typography'] ) ) {
			$settings['typography'] = array();
		}
		if ( empty( $settings['typography']['fontFamilies'] ) ) {
			$settings['typography']['fontFamilies'] = array();
		}
		if ( empty( $settings['typography']['fontFamilies']['theme'] ) ) {
			$settings['typography']['fontFamilies']['theme'] = array();
		}

		$fonts = $settings['typography']['fontFamilies']['theme'];		
		$settings['typography']['fontFamilies']['theme'] = twentig_merge_fonts_to_theme_fonts( $fonts );

		if ( 'twentytwentytwo' === wp_get_theme()->get_stylesheet() ) {
			$theme_sizes = $settings['typography']['fontSizes']['theme'];
			$settings['typography']['fontSizes']['theme'] = twentig_twentytwo_get_font_sizes( $theme_sizes );
		}

		$response->data['settings'] = $settings;
	}
	return $response;
}

/**
 * Renders the layout config to the block wrapper.
 * Overrides wp_render_layout_support_flag() core function to make the default layout work as a preset.
 *
 * @param  string $block_content Rendered block content.
 * @param  array  $block         Block object.
 * @return string                Filtered block content.
 */
function twentig_render_layout_support_flag( $block_content, $block ) {
	$block_type     = WP_Block_Type_Registry::get_instance()->get_registered( $block['blockName'] );
	$support_layout = block_has_support( $block_type, array( '__experimentalLayout' ), false );

	if ( ! $support_layout ) {
		return $block_content;
	}

	$block_gap             = wp_get_global_settings( array( 'spacing', 'blockGap' ) );
	$default_layout        = wp_get_global_settings( array( 'layout' ) );
	$has_block_gap_support = isset( $block_gap ) ? null !== $block_gap : false;
	$default_block_layout  = _wp_array_get( $block_type->supports, array( '__experimentalLayout', 'default' ), array() );
	$used_layout           = isset( $block['attrs']['layout'] ) ? $block['attrs']['layout'] : $default_block_layout;
	$gap_value             = _wp_array_get( $block, array( 'attrs', 'style', 'spacing', 'blockGap' ) );
	if ( is_array( $gap_value ) ) {
		foreach ( $gap_value as $key => $value ) {
			$gap_value[ $key ] = $value && preg_match( '%[\\\(&=}]|/\*%', $value ) ? null : $value;
		}
	} else {
		$gap_value = $gap_value && preg_match( '%[\\\(&=}]|/\*%', $gap_value ) ? null : $gap_value;
	}

	if ( isset( $used_layout['inherit'] ) && $used_layout['inherit'] ) {
		if ( ! $default_layout ) {
			return $block_content;
		}

		$used_layout = array( 
			'contentSize' => '',
			'wideSize'    => ''
		);

		$class_name = 'layout-default';

		if ( ! $gap_value ) {
			$class_name = wp_unique_id( 'wp-container-' ) . ' ' . $class_name;
		}

		$block_content = preg_replace(
			'/' . preg_quote( 'class="', '/' ) . '/',
			'class="' . $class_name . ' ',
			$block_content,
			1
		);

		if ( ! $gap_value ) {
			return $block_content;
		}
	}
	
	$class_name = wp_unique_id( 'wp-container-' );
	$style      = '';

	if ( function_exists( 'wp_should_skip_block_supports_serialization') ) {
		$should_skip_gap_serialization = wp_should_skip_block_supports_serialization( $block_type, 'spacing', 'blockGap' );
		$style                         = wp_get_layout_style( ".$class_name", $used_layout, $has_block_gap_support, $gap_value, $should_skip_gap_serialization );
	} else {
		$style = wp_get_layout_style( ".$class_name", $used_layout, $has_block_gap_support, $gap_value );	
	}

	if ( $gap_value && in_array( $block['blockName'], [ 'core/group', 'core/columns', 'core/column' ], true ) ) {
		$class_name .= ' has-gap';
	}

	$content = preg_replace(
		'/' . preg_quote( 'class="', '/' ) . '/',
		'class="' . esc_attr( $class_name ) . ' ',
		$block_content,
		1
	);

	add_action(
		'wp_head',
		static function () use ( $style ) {
			echo "<style>$style</style>";
		}
	);
		
	return $content;
}
remove_filter( 'render_block', 'wp_render_layout_support_flag' );
add_filter( 'render_block', 'twentig_render_layout_support_flag', 10, 2 );

/**
 * Registers additional global editor settings.
 */
function twentig_register_site_editor_settings() {
	
	$default_layout = wp_get_global_settings( array( 'layout' ) );	

	register_setting(
		'twentig_content_size',
		'twentig_content_size',
		array(
			'type'         => 'string',
			'show_in_rest' => true,
			'default'      => isset( $default_layout['contentSize'] ) ? $default_layout['contentSize'] : '',
		)
	);

	register_setting(
		'twentig_wide_size',
		'twentig_wide_size',
		array(
			'type'         => 'string',
			'show_in_rest' => true,
			'default'      => isset( $default_layout['wideSize'] ) ? $default_layout['wideSize'] : '',
		)
	);

	register_setting(
		'twentig_heading_font',
		'twentig_heading_font',
		array(
			'type'         => 'string',
			'show_in_rest' => true,
			'default'      => '',
		)
	);

	register_setting(
		'twentig_global_spacing',
		'twentig_global_spacing',
		array(
			'type'         => 'boolean',
			'show_in_rest' => true,
			'default'      => twentig_default_global_spacing(),
		)
	);

}
add_action( 'init', 'twentig_register_site_editor_settings' );

/**
 * Get default value for the Twentig global spacing setting.
 */
function twentig_default_global_spacing() {
	$theme = get_template();
	if ( 'twentytwentytwo' === $theme ) {
		return true;
	}
	return false;
}
