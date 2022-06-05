<?php

/**
 * Enqueues block assets for frontend and backend editor.
 */
function twentig_block_assets() {

	$asset_file             = include TWENTIG_PATH . 'dist/index.asset.php';
	$block_library_filename = wp_should_load_separate_core_block_assets() ? 'blocks/common.min' : 'style-index';

	wp_enqueue_style(
		'twentig-blocks',
		plugins_url( 'dist/' . $block_library_filename . '.css', dirname( __FILE__ ) ),
		array(),
		$asset_file['version']
	);

	wp_style_add_data( 'twentig-blocks', 'rtl', 'replace' );
}
add_action( 'enqueue_block_assets', 'twentig_block_assets' );

/**
 * Enqueues block assets for backend editor.
 */
function twentig_block_editor_assets() {

	global $pagenow;

	$asset_file = include TWENTIG_PATH . 'dist/index.asset.php';
	$deps       = $asset_file['dependencies'];
	$env        = 'post-editor';

	// Removes editor related assets when viewing the customizer or widgets screen to prevent conflict with the widgets editor.
	if ( is_customize_preview() || 'widgets.php' === $pagenow ) {
		$env           = 'no-post-editor';
		$edit_post_key = array_search( 'wp-edit-post', $deps );
		if ( false !== $edit_post_key ) {
			unset( $deps[ $edit_post_key ] );
		}
		$edit_site_key = array_search( 'wp-edit-site', $deps );
		if ( false !== $edit_site_key ) {
			unset( $deps[ $edit_site_key ] );
		}		
	}
	
	if ( 'site-editor.php' === $pagenow ) {
		$env = 'site-editor';
	}

	wp_enqueue_script(
		'twentig-blocks-editor',
		plugins_url( '/dist/index.js', dirname( __FILE__ ) ),
		$deps,
		$asset_file['version'],
		false
	);

	$config = apply_filters(
		'twentig_blocks_editor_config',
		array(
			'theme'          => get_template(),
			'block_theme'    => function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ? true : false,
			'branch'         => str_replace( array( '.', ',' ), '-', (float) get_bloginfo( 'version' ) ),
			'cssClasses'     => twentig_get_block_css_classes(),
			'guideAssetsUri' => TWENTIG_ASSETS_URI . '/images/welcome/',
			'env'            => $env
		)
	);

	wp_localize_script( 'twentig-blocks-editor', 'twentigEditorConfig', $config );

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'twentig-blocks-editor', 'twentig' );
	}

	wp_enqueue_style(
		'twentig-editor',
		plugins_url( 'dist/index.css', dirname( __FILE__ ) ),
		array( 'wp-edit-blocks' ),
		$asset_file['version']
	);

	wp_style_add_data( 'twentig-editor', 'rtl', 'replace' );
}
add_action( 'enqueue_block_editor_assets', 'twentig_block_editor_assets' );

/**
 * Enqueue block styles (attach extra styles or override block styles).
 */
function twentig_enqueue_block_styles() {

	if ( ! wp_should_load_separate_core_block_assets() ) {
		return;
	}

	// Add block-specific inline styles.
	$styled_blocks = [ 
		'cover',
		'image',
		'list',
		'table',
		'social-links',
		'post-featured-image',
		'query-title',
		'post-author',
		'quote',
		'search',
		'post-terms',
		'tag-cloud',
	];
 
	foreach ( $styled_blocks as $block_name ) {
		$style_path = TWENTIG_PATH . "dist/blocks/$block_name/style.min.css";
		if ( file_exists( $style_path ) ) {
			$styles = file_get_contents( $style_path );
			wp_add_inline_style( "wp-block-{$block_name}", $styles );
		}
	}
	
	// Override core blocks style.
	$overriden_blocks = [
		'columns',
		'gallery',
		'media-text',
		'post-template',		
		'latest-posts',
		'pullquote'
	];

	foreach ( $overriden_blocks as $block_name ) {
		$style_path = TWENTIG_PATH . "dist/blocks/$block_name/style.min.css";
		if ( file_exists( $style_path ) ) {
			wp_deregister_style( "wp-block-{$block_name}" );
			wp_register_style(
				"wp-block-{$block_name}",
				TWENTIG_ASSETS_URI . "/blocks/{$block_name}/style.min.css",
				array(),
				TWENTIG_VERSION
			);

			// Add a reference to the stylesheet's path to allow calculations for inlining styles in `wp_head`.
			wp_style_add_data( "wp-block-{$block_name}", 'path', $style_path );

			// Add RTL stylesheet.
			$rtl_file_path = str_replace( '.css', '-rtl.css', $style_path ); 
			if ( file_exists( $rtl_file_path ) ) {
				wp_style_add_data( "wp-block-{$block_name}", 'rtl', 'replace' );  
				if ( is_rtl() ) {
					wp_style_add_data( "wp-block-{$block_name}", 'path', $rtl_file_path );
				}
			}		
		}
	}
}
add_action( 'wp_enqueue_scripts', 'twentig_enqueue_block_styles' );

/**
 * Filters the columns block output.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 */
function twentig_filter_columns_block( $block_content, $block ) {

	$attributes = isset( $block['attrs'] ) ? $block['attrs'] : [];
	$gap = _wp_array_get( $attributes, array( 'style', 'spacing', 'blockGap' ), null );

	if ( $gap && false !== strpos( $gap, 'px' ) ) {
		$gap_value = intval( $gap );
	
		if ( $gap_value > 32 ) {
			$class = 'tw-large-gap';
			$block_content = preg_replace(
				'/' . preg_quote( 'class="', '/' ) . '/',
				'class="' . $class . ' ',
				$block_content,
				1
			);
		}
	}
	return $block_content;
}
add_filter( 'render_block_core/columns', 'twentig_filter_columns_block', 10, 2 );

/**
 * Filters the column block output to add a CSS var to store the width attribute.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 */
function twentig_filter_column_block( $block_content, $block ) {

	if ( wp_should_load_separate_core_block_assets() ) {
		return $block_content;
	}
	if ( isset( $block['attrs'] ) && isset( $block['attrs']['width'] ) ) {
		$block_content = str_replace( 'flex-basis', '--col-width:' . $block['attrs']['width'] . ';flex-basis', $block_content );
	}
	return $block_content;
}
add_filter( 'render_block_core/column', 'twentig_filter_column_block', 10, 2 );

/**
 * Wraps the archive title prefix with a span.
 *
 * @param string $prefix Archive title prefix.
 */
function twentig_set_archive_title_block_prefix( $prefix ) {
	if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
		return '<span class="archive-title-prefix">' . $prefix . '</span>';
	}
	return $prefix;
}
add_filter( 'get_the_archive_title_prefix', 'twentig_set_archive_title_block_prefix' );

/**
 * Filters the post author block output.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 */
function twentig_filter_post_author_block( $block_content, $block ) {	
	if ( ! empty( $block['attrs'] ) && isset( $block['attrs']['twIsLink'] ) && $block['attrs']['twIsLink']  ) {
		$author_id = get_post_field( 'post_author', get_the_ID() );
		if ( empty( $author_id ) ) {
			return $block_content;
		}
		
		$author_display_name = get_the_author_meta( 'display_name', $author_id );
		$author_post_url     = get_author_posts_url( $author_id );
	
		$block_content = str_replace(
			'<p class="wp-block-post-author__name">' . $author_display_name,
			sprintf( '<p class="wp-block-post-author__name"><a href="%s">%s</a>', esc_url( $author_post_url ), esc_html( $author_display_name ) ),
			$block_content
		);
	}
	return $block_content;
}
add_filter( 'render_block_core/post-author', 'twentig_filter_post_author_block', 10, 2 );

/**
 * Filters the post excerpt block output.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 */
function twentig_filter_post_excerpt_block( $block_content, $block ) {
	
	if ( empty( $block_content ) || has_excerpt() ) {
		return $block_content;
	}
	
	add_filter( 'excerpt_more', function() {
		return '&hellip;';
	} );

	if ( ! empty( $block['attrs'] ) && isset( $block['attrs']['twExcerptLength'] ) ) {	

		$attributes            = $block['attrs'];	
		$more_text             = ! empty( $attributes['moreText'] ) ? '<a class="wp-block-post-excerpt__more-link" href="' . esc_url( get_the_permalink() ) . '">' . $attributes['moreText'] . '</a>' : '';
		$excerpt_length        = $attributes['twExcerptLength'];		
		$filter_excerpt_length = function() use ( $excerpt_length ) {
			return $excerpt_length;
		};
		add_filter( 'excerpt_length', $filter_excerpt_length );

		$excerpt               = get_the_excerpt();
		$content               = '<p class="wp-block-post-excerpt__excerpt">' . $excerpt;
		$show_more_on_new_line = ! isset( $attributes['showMoreOnNewLine'] ) || $attributes['showMoreOnNewLine'];
		if ( $show_more_on_new_line && ! empty( $more_text ) ) {
			$content .= '</p>';
		} else {
			$content .= " $more_text</p>";
		}
		$block_content = preg_replace('/<p class=\"wp-block-post-excerpt__excerpt\">.*?<\/p>/', $content , $block_content );
		remove_filter( 'excerpt_length', $filter_excerpt_length );
		return $block_content;
	}

	return $block_content;
}
add_filter( 'render_block_core/post-excerpt', 'twentig_filter_post_excerpt_block', 10, 2 );

/**
 * Filters the query block output.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 */
function twentig_filter_query_block( $block_content, $block ) {

	$attributes = isset( $block['attrs'] ) ? $block['attrs'] : [];
	$layout     = isset( $attributes['displayLayout'] ) ? $attributes['displayLayout'] : [];
	$sizes      = '';

	if ( $layout && 'flex' === $layout['type'] ) {
		$columns = $layout['columns'];
		
		if ( 2 === $columns ) {
			$sizes = '(min-width: 1024px) calc(50vw - 60px), (min-width: 640px) calc(50vw - 40px), calc(100vw - 40px)';
		} elseif ( 3 === $columns ) {
			$sizes = '(min-width: 1024px) calc(33vw - 50px), (min-width: 640px) calc(50vw - 40px), calc(100vw - 40px)';
		} else {
			$sizes = '(min-width: 1024px) calc(25vw - 40px), (min-width: 640px) calc(50vw - 40px), calc(100vw - 40px)';
		}
				
	}
	if ( $sizes ) {
		return preg_replace( '/sizes="([^>]+?)"/', 'sizes="' . $sizes . '"', $block_content );
	}
	
	return $block_content;
}
add_filter( 'render_block_core/query', 'twentig_filter_query_block', 10, 2 );

/**
 * Filters the navigation block output.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 */
function twentig_filter_navigation_block( $block_content, $block ) {
	if ( ! empty( $block['attrs'] ) && isset( $block['attrs']['twMenuIcon'] ) ) {
		$current_icon = '<rect x="4" y="7.5" width="16" height="1.5" /><rect x="4" y="15" width="16" height="1.5" />';
		if ( 'three-lines' === $block['attrs']['twMenuIcon'] ) {
			$new_icon = '<rect x="4" y="6.5" width="16" height="1.5"></rect><rect x="4" y="11.25" width="16" height="1.5"></rect><rect x="4" y="16" width="16" height="1.5"></rect>';
			$block_content = str_replace( $current_icon, $new_icon, $block_content );
		} else if ( 'plus' === $block['attrs']['twMenuIcon'] ) {
			$new_icon = '<path d="M21.75 11.3H12.7V2.25h-1.4v9.05H2.25v1.4h9.05v9.05h1.4V12.7h9.05v-1.4"></path>';
			$block_content = str_replace( $current_icon, $new_icon, $block_content );
		}
	}
	return $block_content;
}
add_filter( 'render_block_core/navigation', 'twentig_filter_navigation_block', 10, 2 );

/**
 * Filters the parsed attribute values of the navigation block.
 *
 * @param array $parsed_block The block being rendered.
 *
 * @return array The block being rendered with additional classnames.
 */
function twentig_filter_navigation_block_class( $parsed_block ) {

	if ( 'core/navigation' === $parsed_block['blockName'] ) {
		$nav_class = '';
		$attributes = $parsed_block['attrs'];

		if ( isset( $attributes['twGap'] ) ) {
			$nav_class .= ' tw-gap-' . $attributes['twGap'] ;
		}
		
		if ( isset( $attributes['overlayMenu'] ) && in_array( $attributes['overlayMenu'], ['mobile', 'always'], true ) || ! isset( $attributes['overlayMenu'] ) ) {
			if ( isset( $attributes['twBreakpoint'] ) && $attributes['twBreakpoint'] ) {
				$nav_class .= ' tw-break-' . $attributes['twBreakpoint'];
			}
			if ( isset( $attributes['twMenuIconSize'] ) ) {
				$nav_class .= ' tw-icon-' . $attributes['twMenuIconSize'];
			}				
		}

		if ( $nav_class ) {
			if ( isset( $parsed_block['attrs']['className'] ) ) {
				$parsed_block['attrs']['className'] = trim( esc_html( $parsed_block['attrs']['className'] . $nav_class ) );
			} else {
				$parsed_block['attrs']['className'] = trim( esc_html( $nav_class ) );
			}
		}
	}
	return $parsed_block;
}
add_filter( 'render_block_data', 'twentig_filter_navigation_block_class' );

/**
 * Filters the image block output.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 */
function twentig_filter_image_block( $block_content, $block ) {

	if ( ! empty( $block['attrs'] ) && isset( $block['attrs']['twShape'] ) ) {
		$shape            = $block['attrs']['twShape'];
		$func_name        = "twentig_image_shape_" . str_replace( '-', '_', $shape );		
		$action_hook_name = function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ? 'wp_body_open' : 'wp_footer';
		
		if ( function_exists( $func_name ) ) {
			add_action( $action_hook_name, $func_name, 11 );
		}

		$style = "--shape:url(#tw-shape-$shape);";
			
		if ( strpos( $block_content, 'style="' ) !== false ) {
			return preg_replace(
				'/' . preg_quote( 'style="', '/' ) . '/',
				'style="' . $style . ' ',
				$block_content,
				1
			);
		} else {
			return preg_replace(
				'/' . preg_quote( 'class="', '/' ) . '/',
				'style="' . $style . '" class="',
				$block_content,
				1
			);
		}
	}	
	return $block_content;
}
add_filter( 'render_block_core/image', 'twentig_filter_image_block', 10, 2 );

/**
 * Print all the image shape svgs at the beginning of the body on block editor pages.
 */
function twentig_editor_prints_svg_image_shapes() {

	if ( ! get_current_screen()->is_block_editor() ) {
		return;
	}

	$shapes = [
		'diamond',
		'squircle',
		'organic-square',
		'star-1',
		'star-2',
		'star-3',
		'organic-circle-1',
		'organic-circle-2',
		'organic-circle-3',
		'organic-circle-4'
	];

	foreach( $shapes as $shape ) {
		call_user_func( "twentig_image_shape_" . str_replace( '-', '_', $shape ) );
	}
}
add_action( 'in_admin_header' , 'twentig_editor_prints_svg_image_shapes' );

/**
 * Output the shape's SVG.
 *  
 * @param string $path The SVG path.
 */
function twentig_render_shape_svg( $path ) {
	echo '
	<svg  
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 0 0"
		width="0"
		height="0"
		focusable="false"
		role="none"
		style="position: absolute; left: -9999px; overflow: hidden;"
	><defs>' . $path . '</defs></svg>';
}

/**
 * Output SVG for the diamond.
 */
function twentig_image_shape_diamond() {
	$path = '<clipPath id="tw-shape-diamond" clipPathUnits="objectBoundingBox"><path d="m.935.645-.29.29a.205.205 0 0 1-.29 0l-.29-.29a.205.205 0 0 1 0-.29l.29-.29a.205.205 0 0 1 .29 0l.29.29a.205.205 0 0 1 0 .29Z"/></clipPath>'; 
	twentig_render_shape_svg( $path );
}

/**
 * Output SVG for the squircle.
 */
function twentig_image_shape_squircle() {
	$path = '<clipPath id="tw-shape-squircle" clipPathUnits="objectBoundingBox"><path d="M.005.5C.005.084.085.005.5.005S.995.085.995.5.915.995.5.995.005.915.005.5Z"></path></clipPath>'; 
	twentig_render_shape_svg( $path );
}

/**
 * Output SVG for the organic square.
 */
function twentig_image_shape_organic_square() {
	$path = '<clipPath id="tw-shape-organic-square" clipPathUnits="objectBoundingBox"><path d="M.602.042.637.027a.256.256 0 0 1 .336.336L.958.398a.256.256 0 0 0 0 .204l.015.035a.256.256 0 0 1-.336.336L.602.958a.256.256 0 0 0-.204 0L.363.973A.256.256 0 0 1 .027.637L.042.602a.256.256 0 0 0 0-.204L.027.363A.256.256 0 0 1 .363.027l.035.015a.256.256 0 0 0 .204 0Z"></path></clipPath>'; 
	twentig_render_shape_svg( $path );
}

/**
 * Output SVG for the 1st star.
 */
function twentig_image_shape_star_1() {
	$path = '<clipPath id="tw-shape-star-1" clipPathUnits="objectBoundingBox"><path d="M.364.046a.244.244 0 0 1 .272 0 .244.244 0 0 0 .089.037.244.244 0 0 1 .192.192.244.244 0 0 0 .037.09.244.244 0 0 1 0 .27.244.244 0 0 0-.037.09.244.244 0 0 1-.192.192.244.244 0 0 0-.09.037.244.244 0 0 1-.27 0 .244.244 0 0 0-.09-.037.244.244 0 0 1-.192-.192.244.244 0 0 0-.037-.09.244.244 0 0 1 0-.27.244.244 0 0 0 .037-.09.244.244 0 0 1 .192-.192.244.244 0 0 0 .09-.037Z"></path></clipPath>'; 
	twentig_render_shape_svg( $path );
}

/**
 * Output SVG for the 2nd star.
 */
function twentig_image_shape_star_2() {
	$path = '<clipPath id="tw-shape-star-2" clipPathUnits="objectBoundingBox"><path d="M.404.044.45.018a.102.102 0 0 1 .1 0l.046.026a.102.102 0 0 0 .049.013h.052a.102.102 0 0 1 .087.05l.027.046a.102.102 0 0 0 .036.036l.045.027a.102.102 0 0 1 .05.087l.001.052a.102.102 0 0 0 .013.05L.982.45a.102.102 0 0 1 0 .1L.956.596a.102.102 0 0 0-.013.049v.052a.102.102 0 0 1-.05.087L.846.811a.102.102 0 0 0-.036.036L.784.892a.102.102 0 0 1-.087.05L.645.943a.102.102 0 0 0-.05.013L.55.982a.102.102 0 0 1-.1 0L.404.956A.102.102 0 0 0 .355.943H.303a.102.102 0 0 1-.087-.05L.189.846A.102.102 0 0 0 .153.811L.108.784a.102.102 0 0 1-.05-.087L.057.645a.102.102 0 0 0-.013-.05L.018.55a.102.102 0 0 1 0-.1L.044.404A.102.102 0 0 0 .057.355V.303a.102.102 0 0 1 .05-.087L.154.189A.102.102 0 0 0 .189.153L.216.108a.102.102 0 0 1 .087-.05L.355.057a.102.102 0 0 0 .05-.013Z"></path></clipPath>'; 
	twentig_render_shape_svg( $path );
}

/**
 * Output SVG for the 3rd star.
 */
function twentig_image_shape_star_3() {
	$path = '<clipPath id="tw-shape-star-3" clipPathUnits="objectBoundingBox"><path d="M.426.034.47.013a.07.07 0 0 1 .062 0l.043.021A.07.07 0 0 0 .61.041L.658.038a.07.07 0 0 1 .058.024l.03.036a.07.07 0 0 0 .031.02l.045.015a.07.07 0 0 1 .045.045L.88.223a.07.07 0 0 0 .021.03l.036.031a.07.07 0 0 1 .024.058L.96.39a.07.07 0 0 0 .007.036L.987.47a.07.07 0 0 1 0 .062L.966.574A.07.07 0 0 0 .959.61l.003.048a.07.07 0 0 1-.024.058l-.036.03a.07.07 0 0 0-.02.031L.866.823a.07.07 0 0 1-.045.045L.778.88a.07.07 0 0 0-.032.021l-.03.036a.07.07 0 0 1-.058.024L.61.96a.07.07 0 0 0-.036.007L.53.987a.07.07 0 0 1-.062 0L.426.966A.07.07 0 0 0 .39.959L.342.962A.07.07 0 0 1 .284.938L.254.902a.07.07 0 0 0-.032-.02L.178.866A.07.07 0 0 1 .133.822L.12.778A.07.07 0 0 0 .098.745L.062.716A.07.07 0 0 1 .038.658L.04.61A.07.07 0 0 0 .034.574L.013.53a.07.07 0 0 1 0-.062L.034.426A.07.07 0 0 0 .041.39L.038.342A.07.07 0 0 1 .062.284l.036-.03a.07.07 0 0 0 .02-.032L.134.178A.07.07 0 0 1 .178.133L.223.12a.07.07 0 0 0 .03-.021L.285.062A.07.07 0 0 1 .342.038L.39.04A.07.07 0 0 0 .426.034Z"></path></clipPath>'; 
	twentig_render_shape_svg( $path );
}

/**
 * Output SVG for the 1st organic circle.
 */
function twentig_image_shape_organic_circle_1() {
	$path = '<clipPath id="tw-shape-organic-circle-1" clipPathUnits="objectBoundingBox"><path d="M.987.624A.377.377 0 0 1 .883.82a.594.594 0 0 1-.181.126.582.582 0 0 1-.21.05.36.36 0 0 1-.205-.051.595.595 0 0 1-.162-.14.561.561 0 0 1-.1-.188A.451.451 0 0 1 .013.4.366.366 0 0 1 .11.208.959.959 0 0 1 .283.065a.372.372 0 0 1 .21-.06.525.525 0 0 1 .22.05.348.348 0 0 1 .16.146 1.546 1.546 0 0 1 .096.204.383.383 0 0 1 .018.22Z"></path></clipPath>'; 
	twentig_render_shape_svg( $path );
}

/**
 * Output SVG for the 2nd organic circle.
 */
function twentig_image_shape_organic_circle_2() {
	$path = '<clipPath id="tw-shape-organic-circle-2" clipPathUnits="objectBoundingBox"><path d="M.99.61a.439.439 0 0 1-.083.207.368.368 0 0 1-.175.131.804.804 0 0 1-.22.046.334.334 0 0 1-.205-.052A1.14 1.14 0 0 1 .13.804.389.389 0 0 1 .02.61a.49.49 0 0 1-.002-.23.355.355 0 0 1 .111-.19A1.413 1.413 0 0 1 .308.053.303.303 0 0 1 .512.007.588.588 0 0 1 .717.07a.489.489 0 0 1 .16.134.555.555 0 0 1 .097.19A.529.529 0 0 1 .99.61Z"></path></clipPath>'; 
	twentig_render_shape_svg( $path );
}

/**
 * Output SVG for the 3rd organic circle.
 */
function twentig_image_shape_organic_circle_3() {
	$path = '<clipPath id="tw-shape-organic-circle-3" clipPathUnits="objectBoundingBox"><path d="M.984.603a.412.412 0 0 1-.103.193.853.853 0 0 1-.173.141.381.381 0 0 1-.212.058A.46.46 0 0 1 .28.939a.599.599 0 0 1-.173-.14.425.425 0 0 1-.094-.197.47.47 0 0 1 .008-.216.57.57 0 0 1 .095-.194A.408.408 0 0 1 .285.06.686.686 0 0 1 .498.006.381.381 0 0 1 .71.05a.46.46 0 0 1 .165.142.725.725 0 0 1 .1.195.394.394 0 0 1 .01.217Z"></path></clipPath>'; 
	twentig_render_shape_svg( $path );
}

/**
 * Output SVG for the 4th organic circle.
 */
function twentig_image_shape_organic_circle_4() {
	$path = '<clipPath id="tw-shape-organic-circle-4" clipPathUnits="objectBoundingBox"><path d="M.987.63A.403.403 0 0 1 .9.836.362.362 0 0 1 .723.96a.736.736 0 0 1-.214.035.697.697 0 0 1-.216-.032A.28.28 0 0 1 .128.83 1.5 1.5 0 0 1 .03.624.342.342 0 0 1 .02.402a.724.724 0 0 1 .097-.21.425.425 0 0 1 .17-.15.366.366 0 0 1 .22-.033.935.935 0 0 1 .215.06.511.511 0 0 1 .181.128.329.329 0 0 1 .09.202A1.155 1.155 0 0 1 .986.63Z"></path></clipPath>'; 
	twentig_render_shape_svg( $path );
}

/**
 * Filters the gallery block output.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 */
function twentig_filter_gallery_block( $block_content, $block ) {
	$attributes = isset( $block['attrs'] ) ? $block['attrs'] : [];
	$gap = _wp_array_get( $attributes, array( 'style','spacing', 'blockGap' ), null );

	if ( $gap && false !== strpos( $gap, 'px' ) ) {
		$gap_value = intval( $gap );
		$class     = '';

		if ( $gap_value > 32 ) {
			$class = 'tw-large-gap';
		} elseif ( $gap_value > 20 ) {
			$class = 'tw-medium-gap';
		}
		if ( $class ) {
			$block_content = preg_replace(
				'/' . preg_quote( 'class="', '/' ) . '/',
				'class="' . $class . ' ',
				$block_content,
				1
			);
		}
	}
	return $block_content;
}
add_filter( 'render_block_core/gallery', 'twentig_filter_gallery_block', 10, 2 );
