<?php
/**
 * List Shortcode
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Meta_Boxes
 * @version     1.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SP_Meta_Box_List_Shortcode
 */
class SP_Meta_Box_List_Shortcode {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$the_format = get_post_meta( $post->ID, 'sp_format', true );
		if ( ! $the_format ) {
			$the_format = 'list';
		}
		?>
		<p class="howto">
			<?php esc_html_e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
		<p><input type="text" value="<?php sp_shortcode_template( 'player_' . $the_format, $post->ID ); ?>" readonly="readonly" class="code widefat"></p>
		<?php
	}
}
