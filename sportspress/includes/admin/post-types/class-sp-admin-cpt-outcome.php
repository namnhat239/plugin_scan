<?php
/**
 * Admin functions for the outcomes post type
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Post_Types
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Admin_CPT' ) ) {
	require 'class-sp-admin-cpt.php';
}

if ( ! class_exists( 'SP_Admin_CPT_Outcome' ) ) :

	/**
	 * SP_Admin_CPT_Outcome Class
	 */
	class SP_Admin_CPT_Outcome extends SP_Admin_CPT {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->type = 'sp_outcome';

			// Admin Columns
			add_filter( 'manage_edit-sp_outcome_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_sp_outcome_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

			// Call SP_Admin_CPT constructor
			parent::__construct();
		}

		/**
		 * Change the columns shown in admin.
		 */
		public function edit_columns( $existing_columns ) {
			$columns = array(
				'cb'              => '<input type="checkbox" />',
				'title'           => esc_attr__( 'Label', 'sportspress' ),
				'sp_key'          => esc_attr__( 'Variable', 'sportspress' ),
				'sp_abbreviation' => esc_attr__( 'Abbreviation', 'sportspress' ),
				'sp_description'  => esc_attr__( 'Description', 'sportspress' ),
			);
			return apply_filters( 'sportspress_outcome_admin_columns', $columns );
		}

		/**
		 * Define our custom columns shown in admin.
		 *
		 * @param  string $column
		 */
		public function custom_columns( $column, $post_id ) {
			switch ( $column ) :
				case 'sp_key':
					global $post;
					echo esc_html( $post->post_name );
					break;
				case 'sp_abbreviation':
					global $post;
					echo esc_html( sp_get_post_abbreviation( $post->ID ) );
					break;
				case 'sp_description':
					global $post;
					echo '<span class="description">' . wp_kses_post( $post->post_excerpt ) . '</span>';
					break;
			endswitch;
		}
	}

endif;

return new SP_Admin_CPT_Outcome();
