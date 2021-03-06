<?php
/**
 * Team Details
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Meta_Boxes
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SP_Meta_Box_Team_Details
 */
class SP_Meta_Box_Team_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );

		if ( taxonomy_exists( 'sp_league' ) ) :
			$leagues    = get_the_terms( $post->ID, 'sp_league' );
			$league_ids = array();
			if ( $leagues ) :
				foreach ( $leagues as $league ) :
					$league_ids[] = $league->term_id;
				endforeach;
			endif;
		endif;

		if ( taxonomy_exists( 'sp_season' ) ) :
			$seasons    = get_the_terms( $post->ID, 'sp_season' );
			$season_ids = array();
			if ( $seasons ) :
				foreach ( $seasons as $season ) :
					$season_ids[] = $season->term_id;
				endforeach;
			endif;
		endif;

		if ( taxonomy_exists( 'sp_venue' ) ) :
			$venues    = get_the_terms( $post->ID, 'sp_venue' );
			$venue_ids = array();
			if ( $venues ) :
				foreach ( $venues as $venue ) :
					$venue_ids[] = $venue->term_id;
				endforeach;
			endif;
		endif;

		$short_name   = get_post_meta( $post->ID, 'sp_short_name', true );
		$abbreviation = get_post_meta( $post->ID, 'sp_abbreviation', true );
		$redirect     = get_post_meta( $post->ID, 'sp_redirect', true );
		$url          = get_post_meta( $post->ID, 'sp_url', true );
		?>

		<?php if ( taxonomy_exists( 'sp_league' ) ) { ?>
		<p><strong><?php esc_attr_e( 'Leagues', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'taxonomy'    => 'sp_league',
				'name'        => 'tax_input[sp_league][]',
				'selected'    => $league_ids,
				'values'      => 'term_id',
				'placeholder' => sprintf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Leagues', 'sportspress' ) ),
				'class'       => 'widefat',
				'property'    => 'multiple',
				'chosen'      => true,
			);
			sp_dropdown_taxonomies( $args );
			?>
		</p>
		<?php } ?>

		<?php if ( taxonomy_exists( 'sp_season' ) ) { ?>
		<p><strong><?php esc_attr_e( 'Seasons', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'taxonomy'    => 'sp_season',
				'name'        => 'tax_input[sp_season][]',
				'selected'    => $season_ids,
				'values'      => 'term_id',
				'placeholder' => sprintf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Seasons', 'sportspress' ) ),
				'class'       => 'widefat',
				'property'    => 'multiple',
				'chosen'      => true,
			);
			sp_dropdown_taxonomies( $args );
			?>
		</p>
		<?php } ?>

		<?php if ( taxonomy_exists( 'sp_venue' ) ) { ?>
		<p><strong><?php esc_attr_e( 'Home', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'taxonomy'    => 'sp_venue',
				'name'        => 'tax_input[sp_venue][]',
				'selected'    => $venue_ids,
				'values'      => 'term_id',
				'placeholder' => sprintf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Venue', 'sportspress' ) ),
				'class'       => 'widefat',
				'property'    => 'multiple',
				'chosen'      => true,
			);
			sp_dropdown_taxonomies( $args );
			?>
		</p>
		<?php } ?>

		<p><strong><?php esc_attr_e( 'Site URL', 'sportspress' ); ?></strong></p>
		<p><input type="text" class="widefat" id="sp_url" name="sp_url" value="<?php echo esc_url( $url ); ?>"></p>
		<p><label class="selectit"><input type="checkbox" name="sp_redirect" value="1" <?php checked( $redirect ); ?>> <?php esc_attr_e( 'Redirect', 'sportspress' ); ?></label></p>

		<p><strong><?php esc_attr_e( 'Short Name', 'sportspress' ); ?></strong></p>
		<p><input type="text" id="sp_short_name" name="sp_short_name" value="<?php echo esc_attr( $short_name ); ?>"></p>

		<p><strong><?php esc_attr_e( 'Abbreviation', 'sportspress' ); ?></strong></p>
		<p><input type="text" id="sp_abbreviation" name="sp_abbreviation" value="<?php echo esc_attr( $abbreviation ); ?>"></p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_url', esc_url( sp_array_value( $_POST, 'sp_url', '', 'text' ) ) );
		update_post_meta( $post_id, 'sp_redirect', sp_array_value( $_POST, 'sp_redirect', 0, 'int' ) );
		update_post_meta( $post_id, 'sp_short_name', esc_attr( sp_array_value( $_POST, 'sp_short_name', '', 'text' ) ) );
		update_post_meta( $post_id, 'sp_abbreviation', esc_attr( sp_array_value( $_POST, 'sp_abbreviation', '', 'text' ) ) );
	}
}
