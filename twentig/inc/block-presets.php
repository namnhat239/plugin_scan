<?php
/**
 * Twentig utility classes for blocks.
 *
 * @package twentig
 */

/**
 * Retrieves additional CSS classes for blocks.
 */
function twentig_get_block_css_classes() {

	$classes = array(

		'core/paragraph'    => array(
			'tw-text-uppercase'       => __( 'Make the text uppercase.', 'twentig' ),
			'tw-eyebrow'              => __( 'Make the text small and uppercase.', 'twentig' ),
			'tw-letter-spacing-tight' => __( 'Make the letter spacing tight.', 'twentig' ),
			'tw-letter-spacing-loose' => __( 'Make the letter spacing loose.', 'twentig' ),
			'tw-link-hover-underline' => __( 'Underline link only on hover.', 'twentig' ),
			'tw-link-no-underline'    => __( 'Remove underline from link.', 'twentig' ),
			'tw-text-shadow'          => __( 'Add shadow to text.', 'twentig' ),
			'tw-rounded'              => __( 'Make the corners of the block rounded if a background color is set.', 'twentig' ),
			'tw-sm-text-left'         => __( 'Align text left on mobile.', 'twentig' ),
			'tw-sm-text-center'       => __( 'Align text center on mobile.', 'twentig' ),
			'tw-sm-text-right'        => __( 'Align text right on mobile.', 'twentig' ),
			'tw-md-text-left'         => __( 'Align text left on tablet.', 'twentig' ),
			'tw-md-text-center'       => __( 'Align text center on tablet.', 'twentig' ),
			'tw-md-text-right'        => __( 'Align text right on tablet.', 'twentig' ),
		),
		'core/heading'      => array(
			'tw-link-hover-underline'  => __( 'Underline link only on hover.', 'twentig' ),
			'tw-link-no-underline'     => __( 'Remove underline from link.', 'twentig' ),
			'tw-heading-border-bottom' => __( 'Add a border below the heading.', 'twentig' ),
			'tw-heading-dash-bottom'   => __( 'Add a short line below the heading.', 'twentig' ),
			'tw-text-shadow'           => __( 'Add shadow to text.', 'twentig' ),			
			'tw-text-uppercase'        => __( 'Make the text uppercase.', 'twentig' ),
			'tw-eyebrow'               => __( 'Make the text small and uppercase.', 'twentig' ),
			'tw-letter-spacing-normal' => __( 'Make the letter spacing normal.', 'twentig' ),
			'tw-letter-spacing-loose'  => __( 'Make the letter spacing loose.', 'twentig' ),
			'tw-rounded'               => __( 'Make the corners of the block rounded if a background color is set.', 'twentig' ),
			'tw-sm-text-left'          => __( 'Align text left on mobile.', 'twentig' ),
			'tw-sm-text-center'        => __( 'Align text center on mobile.', 'twentig' ),
			'tw-sm-text-right'         => __( 'Align text right on mobile.', 'twentig' ),
			'tw-md-text-left'          => __( 'Align text left on tablet.', 'twentig' ),
			'tw-md-text-center'        => __( 'Align text center on tablet.', 'twentig' ),
			'tw-md-text-right'         => __( 'Align text right on tablet.', 'twentig' ),
		),		
		'core/post-title'    => array(
			'tw-link-hover-underline'  => __( 'Underline link only on hover.', 'twentig' ),
			'tw-link-no-underline'     => __( 'Remove underline from link.', 'twentig' ),			
			'tw-text-shadow'           => __( 'Add shadow to text.', 'twentig' ),
			'tw-sm-text-left'          => __( 'Align text left on mobile.', 'twentig' ),
			'tw-sm-text-center'        => __( 'Align text center on mobile.', 'twentig' ),
			'tw-sm-text-right'         => __( 'Align text right on mobile.', 'twentig' ),
			'tw-md-text-left'          => __( 'Align text left on tablet.', 'twentig' ),
			'tw-md-text-center'        => __( 'Align text center on tablet.', 'twentig' ),
			'tw-md-text-right'         => __( 'Align text right on tablet.', 'twentig' ),
		),
		'core/list'         => array(
			'tw-font-bold'            => __( 'Make the text bold.', 'twentig' ),
			'tw-font-italic'          => __( 'Make the text italic.', 'twentig' ),
			'tw-text-uppercase'       => __( 'Make the text uppercase.', 'twentig' ),
			'has-text-align-center'   => __( 'Align text center.', 'twentig' ),
			'tw-list-spacing-medium'  => __( 'Set a medium spacing between the list items.', 'twentig' ),
			'tw-list-spacing-loose'   => __( 'Set a loose spacing between the list items.', 'twentig' ),
			'tw-link-hover-underline' => __( 'Underline link only on hover.', 'twentig' ),
			'tw-text-wide'            => __( 'Make the block wide width.', 'twentig' ),
		),
		'core/table'        => array(
			'has-small-font-size' => __( 'Make the font size small.', 'twentig' ),
			'tw-row-valign-top'   => __( 'Vertically align top the text in the cells.', 'twentig' ),
		),
		'core/group'        => array(
			'tw-height-full'          => __( 'Make the block full height.', 'twentig' ),
			'tw-group-overlap-bottom' => __( 'Make the last block of the group overlap the group just below.', 'twentig' ),
			'tw-rounded'              => __( 'Make the corners of the block rounded.', 'twentig' ),
			'tw-border-t-0'           => __( 'Remove top border.', 'twentig' ),
			'tw-border-r-0'           => __( 'Remove right border.', 'twentig' ),
			'tw-border-b-0'           => __( 'Remove bottom border.', 'twentig' ),
			'tw-border-l-0'           => __( 'Remove left border.', 'twentig' ),
		),
		'core/columns'      => array(
			'tw-cols-rounded'     => __( 'Make the corners of the cards rounded.', 'twentig' ),
			'tw-justify-center'   => __( 'Center the columns horizontally.', 'twentig' ),
			'tw-stretched-blocks' => __( 'Make the blocks inside the columns (Group, Cover) the same height.', 'twentig' ),
		),
		'core/media-text'   => array(
			'tw-content-narrow' => __( 'Limit the text width when the block is full width.', 'twentig' ),
			'tw-media-narrow'   => __( 'Limit the media width when the media and the text are stacked.', 'twentig' ),
			'tw-height-full'    => __( 'Make the block full height. You must enable the “Crop image to fill entire column” setting.', 'twentig' ),
			'tw-rounded'        => __( 'Make the corners of the block rounded.', 'twentig' ),
			'tw-img-rounded'    => __( 'Make the corners of the image rounded.', 'twentig' ),
		),
		'core/image'        => array(
			'tw-img-bw'        => __( 'Add a black & white filter to the image.', 'twentig' ),
			'tw-caption-large' => __( 'Make the font size of the caption larger.', 'twentig' ),
		),
		'core/post-featured-image'        => array(
			'tw-img-bw'        => __( 'Add a black & white filter to the image.', 'twentig' ),
		),
		'core/gallery'      => array(
			'tw-caption-large'      => __( 'Make the font size of the caption larger.', 'twentig' ),
			'tw-hover-show-caption' => __( 'Reveal the caption on hover.', 'twentig' ),
			'tw-img-border'         => __( 'Add a border around the images (useful for logos and illustrations).', 'twentig' ),
			'tw-img-border-inner'   => __( 'Add an inner border between the images (useful for logos).', 'twentig' ),
			'tw-img-bw'             => __( 'Add a black & white filter to the images.', 'twentig' ),
			'tw-img-center'         => __( 'Center the images of the last row. You must enable the “Fixed width columns” setting.', 'twentig' ),
		),
		'core/embed'        => array(
			'is-style-tw-frame' => __( 'Add a frame around the block.', 'twentig' ),
		),
		'core/video'        => array(
			'is-style-tw-frame' => __( 'Add a frame around the block.', 'twentig' ),
		),		
		'core/latest-posts' => array(
			'tw-posts-rounded'      => __( 'Make the corners of the cards rounded.', 'twentig' ),
			'tw-img-rounded'        => __( 'Make the corners of the image rounded.', 'twentig' ),
			'has-text-align-center' => __( 'Align text center.', 'twentig' ),
		),
		'core/post-date'    => array(
			'tw-link-hover-underline' => __( 'Underline link only on hover.', 'twentig' ),
			'tw-link-no-underline'    => __( 'Remove underline from link.', 'twentig' ),
		),
		'core/post-terms'   => array(
			'tw-link-hover-underline' => __( 'Underline link only on hover.', 'twentig' ),
			'tw-link-no-underline'    => __( 'Remove underline from link.', 'twentig' ),
		),
		'core/post-author'  => array(
			'tw-link-hover-underline' => __( 'Underline link only on hover.', 'twentig' ),
			'tw-link-no-underline'    => __( 'Remove underline from link.', 'twentig' ),
		),
		'core/query-pagination-next' => array(
			'tw-ml-auto' => __( 'Set margin-left to auto.', 'twentig' ),
		),
		'core/query-title'  => array(
			'tw-sm-text-left'        => __( 'Align text left on mobile.', 'twentig' ),
			'tw-sm-text-center'      => __( 'Align text center on mobile.', 'twentig' ),
			'tw-sm-text-right'       => __( 'Align text right on mobile.', 'twentig' ),
			'tw-md-text-left'        => __( 'Align text left on tablet.', 'twentig' ),
			'tw-md-text-center'      => __( 'Align text center on tablet.', 'twentig' ),
			'tw-md-text-right'       => __( 'Align text right on tablet.', 'twentig' ),
		),
		'core/categories'   => array(
			'tw-link-hover-underline' => __( 'Underline link only on hover.', 'twentig' ),
			'tw-no-bullet'            => __( 'Remove bullet from list.', 'twentig' ),
		),
		'core/archives'     => array(
			'tw-link-hover-underline' => __( 'Underline link only on hover.', 'twentig' ),
			'tw-no-bullet'            => __( 'Remove bullet from list.', 'twentig' ),
		),
		'core/site-title'   => array(
			'tw-link-hover-underline' => __( 'Underline link only on hover.', 'twentig' ),
			'tw-link-no-underline'    => __( 'Remove underline from link.', 'twentig' ),
		),
		'core/navigation'   => array(
			'tw-letter-spacing-loose' => __( 'Make the letter spacing loose.', 'twentig' ),
		),
	);

	if ( is_wp_version_compatible( '5.9' ) ) {
		unset( $classes['core/paragraph']['tw-text-uppercase'] );
		unset( $classes['core/paragraph']['tw-letter-spacing-tight'] );
		unset( $classes['core/paragraph']['tw-letter-spacing-loose'] );
		unset( $classes['core/heading']['tw-text-uppercase'] );
		unset( $classes['core/heading']['tw-letter-spacing-normal'] );
		unset( $classes['core/heading']['tw-letter-spacing-loose'] );
		unset( $classes['core/list']['tw-font-bold'] );
		unset( $classes['core/list']['tw-font-italic'] );
		unset( $classes['core/list']['tw-text-uppercase'] );
		unset( $classes['core/table']['has-small-font-size'] );
		unset( $classes['core/group']['tw-rounded'] );
	}

	if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
		unset( $classes['core/paragraph']['tw-eyebrow'] );
		unset( $classes['core/heading']['tw-eyebrow'] );
		unset( $classes['core/group']['tw-height-full'] );
		unset( $classes['core/media-text']['tw-content-narrow'] );
		unset( $classes['core/list']['tw-text-wide'] );
	} 

	return apply_filters( 'twentig_block_classes', $classes );
}