<?php
/**
 * Connect key.
 *
 * @package SocialMediaFeather
 */

/**
 * Key item match.
 *
 * @param string $item      Item string.
 * @param string $item_list Item list string.
 *
 * @return bool
 */
function synved_connect_key_item_match( $item, $item_list ) {
	if ( true === is_string( $item_list ) ) {
		$item_key = $item_list;

		if ( $item === $item_key ) {
			return true;
		}

		$item_key = '/' . $item_key . '/';

		if ( false !== preg_match( $item_key, $item ) ) {
			return true;
		}
	}

	return false;
}
