<?php 
//************* Home project custom post type ***********************
function quality_portfolio_type()
{	register_post_type( 'quality_portfolio',
		array(
			'labels' => array(
				'name' => __('Portfolio / Project','webriti-companion'),
				'add_new' => __('Add New', 'webriti-companion'),
				'add_new_item' => __('Add New Project','webriti-companion'),
				'edit_item' => __('Add New','webriti-companion'),
				'new_item' => __('New Link','webriti-companion'),
				'all_items' => __('All Portfolio Project','webriti-companion'),
				'view_item' => __('View Link','webriti-companion'),
				'search_items' => __('Search Links','webriti-companion'),
				'not_found' =>  __('No Links found','webriti-companion'),
				'not_found_in_trash' => __('No Links found in Trash','webriti-companion'), 
			),
			'supports' => array('title','editor','thumbnail'),
			'show_in' => true,
			'show_in_nav_menus' => false,
			'rewrite' => array('slug' => 'quality_portfolio'),
			'public' => true,
			'menu_position' =>20,
			'public' => true,
			'menu_icon' => WC__PLUGIN_URL . '/inc/quality/images/option-icon-media.png',
		)
	);
}
add_action( 'init', 'quality_portfolio_type' );

?>