<?php
/*
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Create a group
 *
 * @param int $group_id
 * @param int $member
 * @param int $group
 */
if ( ! function_exists( 'ualBuddyPressCreateGroup' ) ) {

	function ualBuddyPressCreateGroup( $group_id, $member, $group ) {
		$action     = 'Group created';
		$obj_type   = 'BuddyPress';
		$post_id    = $group->id;
		$post_title = 'Group created ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );

	}
}
add_action( 'groups_create_group', 'ualBuddyPressCreateGroup', 15, 3 );

/**
 * Update a group
 *
 * @param int $group_id
 * @param int $group
 */

if ( ! function_exists( 'ualBuddyPressUpdateGroup' ) ) {

	function ualBuddyPressUpdateGroup( $group_id, $group ) {
		$action     = 'Group Update';
		$obj_type   = 'BuddyPress';
		$post_id    = $group->id;
		$post_title = 'Group Update ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );

	}
}
add_action( 'groups_update_group', 'ualBuddyPressUpdateGroup', 15, 2 );

/**
 * Delete a group
 *
 * @param int $group_id
 */

if ( ! function_exists( 'ualBuddyPressDeleteGroup' ) ) {

	function ualBuddyPressDeleteGroup( $group_id ) {
		$action   = 'Group Delete';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = 'Group Delete ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}


add_action( 'groups_before_delete_group', 'ualBuddyPressDeleteGroup', 15, 1 );

/**
 * Leave a group for user
 *
 * @param int $group_id
 * @param int $user_id
 */

if ( ! function_exists( 'ualBuddyPressLeaveGroup' ) ) {

	function ualBuddyPressLeaveGroup( $group_id, $user_id ) {
		$action   = 'Leave Group';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Leave Group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_leave_group', 'ualBuddyPressLeaveGroup', 15, 2 );

/**
 * join a group
 *
 * @param int $group_id
 * @param int $user_id
 */

if ( ! function_exists( 'ualBuddyPressJoinGroup' ) ) {

	function ualBuddyPressJoinGroup( $group_id, $user_id ) {
		$action   = 'Join Group';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Join Group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_join_group', 'ualBuddyPressJoinGroup', 15, 2 );

/**
 * Promote a group
 *
 * @param int $group_id
 * @param int $user_id
 */

if ( ! function_exists( 'ualBuddyPressPromoteGroup' ) ) {

	function ualBuddyPressPromoteGroup( $group_id, $user_id, $status ) {
		$action   = 'promoted Group';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Promoted Group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_promote_member', 'ualBuddyPressPromoteGroup', 15, 3 );

/**
 * Demote a group
 *
 * @param int $group_id
 * @param int $user_id
 */

if ( ! function_exists( 'ualBuddyPressDemoteGroup' ) ) {

	function ualBuddyPressDemoteGroup( $group_id, $user_id ) {
		$action   = 'demoted Group';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Demoted Group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}

add_action( 'groups_demote_member', 'ualBuddyPressDemoteGroup', 15, 2 );

/**
 * Ban a group
 *
 * @param int $group_id
 * @param int $user_id
 */

if ( ! function_exists( 'ualBuddyPressBanGroup' ) ) {

	function ualBuddyPressBanGroup( $group_id, $user_id ) {
		$action   = 'Ban Member';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Ban Member ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_ban_member', 'ualBuddyPressBanGroup', 15, 2 );

/**
 * Unban a group
 *
 * @param int $group_id
 * @param int $user_id
 */


if ( ! function_exists( 'ualBuddyPressUnBanGroup' ) ) {

	function ualBuddyPressUnBanGroup( $group_id, $user_id ) {
		$action   = 'unban member';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Unban Member ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_unban_member', 'ualBuddyPressUnBanGroup', 15, 2 );

/**
 * Remove member a group
 *
 * @param int $group_id
 * @param int $user_id
 */


if ( ! function_exists( 'ualBuddyPressRemoveMemberGroup' ) ) {

	function ualBuddyPressRemoveMemberGroup( $group_id, $user_id ) {
		$action   = 'Remove Member';
		$obj_type = 'BuddyPress';
		$post_id  = $group_id;
		$user     = get_user_by( 'id', $user_id );
		if ( is_numeric( $group_id ) ) {
			$group = groups_get_group(
				array(
					'group_id' => $group_id,
				)
			);
		}
		$post_title = $user->display_name . ' Remove Member ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'groups_remove_member', 'ualBuddyPressRemoveMemberGroup', 15, 2 );

/**
 * Profile field save
 *
 * @param int $field
 */

if ( ! function_exists( 'ualBuddyPressProfileFieldSave' ) ) {

	function ualBuddyPressProfileFieldSave( $field ) {
		$action     = isset( $field->id ) ? 'Updated' : 'Created';
		$obj_type   = 'BuddyPress';
		$post_id    = '';
		$post_title = $action . ' profile field group ' . $field->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'xprofile_field_after_save', 'ualBuddyPressProfileFieldSave', 15, 1 );

/**
 * Profile field delete
 *
 * @param int $field
 */

if ( ! function_exists( 'ualBuddyPressProfileFieldDelete' ) ) {

	function ualBuddyPressProfileFieldDelete( $field ) {
		$action     = 'Deleted';
		$obj_type   = 'BuddyPress';
		$post_id    = '';
		$post_title = $action . ' profile field group ' . $field->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'xprofile_fields_deleted_field', 'ualBuddyPressProfileFieldDelete', 15, 1 );

/**
 *  Create/Update Profile Field Group
 *
 * @param int $group
 */

if ( ! function_exists( 'ualBuddyPressProfileGroupSave' ) ) {

	function ualBuddyPressProfileGroupSave( $group ) {
		global $wpdb;
		$action     = ( $group->id === $wpdb->insert_id ) ? 'created' : 'updated';
		$obj_type   = 'BuddyPress';
		$post_id    = '';
		$post_title = $action . ' profile field group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'xprofile_group_after_save', 'ualBuddyPressProfileGroupSave', 15, 1 );

/**
 * Deleted Profile Field Group
 *group
 *
 * @param int $field
 */

if ( ! function_exists( 'ualBuddyPressProfileGroupDelete' ) ) {

	function ualBuddyPressProfileGroupDelete( $group ) {
		$action     = 'Deleted';
		$obj_type   = 'BuddyPress';
		$post_id    = '';
		$post_title = $action . ' profile field group ' . $group->name;
		ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}
add_action( 'xprofile_groups_deleted_group', 'ualBuddyPressProfileGroupDelete', 15, 1 );
