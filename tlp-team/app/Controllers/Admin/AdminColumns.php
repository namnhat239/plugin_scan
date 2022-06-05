<?php
/**
 * CPT Admin Columns Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin;

/**
 * Admin Columns Class.
 */
class AdminColumns {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_filter( 'manage_edit-team_columns', array( $this, 'arrange_team_columns' ) );
		add_action( 'manage_team_posts_custom_column', array( $this, 'manage_team_columns' ), 10, 2 );
		add_filter( 'manage_edit-team-sc_columns', array( $this, 'arrange_team_sc_columns' ) );
		add_action( 'manage_team-sc_posts_custom_column', array( $this, 'manage_team_sc_columns' ), 10, 2 );
		add_filter( 'manage_edit-team_sortable_columns', array( $this, 'team_column_sort' ) );
	}

	public function arrange_team_columns( $columns ) {
		$column_thumbnail = array( 'thumbnail' => esc_html__( 'Image', 'tlp-team' ) );
		$column_email     = array( 'email' => esc_html__( 'Email', 'tlp-team' ) );
		$column_location  = array( 'location' => esc_html__( 'Location', 'tlp-team' ) );
		return array_slice( $columns, 0, 2, true ) + $column_thumbnail + $column_email + $column_location + array_slice( $columns, 1, null, true );
	}

	public function arrange_team_sc_columns( $columns ) {
		$shortcode = array( 'shortcode' => esc_html__( 'TLP Team Shortcode', 'tlp-team' ) );
		return array_slice( $columns, 0, 2, true ) + $shortcode + array_slice( $columns, 1, null, true );
	}

	public function manage_team_columns( $column ) {

		switch ( $column ) {
			case 'thumbnail':
				echo get_the_post_thumbnail( get_the_ID(), array( 35, 35 ) );
				break;
			case 'designation':
				echo get_post_meta( get_the_ID(), 'designation', true );
				break;
			case 'email':
				echo get_post_meta( get_the_ID(), 'email', true );
				break;
			case 'location':
				echo get_post_meta( get_the_ID(), 'location', true );
				break;
			default:
				break;
		}
	}

	public function manage_team_sc_columns( $column ) {
		switch ( $column ) {
			case 'shortcode':
				echo '<input type="text" onfocus="this.select();" readonly="readonly" value="[tlpteam id=&quot;' . get_the_ID() . '&quot; title=&quot;' . get_the_title() . '&quot;]" class="large-text code tlp-code-sc">';
				break;
			default:
				break;
		}
	}

	function team_column_sort( $columns ) {
		$custom = array(
			'designation' => 'designation',
			'email'       => 'email',
			'location'    => 'location',
		);
		return wp_parse_args( $custom, $columns );
	}
}
