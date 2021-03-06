<?php
/**
 * Player Gallery Thumbnail
 *
 * @author      ThemeBoy
 * @package     SportsPress/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$defaults = array(
	'id'         => null,
	'icontag'    => 'dt',
	'captiontag' => 'dd',
	'caption'    => null,
	'size'       => 'sportspress-crop-medium',
	'link_posts' => get_option( 'sportspress_link_players', 'yes' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

// Add player number to caption if available
$player_number = get_post_meta( $id, 'sp_number', true );
if ( '' !== $player_number ) {
	$caption = '<strong>' . $player_number . '</strong> ' . $caption;
}

// Add caption tag if has caption
if ( $captiontag && $caption ) {
	$caption = '<' . $captiontag . ' class="wp-caption-text gallery-caption small-3 columns' . ( '' !== $player_number ? ' has-number' : '' ) . '">' . wptexturize( $caption ) . '</' . $captiontag . '>';
}

if ( $link_posts ) {
	$caption = '<a href="' . get_permalink( $id ) . '">' . $caption . '</a>';
}

if ( has_post_thumbnail( $id ) ) {
	$thumbnail = get_the_post_thumbnail( $id, $size );
} else {
	$thumbnail = '<img width="150" height="150" src="//www.gravatar.com/avatar/?s=150&d=mm&f=y" class="attachment-thumbnail wp-post-image">';
}

echo wp_kses_post( "<{$itemtag} class='gallery-item'>" );
echo wp_kses_post( "
	<{$icontag} class='gallery-icon portrait'>"
		. '<a href="' . get_permalink( $id ) . '">' . $thumbnail . '</a>'
	. "</{$icontag}>" );
echo wp_kses_post( $caption );
echo wp_kses_post( "</{$itemtag}>" );
