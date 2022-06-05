<?php
/**
 * Option section helpers.
 *
 * @package SocialMediaFeather
 */

/**
 * Section default name.
 *
 * @param string $id   ID string.
 * @param string $page Page string.
 *
 * @return string
 */
function synved_option_section_default_name( $id, $page ) {
	return $page . '_section_general';
}

/**
 * Section defaults.
 *
 * @param string $id   ID string.
 * @param string $page Page string.
 *
 * @return array
 */
function synved_option_section_default( $id, $page ) {
	$section = synved_option_section_default_name( $id, $page );

	return array(
		'name'  => $section,
		'type'  => 'options-section',
		'label' => __( 'General Settings', 'synved-option' ),
		'tip'   => __( 'General Settings for', 'synved-option' ) . ' ' . synved_option_label_from_id( $id ),
	);
}
