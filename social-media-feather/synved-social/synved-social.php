<?php
/**
 * Module Name: Synved Social
 * Description: Social sharing and following tools
 * Author: Synved
 * Version: 1.7.16
 * Author URI: http://synved.com/
 * License: GPLv2
 *
 * LEGAL STATEMENTS
 *
 * NO WARRANTY
 * All products, support, services, information and software are provided "as is" without warranty of any kind, express or implied, including, but not limited to, the implied warranties of fitness for a particular purpose, and non-infringement.
 *
 * NO LIABILITY
 * In no event shall Synved Ltd. be liable to you or any third party for any direct or indirect, special, incidental, or consequential damages in connection with or arising from errors, omissions, delays or other cause of action that may be attributed to your use of any product, support, services, information or software provided, including, but not limited to, lost profits or lost data, even if Synved Ltd. had been advised of the possibility of such damages.
 *
 * @package SocialMediaFeather
 */

define( 'SYNVED_SOCIAL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SYNVED_SOCIAL_LOADED', true );
define( 'SYNVED_SOCIAL_VERSION', 100070016 );
define( 'SYNVED_SOCIAL_VERSION_STRING', '1.7.16' );
define( 'SYNVED_SOCIAL_ADDON_PATH', str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, dirname( __FILE__ ) . '/addons' ) );

$synved_social = array();

require_once SYNVED_SOCIAL_PLUGIN_PATH . 'synved-social-setup.php';
require_once SYNVED_SOCIAL_PLUGIN_PATH . 'inc/class-synvedsocialwidget.php';
require_once SYNVED_SOCIAL_PLUGIN_PATH . 'inc/class-synvedsocialsharewidget.php';
require_once SYNVED_SOCIAL_PLUGIN_PATH . 'inc/class-synvedsocialfollowwidget.php';

/**
 * Get service provider list array.
 *
 * @param string $context Context string.
 * @param bool   $raw     Raw or not.
 *
 * @return array
 */
function synved_social_service_provider_list( $context, $raw = false ) {
	$provider_list = array();

	if ( 'share' === $context ) {
		$provider_list = array(
			'facebook'  => array(
				'link'  => 'https://www.facebook.com/sharer.php?u=%%url%%&t=%%title%%&s=100&p[url]=%%url%%&p[images][0]=%%image%%&p[title]=%%title%%',
				'title' => __( 'Share on Facebook' ),
			),
			'twitter'   => array(
				'link'  => 'https://twitter.com/intent/tweet?url=%%url%%&text=%%message%%',
				'title' => __( 'Share on Twitter' ),
			),
			'reddit'    => array(
				'link'  => 'https://www.reddit.com/submit?url=%%url%%&title=%%title%%',
				'title' => __( 'Share on Reddit' ),
			),
			'pinterest' => array(
				'link'  => 'https://pinterest.com/pin/create/button/?url=%%url%%&media=%%image%%&description=%%title%%',
				'title' => __( 'Pin it with Pinterest' ),
			),
			'linkedin'  => array(
				'link'  => 'https://www.linkedin.com/shareArticle?mini=true&url=%%url%%&title=%%title%%',
				'title' => __( 'Share on Linkedin' ),
			),
			'tumblr'    => array(
				'link'            => 'https://tumblr.com/share?s=&v=3&t=%%title%%&u=%%url%%',
				'title'           => __( 'Share on tumblr' ),
				'default-display' => false,
			),
			'mail'      => array(
				'link'  => 'mailto:?subject=%%title%%&body=%%message%%:%20%%url%%',
				'title' => __( 'Share by email' ),
			),
		);
	} elseif ( 'follow' === $context ) {
		$provider_list = array(
			'facebook'   => array(
				'link'  => 'https://www.facebook.com/facebook',
				'title' => __( 'Follow us on Facebook' ),
			),
			'twitter'    => array(
				'link'  => 'https://twitter.com/twitter',
				'title' => __( 'Follow us on Twitter' ),
			),
			'pinterest'  => array(
				'link'            => 'https://pinterest.com/MyUserName/',
				'title'           => __( 'Our board on Pinterest' ),
				'default-display' => false,
			),
			'linkedin'   => array(
				'link'  => 'https://www.linkedin.com/in/yourid',
				'title' => __( 'Find us on Linkedin' ),
			),
			'rss'        => array(
				'label' => 'RSS',
				'link'  => 'https://feeds.feedburner.com/MyFeedName',
				'title' => __( 'Subscribe to our RSS Feed' ),
			),
			'youtube'    => array(
				'link'  => 'https://www.youtube.com/MyYouTubeName',
				'title' => __( 'Find us on YouTube' ),
			),
			'vimeo'      => array(
				'link'            => 'https://vimeo.com/MyVimeoName',
				'title'           => __( 'Find us on vimeo' ),
				'default-display' => false,
			),
			'tumblr'     => array(
				'link'            => 'https://myname.tumblr.com',
				'title'           => __( 'Find us on tumblr' ),
				'default-display' => false,
			),
			'instagram'  => array(
				'link'            => 'https://instagram.com/myusername',
				'title'           => __( 'Check out our instagram feed' ),
				'default-display' => false,
			),
			'flickr'     => array(
				'link'            => 'https://www.flickr.com/photos/myusername/',
				'title'           => __( 'Check out our flickr feed' ),
				'default-display' => false,
			),
			'foursquare' => array(
				'link'            => 'https://foursquare.com/myusername',
				'title'           => __( 'Check out our foursquare feed' ),
				'default-display' => false,
			),
			'mail'       => array(
				'link'            => 'mailto:mail@example.com?subject=Contact%20Request',
				'title'           => __( 'Contact Us' ),
				'default-display' => false,
			),
		);
	}

	$return_list = $provider_list;

	if ( false === $raw ) {
		global $synved_social;

		$list_name = 'provider_list_' . $context;

		if (
			false === isset( $synved_social[ $list_name ] )
			|| null === $synved_social[ $list_name ]
		) {
			$return_list = array();

			foreach ( $provider_list as $provider_name => $provider_item ) {
				$display = synved_option_get( 'synved_social', $provider_name . '_display' );
				$link    = synved_option_get( 'synved_social', $provider_name . '_' . $context . '_link' );
				$title   = synved_option_get( 'synved_social', $provider_name . '_' . $context . '_title' );

				if (
					true === empty( $display )
					|| true === in_array( $display, array( $context, 'both' ), true )
				) {
					if ( false === empty( $link ) ) {
						$provider_item['link'] = $link;
					}

					if ( false === empty( $title ) ) {
						$provider_item['title'] = $title;
					}

					$return_list[ $provider_name ] = $provider_item;
				}
			}

			$synved_social[ $list_name ] = $return_list;
		} else {
			$return_list = $synved_social[ $list_name ];
		}
	}

	return apply_filters( 'synved_social_service_provider_list', $return_list, $context, $raw );
}

/**
 * Get icon skin list array.
 *
 * @return array
 */
function synved_social_icon_skin_list() {
	global $synved_social;

	if ( true === empty( $synved_social['skin_list'] ) ) {
		$path = synved_social_path();
		$uri  = synved_social_path_uri();

		$icons = array(
			'regular' => array(
				'label'  => __( 'Regular' ),
				'image'  => $uri . '/image/social/regular/preview.png',
				'folder' => '/image/social/regular/',
				'path'   => $path . '/image/social/regular/',
				'uri'    => $uri . '/image/social/regular/',
			),
		);

		if ( function_exists( 'synved_social_addon_extra_icons_get' ) ) {
			$icons_extra = synved_social_addon_extra_icons_get();
			$icons       = array_merge( $icons, $icons_extra );
		}

		$icons = apply_filters( 'synved_social_icon_skin_list', $icons );

		foreach ( $icons as $skin_name => $skin ) {
			$icons[ $skin_name ]['name'] = $skin_name;
		}

		$synved_social['skin_list'] = $icons;
	}

	return $synved_social['skin_list'];
}

/**
 * Get icon skin.
 *
 * @param string $name Name string.
 *
 * @return mixed|null
 */
function synved_social_icon_skin_get( $name = null ) {
	$icons = synved_social_icon_skin_list();

	if ( false === empty( $name ) && false === isset( $icons[ $name ] ) ) {
		foreach ( $icons as $skin_name => $skin ) {
			if ( strtolower( $name ) === strtolower( $skin['label'] ) ) {
				$name = $skin_name;

				break;
			}
		}
	}

	if ( true === empty( $name ) || false === isset( $icons[ $name ] ) ) {
		$selected = synved_option_get( 'synved_social', 'icon_skin' );

		$name = $selected;
	}

	if ( isset( $icons[ $name ] ) ) {
		return $icons[ $name ];
	}

	if ( isset( $icons['regular'] ) ) {
		return $icons['regular'];
	}

	return null;
}

/**
 * Get icon skin size list array.
 *
 * @param array $skin Skin array.
 *
 * @return array
 */
function synved_social_icon_skin_get_size_list( $skin ) {
	if ( isset( $skin['size-list'] ) ) {
		return $skin['size-list'];
	}

	if ( isset( $skin['name'] ) ) {
		$skin_fresh = synved_social_icon_skin_get( $skin['name'] );

		if (
			true === isset( $skin['path'] )
			&& true === isset( $skin_fresh['path'] )
			&& $skin['path'] === $skin_fresh['path']
		) {
			if ( isset( $skin_fresh['size-list'] ) ) {
				return $skin_fresh['size-list'];
			}
		}
	}

	$path      = synved_social_path();
	$skin_path = true === isset( $skin['path'] ) ? $skin['path'] : ( $path . '/image/social/regular/' );

	$sizes     = glob( $skin_path . '*', GLOB_ONLYDIR );
	$sizes     = array_map( 'basename', $sizes );
	$size_list = array();

	foreach ( $sizes as $size_dir ) {
		$size_parts = explode( 'x', $size_dir );
		$size_width = (int) $size_parts[0];

		if ( false === empty( $size_width ) ) {
			$size_list[] = $size_width;
		}
	}

	sort( $size_list, SORT_NUMERIC );

	if ( isset( $skin['name'] ) ) {
		global $synved_social;

		$name = $skin['name'];

		// Ensure the global is set.
		synved_social_icon_skin_list();

		if ( true === isset( $synved_social['skin_list'] ) && null !== $synved_social['skin_list'] ) {
			if ( false === isset( $synved_social['skin_list'][ $name ] ) ) {
				$synved_social['skin_list'][ $name ] = array();
			}

			foreach ( $skin as $key => $value ) {
				$synved_social['skin_list'][ $name ][ $key ] = $value;
			}
		}
	}

	return $size_list;
}

/**
 * Get Icon Skin Image List array.
 *
 * @param array    $skin        Skin array.
 * @param array    $name_list   Name list array.
 * @param int|null $forced_size Forced size width.
 *
 * @return array
 */
function synved_social_icon_skin_get_image_list_raw( $skin, $name_list, $forced_size = null ) {
	$path         = synved_social_path();
	$uri          = synved_social_path_uri();
	$skin_default = synved_social_icon_skin_get( 'regular' );

	$skin_default_path = true === isset( $skin_default['path'] ) ? $skin_default['path'] : ( $path . '/image/social/regular/' );
	$skin_default_uri  = true === isset( $skin_default['uri'] ) ? $skin_default['uri'] : ( $uri . '/image/social/regular/' );
	$skin_sel_path     = true === isset( $skin['path'] ) ? $skin['path'] : ( $path . '/image/social/regular/' );
	$skin_sel_uri      = true === isset( $skin['uri'] ) ? $skin['uri'] : ( $uri . '/image/social/regular/' );

	$default_size_list = synved_social_icon_skin_get_size_list( $skin_default );
	$sel_size_list     = synved_social_icon_skin_get_size_list( $skin );

	$skin_list = array(
		array(
			'path' => $skin_sel_path,
			'uri'  => $skin_sel_uri,
			'list' => $sel_size_list,
		),
		array(
			'path' => $skin_default_path,
			'uri'  => $skin_default_uri,
			'list' => $default_size_list,
		),
	);

	$image_list = array();

	foreach ( $name_list as $name ) {
		foreach ( $skin_list as $skin ) {
			$skin_path  = $skin['path'];
			$skin_uri   = $skin['uri'];
			$size_list  = $skin['list'];
			$size_count = count( $size_list );

			$image_size_list         = array();
			$image_size_list_skipped = array();

			for ( $i = 0; $i < $size_count; $i++ ) {
				$image_size = $size_list[ $i ];
				$image_sub  = '/' . $name . '.png';
				$size_name  = $image_size . 'x' . $image_size;
				$image_path = $skin_path . $size_name . $image_sub;
				$image_uri  = $skin_uri . $size_name . $image_sub;

				if ( true === file_exists( $image_path ) ) {
					$image_size_meta = array(
						'name' => $size_name,
						'sub'  => $image_sub,
						'path' => $image_path,
						'uri'  => $image_uri,
					);

					foreach ( $image_size_list_skipped as $image_size_skipped ) {
						$image_size_list[ $image_size_skipped ] = $image_size_meta;
					}

					$image_size_skipped             = array();
					$image_size_list[ $image_size ] = $image_size_meta;

					if (
						false === empty( $forced_size )
						&& ( $image_size > $forced_size || $i === $size_count - 1 )
						&& false === isset( $image_size_list[ $forced_size ] )
					) {
						$image_size_list[ $forced_size ] = $image_size_meta;
					}
				} else {
					$image_size_skipped[] = $image_size;
				}
			}

			if ( false === empty( $image_size_list ) ) {
				break;
			}
		}

		$image_list[ $name ] = $image_size_list;
	}

	return $image_list;
}

/**
 * Get filtered Icon skin image list array.
 *
 * @param array $skin        Skin array.
 * @param array $name_list   Name list array.
 * @param int   $forced_size Forced size int.
 *
 * @return array Image list.
 */
function synved_social_icon_skin_get_image_list( $skin, $name_list, $forced_size = null ) {
	$image_list = synved_social_icon_skin_get_image_list_raw( $skin, $name_list, $forced_size );
	$image_list = apply_filters( 'synved_social_skin_image_list', $image_list, $skin, $name_list, $forced_size );

	return $image_list;
}

/**
 * Get Button list shortcode HTML.
 *
 * @param array       $atts    Attributes.
 * @param string      $content Content string.
 * @param string      $code    Code string.
 * @param string|null $context Context string.
 *
 * @return string
 */
function synved_social_button_list_shortcode( $atts, $content = null, $code = '', $context = null ) {
	$vars_def   = array(
		'url'     => null,
		'image'   => null,
		'title'   => null,
		'message' => null,
	);
	$params_def = array(
		'skin'           => null,
		'size'           => null,
		'spacing'        => null,
		'container'      => null,
		'container_type' => null,
		'class'          => null,
		'show'           => null,
		'hide'           => null,
		'prompt'         => null,
		'custom1'        => null,
		'custom2'        => null,
		'custom3'        => null,
	);
	$vars       = shortcode_atts( $vars_def, $atts, 'feather_' . $context );
	$params     = shortcode_atts( $params_def, $atts, 'feather_' . $context );
	$vars       = array_filter( $vars );
	$params     = array_filter( $params );

	$vars   = apply_filters( 'synved_social_shortcode_variable_list', $vars, $context, $atts );
	$params = apply_filters( 'synved_social_shortcode_parameter_list', $params, $context, $atts );

	if ( 'share' === $context ) {
		return synved_social_share_markup( $vars, null, $params );
	} elseif ( 'follow' === $context ) {
		return synved_social_follow_markup( $vars, null, $params );
	}

	return '';
}

/**
 * Get Share shortcode HTML output.
 *
 * @param array  $atts    Attributes array.
 * @param string $content Content string.
 * @param string $code    Code string.
 *
 * @return string
 */
function synved_social_share_shortcode( $atts, $content = null, $code = '' ) {
	return synved_social_button_list_shortcode( $atts, $content, $code, 'share' );
}

/**
 * Get Follow shortcode HTML output.
 *
 * @param array  $atts    Attributes array.
 * @param string $content Content string.
 * @param string $code    Code string.
 *
 * @return string
 */
function synved_social_follow_shortcode( $atts, $content = null, $code = '' ) {
	return synved_social_button_list_shortcode( $atts, $content, $code, 'follow' );
}

/**
 * Button list markup item.
 *
 * Escaped via wp_kses_post.
 *
 * @param array $out_item Out item array.
 *
 * @return string
 */
function synved_social_button_list_markup_item_out( $out_item ) {
	$out     = null;
	$tag     = isset( $out_item['tag'] ) ? $out_item['tag'] : null;
	$content = isset( $out_item['content'] ) ? $out_item['content'] : null;
	$list    = isset( $out_item['child-list'] ) ? $out_item['child-list'] : null;

	unset( $out_item['tag'] );
	unset( $out_item['content'] );
	unset( $out_item['child-list'] );

	if ( false === empty( $tag ) ) {
		$out .= '<' . $tag;

		foreach ( $out_item as $attr_name => $attr_value ) {
			if ( null !== $attr_name && null !== $attr_value ) {
				if ( true === in_array( $attr_name, array( 'href', 'src' ), true ) ) {
					$attr_value = str_ireplace( array( '[', ']' ), array( '&#91;', '&#93;' ), $attr_value );
					$attr_value = esc_url( $attr_value );
				} else {
					$attr_value = esc_attr( $attr_value );
				}

				$out .= ' ' . $attr_name . '="' . $attr_value . '"';
			}
		}

		if ( false === empty( $list ) || false === empty( $content ) ) {
			$out .= '>';
		} else {
			$out .= ' />';
		}
	}

	if ( false === empty( $list ) ) {
		foreach ( $list as $child ) {
			$out .= synved_social_button_list_markup_item_out( $child );
		}
	}

	if ( false === empty( $content ) ) {
		$out .= $content;
	}

	if (
		false === empty( $tag )
		&& (
			false === empty( $list )
			|| false === empty( $content ) )
	) {
		$out .= '</' . $tag . '>';
	}

	return wp_kses_post( $out );
}

/**
 * Button list markup.
 *
 * Escaped via wp_kses_post.
 *
 * @param string     $context Context string.
 * @param array|null $vars    Variables array.
 * @param array|null $buttons Buttons array.
 * @param array|null $params  Parameters array.
 *
 * @return string
 */
function synved_social_button_list_markup( $context, $vars = null, $buttons = null, $params = null ) {
	$buttons_default = synved_social_service_provider_list( $context );

	if ( true === empty( $buttons ) ) {
		$buttons = $buttons_default;
	} else {
		$keys = array_keys( $buttons );

		foreach ( $keys as $key ) {
			if ( true === empty( $buttons[ $key ] ) && true === isset( $buttons_default[ $key ] ) ) {
				$buttons[ $key ] = $buttons_default[ $key ];
			}
		}
	}

	$id = get_the_ID();

	if ( true === empty( $id ) ) {
		global $post;

		if ( false === empty( $post ) ) {
			$id = $post->ID;
		}
	}

	if ( false === isset( $vars['url'], $vars['short_url'] ) ) {
		$home_url = home_url();
		$req_uri  = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING );

		$path     = wp_parse_url( $home_url, PHP_URL_PATH );
		$path_len = strlen( $path );

		if ( strtolower( substr( $req_uri, 0, $path_len ) ) === strtolower( $path ) ) {
			$req_uri = substr( $req_uri, $path_len );
		}

		$url       = home_url( $req_uri );
		$short_url = $url;

		if ( false === empty( $id ) && true === in_the_loop() ) {
			$use_shortlinks = boolval( synved_option_get( 'synved_social', 'use_shortlinks' ) );
			$url            = get_permalink( $id );
			$short_url      = wp_get_shortlink( $id );

			if ( false === empty( $short_url ) ) {
				if ( true === $use_shortlinks && function_exists( 'wp_get_shortlink' ) ) {
					$url = $short_url;
				}
			} else {
				$short_url = $url;
			}
		} elseif ( true === is_front_page() ) {
			$url = $home_url;
		}

		if ( false === isset( $vars['url'] ) ) {
			$vars['url'] = $url;
		}

		if ( false === isset( $vars['short_url'] ) ) {
			$vars['short_url'] = $short_url;
		}
	}

	if ( false === isset( $vars['image'] ) ) {
		$image_src = null;

		if ( false === empty( $id ) ) {
			$image_id = get_post_thumbnail_id( $id );

			if ( false === empty( $image_id ) ) {
				$src       = wp_get_attachment_image_src( $image_id, 'full' );
				$image_src = $src[0];
			} else {
				$the_post = get_post( $id );
				$match    = null;

				if ( false !== preg_match( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $the_post->post_content, $match ) > 0 ) {
					$image_src = $match[1];
				}
			}
		}

		$vars['image'] = $image_src;
	}

	if ( false === isset( $vars['title'] ) ) {
		$title = get_the_title();
		// Do this encoding to prevent non-tags things, like emoticons, from being stripped, i.e. <8.
		$title         = preg_replace( '/\\<\\s*([^[:alpha:]\\/])/', '&lt;$1', $title );
		$vars['title'] = html_entity_decode( wp_strip_all_tags( $title ) );
	}

	if ( false === isset( $vars['message'] ) ) {
		$message = synved_option_get( 'synved_social', 'share_message_default' );

		if ( true === empty( $message ) ) {
			$message = __( 'Hey check this out', 'social-media-feather' );
		}

		$vars['message'] = $message;
	}

	if ( false === isset( $vars['author_wp'] ) ) {
		if ( false === empty( $id ) && true === in_the_loop() ) {
			$author = get_the_author();

			if ( false === empty( $author ) ) {
				$vars['author_wp'] = $author;
			}
		}
	}

	if ( true === isset( $vars['author'] ) ) {
		if ( false === empty( $id ) && true === in_the_loop() ) {
			$author = get_the_author_meta( '_synved_twitter_handle' );

			if ( true === empty( $author ) ) {
				$author = get_the_author_meta( 'twitter' );
			}

			if ( true === empty( $author ) ) {
				$author = get_the_author();
			}

			if ( false === empty( $author ) ) {
				$vars['author'] = $author;
			}
		}
	}

	if ( false === isset( $vars['date'] ) ) {
		if ( false === empty( $id ) && true === in_the_loop() ) {
			$date = get_the_date( '', $id );

			if ( false === empty( $date ) ) {
				$vars['date'] = $date;
			}
		}
	}

	if ( true === isset( $vars['url'] ) && false === isset( $vars['url_trimmed'] ) ) {
		$url_trimmed = trim( $vars['url'] );

		while ( substr( $url_trimmed, -1 ) === '/' ) {
			$url_trimmed = substr( $url_trimmed, 0, -1 );
		}

		while ( strtolower( substr( $url_trimmed, -3 ) ) === '%2f' ) {
			$url_trimmed = substr( $url_trimmed, 0, -3 );
		}

		$vars['url_trimmed'] = $url_trimmed;
	}

	if ( true === isset( $params['class'] ) && false === is_array( $params['class'] ) ) {
		$class           = explode( ' ', $params['class'] );
		$params['class'] = array_map( 'trim', $class );
	}

	if ( true === isset( $params['show'] ) && false === is_array( $params['show'] ) ) {
		$show           = explode( ',', $params['show'] );
		$params['show'] = array_map( 'trim', $show );
	}

	if ( true === isset( $params['hide'] ) && false === is_array( $params['hide'] ) ) {
		$hide           = explode( ',', $params['hide'] );
		$params['hide'] = array_map( 'trim', $hide );
	}

	$vars   = apply_filters( 'synved_social_markup_variable_list', $vars, $context, $params );
	$params = apply_filters( 'synved_social_markup_parameter_list', $params, $context, $vars );

	if ( false === empty( $vars ) ) {
		$vars = array_map( 'wp_kses_decode_entities', $vars );
		$vars = urlencode_deep( $vars );

		// NOTE: urlencode_deep converts space characters to + rather than %20 which messes things up.
		$vars['message'] = str_ireplace( '+', '%20', $vars['message'] );
		$vars['title']   = str_ireplace( '+', '%20', $vars['title'] );

		// NOTE: urlencode_deep tries to be smart and apostrophes (') to %19 not %27 and double quotes (") to their equivalent open/closed counterparts which doesn't work on most social networks sharings.
		$vars['message'] = str_ireplace( '%18', '%27', $vars['message'] );
		$vars['title']   = str_ireplace( '%18', '%27', $vars['title'] );
		$vars['message'] = str_ireplace( '%19', '%27', $vars['message'] );
		$vars['title']   = str_ireplace( '%19', '%27', $vars['title'] );
		$vars['message'] = str_ireplace( '%1c', '%22', $vars['message'] );
		$vars['title']   = str_ireplace( '%1c', '%22', $vars['title'] );
		$vars['message'] = str_ireplace( '%1d', '%22', $vars['message'] );
		$vars['title']   = str_ireplace( '%1d', '%22', $vars['title'] );
	}

	$path = synved_social_path();
	$uri  = synved_social_path_uri();
	$skin = synved_social_icon_skin_get();

	if ( true === isset( $params['skin'] ) ) {
		$skin = synved_social_icon_skin_get( $params['skin'] );
	}

	$skin_path = true === isset( $skin['path'] ) ? $skin['path'] : ( $path . '/image/social/regular/' );
	$skin_uri  = true === isset( $skin['uri'] ) ? $skin['uri'] : ( $uri . '/image/social/regular/' );

	$icon_size = synved_option_get( 'synved_social', 'icon_size' );
	$size      = 48;

	if ( false === empty( $icon_size ) ) {
		$size = $icon_size;
	}

	if ( true === isset( $params['size'] ) ) {
		$size = $params['size'];

		if ( is_string( $size ) ) {
			$size       = strtolower( $size );
			$size_parts = explode( 'x', $size );
			$size       = (int) $size_parts[0];
		}
	}

	$icon_spacing           = synved_option_get( 'synved_social', 'icon_spacing' );
	$buttons_container      = synved_option_get( 'synved_social', 'buttons_container' );
	$buttons_container_type = synved_option_get( 'synved_social', 'buttons_container_type' );
	$buttons_alignment      = synved_option_get( 'synved_social', 'buttons_alignment_' . $context );
	$layout_rtl             = boolval( synved_option_get( 'synved_social', 'layout_rtl' ) );
	$spacing                = 5;
	$container              = 'none';
	$container_type         = 'basic';
	$alignment              = 'none';

	if ( false === empty( $icon_spacing ) ) {
		$spacing = $icon_spacing;
	}

	if ( true === isset( $params['spacing'] ) ) {
		$spacing = $params['spacing'];
	}

	if ( false === empty( $buttons_container ) ) {
		$container = $buttons_container;
	}

	if ( false === empty( $buttons_container_type ) ) {
		$container_type = $buttons_container_type;
	}

	if ( false === empty( $buttons_alignment ) ) {
		$alignment = $buttons_alignment;
	}

	if ( true === isset( $params['alignment'] ) ) {
		$alignment = $params['alignment'];
	}

	if ( 'none' !== $alignment ) {
		if ( 'none' === $container ) {
			$container = $context;
		} elseif ( 'both' !== $container && $container !== $context ) {
			$container = 'both';
		}
	}

	// Allow parameters to override container after we decide a default based on selected alignment.
	if ( true === isset( $params['container'] ) ) {
		$container = $params['container'];
	}

	if ( 'none' !== $alignment ) {
		$container_type = 'block';
	}

	// Allow parameters to override container after we decide a default based on selected alignment.
	if ( true === isset( $params['container_type'] ) ) {
		$container_type = $params['container_type'];
	}

	$class = true === isset( $params['class'] ) ? $params['class'] : null;
	$show  = true === isset( $params['show'] ) ? $params['show'] : null;
	$hide  = true === isset( $params['hide'] ) ? $params['hide'] : null;

	if ( false === empty( $show ) ) {
		$button_list = array();

		foreach ( $show as $button_key ) {
			if ( true === isset( $buttons[ $button_key ] ) ) {
				$button_list[ $button_key ] = $buttons[ $button_key ];

				unset( $buttons[ $button_key ] );
			}
		}

		foreach ( $buttons as $button_key => $button_item ) {
			$button_list[ $button_key ] = $button_item;
		}

		$buttons = $button_list;
	}

	if ( false === empty( $hide ) ) {
		foreach ( $hide as $button_key ) {
			if ( true === isset( $buttons[ $button_key ] ) ) {
				unset( $buttons[ $button_key ] );
			}
		}
	}

	$out_list        = array();
	$out_params      = array();
	$image_list      = array();
	$icon_resolution = synved_option_get( 'synved_social', 'icon_resolution' );
	$resolutions     = array(
		'normal' => $size,
		'hidef'  => $size * 2,
	);

	if ( true === is_feed() ) {
		$icon_resolution = 'single';
	}

	if ( 'single' === $icon_resolution ) {
		$resolutions = array( 'single' => $size * 2 );
	}

	$button_keys = array_keys( $buttons );

	foreach ( $resolutions as $resolution_name => $resolution_size ) {
		$image_list[ $resolution_name ] = synved_social_icon_skin_get_image_list(
			$skin,
			$button_keys,
			$resolution_size
		);
	}

	$index = 0;
	$count = count( $buttons );

	foreach ( $buttons as $button_key => $button_item ) {
		$href  = $button_item['link'];
		$title = $button_item['title'];

		$matches = null;

		if ( preg_match_all( '/%%(\\w+)%%/', $href, $matches, PREG_SET_ORDER ) > 0 ) {
			foreach ( $matches as $match ) {
				$var_key = $match[1];
				$replace = null;

				if ( isset( $vars[ $var_key ] ) ) {
					$replace = $vars[ $var_key ];
				}

				$href = str_replace( $match[0], $replace, $href );
			}
		}

		$icon_sizes = $resolutions;

		foreach ( $icon_sizes as $icon_def => $icon_size ) {
			$image      = $image_list[ $icon_def ][ $button_key ];
			$image_size = $image[ $icon_size ];
			$image_sub  = $image_size['sub'];
			$image_path = $image_size['path'];
			$image_uri  = $image_size['uri'];

			if ( false === file_exists( $image_path ) ) {
				$size_list  = array_keys( $image );
				$image_path = apply_filters(
					'synved_social_button_image_path',
					$image_path,
					$image_uri,
					$icon_size,
					$image_sub,
					$skin_path,
					$skin_uri,
					$size_list
				);
				$image_uri  = apply_filters(
					'synved_social_button_image_uri',
					$image_uri,
					$image_path,
					$icon_size,
					$image_sub,
					$skin_path,
					$skin_uri,
					$size_list
				);
			}

			$style  = 'margin:0;';
			$style .= 'margin-bottom:' . $spacing . 'px;';

			if ( $index < $count - 1 || false === empty( $layout_rtl ) ) {
				$style .= 'margin-right:' . $spacing . 'px;';
			}

			$class_extra = null;

			if ( false === empty( $class ) ) {
				$class_extra = ' ' . implode( ' ', $class );
			}

			// Don't use "nofancybox" because some plugins/themes interpret it as enabling fancybox.
			$class_extra .= ' nolightbox';

			$out_button = array(
				'tag'           => 'a',
				'class'         => 'synved-social-button synved-social-button-' . $context . ' synved-social-size-' . $size . ' synved-social-resolution-' . $icon_def . ' synved-social-provider-' . $button_key . $class_extra,
				'data-provider' => $button_key,
				'target'        => 'mail' !== $button_key ? '_blank' : null,
				'rel'           => 'nofollow',
				'title'         => $title,
				'href'          => $href,
				'style'         => 'font-size: 0px; width:' . $size . 'px; height:' . $size . 'px;' . $style,
				'child-list'    => array(
					array(
						'tag'    => 'img',
						'alt'    => 'facebook' === $button_key ? 'Facebook' : $button_key,
						'title'  => $title,
						'class'  => 'synved-share-image synved-social-image synved-social-image-' . $context,
						'width'  => $size,
						'height' => $size,
						'style'  => 'display: inline; width:' . $size . 'px; height:' . $size . 'px; margin: 0; padding: 0; border: none; box-shadow: none;',
						'src'    => $image_uri,
					),
				),
			);

			$out_list[ $icon_def ][ $button_key ]   = $out_button;
			$out_params[ $icon_def ][ $button_key ] = array( 'icon-resolution' => $icon_def );
		}

		$index++;
	}

	$out = null;

	if ( false === empty( $out_list ) ) {
		foreach ( $out_list as $def_key => $def_list ) {
			$out_list[ $def_key ] = apply_filters(
				'synved_social_button_list_markup',
				$def_list,
				$out_params[ $def_key ],
				$context,
				$vars,
				$params
			);
		}
	}

	if ( false === empty( $out_list ) ) {
		$container_tag = 'span';

		if ( 'block' === $container_type ) {
			$container_tag = 'div';
		}

		if ( 'none' !== $container && ( 'both' === $container || $container === $context ) ) {
			$container_style = 'none' !== $alignment ? ' style="text-align: ' . $alignment . '"' : null;

			$out .= '<' . $container_tag . ' class="synved-social-container synved-social-container-' . $context . '"' . $container_style . '>';
		}

		foreach ( $out_list as $def_list ) {
			foreach ( $def_list as $out_item ) {
				$out .= synved_social_button_list_markup_item_out( $out_item );
			}
		}

		if ( false !== boolval( synved_option_get( 'synved_social', 'show_credit', false ) ) ) {
			$out .= '<a class="synved-social-credit" target="_blank" rel="nofollow" title="' . __(
				'WordPress Social Media Feather',
				'social-media-feather'
			) . '" href="http://synved.com/wordpress-social-media-feather/" style="color:#444; text-decoration:none; font-size:8px; margin-left:5px;vertical-align:10px;white-space:nowrap;"><span>' . __(
				'by ',
				'social-media-feather'
			) . '</span><img style="display: inline;margin:0;padding:0;width:16px;height:16px;" width="16" height="16" alt="feather" src="' . $uri . '/image/icon.png" /></a>';
		}

		if ( 'none' !== $container && ( 'both' === $container || $container === $context ) ) {
			$out .= '</' . $container_tag . '>';
		}
	}

	return wp_kses_post( $out );
}

/**
 * Share markup.
 *
 * @param array|null $vars    Variables array.
 * @param array|null $buttons Buttons array.
 * @param array|null $params  Parameters array.
 *
 * @return string
 */
function synved_social_share_markup( $vars = null, $buttons = null, $params = null ) {
	return synved_social_button_list_markup( 'share', $vars, $buttons, $params );
}

/**
 * Follow markup.
 *
 * @param array|null $vars    Variables array.
 * @param array|null $buttons Buttons array.
 * @param array|null $params  Parameters array.
 *
 * @return string
 */
function synved_social_follow_markup( $vars = null, $buttons = null, $params = null ) {
	return synved_social_button_list_markup( 'follow', $vars, $buttons, $params );
}
