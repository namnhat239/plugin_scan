<?php
/**
 * The output of the "Advanced Sidebar - Categories" widget.
 *
 * @since   8.8.0
 *
 * To edit copy this file to a folder in your theme called 'advanced-sidebar-menu' and edit at will.
 *
 * @notice  Do NOT edit this file in this location, or it will break on plugin update.
 *
 * @package advanced-sidebar-menu
 */

use Advanced_Sidebar_Menu\Menus\Category;

$current_menu = Category::get_current();
$child_terms = $current_menu->get_child_terms();
$content = '';

// Display parent category.
if ( $current_menu->include_parent() ) {
	$content .= '<ul class="parent-sidebar-menu" data-level="0">';

	$list_args = $current_menu->get_list_categories_args( Category::LEVEL_PARENT );
	$content .= $current_menu->openListItem( wp_list_categories( $list_args ) );
}

if ( ! empty( $child_terms ) ) {
	$content .= '<ul class="child-sidebar-menu" data-level="1">';

	// Always display child categories.
	if ( $current_menu->display_all() ) {
		$list_args = $current_menu->get_list_categories_args( Category::LEVEL_DISPLAY_ALL );
		$content .= wp_list_categories( $list_args );
	} else {
		foreach ( $child_terms as $_term ) {
			// Child terms.
			if ( ! $current_menu->is_excluded( $_term->term_id ) ) {
				$list_args = $current_menu->get_list_categories_args( Category::LEVEL_CHILD, $_term );
				$content .= $current_menu->openListItem( wp_list_categories( $list_args ) );

				// Grandchild terms.
				if ( $current_menu->is_current_term( $_term ) || $current_menu->is_current_term_ancestor( $_term ) ) {
					$content .= '<ul class="grandchild-sidebar-menu children" data-level="2">';

					$list_args = $current_menu->get_list_categories_args( Category::LEVEL_GRANDCHILD, $_term );
					$content .= wp_list_categories( $list_args );

					$content .= '</ul>';
				}

				$content .= '</li>';
			}
		}
	}

	$content .= '</ul><!-- End .child-sidebar-menu -->';
}

if ( $current_menu->include_parent() ) {
	$content .= '</li></ul><!-- End .parent-sidebar-menu -->';
}

return $content;
