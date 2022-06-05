<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="wrap wpnwm-wrap">
	<style type="text/css">
		.wpos-box{box-shadow: 0 5px 30px 0 rgba(214,215,216,.57);background: #fff; padding-bottom:10px; position:relative;}
		.wpos-box ul{padding: 15px;}
		.wpos-box h5{background:#555; color:#fff; padding:15px; text-align:center;}
		.wpos-box h4{ padding:0 15px; margin:5px 0; font-size:18px;}
		.wpos-box .button{margin:0px 15px 15px 15px; text-align:center; padding:7px 15px; font-size:15px;display:inline-block;}
		.wpos-box .wpos-list{list-style:square; margin:10px 0 0 20px;}
		.wpos-clearfix:before, .wpos-clearfix:after{content: "";display: table;}
		.wpos-clearfix::after{clear: both;}
		.wpos-clearfix{clear: both;}
		.wpos-col{width: 47%; float: left; margin-right:10px; margin-bottom:10px;}
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wpnwm-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpnwm-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.button-orange{background: #ff5d52 !important;border-color: #ff5d52 !important; font-weight: 600;}
		.button-blue{background: #0055fb !important;border-color: #0055fb !important; font-weight: 600;}
	</style>
	<h2><?php _e( 'How It Works', 'sp-news-and-widget' ); ?></h2>
	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">

				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="meta-box-sortables">

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'How It Works - Display and Shortcode', 'sp-news-and-widget' ); ?></span>
								</h2>
							</div>

							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<th>
												<label><?php _e('Getting Started', 'sp-news-and-widget'); ?></label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1: This plugin create a News menu tab in WordPress menu with custom post type.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-2: Go to "News > Add news item tab".', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-3: Add news title, description, category, and image as featured image.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-4: Repeat this process and add multiple news item.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-4: To display news category wise you can use category shortcode under "News > News category"', 'sp-news-and-widget'); ?></li>
												</ul>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php _e('How Shortcode Works', 'sp-news-and-widget'); ?></label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1. Create a page like Our News OR Latest News.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('<b>Please make sure that Permalink link should not be "/news" Otherwise all your news will go to archive page. You can give it other name like "/ournews, /latestnews etc"</b>', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-2. Put below shortcode as per your need.', 'sp-news-and-widget'); ?></li>
												</ul>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php _e('All Shortcodes', 'sp-news-and-widget'); ?></label>
											</th>
											<td>
												<span class="wpos-copy-clipboard wpnwm-shortcode-preview">[sp_news grid="list"]</span> – <?php _e('News in List View', 'sp-news-and-widget'); ?> <br />
												<span class="wpos-copy-clipboard wpnwm-shortcode-preview">[sp_news grid="1"]</span> – <?php _e('Display News in grid 1', 'sp-news-and-widget'); ?> <br />
												<span class="wpos-copy-clipboard wpnwm-shortcode-preview">[sp_news grid="2"]</span> – <?php _e('Display News in grid 2', 'sp-news-and-widget'); ?> <br />
												<span class="wpos-copy-clipboard wpnwm-shortcode-preview">[sp_news grid="3"]</span> – <?php _e('Display News in grid 3', 'sp-news-and-widget'); ?>
											</td>
										</tr>
										<tr>
											<th>
												<label><?php _e('Documentation', 'sp-news-and-widget'); ?>:</label>
											</th>
											<td>
												<a class="button button-primary" href="https://docs.essentialplugin.com/wp-news-and-scrolling-widgets/" target="_blank"><?php _e('Check Documentation', 'sp-news-and-widget'); ?></a>
											</td>
										</tr>
										<tr>
											<th>
												<label><?php _e('Demo', 'sp-news-and-widget'); ?>:</label>
											</th>
											<td>
												<a class="button button-primary" href="https://demo.essentialplugin.com/sp-news/" target="_blank"><?php _e('Check Free Demo', 'sp-news-and-widget'); ?></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div><!-- .inside -->
						</div><!-- #general -->

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Gutenberg Support', 'sp-news-and-widget' ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<th>
												<label><?php _e('How it Work', 'sp-news-and-widget'); ?>:</label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1. Go to the Gutenberg editor of your page.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-2. Search "news" keyword in the Gutenberg block list.', 'sp-news-and-widget'); ?></li>
													<li><?php _e('Step-3. Add any block of news and you will find its relative options on the right end side.', 'sp-news-and-widget'); ?></li>
												</ul>
											</td>
										</tr>											
									</tbody>
								</table>
							</div><!-- .inside -->
						</div><!-- #general -->

						

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Help to improve this plugin!', 'sp-news-and-widget' ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<p><?php _e( 'Enjoyed this plugin? You can help by rate this plugin ', 'sp-news-and-widget' ); ?><a href="https://wordpress.org/support/plugin/sp-news-and-widget/reviews/#new-post" target="_blank"><?php _e( '5 stars!', 'sp-news-and-widget' ); ?></a></p>
							</div><!-- .inside -->
						</div><!-- #general -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #post-body-content -->

				<!--Upgrad to Pro HTML -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="meta-box-sortables">
						<div class="postbox wpos-pro-box">

							<h3 class="hndle">
								<span><?php _e( 'News Premium Features', 'sp-news-and-widget' ); ?></span>
							</h3>
							<div class="inside">
								<ul class="wpos-list">
									<li>120+ stunning and cool designs</li>
									<li>6 shortcodes</li>
									<li>50 Designs for News Grid Layout.</li>
									<li>45 Designs for News Slider/Carousel Layout.</li>
									<li>8 Designs for News List View.</li>
									<li>3 Designs News Grid Box.</li>
									<li>8 Designs News Grid Box Slider.</li>
									<li>WPBakery Page Builder Supports</li>
									<li>Gutenberg, Elementor, Beaver and SiteOrigin Page Builder Support. <span class="wpos-new-feature">New</span></li>
									<li>Divi Page Builder Native Support. <span class="wpos-new-feature">New</span></li>
									<li>Fusion (Avada) Page Builder Native Support. <span class="wpos-new-feature">New</span></li>
									<li>WP Templating Features</li>
									<li>News Ticker.</li>
									<li>7 different types of Latest News widgets.</li>
									<li>Recent News Slider</li>
									<li>Recent News Carousel</li>
									<li>Recent News in Grid view</li>
									<li>Create a News Page OR News website</li>
									<li>Custom Read More link for News Post</li>
									<li>News display with categories</li>
									<li>Drag & Drop feature to display News post in your desired order and other 6 types of order parameter</li>
									<li>Publicize' support with Jetpack to publish your News post on your social network</li>
									<li>Custom CSS</li>
									<li>100% Multi language</li>
								</ul>
								<div class="upgrade-to-pro"><?php echo sprintf( __( 'Gain access to <strong>WP News and Scrolling Widgets</strong>', 'sp-news-and-widget' ) ); ?></div>
								<a class="button button-primary wpos-button-full button-orange" href="<?php echo WPNW_PLUGIN_LINK_UPGRADE; ?>" target="_blank"><?php _e('Grab News Now', 'sp-news-and-widget'); ?></a>
							</div><!-- .inside -->
						</div><!-- #general -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #post-container-1 -->

			</div><!-- #post-body -->
		</div><!-- #poststuff -->
	</div>
</div>