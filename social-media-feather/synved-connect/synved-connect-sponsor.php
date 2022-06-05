<?php
/**
 * Connect Sponsor.
 *
 * @package SocialMediaFeather
 */

/**
 * Connect sponsor list.
 *
 * @param array $filter Filter array.
 *
 * @return array|mixed|string[][]
 */
function synved_connect_sponsor_list( $filter = null ) {
	global $synved_connect;

	$sponsor_list = array(
		'wordpress-themes-1' => array(
			'type'  => 'credit',
			'label' => 'WordPress Themes',
			'tip'   => 'WordPress plugins and themes',
			'link'  => 'https://synved.com',
			'text'  => '%%link%% by Synved',
		),
		'wordpress-design-1' => array(
			'type'  => 'credit',
			'label' => 'WordPress Design',
			'tip'   => 'WordPress development, themes and custom plugins',
			'link'  => 'https://synved.com',
			'text'  => '%%link%% by Synved',
		),
		'synved-options'     => array(
			'type'  => 'intern',
			'label' => 'WordPress Options',
			'tip'   => 'Add options to your products, the easy way!',
			'link'  => 'https://synved.com/wordpress-options/',
			'text'  => 'If you are a designer or developer you might want to chek out our free plugin %%link%% to easily add options to your WordPress products and sell your own addons for them too',
		),
		'hosting-1'          => array(
			'type'  => 'extern',
			'label' => 'professional hosting',
			'tip'   => 'recommended hosting',
			'link'  => 'https://synved.com/suggests/hosting/',
			'text'  => 'If searching for a reliable hosting service you might wanna check out our recommended %%link%%',
		),
		'photocrati'         => array(
			'type'  => 'extern',
			'label' => 'Photocrati',
			'tip'   => 'WordPress theme for gallery management',
			'link'  => 'https://synved.com/suggests/photocrati/',
			'text'  => 'For more advanced image and gallery management features we recommend %%link%% and their fantastic support ;)',
		),
	);

	if ( true === isset( $synved_connect['sponsor-list'] ) ) {
		$sponsor_list = $synved_connect['sponsor-list'];
	} else {
		$feed = fetch_feed( 'https://feeds.feedburner.com/_SynvedConnectList?format=xml' );

		if ( false === is_wp_error( $feed ) ) {
			$maxitems   = $feed->get_item_quantity();
			$feed_items = $feed->get_items( 0, $maxitems );
			$feed_list  = array();

			if ( $maxitems > 0 ) {
				foreach ( $feed_items as $feed_item ) {
					$id    = $feed_item->get_id();
					$label = $feed_item->get_title();
					$tip   = $feed_item->get_description();
					$link  = $feed_item->get_permalink();
					$text  = $feed_item->get_content();

					$id_parts = array();
					parse_str( wp_parse_url( $id, PHP_URL_QUERY ), $id_parts );
					$id = $id_parts['guid'];

					$feed_categories = $feed_item->get_categories();
					$type            = null;

					if ( false === empty( $feed_categories ) ) {
						$category_tag = 'synved-connect-type-';

						foreach ( $feed_categories as $feed_category ) {
							$feed_category = $feed_category->get_label();

							if ( substr( $feed_category, 0, strlen( $category_tag ) ) === $category_tag ) {
								$type = substr( $feed_category, strlen( $category_tag ) );

								break;
							}
						}
					}

					if ( false === empty( $type ) && false === empty( $id ) ) {
						$feed_list[ $id ] = array(
							'type'  => $type,
							'label' => $label,
							'tip'   => $tip,
							'link'  => $link,
							'text'  => $text,
						);
					}
				}
			}

			if ( false === empty( $feed_list ) ) {
				$sponsor_list = $feed_list;
			}
		}

		$synved_connect['sponsor-list'] = $sponsor_list;
	}

	if ( false === empty( $filter ) ) {
		$final_list = array();

		foreach ( $sponsor_list as $sponsor_key => $sponsor_item ) {
			foreach ( $filter as $filter_key => $filter_value ) {
				if ( isset( $sponsor_item[ $filter_key ] ) ) {
					$sponsor_value = $sponsor_item[ $filter_key ];

					if ( true === synved_connect_key_item_match( $sponsor_value, $filter_value ) ) {
						$final_list[ $sponsor_key ] = $sponsor_item;
					}
				}
			}
		}

		$sponsor_list = $final_list;
	}

	$sponsor_keys = array_keys( $sponsor_list );

	foreach ( $sponsor_keys as $sponsor_key ) {
		$sponsor_list[ $sponsor_key ]['id'] = $sponsor_key;
	}

	return $sponsor_list;
}

/**
 * Sponsor ID pick.
 *
 * @param array $filter Filter array.
 *
 * @return int|string
 */
function synved_connect_sponsor_id_pick( $filter = null ) {
	$sponsor_list = synved_connect_sponsor_list( $filter );
	$sponsor_keys = array_keys( $sponsor_list );
	$count        = count( $sponsor_keys );

	$index      = wp_rand( 0, $count - 1 );
	$sponsor_id = $sponsor_keys[ $index ];

	return $sponsor_id;
}

/**
 * Sponsor item by id.
 *
 * @param string $sponsor_id Sponsor ID string.
 * @param array  $filter     Filter array.
 *
 * @return mixed|string[]|null
 */
function synved_connect_sponsor_item_by_id( $sponsor_id, $filter = null ) {
	$sponsor_list = synved_connect_sponsor_list( $filter );

	if ( false === empty( $sponsor_id ) && true === isset( $sponsor_list[ $sponsor_id ] ) ) {
		$sponsor = $sponsor_list[ $sponsor_id ];

		return $sponsor;
	}

	return null;
}

/**
 * Sponsor item pick.
 *
 * @param array $filter Filter array.
 *
 * @return mixed|string[]|null
 */
function synved_connect_sponsor_item_pick( $filter = null ) {
	$sponsor_id = synved_connect_sponsor_id_pick( $filter );

	return synved_connect_sponsor_item_by_id( $sponsor_id );
}

/**
 * Sponsor item.
 *
 * @param string $component Component string.
 * @param array  $filter    Filter array.
 *
 * @return mixed|string[]|null
 */
function synved_connect_sponsor_item( $component = null, $filter = null ) {
	$sponsor_id = synved_connect_id_get( $component );

	return synved_connect_sponsor_item_by_id( $sponsor_id, $filter );
}

/**
 * Sponsor link.
 *
 * @param array|null $sponsor_item Sponsor item array.
 *
 * @return string|null
 */
function synved_connect_sponsor_link( array $sponsor_item = null ) {
	if ( true === empty( $sponsor_item ) ) {
		$sponsor_item = synved_connect_sponsor_item();
	}

	if ( true === empty( $sponsor_item ) ) {
		return null;
	}

	$sponsor_label = true === isset( $sponsor_item['label'] ) ? $sponsor_item['label'] : null;
	$sponsor_tip   = true === isset( $sponsor_item['tip'] ) ? $sponsor_item['tip'] : null;
	$sponsor_link  = true === isset( $sponsor_item['link'] ) ? $sponsor_item['link'] : null;

	return '<a class="synved-connect-link" href="' . $sponsor_link . '" target="_blank" title="' . $sponsor_tip . '">' . $sponsor_label . '</a>';
}

/**
 * Sponsor content.
 *
 * @param array|null $sponsor_item Sponsor array.
 *
 * @return array|string|string[]|null
 */
function synved_connect_sponsor_content( array $sponsor_item = null ) {
	if ( true === empty( $sponsor_item ) ) {
		$sponsor_item = synved_connect_sponsor_item();

		if ( true === empty( $sponsor_item ) ) {
			$sponsor_item = synved_connect_sponsor_item_pick();
		}
	}

	if ( true === empty( $sponsor_item ) ) {
		return null;
	}

	$sponsor_type   = true === isset( $sponsor_item['type'] ) ? $sponsor_item['type'] : null;
	$sponsor_text   = true === isset( $sponsor_item['text'] ) ? $sponsor_item['text'] : null;
	$sponsor_markup = true === isset( $sponsor_item['markup'] ) ? $sponsor_item['markup'] : null;
	$sponsor_link   = synved_connect_sponsor_link( $sponsor_item );

	if ( true === empty( $sponsor_text ) ) {
		if ( 'credit' === $sponsor_type ) {
			$sponsor_text = '%%link%% by Synved';
		} else {
			return null;
		}
	}

	if ( true === empty( $sponsor_markup ) ) {
		if ( 'credit' === $sponsor_type ) {
			$sponsor_markup = '<span class="%%class%%">%%content%%</span>';
		} else {
			$sponsor_markup = '<div class="%%class%%">%%content%%</div>';
		}
	}

	$sponsor_class = 'sponsor-holder';

	if ( false === empty( $sponsor_type ) ) {
		$sponsor_class .= ' sponsor-type-' . $sponsor_type;
	}

	$sponsor_markup = str_replace( '%%class%%', $sponsor_class, $sponsor_markup );

	$sponsor_content = str_replace( '%%link%%', $sponsor_link, $sponsor_text );

	return str_replace( '%%content%%', $sponsor_content, $sponsor_markup );
}
