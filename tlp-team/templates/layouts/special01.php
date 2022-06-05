<?php
/**
 * Template: Special Layout 1.
 *
 * @package RT_Team
 */

use RT\Team\Helpers\Fns;

$html = null;

if ( 1 === $i ) {
	$class = $class . ' selected';
}

$html .= '<div class="' . esc_attr( $grid ) . ' ' . esc_attr( $class ) . '" data-id="' . absint( $mID ) . '">';
$html .= '<div class="single-team-item image-wrapper" data-id="' . absint( $mID ) . '">' . Fns::htmlKses( $imgHtml, 'image' ) . '</div>';
$html .= '</div>';

// HTML already escaped.
echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
