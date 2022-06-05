<?php
/**
 * Function Custom meta box for Premium
 * 
 * @package WP Blog and Widget
 * @since 2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="pro-notice"><?php echo sprintf( __( 'Utilize these <a href="%s" target="_blank">Premium Features</a> to get best of this plugin.', 'wp-blog-and-widgets'), WPBAW_PLUGIN_LINK_UNLOCK); ?></div>
<table class="form-table wpbaw-metabox-table">
	<tbody>

		<tr class="wpbaw-pro-feature">
			<th>
				<?php _e('Layouts', 'wp-blog-and-widgets'); ?><span class="wpbaw-pro-tag"><?php _e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php _e('6 (Blog, Recent Blog, Slider, Gridbox , Gridbox Slider , List). In lite version only 1 layout.', 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php _e('Designs', 'wp-blog-and-widgets'); ?><span class="wpbaw-pro-tag"><?php _e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php _e('120+. In lite version only one design.', 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php _e('Widgets', 'wp-blog-and-widgets'); ?><span class="wpbaw-pro-tag"><?php _e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php _e('6. In lite version only one widget.', 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php _e('WP Templating Features ', 'wp-blog-and-widgets'); ?><span class="wpbaw-pro-tag"><?php _e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php _e('You can modify plugin html/designs in your current theme.', 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php _e('Shortcode Generator ', 'wp-blog-and-widgets'); ?><span class="wpbaw-pro-tag"><?php _e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Play with all shortcode parameters with preview panel. No documentation required.' , 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php _e('Drag & Drop Slide Order Change', 'wp-blog-and-widgets'); ?><span class="wpbaw-pro-tag"><?php _e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Arrange your desired slides with your desired order and display.' , 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php _e('Page Builder Support', 'wp-blog-and-widgets'); ?><span class="wpbaw-pro-tag"><?php _e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Gutenberg Block, Elementor, Bevear Builder, SiteOrigin, Divi, Visual Composer and Fusion Page Builder Support', 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
		<tr class="wpbaw-pro-feature">
			<th>
				<?php _e('Exclude Blog and Exclude Some Categories', 'wp-blog-and-widgets'); ?><span class="wpbaw-pro-tag"><?php _e('PRO','wp-blog-and-widgets');?></span>
			</th>
			<td>
				<span class="description"><?php _e('Do not display the blog & Do not display the blogs for particular categories.' , 'wp-blog-and-widgets'); ?></span>
			</td>
		</tr>
	</tbody>
</table><!-- end .wpbaw-metabox-table -->

