<?php
/**
 * Option items.
 *
 * @package SocialMediaFeather
 */

/**
 * Item query into.
 *
 * @param array|null $filter Filter array.
 * @param array|null $list List array.
 *
 * @return mixed|null
 */
function synved_option_item_query_into( $filter, $list ) {
	if ( true === empty( $list ) ) {
		return null;
	}

	foreach ( $list as $item_name => $item ) {
		if ( false === empty( $filter ) ) {
			$filter_keys  = array_keys( $filter );
			$filter_name  = $filter_keys[0];
			$filter_value = $filter[ $filter_keys[0] ];

			if ( true === is_string( $filter_name ) || false === is_array( $filter_value ) ) {
				$filter = array( $filter );
			}

			$found_item = true;

			foreach ( $filter as $filter_index => $filter_list ) {
				$pass = false;

				foreach ( $filter_list as $filter_name => $filter_value ) {
					if ( 'name' === $filter_name ) {
						if ( $item_name === $filter_value ) {
							$pass = true;
						}
					} else {
						$property = synved_option_item_property( $item, $filter_name );

						if ( $property === $filter_value ) {
							$pass = true;
						}
					}
				}

				$found_item = ( $found_item && $pass );

				if ( false === $found_item ) {
					break;
				}
			}

			if ( $found_item ) {
				return $item;
			}
		}

		$type = synved_option_item_type( $item );

		if ( 'options-page' === $type
			&& true === isset( $item['sections'] )
			&& false === empty( $item['sections'] ) ) {
			$ret = synved_option_item_query_into( $filter, $item['sections'] );

			if ( false === empty( $ret ) ) {
				return $ret;
			}
		} elseif ( 'options-section' === $type
				&& true === isset( $item['settings'] )
				&& false === empty( $item['settings'] )
		) {
			$ret = synved_option_item_query_into( $filter, $item['settings'] );

			if ( false === empty( $ret ) ) {
				return $ret;
			}
		}
	}

	return null;
}

/**
 * Item Query.
 *
 * @param string $id     ID string.
 * @param array  $filter Filter array.
 *
 * @return mixed|null
 */
function synved_option_item_query( $id, $filter ) {
	global $synved_option_list;

	foreach ( $synved_option_list as $list_id => $list ) {
		if ( true === empty( $id ) || $list_id === $id ) {
			$items = synved_option_item_list( $list_id );

			$ret = synved_option_item_query_into( $filter, $items );

			if ( false === empty( $ret ) || false === empty( $id ) ) {
				return $ret;
			}
		}
	}

	return null;
}

/**
 * Item find.
 *
 * @param string $id   ID string.
 * @param string $name Name string.
 *
 * @return mixed|null
 */
function synved_option_item_find( $id, $name ) {
	return synved_option_item_query( $id, array( 'name' => $name ) );
}

/**
 * Find item alias.
 *
 * @param string $id   ID string.
 * @param string $name Name string.
 *
 * @return mixed|null
 */
function synved_option_item( $id, $name ) {
	return synved_option_item_find( $id, $name );
}

/**
 * Item property with fallback.
 *
 * @param array  $item     Item array.
 * @param string $property Property string.
 * @param mixed  $default  Fallback value.
 *
 * @return false|mixed|null
 */
function synved_option_item_property( $item, $property, $default = null ) {
	$prop = true === isset( $item[ $property ] ) ? $item[ $property ] : $default;

	if ( true === $prop instanceof SynvedOptionCallback ) {
		$prop = $prop->invoke( array( $default, $item ) );
	}

	return $prop;
}

/**
 * Item type.
 *
 * @param array $item Item array.
 *
 * @return false|mixed|string|null
 */
function synved_option_item_type( array $item ) {
	$type     = true === isset( $item['type'] ) ? $item['type'] : null;
	$default  = synved_option_item_default( $item );
	$callback = null;

	if ( $type instanceof SynvedOptionCallback ) {
		$callback = $type;

		$type = null;
	}

	if ( true === empty( $type ) && true === isset( $item['sections'] ) ) {
		$type = 'options-page';
	}

	if ( true === empty( $type ) && true === isset( $item['settings'] ) ) {
		$type = 'options-section';
	}

	if ( true === empty( $type ) ) {
		$type = 'text';

		if ( null !== $default ) {
			if ( true === is_bool( $default ) ) {
				$type = 'boolean';
			} elseif ( true === is_int( $default ) ) {
				$type = 'integer';
			} elseif ( true === is_string( $default ) ) {
				$type = 'text';
			} elseif ( true === is_float( $default ) ) {
				$type = 'decimal';
			}
		}
	}

	if ( false === empty( $callback ) ) {
		return $callback->invoke( array( $type, $item ) );
	}

	return $type;
}

/**
 * Item mode.
 *
 * @param array $item Item array.
 *
 * @return array|false|mixed|string[]|null
 */
function synved_option_item_mode( array $item ) {
	$mode = true === isset( $item['mode'] ) ? $item['mode'] : null;

	if ( true === $mode instanceof SynvedOptionCallback ) {
		$mode = $mode->invoke( array( null, $item ) );
	}

	if ( false === empty( $mode ) && true === is_string( $mode ) ) {
		$mode = explode( ',', $mode );
	}

	if ( true === empty( $mode ) ) {
		$mode = array();
	}

	return $mode;
}

/**
 * Item style.
 *
 * @param array $item Item array.
 *
 * @return array|false|mixed|string[]|null
 */
function synved_option_item_style( array $item ) {
	$style = true === isset( $item['style'] ) ? $item['style'] : null;

	if ( $style instanceof SynvedOptionCallback ) {
		$style = $style->invoke( array( null, $item ) );
	}

	if ( false === empty( $style ) && true === is_string( $style ) ) {
		$style = explode( ',', $style );
	}

	if ( true === empty( $style ) ) {
		$style = array();
	}

	return $style;
}

/**
 * Item label.
 *
 * @param array $item Item array.
 *
 * @return false|mixed|null
 */
function synved_option_item_label( array $item ) {
	$label = true === isset( $item['label'] ) ? $item['label'] : null;

	if ( true === $label instanceof SynvedOptionCallback ) {
		$label = $label->invoke( array( null, $item ) );
	}

	return $label;
}

/**
 * Item tip.
 *
 * @param array $item Item array.
 *
 * @return false|mixed|null
 */
function synved_option_item_tip( array $item ) {
	$tip = true === isset( $item['tip'] ) ? $item['tip'] : null;

	if ( true === $tip instanceof SynvedOptionCallback ) {
		$tip = $tip->invoke( array( null, $item ) );
	}

	return $tip;
}

/**
 * Item hint.
 *
 * @param array $item Item array.
 *
 * @return false|mixed|null
 */
function synved_option_item_hint( array $item ) {
	$hint = true === isset( $item['hint'] ) ? $item['hint'] : null;

	if ( true === $hint instanceof SynvedOptionCallback ) {
		$hint = $hint->invoke( array( null, $item ) );
	}

	return $hint;
}

/**
 * Item default.
 *
 * @param array $item Item array.
 *
 * @return false|mixed|null
 */
function synved_option_item_default( array $item ) {
	$default = true === isset( $item['default'] ) ? $item['default'] : null;

	if ( true === $default instanceof SynvedOptionCallback ) {
		$default = $default->invoke( array( null, $item ) );
	}

	return $default;
}

/**
 * Item role.
 *
 * @param array $item Item array.
 *
 * @return false|mixed|string|null
 */
function synved_option_item_role( array $item ) {
	$role = true === isset( $item['role'] ) ? $item['role'] : null;

	if ( true === $role instanceof SynvedOptionCallback ) {
		$role = $role->invoke( array( null, $item ) );
	}

	if ( true === empty( $role ) ) {
		$role = 'manage_options';
	}

	return $role;
}

/**
 * Item parent.
 *
 * @param array $item Item array.
 *
 * @return false|mixed|string|null
 */
function synved_option_item_parent( array $item ) {
	$parent = true === isset( $item['parent'] ) ? $item['parent'] : null;

	if ( true === $parent instanceof SynvedOptionCallback ) {
		$parent = $parent->invoke( array( null, $item ) );
	}

	switch ( $parent ) {
		case 'dashboard':
			$parent = 'index.php';
			break;
		case 'posts':
			$parent = 'edit.php';
			break;
		case 'media':
			$parent = 'upload.php';
			break;
		case 'links':
			$parent = 'link-manager.php';
			break;
		case 'pages':
			$parent = 'edit.php?post_type=page';
			break;
		case 'comments':
			$parent = 'edit-comments.php';
			break;
		case 'appearance':
			$parent = 'themes.php';
			break;
		case 'plugins':
			$parent = 'plugins.php';
			break;
		case 'users':
			$parent = 'users.php';
			break;
		case 'tools':
			$parent = 'tools.php';
			break;
		case null:
			// no break.
		case 'settings':
			$parent = 'options-general.php';
			break;
	}

	return $parent;
}

/**
 * Item page.
 *
 * @param string $id   ID string.
 * @param string $name Name string.
 *
 * @return int|string|null
 */
function synved_option_item_page( $id, $name ) {
	$items = synved_option_item_list( $id );

	if ( false === empty( $items ) ) {
		foreach ( $items as $page_name => $page ) {
			$sections = true === isset( $page['sections'] ) ? $page['sections'] : array();

			foreach ( $sections as $section_name => $section ) {
				if (
					$section_name === $name
					|| (
						true === isset( $section['settings'] )
						&& true === array_key_exists( $name, $section['settings'] )
					)
				) {
					return $page_name;
				}
			}
		}
	}

	return null;
}

/**
 * Page link URL.
 *
 * @param string $id   ID string.
 * @param string $name Name string.
 *
 * @return string|null
 */
function synved_option_item_page_link_url( $id, $name ) {
	$page_name = synved_option_item_page( $id, $name );

	if ( false === empty( $page_name ) ) {
		return synved_option_page_link_url( $id, $page_name );
	}

	return null;
}

/**
 * Item set parse.
 *
 * @param array  $item Item array.
 * @param string $set  Set string.
 *
 * @return array
 */
function synved_option_item_set_parse( array $item, $set ) {
	$type = synved_option_item_type( $item );
	preg_match_all( '/\\s*(?:(\\d+(?:(?:\\.|(?:\\s*-\\s*))\\d+)*)|([^=,]+))\s*(?:=\s*((?:[^,"]+)|(?:"(?:(?:[^"\\\\])|(?:\\.))*")))?(?:,|$)/', $set, $matches, PREG_SET_ORDER );

	$set = array();

	foreach ( $matches as $match ) {
		$number = true === isset( $match[1] ) ? $match[1] : null;
		$value  = true === isset( $match[2] ) ? $match[2] : null;
		$label  = true === isset( $match[3] ) ? $match[3] : null;

		if ( false === empty( $number ) && true === empty( $value ) ) {
			$value = $number;
		}

		$label = trim( $label, '"' );
		$value = array( $value => $label );

		if ( false === empty( $number ) ) {
			$range = explode( '-', $number );
			$count = count( $range );

			if ( $count > 1 ) {
				$value_range = array();

				for ( $i = 0; $i < $count; $i++ ) {
					$range_item  = $range[ $i ];
					$range_value = synved_option_item_sanitize_value_basic( $item, $range_item, 0 );

					if ( 0 === $range_value ) {
						$range_value = $range_item;
					}

					$value_range[ $range_value ] = $range_value;
				}

				$value = $value_range;
			}
		}

		$set[] = $value;
	}

	return $set;
}

/**
 * Item set.
 *
 * @param array $item Item array.
 *
 * @return array|false|mixed|null
 */
function synved_option_item_set( array $item ) {
	$set = true === isset( $item['set'] ) ? $item['set'] : null;

	if ( true === $set instanceof SynvedOptionCallback ) {
		$set = $set->invoke( array( null, $item ) );
	}

	if ( null !== $set && false === is_array( $set ) ) {
		$set = synved_option_item_set_parse( $item, $set );
	}

	return $set;
}

/**
 * Item callback.
 *
 * @param array  $item                Item array.
 * @param string $callback_id         Callback ID string.
 * @param array  $callback_parameters Callback params array.
 *
 * @return mixed|string|SynvedOptionCallback|null
 */
function synved_option_item_callback( array $item, $callback_id, $callback_parameters = null ) {
	$callback = true === isset( $item[ $callback_id ] ) ? $item[ $callback_id ] : null;

	if ( false === empty( $callback ) ) {
		$callback = trim( $callback );

		if ( false === $callback instanceof SynvedOptionCallback ) {
			if ( true === is_callable( $callback ) ) {
				$callback = synved_option_callback( $callback );
			} else {
				$callback = null;
			}
		}
	}

	return $callback;
}

/**
 * Item validate.
 *
 * @param array $item Item array.
 *
 * @return mixed|string|SynvedOptionCallback|null
 */
function synved_option_item_validate( array $item ) {
	return synved_option_item_callback( $item, 'validate', '$value, $name, $id, $item' );
}

/**
 * Item render.
 *
 * @param array $item Item array.
 *
 * @return mixed|string|SynvedOptionCallback|null
 */
function synved_option_item_render( array $item ) {
	return synved_option_item_callback( $item, 'render', '$value, $params, $name, $id, $item' );
}

/**
 * Render fragment.
 *
 * @param array $item Item array.
 *
 * @return mixed|string|SynvedOptionCallback|null
 */
function synved_option_item_render_fragment( array $item ) {
	return synved_option_item_callback( $item, 'render-fragment', '$fragment, $output, $params, $name, $id, $item' );
}

/**
 * Sanitize item.
 *
 * @param array $item Item array.
 *
 * @return mixed|string|SynvedOptionCallback|null
 */
function synved_option_item_sanitize( array $item ) {
	return synved_option_item_callback( $item, 'sanitize', '$value, $name, $id, $item' );
}

/**
 * Sanitize raw item.
 *
 * @param array $item Item array.
 *
 * @return mixed|string|SynvedOptionCallback|null
 */
function synved_option_item_sanitize_raw( array $item ) {
	return synved_option_item_callback( $item, 'sanitize-raw', '$value, $name, $id, $item' );
}

/**
 * Set check value.
 *
 * @param array  $item  Item array.
 * @param array  $set   Set array.
 * @param string $value Value string.
 *
 * @return bool
 */
function synved_option_item_set_check_value( array $item, $set, $value ) {
	if ( true === empty( $set ) ) {
		return true;
	}

	foreach ( $set as $set_it ) {
		if ( false === is_array( $set_it ) ) {
			$set_it = array( $set_it );
		}

		$set_it_keys = array_keys( $set_it );

		if ( true === isset( $set_it_keys[1] ) ) {
			if ( $value >= $set_it_keys[0] && $value <= $set_it_keys[1] ) {
				return true;
			}
		} elseif ( true === isset( $set_it_keys[0] ) && $set_it_keys[0] === $value ) {
			return true;
		}
	}

	return false;
}

/**
 * Item validate value.
 *
 * @param string     $id        ID string.
 * @param string     $name      Name string.
 * @param string     $value     Value string.
 * @param string     $new_value New value string.
 * @param array|null $item      Item array.
 *
 * @return array|null
 */
function synved_option_item_validate_value( $id, $name, $value, &$new_value = null, array $item = null ) {
	if ( true === empty( $item ) ) {
		return null;
	}

	$validate   = synved_option_item_validate( $item );
	$is_valid   = true;
	$error      = null;
	$error_list = array();

	if ( false === empty( $validate ) ) {
		$new_value = $value;

		try {
			$validate->invoke( array( $new_value, $name, $id, $item ) );
		} catch ( Exception $ex ) {
			$is_valid = false;

			$error = $ex->getMessage();
		}
	}

	if ( false === $is_valid ) {
		if ( true === empty( $error ) ) {
			$error = __( 'Selected value is invalid', 'synved-option' );
		}

		$error_list[] = array(
			'code'    => null,
			'type'    => null,
			'message' => $error,
		);
	}

	return $error_list;
}

/**
 * Item sanitize value basic.
 *
 * @param array  $item    Item array.
 * @param string $value   Value string.
 * @param string $default Default value string.
 *
 * @return bool|float|int|mixed|string|null
 */
function synved_option_item_sanitize_value_basic( array $item, $value, $default = null ) {
	$type = synved_option_item_type( $item );
	$set  = true === isset( $item['set'] ) ? $item['set'] : null;

	if ( true === empty( $default ) ) {
		$default = synved_option_item_default( $item );
	}

	switch ( $type ) {
		case 'boolean':
			if ( true === empty( $value ) ) {
				$value = $default;
			}

			$value = $value ? true : 0;

			break;
		case 'integer':
			if ( true === empty( $value ) ) {
				$value = $default;
			}

			$value = intval( $value );
			break;
		case 'decimal':
			if ( true === empty( $value ) ) {
				$value = $default;
			}

			$value = floatval( $value );
			break;
		case 'text':
		case 'style':
		case 'script':
		case 'image':
		case 'video':
			$old_value = $value;
			$value     = strval( $value );

			if ( true === empty( $old_value ) || ( true === empty( $value ) && null !== $set ) ) {
				$value = $default;
			}

			break;
		case 'color':
			$value = strval( $value );

			if ( true === empty( $value ) ) {
				$value = $default;
			}

			break;
	}

	return $value;
}

/**
 * Sanitize item value.
 *
 * @param string     $id    ID string.
 * @param string     $name  Name string.
 * @param string     $value Value string.
 * @param array|null $item  Item array.
 *
 * @return array|bool|float|int|mixed|string|null
 */
function synved_option_item_sanitize_value( $id, $name, $value, array $item = null ) {
	if ( true === empty( $item ) ) {
		$item = synved_option_item( $id, $name );
	}

	if ( true === empty( $item ) ) {
		return null;
	}

	$type         = synved_option_item_type( $item );
	$default      = synved_option_item_default( $item );
	$set          = synved_option_item_set( $item );
	$sanitize     = synved_option_item_sanitize( $item );
	$sanitize_raw = synved_option_item_sanitize_raw( $item );

	if ( false === empty( $sanitize_raw ) ) {
		return $sanitize_raw->invoke( array( $value, $name, $id, $item ) );
	}

	$value    = synved_option_item_sanitize_value_basic( $item, $value, $default );
	$is_valid = true;

	if ( false === empty( $set ) ) {
		if ( true === is_array( $value ) ) {
			$is_valid  = false;
			$new_value = array();

			foreach ( $value as $single_key => $single_value ) {
				if ( true === synved_option_item_set_check_value( $item, $set, $single_value ) ) {
					$new_value[ $single_key ] = $single_value;
				}
			}

			if ( false === empty( $new_value ) ) {
				$is_valid = true;
				$value    = $new_value;
			}
		} else {
			if ( false === synved_option_item_set_check_value( $item, $set, $value ) ) {
				$value = $default;
			}
		}
	}

	if ( $is_valid ) {
		if ( false === empty( $sanitize ) ) {
			$value = $sanitize->invoke( array( $value, $name, $id, $item ) );
		}

		return $value;
	}

	return null;
}
