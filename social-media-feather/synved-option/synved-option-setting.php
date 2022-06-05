<?php
/**
 * Option settings.
 *
 * @package SocialMediaFeather
 */

/**
 * Synved option sanitize item callback helper.
 *
 * @param array $post Post object array.
 *
 * @return array|mixed Sanitized object array.
 */
function synved_option_setting_sanitize_item_callback( $post ) {
	if ( true === is_array( $post ) ) {
		foreach ( $post as $item_key => &$item ) {
			switch ( $item_key ) {
				case 'rss_follow_link':
				case 'youtube_follow_link':
				case 'vimeo_follow_link':
				case 'instagram_follow_link':
				case 'flickr_follow_link':
				case 'foursquare_follow_link':
				case 'facebook_follow_link':
				case 'facebook_share_link':
				case 'twitter_follow_link':
				case 'twitter_share_link':
				case 'reddit_share_link':
				case 'pinterest_follow_link':
				case 'pinterest_share_link':
				case 'linkedin_follow_link':
				case 'linkedin_share_link':
				case 'tumblr_follow_link':
				case 'tumblr_share_link':
					$item = esc_url( $item );
					break;
				case 'automatic_append_postfix':
				case 'automatic_append_separator':
				case 'automatic_append_prefix':
				case 'automatic_follow_postfix':
				case 'automatic_follow_prefix':
				case 'automatic_share_postfix':
				case 'automatic_share_prefix':
				case 'custom_style':
				case 'share_prefix_markup':
					$item = str_replace( 'alert(', 'alert\(', $item );
					$item = wp_kses_post( $item );
					break;
				case 'automatic_share_position':
				case 'automatic_follow_position':
				case 'share_message_default':
					$item = esc_html( $item );
					break;
				case 'fb_app_id':
					$item = preg_replace( '/[^a-zA-Z0-9]+/', '', $item );
					break;
				case 'automatic_follow':
				case 'automatic_follow_before_share':
				case 'automatic_follow_single':
				case 'automatic_share':
				case 'automatic_share_single':
				case 'show_credit':
				case 'layout_rtl':
				case 'accepted_sharethis_terms':
				case 'hide_sharethis_terms':
				case 'share_full_url':
				case 'shortcode_widgets':
				case 'use_shortlinks':
					$item = filter_var( $item, FILTER_SANITIZE_NUMBER_INT );
					break;
				default:
					$item = filter_var( $item, FILTER_SANITIZE_STRING );
					break;
			}
		}
	}

	return $post;
}

/**
 * Option settings sanitizer callback.
 *
 * @param string $id     Item ID string.
 * @param array  $values Values array.
 *
 * @return array
 */
function synved_option_setting_sanitize_cb( $id, $values ) {
	if ( false === empty( $values ) && true === is_array( $values ) ) {
		foreach ( $values as $name => $value ) {
			$values[ $name ] = synved_option_item_sanitize_value( $id, $name, $values[ $name ] );
		}

		return $values;
	}

	return array();
}

/**
 * Option settings callback.
 *
 * @param string $id   Item ID string.
 * @param string $name Item name string.
 * @param string $item Item value.
 *
 * @return string|null
 */
function synved_option_setting_cb( $id, $name, $item ) {
	return synved_option_render_item( $id, $name, $item, true );
}
