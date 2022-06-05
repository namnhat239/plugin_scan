<?php
/**
 * Opinionstage Create Admin page
 *
 * @package OpinionStageWordPressPlugin
 */

defined( 'ABSPATH' ) || die();
?>

<style type="text/css">
	.content-item-image.quiz{
		background-image: url(<?php echo esc_url( plugins_url( '', dirname( __FILE__ ) ) . '/images/form-not-found.png' ); ?>);
	}
</style>
<div id="opinionstage-content">
	<div opinionstage-my-items-view class="opinionstage-my-items-view">
	</div>
	<div id="opinionistage-my-items-page-modal-wrapper">
		<div class="opinionistage-my-items-page-modal">
			<div class="inner">
				<span id="opinionstage-dialog-close" class="opinionstage-close"></span>
				<div id="published-item-details">
					<ul>
						<li><a href="https://help.opinionstage.com/en/articles/5161692-how-to-add-items-to-a-post-page" target="_blank"><?php esc_html_e( 'How to add to a post/page', 'social-polls-by-opinionstage' ); ?></a></li>
						<li><a href="https://help.opinionstage.com/en/articles/5161716-how-to-add-an-item-to-a-sidebar-widget" target="_blank"><?php esc_html_e( 'How to add to a sidebar Widget', 'social-polls-by-opinionstage' ); ?></a></li>
						<li><a href="https://help.opinionstage.com/en/articles/5161746-how-to-add-an-item-as-a-popup-on-wordpress" target="_blank"><?php esc_html_e( 'How to add as a popup', 'social-polls-by-opinionstage' ); ?></a></li>
						<li><a href="https://help.opinionstage.com/en/articles/5161782-how-to-add-an-item-using-the-wordpress-shortcode" target="_blank"><?php esc_html_e( 'How to add with the WordPress shortcode', 'social-polls-by-opinionstage' ); ?></a></li>
					</ul>
					<div class="opinionstage-textarea-wrapper">
						<textarea name="opinionstage-widget-shortcode" id="opinionstage-widget-shortcode" data-wp-embed-code rows="2" readonly="readonly"></textarea> <a data-copy-text-from="data-wp-embed-code" href="#" class="no-text-decoration">Copy</a>
					</div>
				</div>
				<p>
					<?php esc_html_e( 'Need Help?', 'social-polls-by-opinionstage' ); ?>
					<a href="<?php echo esc_url( OPINIONSTAGE_LIVE_CHAT_URL_UTM ); ?>" target="_blank"><?php esc_html_e( 'Contact Us' ); ?></a></p>
				</p>
			</div>
		</div>
	</div>
</div>
