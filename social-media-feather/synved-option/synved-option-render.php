<?php
/**
 * Option render.
 *
 * @package SocialMediaFeather
 */

/**
 * Render field name.
 *
 * @param string $id   ID string.
 * @param string $name Name string.
 *
 * @return string
 */
function synved_option_render_field_name( $id, $name ) {
	$out_name = synved_option_name_default( $id ) . '[' . $name . ']';

	return $out_name;
}

/**
 * Render field ID.
 *
 * @param string $id   ID string.
 * @param string $name Name string.
 *
 * @return string
 */
function synved_option_render_field_id( $id, $name ) {
	return synved_option_name_default( $id ) . '_' . $name;
}

/**
 * Render item.
 *
 * @param string      $id      ID string.
 * @param string      $name    Name string.
 * @param array|null  $item    Item array.
 * @param bool        $render  Should we render? True if yes, false if no (default = no).
 * @param array|null  $params  Parameter array.
 * @param string|null $context Context string.
 *
 * @return string|null
 */
function synved_option_render_item( $id, $name, $item = null, $render = false, $params = null, $context = null ) {
	if ( true === empty( $item ) ) {
		$item = synved_option_item( $id, $name );
	}

	// If item is still empty, return null.
	if ( true === empty( $item ) ) {
		return null;
	}

	$value         = synved_option_get( $id, $name );
	$type          = synved_option_item_type( $item );
	$style         = synved_option_item_style( $item );
	$label         = synved_option_item_label( $item );
	$tip           = synved_option_item_tip( $item );
	$hint          = synved_option_item_hint( $item );
	$default       = synved_option_item_default( $item );
	$set           = synved_option_item_set( $item );
	$set_is_linear = false;

	if ( false === empty( $set ) ) {
		$set_is_linear = true;

		foreach ( $set as $set_it ) {
			if ( count( $set_it ) > 1 ) {
				$set_is_linear = false;

				break;
			}
		}
	}

	$out_name = synved_option_render_field_name( $id, $name );
	$out_id   = synved_option_render_field_id( $id, $name );
	$out      = null;

	if ( isset( $params['output_name'] ) ) {
		$out_name = $params['output_name'];
	}

	if ( isset( $params['output_id'] ) ) {
		$out_id = $params['output_id'];
	}

	if ( isset( $params['tip'] ) ) {
		$tip = $params['tip'];
	}

	if ( isset( $params['default'] ) ) {
		$default = $params['default'];
	}

	if ( isset( $params['value'] ) ) {
		$value = $params['value'];
	}

	$new_value  = $value;
	$error_list = synved_option_item_validate_value( $id, $name, $value, $new_value, $item );

	if ( $new_value !== $value && ( true === empty( $context ) || 'settings' === $context ) ) {
		synved_option_set( $id, $name, $new_value );

		$value = synved_option_get( $id, $name );
	}

	if ( false === empty( $error_list ) ) {
		foreach ( $error_list as $error ) {
			$out .= '<div id="message" class="error"><p>For "<i>' . $label . '</i>": ' . $error['message'] . '</p></div>';
		}
	}

	if ( true === $set_is_linear ) {
		$out .= '<select name="' . $out_name . '" id="' . $out_id . '">';

		// TODO: Exception: remove at some point.
		if ( true === isset( $params['set_before'] ) ) {
			$set_before = $params['set_before'];

			$set = array_merge( $set_before, $set );
		}

		foreach ( $set as $set_it ) {
			$set_it_keys = array_keys( $set_it );

			$out .= '<option value="' . esc_attr( $set_it_keys[0] ) . '" '
					. selected( $value === $set_it_keys[0], true, false ) . '>'
					. esc_html( $set_it[ $set_it_keys[0] ] )
					. '</option>';
		}

		$out .= '</select>';
	} else {
		$placeholder = null;

		if ( false === empty( $hint ) ) {
			$placeholder = ' placeholder="' . esc_attr( $hint ) . '"';
		}

		switch ( $type ) {
			case 'boolean':
				$out .= '<fieldset><legend class="screen-reader-text"><span>'
						. esc_attr( $label )
						. '</span></legend><label for="'
						. esc_attr( $out_id )
						. '"><input type="hidden" name="'
						. esc_attr( $out_name )
						. '" value="0" /><input name="'
						. esc_attr( $out_name )
						. '" id="'
						. esc_attr( $out_id )
						. '" type="checkbox" value="1" class="code" '
						. checked( true === $value, true, false )
						. $placeholder
						. ' /> '
						. '</label>&nbsp;&nbsp;<span class="description" style="vertical-align:middle;">'
						. esc_html( $tip )
						. '</span></fieldset>';

				break;
			case 'text':
			case 'style':
			case 'script':
			case 'image':
			case 'video':
			case 'media':
				$atts      = array(
					'name'  => $out_name,
					'type'  => 'text',
					'id'    => $out_id,
					'value' => $value,
					'class' => 'regular-text',
				);
				$att_style = array();
				$content   = null;
				$tag       = 'input';
				$extended  = false;

				if ( false === empty( $style ) ) {
					if ( true === in_array( 'wide', $style, true ) ) {
						$atts['class'] = 'wide-text';
					}

					if ( true === in_array( 'extend', $style, true ) ) {
						$extended = true;
					}
				}

				if ( true === in_array( $type, array( 'style', 'script' ), true ) ) {
					$extended = true;

					$att_style['width']  = '450px';
					$att_style['height'] = '250px';
				}

				if ( true === $extended ) {
					$tag = 'textarea';

					if ( true === isset( $atts['value'] ) ) {
						$content = $atts['value'];

						unset( $atts['value'] );
					}

					if ( true === empty( $content ) ) {
						$content = '';
					}

					unset( $atts['type'] );
				}

				if ( false === empty( $hint ) ) {
					$atts['placeholder'] = $hint;
				}

				if ( false === empty( $att_style ) ) {
					$att_css = null;

					foreach ( $att_style as $style_name => $style_value ) {
						$att_css .= $style_name . ':' . $style_value . ';';
					}

					$atts['style'] = $att_css;
				}

				$out .= '<' . $tag;

				foreach ( $atts as $att_name => $att_value ) {
					$out .= ' ' . $att_name . '="' . esc_attr( $att_value ) . '"';
				}

				if ( false === empty( $content ) ) {
					$out .= '>' . esc_html( $content ) . '</' . $tag . '>';
				} else {
					$out .= ' />';
				}

				if ( true === in_array( $type, array( 'image', 'video', 'media' ), true ) ) {
					$out .= '<input type="hidden" name="' . esc_attr( synved_option_render_field_name( $id, $name . '_info_' ) ) . '" value="' . esc_attr( $type ) . '" />';
					$out .= '&nbsp;&nbsp;<input type="button" class="synved-option-upload-button" value="' . esc_attr( __( 'Select File', 'synved-option' ) ) . '"' . $placeholder . ' />';
				}

				break;
			case 'color':
				$out .= '<div style="position:relative; float: left;">';
				$out .= '<input name="'
						. esc_attr( $out_name )
						. '" id="'
						. esc_attr( $out_id )
						. '" type="text" value="'
						. esc_attr( $value )
						. '" class="code medium-text color-input"'
						. $placeholder
						. ' />';
				$out .= '<div class="synved-option-color-input-picker" style="background:white;border:solid 1px #ccc;display:none;position:absolute;top:100%;left:0;z-index:10000;"></div>';
				$out .= '</div>';

				break;
			case 'integer':
			case 'decimal':
				$out .= '<input name="'
						. esc_attr( $out_name )
						. '" id="'
						. esc_attr( $out_id )
						. '" type="text" value="'
						. esc_attr( $value )
						. '" class="code small-text"'
						. $placeholder . ' />';
				break;
			case 'user':
			case 'author':
			case 'category':
			case 'page':
				$args = array(
					'echo'            => false,
					'name'            => esc_attr( $out_name ),
					'id'              => esc_attr( $name ),
					'selected'        => esc_attr( $value ),
					'show_option_all' => __( 'Every', 'synved-option' ) . ' ' . ucfirst( $type ),
				);

				$drop_out = null;

				switch ( $type ) {
					case 'author':
						$args['who'] = 'author';
						// no break.
					case 'user':
						$drop_out = wp_dropdown_users( $args );
						break;
					case 'category':
						$drop_out = wp_dropdown_categories( $args );
						break;
					case 'page':
						$args['show_option_no_change'] = esc_attr( $args['show_option_all'] );

						$drop_out = wp_dropdown_pages( $args ); // phpcs:ignore
						break;
				}

				$out .= $drop_out;

				break;
			case 'tag-list':
				$out .= '<input name="'
						. esc_attr( $out_name )
						. '" id="'
						. esc_attr( $out_id )
						. '" type="text" value="'
						. esc_attr( $value )
						. '" class="regular-text synved-option-tag-selector"'
						. $placeholder
						. ' />';

				break;
		}

		if ( false === empty( $hint ) ) {
			$out .= ' <span class="snvdopt"><a class="button synved-option-reset-button" title="' . __(
				'Set value to default hinted background value',
				'synved-option'
			) . '" style="display: inline-block; padding: 0; vertical-align: middle; cursor: pointer;"><span class="ui-icon ui-icon-arrowrefresh-1-w"> </span></a></span>';
		}
	}

	$item_render = synved_option_item_render( $item );

	if ( false === empty( $item_render ) ) {
		try {
			$params  = array(
				'output_name' => $out_name,
				'output_id'   => $out_id,
				'output'      => $out,
				'set'         => $set,
				'label'       => $label,
			);
			$new_out = $item_render->invoke( array( $value, $params, $name, $id, $item ) );
		} catch ( Exception $ex ) {
			$new_out = '';
		}

		if ( false === empty( $new_out ) ) {
			$out = $new_out;
		}
	}

	if ( false === empty( $out ) ) {
		if ( false === empty( $tip ) && 'boolean' !== $type ) {
			$tip_class = ' description-' . $type;
			$out      .= '&nbsp;&nbsp;<span class="description' . $tip_class . '">' . $tip . '</span>';
		}

		if ( $render ) {
			echo wp_kses( $out, synved_get_allowed_html_array() );
		} else {
			return wp_kses( $out, synved_get_allowed_html_array() );
		}
	}

	return null;
}
