<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package WP Blog and Widget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="wrap wpbawh-wrap">
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
		.wpbawh-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpbawh-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.wpos-new-feature{ font-size: 10px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
		.button-orange{background: #ff5d52 !important;border-color: #ff5d52 !important; font-weight: 600;}
		.button-blue{background: #0055fb !important;border-color: #0055fb !important; font-weight: 600;}
	</style>
	<h2><?php _e( 'How It Works', 'wp-blog-and-widgets' ); ?></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">

			<!--How it workd HTML -->
			<div id="post-body-content">

				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php _e( 'How It Works - Display and Shortcode', 'wp-blog-and-widgets' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php _e('Getting Started', 'wp-blog-and-widgets'); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php _e('Step-1: This plugin create a BLOG menu tab in WordPress menu with custom post type.".', 'wp-blog-and-widgets'); ?></li>
												<li><?php _e('Step-2: Go to "Blog --> Add blog tab".', 'wp-blog-and-widgets'); ?></li>
												<li><?php _e('Step-3: Add blog title, description, category, and images as featured image.', 'wp-blog-and-widgets'); ?></li>
												<li><?php _e('Step-4: <b>NOTE</b> :- If you want to create a blog page with WordPress existing POST section then try our other plugin --', 'wp-blog-and-widgets'); ?> <a href="https://www.essentialplugin.com/wordpress-plugin/blog-designer-post-and-widget/" target="_blank">Blog Designer – Post and Widget</a></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php _e('How Shortcode Works', 'wp-blog-and-widgets'); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php _e('Step-1. Create a page like Blog OR Our Blog.', 'wp-blog-and-widgets'); ?></li>
												<li><?php _e('Step-2. Put below shortcode as per your need.', 'wp-blog-and-widgets'); ?></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php _e('All Shortcodes', 'wp-blog-and-widgets'); ?>:</label>
										</th>
										<td>
											<span class="wpbawh-shortcode-preview wpos-copy-clipboard">[blog]</span> – <?php _e('Blog in List View', 'wp-blog-and-widgets'); ?> <br />
											<span class="wpbawh-shortcode-preview wpos-copy-clipboard">[blog grid="1"]</span> – <?php _e('Display blog in grid 1', 'wp-blog-and-widgets'); ?> <br />
											<span class="wpbawh-shortcode-preview wpos-copy-clipboard">[blog grid="2"]</span> – <?php _e('Display blog in grid 2', 'wp-blog-and-widgets'); ?> <br />
											<span class="wpbawh-shortcode-preview wpos-copy-clipboard">[blog grid="3"]</span> – <?php _e('Display blog in grid 3', 'wp-blog-and-widgets'); ?><br />
											<span class="wpbawh-shortcode-preview wpos-copy-clipboard">[recent_blog_post]</span> – <?php _e('Display recent blog posts', 'wp-blog-and-widgets'); ?>
										</td>
									</tr>
									<tr>
										<th>
											<label><?php _e('Documentation', 'wp-blog-and-widgets'); ?>:</label>
										</th>
										<td>
											<a class="button button-primary" href="https://docs.essentialplugin.com/wp-blog-and-widgets/" target="_blank"><?php _e('Check Documentation', 'wp-blog-and-widgets'); ?></a>
										</td>
									</tr>
									<tr>
										<th>
											<label><?php _e('Demo', 'wp-blog-and-widgets'); ?></label>
										</th>
										<td>
											<a class="button button-primary" href="https://demo.essentialplugin.com/blog-demo/" target="_blank"><?php _e('Check Free Demo', 'wp-blog-and-widgets'); ?></a>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->

				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php _e( 'Gutenberg Support', 'wp-blog-and-widgets' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php _e('How it Work', 'wp-blog-and-widgets'); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php _e('Step-1. Go to the Gutenberg editor of your page.', 'wp-blog-and-widgets'); ?></li>
												<li><?php _e('Step-2. Search "blog post" keyword in the Gutenberg block list.', 'wp-blog-and-widgets'); ?></li>
												<li><?php _e('Step-3. Add any block of blog post and you will find its relative options on the right end side.', 'wp-blog-and-widgets'); ?></li>
											</ul>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->

				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php _e( 'Need Support & Solutions?', 'wp-blog-and-widgets' ); ?></span>
							</h2>
						</div>
						<div class="inside wpos-clearfix">
							<div class="wpos-col">
								<div class="wpos-box">
									<h5 style="font-size: 18px;line-height: 30px;margin: 10px 0px; background:#0055fb;">Blog and Widget Premium Features</h5>
									<h4>Single Plugin</h4>
									<ul class="wpos-list">
										<li>50 Designs for Grid Layout</li>
										<li>45 Designs for Slider/Carousel</li>
										<li>8 Designs for List View</li>
										<li>13 Designs for Grid Box</li>
										<li>8 Designs for News Grid Box Slider</li>
										<li>6 Shortcodes</li>
										<li>6 Widgets (slider,list and category etc)</li>
										<li><a href="<?php echo WPBAW_PLUGIN_LINK_UPGRADE; ?>" target="_blank">View More All Features</a></li>
									</ul>
									<div style="text-align:center;">
										<a class="button button-primary button-blue" href="<?php echo WPBAW_PLUGIN_LINK_UPGRADE; ?>" target="_blank"><?php _e('Grab Now', 'wp-blog-and-widgets'); ?></a>
									</div>
								</div>
							</div>
							<div class="wpos-col">
								<div class="wpos-box">
									<h5 style="font-size: 18px;line-height: 30px;margin: 10px 0px; background:#ff5d52;">Essential Bundle With Blog and Widget</h5>
									<h4>Bundle Deal</h4>
									<ul style="margin: 0;list-style: none;font-size: 16px;">
										<li style="margin-bottom:8px;">
											<span style="display:inline-block;vertical-align: middle;"><img src="<?php echo WPBAW_URL; ?>assets/images/utility-50.png" width="24"></span> <span style="display:inline-block;vertical-align: middle;">39 Utility Plugins</span>
										</li>
										<li style="margin-bottom:8px;">
											<span style="display:inline-block;vertical-align: middle;"><img src="<?php echo WPBAW_URL; ?>assets/images/inboundwp-50.png" width="24"></span> <span style="display:inline-block;vertical-align: middle;">6 Marketing Tools</span>
										</li>
										<li style="margin-bottom:8px;">
											<span style="display:inline-block;vertical-align: middle;"><img src="<?php echo WPBAW_URL; ?>assets/images/SlidersPack-50.png" width="24"></span> <span style="display:inline-block;vertical-align: middle;"> 10 SlidersPack</span>
										</li>
										<li style="margin-bottom:8px;">
											<span style="display:inline-block;vertical-align: middle;"><img src="<?php echo WPBAW_URL; ?>assets/images/popup-anything-icon.png" width="24"></span> <span style="display:inline-block;vertical-align: middle;"> Popup Anything A Marketing Popup</span>
										</li>
										<li>
											<span style="display:inline-block;vertical-align: middle;"><img src="<?php echo WPBAW_URL; ?>assets/images/security-icon.png" width="24"></span> <span style="display:inline-block;vertical-align: middle;"> Essential Security</span>
										</li>
									</ul>
									<div style="text-align:center;">
										<a class="button button-primary button-orange" href="<?php echo WPBAW_PLUGIN_BUNDLE_LINK; ?>" target="_blank"><?php _e('Grab Now', 'wp-blog-and-widgets'); ?></a>
									</div>
								</div>
							</div>
							<div class="wpos-clearfix">
								<img src="<?php echo WPBAW_URL; ?>assets/images/page-builder-support.jpg" style="max-width:100% " />
							</div>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->

				<!-- Help to improve this plugin! -->
				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php _e( 'Help to improve this plugin!', 'wp-blog-and-widgets' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<p><?php echo sprintf( __( 'Enjoyed this plugin? You can help by rate this plugin <a href="%s" target="_blank">5 stars!', 'wp-blog-and-widgets'), 'https://wordpress.org/support/plugin/wp-blog-and-widgets/reviews/' ); ?></a></p>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->

			</div><!-- #post-body-content -->

			<!--Upgrad to Pro HTML -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox wpos-pro-box">

						<h3 class="hndle">
							<span><?php _e( 'Blog and Widegt Premium Features', 'wp-blog-and-widgets' ); ?></span>
						</h3>
						<div class="inside">
							<ul class="wpos-list">
								<li>50 Designs for Grid Layout</li>
								<li>45 Designs for Slider/Carousel</li>
								<li>8 Designs for List View</li>
								<li>13 Designs for Grid Box</li>
								<li>8 Designs for News Grid Box Slider</li>
								<li>6 Shortcodes</li>
								<li>6 Widgets (slider,list and category etc)</li>
								<li>Gutenberg Block Supports</li>
								<li>WPBakery Page Builder Supports</li>
								<li>Gutenberg, Elementor, Beaver and SiteOrigin Page Builder Support <span class="wpos-new-feature">New</span></li>
								<li>Divi Page Builder Native Support <span class="wpos-new-feature">New</span></li>
								<li>Fusion Page Builder (Avada) native support <span class="wpos-new-feature">New</span></li>
								<li>WP Templating Features</li>
								<li>Fully Responsive and Touch Based Slider</li>
								<li>Custom Read More link for Blog Post</li>
								<li>Blog display with categories</li>
								<li>Drag & Drop feature to display Blog post in your desired order and other 6 types of order parameter</li>
								<li>Publicize' support with Jetpack to publish your Blog post on your social network</li>
								<li>Custom CSS</li>
								<li>100% Multi language</li>
							</ul>
							<div class="upgrade-to-pro">Gain access to <strong>WP Blog and Widget</strong></div>
							<a class="button button-primary wpos-button-full button-orange" href="<?php echo WPBAW_PLUGIN_LINK_UPGRADE; ?>" target="_blank"><?php _e('Grab Blog and Widget Now', 'wp-blog-and-widgets'); ?></a>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->
			</div><!-- #post-container-1 -->

		</div><!-- #post-body -->
	</div><!-- #poststuff -->
</div><!-- end .wpbawh-wrap -->