<?php
/**
 * Option page.
 *
 * @package SocialMediaFeather
 */

/**
 * Option page default values.
 *
 * @param string $id ID string.
 *
 * @return array Page default value array.
 */
function synved_option_page_default( $id ) {
	return array(
		'name'  => 'page_settings',
		'type'  => 'options-page',
		'label' => synved_option_label_from_id( $id ),
	);
}

/**
 * Option page slug.
 *
 * @param string $id   ID string.
 * @param string $name Name string.
 * @param array  $item Item array.
 *
 * @return mixed|null
 */
function synved_option_page_slug( $id, $name, $item = null ) {
	if ( true === empty( $item ) ) {
		$item = synved_option_item( $id, $name );
	}

	$type = synved_option_item_type( $item );

	if ( 'options-page' === $type ) {
		global $synved_option_list;

		if ( isset( $synved_option_list[ $id ]['pages'][ $name ]['wp-page-slug'] ) ) {
			return $synved_option_list[ $id ]['pages'][ $name ]['wp-page-slug'];
		}
	}

	return null;
}

/**
 * Option page link.
 *
 * @param string $id   ID string.
 * @param string $name Name string.
 * @param array  $item Item array.
 *
 * @return string|null
 */
function synved_option_page_link_url( $id, $name, $item = null ) {
	if ( true === empty( $item ) ) {
		$item = synved_option_item( $id, $name );
	}

	$type   = synved_option_item_type( $item );
	$parent = synved_option_item_parent( $item );
	$slug   = synved_option_page_slug( $id, $name, $item );

	if ( 'options-page' === $type && false === empty( $slug ) ) {
		return $parent . '?page=' . $slug;
	}

	return null;
}

/**
 * Option page callback function.
 *
 * @param string $id   ID string.
 * @param string $name Name string.
 * @param array  $item Item array.
 *
 * @return void
 */
function synved_option_page_cb( $id, $name, $item ) {
	$role = synved_option_item_role( $item );

	if ( false === current_user_can( $role ) ) {
		wp_die(
			esc_html(
				__(
					'You do not have sufficient permissions to access this page.',
					'synved-option'
				)
			)
		);
	}

	ob_start();
	?>
	<div id="social-media-feather-app"></div>
	<?php
	$html = ob_get_clean();

	echo wp_kses( $html, synved_get_allowed_html_array() );
}

/**
 * Add option page.
 *
 * @param string $id   ID string.
 * @param string $name Name string.
 * @param array  $item Item array.
 *
 * @return false|string|null
 */
function synved_option_page_add( $id, $name, $item ) {
	global $synved_option_list;

	define( 'SYNVEDOPTION', $id );
	define( 'SYNVEDNAME', $name );

	$type = synved_option_item_type( $item );

	if ( 'options-page' === $type ) {
		$label = synved_option_item_label( $item );
		$label = false === empty( $label ) ? $label : $name;

		$parent = synved_option_item_parent( $item );
		$role   = synved_option_item_role( $item );

		$page_slug = $id . '_' . $name;

		$page = add_submenu_page(
			$parent,
			$label,
			$label,
			$role,
			$page_slug,
			function () {
				synved_option_page_cb(
					SYNVEDOPTION,
					SYNVEDNAME,
					synved_option_item_find( SYNVEDOPTION, SYNVEDNAME )
				);
			}
		);

		$synved_option_list[ $id ]['pages'][ $name ]['wp-page-slug'] = $page_slug;
		$synved_option_list[ $id ]['pages'][ $name ]['wp-page']      = $page;

		return $page;
	}

	return null;
}

/**
 * Page add callback function.
 *
 * @return void
 */
function synved_option_page_add_cb() {
	global $synved_option_list;

	if ( false === empty( $synved_option_list ) ) {
		foreach ( $synved_option_list as $id => $list ) {
			$pages = $list['pages'];

			foreach ( $pages as $name => $item ) {
				synved_option_page_add( $id, $name, $item );
			}
		}
	}
}
