<?php
/**
 * Option callback class.
 *
 * @package SocialMediaFeather
 */

define( 'SYNVED_OPTION_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once SYNVED_OPTION_PLUGIN_PATH . 'synved-option-item.php';
require_once SYNVED_OPTION_PLUGIN_PATH . 'synved-option-page.php';
require_once SYNVED_OPTION_PLUGIN_PATH . 'synved-option-section.php';
require_once SYNVED_OPTION_PLUGIN_PATH . 'synved-option-render.php';
require_once SYNVED_OPTION_PLUGIN_PATH . 'synved-option-setting.php';
require_once SYNVED_OPTION_PLUGIN_PATH . 'inc/class-synvedoptioncallback.php';

$synved_option      = array();
$synved_option_list = array();

/**
 * Option callback.
 *
 * @param callable   $callback            Callable callback fucntion.
 * @param mixed|null $default             Default value.
 * @param array|null $callback_parameters Callback paremeters.
 *
 * @return SynvedOptionCallback
 */
function synved_option_callback( $callback, $default = null, $callback_parameters = null ) {
	$object = null;
	$func   = $callback;

	if ( is_array( $callback ) ) {
		$object = $callback[0];
		$func   = $callback[1];
	}

	return new SynvedOptionCallback( $func, $object, $default, $callback_parameters );
}

/**
 * Register option.
 *
 * @param string $id      ID string.
 * @param array  $options Options array.
 *
 * @return void
 */
function synved_option_register( $id, array $options ) {
	global $synved_option_list;

	$synved_option_list[ $id ] = array(
		'options'  => $options,
		'items'    => array(),
		'names'    => array(),
		'groups'   => array(),
		'pages'    => array(),
		'sections' => array(),
		'outputs'  => array(),
	);
}

/**
 * Option item list.
 *
 * @param string $id ID string.
 *
 * @return array|mixed|null
 */
function synved_option_item_list( $id ) {
	global $synved_option_list;

	if ( true === isset( $synved_option_list[ $id ] ) ) {
		$list = false === empty( $synved_option_list[ $id ]['items'] ) ? $synved_option_list[ $id ]['items'] : null;

		if ( true === empty( $list ) ) {
			$list                               = synved_option_prepare_list( $id );
			$synved_option_list[ $id ]['items'] = $list;
		}

		return $list;
	}

	return null;
}

/**
 * Prepare option list.
 *
 * @param string $id ID string.
 *
 * @return array|null
 */
function synved_option_prepare_list( $id ) {
	global $synved_option_list;

	if ( true === isset( $synved_option_list[ $id ] ) ) {
		$options = false === empty( $synved_option_list[ $id ]['options'] ) ? $synved_option_list[ $id ]['options'] : array();
		$options = apply_filters( 'synved_option_init_list', $options, $id );
		$options = apply_filters( 'synved_option_init_list_' . $id, $options, $id );

		$final_list      = array();
		$default_page    = null;
		$default_section = null;

		foreach ( $options as $name => $item ) {
			$type = synved_option_item_type( $item );

			if ( 'options-page' === $type ) {
				$item = synved_option_prepare_list_item( $id, null, null, $name, $item );

				if ( false === empty( $item ) ) {
					$final_list[ $name ] = $item;
				}
			} else {
				if ( true === empty( $default_page ) ) {
					$default_page = synved_option_page_default( $id );
					$default_page = synved_option_prepare_list_item(
						$id,
						null,
						null,
						$default_page['name'],
						$default_page
					);

					$final_list[ $default_page['name'] ] = &$default_page;
				}

				if ( 'options-section' === $type ) {
					$item = synved_option_prepare_list_item( $id, $default_page['name'], null, $name, $item );

					if ( false === empty( $item ) ) {
						$default_page['sections'][ $name ] = $item;
					}
				} else {
					if ( true === empty( $default_section ) ) {
						$default_section = synved_option_section_default( $id, $default_page['name'] );
						$default_section = synved_option_prepare_list_item(
							$id,
							$default_page['name'],
							null,
							$default_section['name'],
							$default_section
						);

						$default_page['sections'][ $default_section['name'] ] = &$default_section;
					}

					$default_section['settings'][ $name ] = $item;
				}
			}
		}

		if ( false === empty( $default_page ) ) {
			$item = $default_page;
			$name = $item['name'];
			$item = synved_option_prepare_list_item( $id, null, null, $name, $item );

			if ( false === empty( $item ) ) {
				$final_list[ $name ] = $item;
			}
		}

		return $final_list;
	}

	return null;
}

/**
 * Prepare option list item.
 *
 * @param string $id      ID string.
 * @param string $page    Page sring.
 * @param string $section Section string.
 * @param string $name    Name string.
 * @param array  $item    Item array.
 *
 * @return array
 */
function synved_option_prepare_list_item( $id, $page, $section, $name, array $item ) {
	global $synved_option_list;

	$type     = synved_option_item_type( $item );
	$sections = isset( $item['sections'] ) ? $item['sections'] : null;
	$settings = isset( $item['settings'] ) ? $item['settings'] : null;

	$item['_synved_option_id']   = $id;
	$item['_synved_option_name'] = $name;

	if ( 'options-page' === $type ) {
		if ( false === empty( $sections ) ) {
			$list = $sections;

			foreach ( $list as $child_name => $child_item ) {
				$child_item = synved_option_prepare_list_item( $id, $name, null, $child_name, $child_item );

				if ( false === empty( $child_item ) ) {
					$list[ $child_name ] = $child_item;
				}
			}

			$item['sections'] = $list;
		}

		$synved_option_list[ $id ]['pages'][ $name ] = $item;
	} elseif ( 'options-section' === $type ) {
		if ( false === empty( $settings ) ) {
			$list = $settings;

			foreach ( $list as $child_name => $child_item ) {
				$child_item = synved_option_prepare_list_item( $id, $page, $name, $child_name, $child_item );

				if ( false === empty( $child_item ) ) {
					$list[ $child_name ] = $child_item;
				}
			}

			$item['settings'] = $list;
		}

		$synved_option_list[ $id ]['sections'][ $name ] = $item;
	} elseif ( true === in_array( $type, array( 'style', 'script' ), true ) ) {
		$synved_option_list[ $id ]['outputs'][ $name ] = $item;
	}

	return $item;
}

/**
 * Option value list for ID.
 *
 * @param string $id ID string.
 *
 * @return array|mixed
 */
function synved_option_value_list( $id ) {
	global $synved_option_list;

	if ( false === isset( $synved_option_list[ $id ]['values'] )
		|| null === $synved_option_list[ $id ]['values'] ) {
		$options = get_option( synved_option_name_default( $id ) );

		if ( false === is_null( $options ) && true === is_array( $options ) ) {
			$synved_option_list[ $id ]['values'] = $options;
		} else {
			return array();
		}
	}

	return $synved_option_list[ $id ]['values'];
}

/**
 * Option get.
 *
 * @param string $id      ID string.
 * @param string $name    Name string.
 * @param string $default Default value string.
 *
 * @return mixed
 */
function synved_option_get( $id, $name, $default = null ) {
	$options = synved_option_value_list( $id );
	$value   = true === isset( $options[ $name ] ) ? $options[ $name ] : null;
	$item    = synved_option_item( $id, $name );

	if ( false === isset( $options[ $name ] ) && null !== $default ) {
		$value = $default;
	}

	if ( false === empty( $item ) ) {
		$value = synved_option_item_sanitize_value( $id, $name, $value, $item );
	} elseif ( false === empty( $default ) ) {
		$value = $default;
	}

	return $value;
}

/**
 * Option set.
 *
 * @param string $id    ID string.
 * @param string $name  Name string.
 * @param string $value Value string.
 *
 * @return void
 */
function synved_option_set( $id, $name, $value ) {
	global $synved_option_list;

	$options_name = synved_option_name_default( $id );
	$options      = get_option( $options_name );

	$options[ $name ] = synved_option_item_sanitize_value( $id, $name, $value );
	update_option( $options_name, $options );

	unset( $synved_option_list[ $id ]['values'] );
}

/**
 * Option label from ID.
 *
 * @param string $id ID string.
 *
 * @return string
 */
function synved_option_label_from_id( $id ) {
	return ucwords( str_replace( '_', ' ', $id ) );
}

/**
 * Option name default.
 *
 * @param string $id ID string.
 *
 * @return string
 */
function synved_option_name_default( $id ) {
	global $synved_option_list;

	$name = $id . '_settings';

	if ( false === isset( $synved_option_list[ $id ]['names'][ $name ] ) ) {
		$synved_option_list[ $id ]['names'][ $name ] = array(
			'type'  => 'name',
			'label' => synved_option_label_from_id( $id ),
		);
	}

	return $name;
}

/**
 * Option group default.
 *
 * @param string $id ID string.
 *
 * @return string
 */
function synved_option_group_default( $id ) {
	global $synved_option_list;

	$group = $id . '_settings_group';

	if ( false === isset( $synved_option_list[ $id ]['groups'][ $group ] ) ) {
		$synved_option_list[ $id ]['groups'][ $group ] = array(
			'type'  => 'group',
			'label' => synved_option_label_from_id( $id ),
		);
	}

	return $group;
}

/**
 * Get allowed HTML array for Synved forms.
 *
 * @return array
 */
function synved_get_allowed_html_array() {
	return array(
		'a'        => array(
			'class'  => array(),
			'href'   => array(),
			'style'  => array(),
			'target' => array(),
			'title'  => array(),
		),
		'b'        => array(),
		'br'       => array(),
		'div'      => array(
			'class' => array(),
			'id'    => array(),
			'style' => array(),
		),
		'fieldset' => array(),
		'form'     => array(
			'action' => array(),
			'method' => array(),
		),
		'h1'       => array(),
		'h2'       => array(),
		'h3'       => array(),
		'img'      => array(
			'src'   => array(),
			'style' => array(),
		),
		'input'    => array(
			'class'       => array(),
			'checked'     => array(),
			'id'          => array(),
			'name'        => array(),
			'size'        => array(),
			'placeholder' => array(),
			'type'        => array(),
			'value'       => array(),
		),
		'label'    => array(
			'class' => array(),
			'for'   => array(),
			'title' => array(),
		),
		'legend'   => array(
			'class' => array(),
		),
		'meta'     => array(
			'content'  => array(),
			'property' => array(),
		),
		'option'   => array(
			'selected' => array(),
			'value'    => array(),
		),
		'p'        => array(
			'align' => array(),
			'class' => array(),
			'style' => array(),
		),
		'script'   => array(
			'type' => array(),
		),
		'select'   => array(
			'id'       => array(),
			'name'     => array(),
			'multiple' => array(),
		),
		'span'     => array(
			'class' => array(),
			'style' => array(),
		),
		'strong'   => array(),
		'style'    => array(
			'type' => array(),
		),
		'table'    => array(
			'class' => array(),
			'role'  => array(),
		),
		'td'       => array(),
		'tr'       => array(),
		'th'       => array(
			'scope' => array(),
		),
	);
}

/**
 * WP Handle Setting.
 *
 * @param string $id      ID string.
 * @param string $page    Page string.
 * @param string $section Section string.
 * @param string $name    Name string.
 * @param array  $item    Item array.
 *
 * @return void
 */
function synved_option_wp_handle_setting( $id, $page, $section, $name, $item ) {
	$type     = synved_option_item_type( $item );
	$label    = synved_option_item_label( $item );
	$sections = true === isset( $item['sections'] ) ? $item['sections'] : null;
	$settings = true === isset( $item['settings'] ) ? $item['settings'] : null;

	if ( 'options-page' === $type ) {
		if ( false === empty( $sections ) ) {
			$page_slug = synved_option_page_slug( $id, $name, $item );

			foreach ( $sections as $child_name => $child_item ) {
				synved_option_wp_handle_setting( $id, $page_slug, null, $child_name, $child_item );
			}
		}
	} elseif ( 'options-section' === $type ) {
		add_settings_section(
			$name,
			$label,
			function () use ( $name, $id ) {
				$item = synved_option_item_find( $id, $name );
				$tip  = synved_option_item_tip( $item );

				if ( false === empty( $tip ) ) {
					echo wp_kses( '<p>' . esc_html( $tip ) . '</p>', synved_get_allowed_html_array() );
				}
			},
			$page
		);

		if ( false === empty( $settings ) ) {
			foreach ( $settings as $child_name => $child_item ) {
				synved_option_wp_handle_setting( $id, $page, $name, $child_name, $child_item );
			}
		}
	} else {
		add_settings_field(
			$name,
			$label,
			'synved_option_call_array',
			$page,
			$section,
			array( 'synved_option_setting_cb', array( $id, $name, $item ) )
		);
	}
}

/**
 * Include module addon list.
 *
 * @param string      $module_id Module ID.
 * @param string|null $filter    Filter to run.
 *
 * @return void
 */
function synved_option_include_module_addon_list( $module_id, $filter = null ) {
	global $synved_option;

	$synved_option['module-addon-list'][] = array(
		'module-id' => $module_id,
		'filter'    => $filter,
	);
}

/**
 * Option init.
 *
 * @return void
 */
function synved_option_init() {
	global $synved_option_list;

	if ( false === empty( $synved_option_list ) ) {
		foreach ( $synved_option_list as $id => $list ) {
			$items = synved_option_item_list( $id );
		}
	}

	$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

	if ( false === empty( $action ) && 'synved_option' === $action ) {
		ob_start();
	}
}

/**
 * Call array.
 *
 * @param array $args Arguments array.
 *
 * @return void
 */
function synved_option_call_array( $args ) {
	call_user_func_array( $args[0], $args[1] );
}

/**
 * Option path URI.
 *
 * @param string|null $path Path string.
 *
 * @return string
 */
function synved_option_path_uri( $path = null ) {
	$uri = plugins_url( '/synved-options' ) . '/synved-option';

	if ( function_exists( 'synved_plugout_module_uri_get' ) ) {
		$mod_uri = synved_plugout_module_uri_get( 'synved-option' );

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
 * Print head outputs.
 *
 * @return void
 */
function synved_option_print_head_outputs() {
	global $synved_option_list;

	$output = '';

	foreach ( $synved_option_list as $id => $list ) {
		$items   = synved_option_item_list( $id );
		$outputs = $list['outputs'];

		foreach ( $outputs as $name => $item ) {
			$type = synved_option_item_type( $item );
			$mode = synved_option_item_mode( $item );

			if ( true === in_array( 'manual', $mode, true ) ) {
				continue;
			}

			$content = synved_option_get( $id, $name );
			$tag     = null;
			$attrs   = null;

			if ( 'style' === $type ) {
				$tag           = $type;
				$attrs['type'] = 'text/css';
			} elseif ( 'script' === $type ) {
				$tag           = $type;
				$attrs['type'] = 'text/javascript';

				$content = '/* <![CDATA[ */' . "\r\n" . $content . "\r\n" . '/* ]]> */';
			}

			if ( false === empty( $tag ) ) {
				$output .= "\r\n" . '<' . $tag;

				foreach ( $attrs as $attr_name => $attr_value ) {
					$output .= ' ' . esc_attr( $attr_name ) . '="' . esc_attr( $attr_value ) . '"';
				}

				$output .= '>';
				$output .= $content;
				$output .= '</' . $tag . '>' . "\r\n";
			}
		}
	}

	$fb_app_id = synved_option_get( 'synved_social', 'fb_app_id' );

	if ( false === empty( $fb_app_id ) ) {
		$output .= sprintf( '<meta property="fb:app_id" content="%s" />', esc_attr( $fb_app_id ) );
		$output .= '<script>
			window.fbAsyncInit = function() {
				FB.init({
					appId      : ' . esc_attr( $fb_app_id ) . ',
					xfbml      : true,
					version    : \'v2.8\'
				});
				FB.AppEvents.logPageView();
			};

			(function(d, s, id){
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {return;}
				js = d.createElement(s); js.id = id;
				js.src = "https://connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, \'script\', \'facebook-jssdk\'));
		</script>';
	}

	echo wp_kses( $output, synved_get_allowed_html_array() );
}

/**
 * After setup theme.
 *
 * @return void
 */
function synved_option_wp_after_setup_theme() {
	global $synved_option;

	foreach ( $synved_option['module-addon-list'] as $module_addon_load ) {
		$module_id = $module_addon_load['module-id'];
		$filter    = $module_addon_load['filter'];

		$addon_list = synved_plugout_module_addon_list( $module_id, $filter );

		if ( false === empty( $addon_list ) ) {
			foreach ( $addon_list as $addon_name => $addon_file ) {
				if ( true === file_exists( $addon_file ) ) {
					include_once $addon_file;
				}
			}
		}
	}
}

/**
 * WP Init.
 *
 * @return void
 */
function synved_option_wp_init() {
	synved_option_init();

	if ( false === is_admin() ) {
		add_action( 'wp_head', 'synved_option_print_head_outputs' );
	}
}

/**
 * WP Admin Menu.
 *
 * @return void
 */
function synved_option_wp_admin_menu() {
	synved_option_page_add_cb();
}

/**
 * WP Admin Init.
 *
 * @return void
 */
function synved_option_wp_admin_init() {
	global $synved_option_list;

	if ( false === empty( $synved_option_list ) ) {
		foreach ( $synved_option_list as $id => $list ) {
			$dbname = synved_option_name_default( $id );
			$group  = synved_option_group_default( $id );

			$option_setting_sanitize_cb                      = synved_option_setting_sanitize_cb( $id, $list );
			$option_setting_sanitize_cb['sanitize_callback'] = 'synved_option_setting_sanitize_item_callback';

			register_setting( $group, $dbname, $option_setting_sanitize_cb );

			$items = synved_option_item_list( $id );

			foreach ( $items as $name => $item ) {
				synved_option_wp_handle_setting( $id, null, null, $name, $item );
			}
		}
	}
}

/**
 * WP Upgrader Source Selection.
 *
 * @param string|WP_Error $source        Source string.
 * @param string          $remote_source Remote source string.
 * @param Plugin_Upgrader $object        Upgrader object.
 *
 * @return mixed|WP_Error
 */
function synved_option_wp_upgrader_source_selection( $source, $remote_source, $object = null ) {
	if ( is_wp_error( $source ) ) {
		return $source;
	}

	if (
		false === empty( $object )
		&& true === $object instanceof Plugin_Upgrader
		&& true === method_exists( $object, 'check_package' )
	) {
		$result = $object->check_package( $source );

		if ( true === is_wp_error( $result ) ) {
			$folder_name = basename( $source );
			$addon_item  = synved_option_item_query(
				null,
				array(
					array( 'type' => 'addon' ),
					array( 'folder' => $folder_name ),
				)
			);

			if ( false === empty( $addon_item ) ) {
				$id         = $addon_item['_synved_option_id'];
				$name       = $addon_item['_synved_option_name'];
				$addon_page = synved_option_item_page( $id, $name );
				$page_item  = synved_option_item( $id, $addon_page );
				$page_label = synved_option_item_label( $page_item );
				$page_url   = synved_option_item_page_link_url( $id, $name );

				$source = new WP_Error(
					'synved_option_invalid_plugin_is_addon',
					sprintf(
					/* translators: %1$s is the page URL. %2$s is the page label. */
						__(
							'This addon must be installed through the <a href="%1$s">%2$s settings page</a>.'
						),
						$page_url,
						$page_label
					),
					''
				);
			}
		}
	}

	return $source;
}

/**
 * WP Upgrader Pre Install.
 *
 * @param bool|WP_Error $perform Response.
 * @param array         $extra   Hook extra.
 *
 * @return mixed
 */
function synved_option_wp_upgrader_pre_install( $perform, $extra ) {
	if ( false === isset( $extra['plugin'] ) ) {
		return $perform;
	}

	$upgrade_transfer = get_option( 'synved_option_wp_upgrade_addon_transfer' );

	if ( false === empty( $upgrade_transfer ) ) {
		$upgrade_transfer_time = get_option( 'synved_option_wp_upgrade_addon_transfer_time' );

		if ( true === empty( $upgrade_transfer_time ) || ( time() - $upgrade_transfer_time > ( 60 * 60 * 1 ) ) ) {
			$upgrade_transfer = null;

			update_option( 'synved_option_wp_upgrade_addon_transfer', '' );
		}
	}

	if ( true === function_exists( 'synved_plugout_get_module_list' ) ) {
		$module_list = synved_plugout_get_module_list();
	} else {
		global $synved_plugout;

		$module_list = array_keys( $synved_plugout['module-list'] );
	}

	$plugins_dir = WP_PLUGIN_DIR;
	$plugins_dir = rtrim(
		str_replace(
			array( '\\', '/' ),
			DIRECTORY_SEPARATOR,
			realpath( $plugins_dir )
		),
		DIRECTORY_SEPARATOR
	) . DIRECTORY_SEPARATOR;

	$plugin     = $extra['plugin'];
	$plugin_dir = rtrim(
		str_replace(
			array( '\\', '/' ),
			DIRECTORY_SEPARATOR,
			realpath( dirname( $plugins_dir . $plugin ) )
		),
		DIRECTORY_SEPARATOR
	) . DIRECTORY_SEPARATOR;

	$dir  = get_temp_dir();
	$name = time();
	$dir  = rtrim( $dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . wp_unique_filename(
		$dir,
		$name
	) . DIRECTORY_SEPARATOR;

	$list = array();

	foreach ( $module_list as $module_id ) {
		$addon_list = synved_plugout_module_addon_list( $module_id );

		if ( false === empty( $addon_list ) ) {
			foreach ( $addon_list as $addon_name => $addon_file ) {
				if ( true === file_exists( $addon_file ) ) {
					$addon_dir  = dirname( $addon_file );
					$parent_dir = dirname( $addon_dir );

					// Clean names for comparison.
					$addon_dir = rtrim(
						str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, realpath( $addon_dir ) ),
						DIRECTORY_SEPARATOR
					) . DIRECTORY_SEPARATOR;

					$parent_dir = rtrim(
						str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, realpath( $parent_dir ) ),
						DIRECTORY_SEPARATOR
					) . DIRECTORY_SEPARATOR;

					if (
						strtolower( $parent_dir ) !== strtolower( $plugins_dir )
						&& false !== strpos( strtolower( $addon_dir ), strtolower( $plugin_dir ) )
					) {
						$path  = $dir;
						$diff  = substr( $addon_dir, strlen( $plugins_dir ) );
						$path .= $diff;

						wp_mkdir_p( $path );

						copy_dir( $addon_dir, $path );

						$list[] = array(
							'original'  => $addon_dir,
							'temporary' => $path,
						);
					}
				}
			}
		}
	}

	if ( false === empty( $list ) ) {
		update_option(
			'synved_option_wp_upgrade_addon_transfer',
			array(
				'directory' => $dir,
				'list'      => $list,
			)
		);
		update_option( 'synved_option_wp_upgrade_addon_transfer_time', time() );
	}

	return $perform;
}

/**
 * WP Upgrader Post Install.
 *
 * @param bool|WP_Error $perform Installation response.
 * @param array         $extra   Extra arguments passed to hooked filters.
 * @param array         $result  Installation result data.
 *
 * @return mixed
 */
function synved_option_wp_upgrader_post_install( $perform, $extra, $result = null ) {
	$upgrade_transfer = get_option( 'synved_option_wp_upgrade_addon_transfer' );

	if ( false === empty( $upgrade_transfer ) ) {
		$list = $upgrade_transfer['list'];

		foreach ( $list as $upgrade_item ) {
			$original  = $upgrade_item['original'];
			$temporary = $upgrade_item['temporary'];

			wp_mkdir_p( $original );

			copy_dir( $temporary, $original );
		}

		global $wp_filesystem;

		if ( false === empty( $wp_filesystem ) ) {
			$directory = $upgrade_transfer['directory'];

			$wp_filesystem->delete( $directory, true );
		}

		update_option( 'synved_option_wp_upgrade_addon_transfer', '' );
	}

	return $perform;
}

/**
 * Admin enqueue scripts.
 *
 * @return void
 */
function synved_option_admin_enqueue_scripts() {
	$uri = synved_option_path_uri();
	$dir = plugin_dir_path( __FILE__ );

	wp_register_style(
		'synved-option-jquery-ui',
		$uri . '/jqueryUI/css/snvdopt/jquery-ui-1.9.2.custom.min.css',
		false,
		'1.9.2'
	);
	wp_register_style(
		'synved-option-admin',
		$uri . '/style/admin.css',
		array( 'wp-jquery-ui-dialog', 'synved-option-jquery-ui' ),
		filemtime( $dir . '/style/admin.css' )
	);

	wp_register_script(
		'synved-option-script-custom',
		$uri . '/script/custom.js',
		array(
			'jquery',
			'suggest',
			'media-upload',
			'thickbox',
			'jquery-ui-core',
			'jquery-ui-progressbar',
			'jquery-ui-dialog',
			'wp-util',
		),
		filemtime( $dir . '/script/custom.js' ),
		false
	);

	wp_localize_script(
		'synved-option-script-custom',
		'SynvedOptionVars',
		array(
			'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
			'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
			'ajaxurl'             => admin_url( 'admin-ajax.php' ),
			'synvedSecurity'      => wp_create_nonce( 'synved-option-submit-nonce' ),
			'termsURLAgree'       => admin_url( 'options-general.php?page=social-media-feather-settings&accept-terms=yes' ),
			'termsURLDisagree'    => admin_url( 'options-general.php?page=social-media-feather-settings&accept-terms=no' ),
		)
	);

	$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

	$enqueue = false;

	global $synved_option_list;

	if ( false === empty( $synved_option_list ) ) {
		foreach ( $synved_option_list as $id => $list ) {
			if ( false === empty( $list['pages'] ) ) {
				$page_list = $list['pages'];

				foreach ( $page_list as $name => $page_object ) {
					if ( synved_option_page_slug( $id, $name ) === $page ) {
						$enqueue = true;

						break;
					}
				}
			}
		}
	}

	if ( $enqueue ) {
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'synved-option-jquery-ui' );
		wp_enqueue_style( 'synved-option-admin' );

		wp_enqueue_script( 'plupload-all' );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'suggest' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'farbtastic' );
		wp_enqueue_script( 'synved-option-script-custom' );
	}
}

add_action( 'after_setup_theme', 'synved_option_wp_after_setup_theme' );
add_action( 'init', 'synved_option_wp_init' );
add_filter( 'upgrader_source_selection', 'synved_option_wp_upgrader_source_selection', 9, 3 );
add_filter( 'upgrader_pre_install', 'synved_option_wp_upgrader_pre_install', 6, 2 );
add_filter( 'upgrader_post_install', 'synved_option_wp_upgrader_post_install', 10, 3 );

// Add 'display' to safe style list.
add_filter(
	'safe_style_css',
	function ( $styles ) {
		$styles[] = 'display';

		return $styles;
	}
);


if ( is_admin() ) {
	add_action( 'admin_init', 'synved_option_wp_admin_init' );
	add_action( 'admin_enqueue_scripts', 'synved_option_admin_enqueue_scripts' );
}

synved_option_include_module_addon_list( 'synved-option' );
