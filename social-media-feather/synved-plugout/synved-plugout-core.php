<?php
/**
 * Core plugout file.
 *
 * @package SocialMediaFeather
 */

/**
 * Plugout version.
 */
const SYNVED_PLUGOUT_VERSION = 100000002;

$synved_plugout = array();

/**
 * Plugout version.
 *
 * @return int
 */
function synved_plugout_version() {
	return SYNVED_PLUGOUT_VERSION;
}

/**
 * Plugout path default.
 *
 * @param string $path_id Path ID string.
 *
 * @return string|null
 */
function synved_plugout_path_default( $path_id ) {
	switch ( $path_id ) {
		case 'module':
			return dirname( dirname( __FILE__ ) );
	}

	return null;
}

/**
 * Plugout path get.
 *
 * @param string $path_id Path ID string.
 *
 * @return mixed|string|null
 */
function synved_plugout_path_get( $path_id ) {
	global $synved_plugout;

	if ( isset( $synved_plugout['path'][ $path_id ] ) ) {
		return $synved_plugout['path'][ $path_id ];
	}

	return synved_plugout_path_default( $path_id );
}

/**
 * Get module list.
 *
 * @return int[]|string[]
 */
function synved_plugout_get_module_list() {
	global $synved_plugout;

	return array_keys( $synved_plugout['module-list'] );
}

/**
 * Register module.
 *
 * @param string $module_id     Module ID.
 * @param string $module_prefix Module prefix.
 * @param string $module_name   Module name.
 *
 * @return bool
 */
function synved_plugout_module_register( $module_id, $module_prefix = null, $module_name = null ) {
	global $synved_plugout;

	if ( false === isset( $synved_plugout['module-list'][ $module_id ] ) ) {
		$synved_plugout['module-list'][ $module_id ] = array(
			'id'            => $module_id,
			'name'          => $module_name,
			'prefix'        => $module_prefix,
			'location'      => null,
			'callback-list' => array(),
		);

		return true;
	}

	return false;
}

/**
 * Module path add.
 *
 * @param string      $module_id Module ID string.
 * @param string      $type      Type string.
 * @param string      $path      Path string.
 * @param string|null $meta      Meta string.
 *
 * @return bool
 */
function synved_plugout_module_path_add( $module_id, $type, $path, $meta = null ) {
	global $synved_plugout;

	if ( true === isset( $synved_plugout['module-list'][ $module_id ] ) ) {
		$path = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, $path );

		$path_object         = $meta ? $meta : array();
		$path_object['path'] = $path;

		$synved_plugout['module-list'][ $module_id ]['path-list'][ $type ][] = $path_object;

		return true;
	}

	return false;
}

/**
 * Path get.
 *
 * @param string $module_id Module ID string.
 * @param string $type      Type string.
 *
 * @return mixed|null
 */
function synved_plugout_module_path_get( $module_id, $type ) {
	$path_list = synved_plugout_module_path_list_get( $module_id, $type, 'first' );

	if ( false === empty( $path_list ) ) {
		return $path_list[0]['path'];
	}

	return null;
}

/**
 * Path list get.
 *
 * @param string     $module_id Module ID string.
 * @param string     $type      Type string.
 * @param array|null $criteria  Criteria array.
 *
 * @return array|null
 */
function synved_plugout_module_path_list_get( $module_id, $type, $criteria = null ) {
	global $synved_plugout;

	if ( true === isset( $synved_plugout['module-list'][ $module_id ] ) ) {
		$path_list = $synved_plugout['module-list'][ $module_id ]['path-list'];

		if ( false === empty( $path_list ) ) {
			$return_list = array();

			foreach ( $path_list as $path_type => $path_type_list ) {
				if ( true === empty( $type ) || $type === $path_type ) {
					foreach ( $path_type_list as $path_object ) {
						$return_item = array();

						$return_item['type'] = $path_type;
						$return_item['path'] = $path_object['path'];
						$return_item['meta'] = $path_object;

						$return_list[] = $return_item;
					}
				}
			}

			return $return_list;
		}
	}

	return null;
}

/**
 * Get module.
 *
 * @param string $module_id Module ID string.
 *
 * @return mixed|null
 */
function synved_plugout_module_get( $module_id ) {
	global $synved_plugout;

	if ( true === isset( $synved_plugout['module-list'][ $module_id ] ) ) {
		return $synved_plugout['module-list'][ $module_id ];
	}

	return null;
}

/**
 * Get module version.
 *
 * @param string $module_id Module ID string.
 *
 * @return mixed
 */
function synved_plugout_module_version( $module_id ) {
	$module = synved_plugout_module_get( $module_id );

	if ( false === empty( $module ) ) {
		$module_cb  = true === isset( $module['callback-list'] ) ? $module['callback-list'] : null;
		$version_cb = str_replace( '-', '_', $module_id ) . '_version';

		if ( true === isset( $module_cb['version'] ) ) {
			$version_cb = $module_cb['version'];
		}

		if ( true === is_callable( $version_cb ) ) {
			return $version_cb();
		}
	}

	return false;
}

/**
 * Get module location.
 *
 * @param string $module_id Module ID string.
 *
 * @return mixed|null
 */
function synved_plugout_module_location_get( $module_id ) {
	$module = synved_plugout_module_get( $module_id );

	if ( false === empty( $module ) ) {
		if ( true === isset( $module['location'] ) ) {
			return $module['location'];
		}
	}

	return null;
}

/**
 * Get module directory.
 *
 * @param string $module_id Module ID string.
 *
 * @return string|null
 */
function synved_plugout_module_directory_get( $module_id ) {
	$location = synved_plugout_module_location_get( $module_id );

	if ( false === empty( $location ) ) {
		return dirname( $location );
	}

	return null;
}

/**
 * Get module URI.
 *
 * @param string $module_id Module ID string.
 *
 * @return string|null
 */
function synved_plugout_module_uri_get( $module_id ) {
	$directory = synved_plugout_module_directory_get( $module_id );

	if ( false === empty( $directory ) ) {
		$directory   = strtolower( str_replace( array( '\\', '/' ), '/', $directory ) );
		$content_dir = strtolower( str_replace( array( '\\', '/' ), '/', WP_CONTENT_DIR ) );
		$base_len    = strlen( $content_dir );

		if ( substr( $directory, 0, $base_len ) === $content_dir ) {
			return content_url( substr( $directory, $base_len ) );
		}
	}

	return null;
}

/**
 * Import module.
 *
 * @param string $module_id Module ID string.
 *
 * @return bool
 */
function synved_plugout_module_import( $module_id ) {
	// This is needed because on activation the plugin's code is included *after* the theme code.
	$request_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING );
	$action      = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );

	if ( false !== strpos( $request_uri, '/plugins.php?' ) && 'activate' === $action ) {
		return false;
	}

	global $synved_plugout;

	if ( isset( $synved_plugout['module-list'][ $module_id ] ) ) {
		$lib_path  = synved_plugout_module_path_get( $module_id, 'library' );
		$core_path = synved_plugout_module_path_get( $module_id, 'core' );

		if ( true === empty( $lib_path ) ) {
			if ( true === empty( $core_path ) ) {
				$module_path = synved_plugout_path_get( 'module' );

				if ( substr( $module_path, -1 ) !== DIRECTORY_SEPARATOR ) {
					$module_path .= DIRECTORY_SEPARATOR;
				}

				$core_path = $module_path . $module_id;
			}

			if ( true === is_dir( $core_path ) ) {
				if ( substr( $core_path, -1 ) !== DIRECTORY_SEPARATOR ) {
					$core_path .= DIRECTORY_SEPARATOR;
				}

				$lib_path = $core_path . $module_id;

				if ( true === file_exists( $lib_path . '.php' ) ) {
					$lib_path .= '.php';
				} elseif ( true === file_exists( $lib_path . '.inc.php' ) ) {
					$lib_path .= '.inc.php';
				} else {
					$lib_path = null;
				}
			}
		}

		if ( false === empty( $lib_path ) && true === file_exists( $lib_path ) ) {
			$version = synved_plugout_module_version( $module_id );

			if ( false === $version ) {
				$synved_plugout['module-list'][ $module_id ]['location'] = $lib_path;

				include_once $lib_path;
			}

			return true;
		}
	}

	return false;
}

/**
 * Scan addon path.
 *
 * @param string      $path Path string.
 * @param string|null $filter Filter string.
 *
 * @return array|void
 */
function synved_plugout_module_addon_scan_path( $path, $filter = null ) {
	if ( true === empty( $filter ) ) {
		$filter = '*';
	}

	$path = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $path );

	if ( substr( $path, -1 ) !== DIRECTORY_SEPARATOR ) {
		$path .= DIRECTORY_SEPARATOR;
	}

	if ( true === is_dir( $path ) ) {
		$list         = glob( $path . '*', GLOB_ONLYDIR );
		$addon_list   = array();
		$filter_regex = '/' . str_replace( array( '*' ), array( '.*' ), $filter ) . '/';

		if ( false === empty( $list ) ) {
			foreach ( $list as $addon_dir ) {
				$path = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $addon_dir );
				$path = rtrim( $path, DIRECTORY_SEPARATOR );
				$base = basename( $addon_dir );

				if ( false !== preg_match( $filter_regex, $base ) ) {
					$filename = $addon_dir . DIRECTORY_SEPARATOR . $base . '.php';

					if ( true === file_exists( $filename ) ) {
						$addon_list[ $base ] = $filename;
					}
				}
			}
		}

		return $addon_list;
	}
}

/**
 * Module addon list.
 *
 * @param string      $module_id Module ID string.
 * @param string|null $filter    Filter string.
 *
 * @return array
 */
function synved_plugout_module_addon_list( $module_id, $filter = null ) {
	$addon_list = array();
	$path_list  = synved_plugout_module_path_list_get( $module_id, 'addon' );

	if ( true === empty( $path_list ) ) {
		foreach ( $path_list as $path_item ) {
			$path       = $path_item['path'];
			$extra_list = synved_plugout_module_addon_scan_path( $path, $filter );

			if ( false === empty( $extra_list ) ) {
				$addon_list = array_merge( $addon_list, $extra_list );
			}
		}
	}

	$path_list = synved_plugout_module_path_list_get( $module_id, 'addon-file' );

	if ( false === empty( $path_list ) ) {
		foreach ( $path_list as $path_item ) {
			$path       = $path_item['path'];
			$extra_list = null;

			if (
				true === file_exists( $path )
				&& strtolower( substr( $path, -4 ) ) === '.php'
			) {
				$addon_name = true === isset( $path_item['path']['meta']['addon-name'] ) ? $path_item['path']['meta']['addon-name'] : basename( $path, '.php' );
				$extra_list = array( $addon_name => $path );
			}

			if ( false === empty( $extra_list ) ) {
				$addon_list = array_merge( $addon_list, $extra_list );
			}
		}
	}

	return $addon_list;
}
