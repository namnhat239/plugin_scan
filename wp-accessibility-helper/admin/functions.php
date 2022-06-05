<?php
/**
 * WP Accessible Helper
 *
 * @package WAH
 */

/**
 * Get page number
 *
 * @return string page number
 */
function wah_get_page_number() {
	if ( get_query_var( 'page' ) ) {
		$paged = get_query_var( 'page' );
	} elseif ( ! empty( $_GET['paged'] ) && is_numeric( $_GET['paged'] ) ) {
		$paged = (int) $_GET['paged'];
	} else {
		$paged = 1;
	}
	return $paged;
}
/**
 * Get allowed types
 *
 * @return array types
 */
function wah_get_allowed_types() {
	$allow_types = array( 'attachment' );
	return $allow_types;
}

function wah_get_posts_counter( $type, $status = null ) {
	if ( in_array( $type, wah_get_allowed_types() ) ) {
		if ( empty( $status ) || ! is_array( $status ) ) {
			$status = array( 'publish', 'inherit' );
		}
		$post_counter = 0;
		$count_posts  = wp_count_posts( $type );
		if ( isset( $count_posts ) ) {
			foreach ( $status as $st ) {
				$post_counter = $post_counter + $count_posts->{ sanitize_text_field( $st ) };
			}
		}
		return $post_counter;
	}
}
/**
 * WAH get pagination
 *
 * @param  string  $type                         type name.
 * @param  integer $posts_per_page               posts per page.
 */
function wah_get_pagination( $type, $posts_per_page = 10 ) {
	if ( in_array( $type, wah_get_allowed_types() ) ) {
		$post_counter    = wah_get_posts_counter( 'attachment', array( 'inherit' ) );
		$current_page    = wah_get_page_number();
		$number_of_pages = $post_counter / $posts_per_page;
		$this_url        = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$output[]        = '<ul class="admin_page_pagination">';
		if ( $current_page != 1 && ceil( $number_of_pages ) > 1 ) {
			$prev_page = add_query_arg( 'paged', ( $current_page - 1 ), sanitize_text_field( $this_url ) );
			$output[]  = "<li class='item prev_page'><a href='$prev_page'><i>&#10094;&#10094;</i></a></li>";
		}
		for ( $i = 1; $i <= ceil( $number_of_pages ); $i++ ) {
			$new_url       = add_query_arg( 'paged', $i, sanitize_text_field( $this_url ) );
			$current_class = '';
			if ( $current_page == $i ) {
				$current_class = 'current';
			}
			$output[] = "<li class='item sanitize_url( $current_class )'><a href='$new_url'>$i</a></li>";
		}
		if ( ceil( $number_of_pages ) != $current_page ) {
			$next_page = add_query_arg( 'paged', ( $current_page + 1 ), sanitize_text_field( $this_url ) );
			$prev_page = add_query_arg( 'paged', ( $current_page + 1 ), sanitize_text_field( $this_url ) );
			$output[]  = "<li class='item next_page'><a href='$next_page'><i>&#10095;&#10095;</i></a></li>";
		}
		$output[] = '</ul>';
		echo wp_kses_post( implode( '', $output ) );
	}
}
/**
 * Get client IP
 *
 * @return string Client IP Address
 */
function wah_get_client_ip() {
	$ipaddress = '';
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
		$ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED'] ) );
	} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
		$ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED_FOR'] ) );
	} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
		$ipaddress = sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED'] ) );
	} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$ipaddress = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
	} else {
		$ipaddress = 'UNKNOWN';
	}
	return $ipaddress;
}
/**
 * Get admin widgets list
 *
 * @return array widgets order.
 */
function wah_get_admin_widgets_list() {
	$wah_keyboard_navigation_setup = get_option( 'wah_keyboard_navigation_setup' );
	$wah_readable_fonts_setup      = get_option( 'wah_readable_fonts_setup' );
	$contrast_setup                = get_option( 'wah_contrast_setup' );
	$underline_links_setup         = get_option( 'wah_underline_links_setup' );
	$wah_highlight_links_enable    = get_option( 'wah_highlight_links_enable' );
	$wah_greyscale_enable          = get_option( 'wah_greyscale_enable' );
	$wah_invert_enable             = get_option( 'wah_invert_enable' );
	$wah_remove_animations_setup   = get_option( 'wah_remove_animations_setup' );
	$remove_styles_setup           = get_option( 'wah_remove_styles_setup' );
	$wah_lights_off_setup          = get_option( 'wah_lights_off_setup' );
	$widgets_object                = array();
	$widgets_object['widget-1']    = array(
		'active' => 1,
		'html'   => 'Font resize',
		'class'  => 'active',
	);
	$widgets_object['widget-2']    = array(
		'active' => $wah_keyboard_navigation_setup,
		'html'   => 'Keyboard navigation',
		'class'  => $wah_keyboard_navigation_setup ? 'active' : 'notactive',
	);
	$widgets_object['widget-3']    = array(
		'active' => $wah_readable_fonts_setup,
		'html'   => 'Readable Font',
		'class'  => $wah_readable_fonts_setup ? 'active' : 'notactive',
	);
	$widgets_object['widget-4']    = array(
		'active' => $contrast_setup,
		'html'   => 'Contrast',
		'class'  => $contrast_setup ? 'active' : 'notactive',
	);
	$widgets_object['widget-5']    = array(
		'active' => $underline_links_setup,
		'html'   => 'Underline links',
		'class'  => $underline_links_setup ? 'active' : 'notactive',
	);
	$widgets_object['widget-6']    = array(
		'active' => $wah_highlight_links_enable,
		'html'   => 'Highlight links',
		'class'  => $wah_highlight_links_enable ? 'active' : 'notactive',
	);
	$widgets_object['widget-7']    = array(
		'active' => 1,
		'html'   => 'Clear cookies',
		'class'  => 'active',
	);
	$widgets_object['widget-8']    = array(
		'active' => $wah_greyscale_enable,
		'html'   => 'Image Greyscale',
		'class'  => $wah_greyscale_enable ? 'active' : 'notactive',
	);
	$widgets_object['widget-9']    = array(
		'active' => $wah_invert_enable,
		'html'   => 'Invert colors',
		'class'  => $wah_invert_enable ? 'active' : 'notactive',
	);
	$widgets_object['widget-10']   = array(
		'active' => $wah_remove_animations_setup,
		'html'   => 'Remove Animations',
		'class'  => $wah_remove_animations_setup ? 'active' : 'notactive',
	);
	$widgets_object['widget-11']   = array(
		'active' => $remove_styles_setup,
		'html'   => 'Remove styles',
		'class'  => $remove_styles_setup ? 'active' : 'notactive',
	);
	$widgets_object['widget-12']   = array(
		'active' => $wah_lights_off_setup,
		'html'   => 'Lights Off',
		'class'  => $wah_lights_off_setup ? 'active' : 'notactive',
	);
	$wah_widgets_order             = get_option( 'wah_sidebar_widgets_order' );
	if ( ! $wah_widgets_order ) {
		return $widgets_object;
	} else {
		$wah_serialize_widgets = $wah_widgets_order;
		$sortedwidgets_object  = array();
		foreach ( $wah_serialize_widgets as $id => $array ) {
			$sortedwidgets_object[ $id ] = array(
				'active' => $array['active'],
				'html'   => $array['html'],
				'class'  => $array['class'],
			);
		}
		return $sortedwidgets_object;
	}
}
// Get widgets status
function wah_get_widgets_status() {
	$widgets_status                                  = array();
	$widgets_status['wah_keyboard_navigation_setup'] = get_option( 'wah_keyboard_navigation_setup' );
	$widgets_status['wah_readable_fonts_setup']      = get_option( 'wah_readable_fonts_setup' );
	$widgets_status['contrast_setup']                = get_option( 'wah_contrast_setup' );
	$widgets_status['underline_links_setup']         = get_option( 'wah_underline_links_setup' );
	$widgets_status['wah_highlight_links_enable']    = get_option( 'wah_highlight_links_enable' );
	$widgets_status['wah_greyscale_enable']          = get_option( 'wah_greyscale_enable' );
	$widgets_status['wah_invert_enable']             = get_option( 'wah_invert_enable' );
	$widgets_status['wah_remove_animations_setup']   = get_option( 'wah_remove_animations_setup' );
	$widgets_status['remove_styles_setup']           = get_option( 'wah_remove_styles_setup' );
	$widgets_status['wah_lights_off_setup']          = get_option( 'wah_lights_off_setup' );
	return $widgets_status;
}
/**
 * Update array of ordered widgets
 */
function update_serialize_order_array() {
	$widgets_object        = array();
	$widgets_status        = wah_get_widgets_status();
	$wah_serialize_widgets = get_option( 'wah_sidebar_widgets_order' );

	if ( ! $wah_serialize_widgets ) {
		$widgets_object['widget-1']  = array(
			'active' => 1,
			'html'   => 'Font resize',
			'class'  => 'active',
		);
		$widgets_object['widget-2']  = array(
			'active' => $widgets_status['wah_keyboard_navigation_setup'],
			'html'   => 'Keyboard navigation',
			'class'  => $widgets_status['wah_keyboard_navigation_setup'] ? 'active' : 'notactive',
		);
		$widgets_object['widget-3']  = array(
			'active' => $widgets_status['wah_readable_fonts_setup'],
			'html'   => 'Readable Font',
			'class'  => $widgets_status['wah_readable_fonts_setup'] ? 'active' : 'notactive',
		);
		$widgets_object['widget-4']  = array(
			'active' => $widgets_status['contrast_setup'],
			'html'   => 'Contrast',
			'class'  => $widgets_status['contrast_setup'] ? 'active' : 'notactive',
		);
		$widgets_object['widget-5']  = array(
			'active' => $widgets_status['underline_links_setup'],
			'html'   => 'Underline links',
			'class'  => $widgets_status['underline_links_setup'] ? 'active' : 'notactive',
		);
		$widgets_object['widget-6']  = array(
			'active' => $widgets_status['wah_highlight_links_enable'],
			'html'   => 'Highlight links',
			'class'  => $widgets_status['wah_highlight_links_enable'] ? 'active' : 'notactive',
		);
		$widgets_object['widget-7']  = array(
			'active' => 1,
			'html'   => 'Clear cookies',
			'class'  => 'active',
		);
		$widgets_object['widget-8']  = array(
			'active' => $widgets_status['wah_greyscale_enable'],
			'html'   => 'Image Greyscale',
			'class'  => $widgets_status['wah_greyscale_enable'] ? 'active' : 'notactive',
		);
		$widgets_object['widget-9']  = array(
			'active' => $widgets_status['wah_invert_enable'],
			'html'   => 'Invert colors',
			'class'  => $widgets_status['wah_invert_enable'] ? 'active' : 'notactive',
		);
		$widgets_object['widget-10'] = array(
			'active' => $widgets_status['wah_remove_animations_setup'],
			'html'   => 'Remove Animations',
			'class'  => $widgets_status['wah_remove_animations_setup'] ? 'active' : 'notactive',
		);
		$widgets_object['widget-11'] = array(
			'active' => $widgets_status['remove_styles_setup'],
			'html'   => 'Remove styles',
			'class'  => $widgets_status['remove_styles_setup'] ? 'active' : 'notactive',
		);
		$widgets_object['widget-12'] = array(
			'active' => $widgets_status['wah_lights_off_setup'],
			'html'   => 'Lights Off',
			'class'  => $widgets_status['wah_lights_off_setup'] ? 'active' : 'notactive',
		);
	} else {
		foreach ( $wah_serialize_widgets as $serialize_id => $wah_serialize_data ) {
			if ( $serialize_id == 'widget-1' ) {
				$active_status = 1;
				$html          = 'Font resize';
			} elseif ( $serialize_id == 'widget-2' ) {
				$active_status = $widgets_status['wah_keyboard_navigation_setup'];
				$html          = 'Keyboard navigation';
			} elseif ( $serialize_id == 'widget-3' ) {
				$active_status = $widgets_status['wah_readable_fonts_setup'];
				$html          = 'Readable Font';
			} elseif ( $serialize_id == 'widget-4' ) {
				$active_status = $widgets_status['contrast_setup'];
				$html          = 'Contrast';
			} elseif ( $serialize_id == 'widget-5' ) {
				$active_status = $widgets_status['underline_links_setup'];
				$html          = 'Underline links';
			} elseif ( $serialize_id == 'widget-6' ) {
				$active_status = $widgets_status['wah_highlight_links_enable'];
				$html          = 'Highlight links';
			} elseif ( $serialize_id == 'widget-7' ) {
				$active_status = 1;
				$html          = 'Clear cookies';
			} elseif ( $serialize_id == 'widget-8' ) {
				$active_status = $widgets_status['wah_greyscale_enable'];
				$html          = 'Image Greyscale';
			} elseif ( $serialize_id == 'widget-9' ) {
				$active_status = $widgets_status['wah_invert_enable'];
				$html          = 'Invert colors';
			} elseif ( $serialize_id == 'widget-10' ) {
				$active_status = $widgets_status['wah_remove_animations_setup'];
				$html          = 'Remove Animations';
			} elseif ( $serialize_id == 'widget-11' ) {
				$active_status = $widgets_status['remove_styles_setup'];
				$html          = 'Remove styles';
			} elseif ( $serialize_id == 'widget-12' ) {
				$active_status = $widgets_status['wah_lights_off_setup'];
				$html          = 'Lights off';
			}
			$widgets_object[ $serialize_id ] = array(
				'active' => $active_status,
				'html'   => $html,
				'class'  => $active_status ? 'active' : 'notactive',
			);
		}
	}
	$serialize_data = $widgets_object;
	update_option( 'wah_sidebar_widgets_order', $serialize_data );
}
/**
 * Render "Select" element
 *
 * @param  string $label      label.
 * @param  string $option     option.
 * @param  string $id         ID.
 */
function render_select_element( $label, $option, $id ) {
	$font_resize_options = array(
		'rem'    => __( 'REM units resize', 'wp-accessibility-helper' ),
		'zoom'   => __( 'Zoom in/out page', 'wp-accessibility-helper' ),
		'script' => __( 'Script base resize', 'wp-accessibility-helper' ),
	);
	?>
	<div class="form_row">
		<div class="form30">
			<label for="<?php echo esc_html( $id ); ?>" class="text_label">
				<?php echo esc_html( $label ); ?>
			</label>
		</div>
		<div class="form70">
			<select name="<?php echo esc_html( $id ); ?>" id="<?php echo esc_html( $id ); ?>">
				<?php foreach ( $font_resize_options as $key => $value ) : ?>
					<option value="<?php echo esc_html( $key ); ?>"
						<?php if ( $option == $key ) : ?>
							selected="selected"
						<?php endif; ?>>
						<?php echo esc_html( $value ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<?php
}
/**
 * Switch element
 *
 * @param  string $label       label.
 * @param  string $option      option.
 * @param  string $id          id.
 * @param  string $on          on string.
 * @param  string $off         off string.
 */
function render_switch_element( $label, $option, $id, $on = 'On', $off = 'Off' ) {
	?>
	<div class="form_row">
		<div class="form30">
			<label for="<?php echo esc_html( $id ); ?>" class="text_label">
				<?php echo esc_html( $label ); ?>
			</label>
		</div>
		<div class="form70">
			<label class="switch">
				<input class="switch-input" name="<?php echo esc_html( $id ); ?>" id="<?php echo esc_html( $id ); ?>"
					type="checkbox" value="<?php echo esc_html( $option ); ?>"
					<?php if ( 1 == $option ) : ?>
						checked
					<?php endif; ?> />
				<span class="switch-label" data-on="<?php echo esc_html( $on ); ?>" data-off="<?php echo esc_html( $off ); ?>"></span>
				<span class="switch-handle"></span>
			</label>
		</div>
	</div>
	<?php
}
/**
 * Render Form "title" element
 *
 * @param  string $label          label.
 * @param  string $option         option.
 * @param  string $id             ID.
 * @param  string $placeholder    placeholder.
 * @param  string $depid          dependencies ID.
 */
function render_title_element( $label, $option, $id, $placeholder = '', $depid = '' ) {
	?>
	<div class="form_row"
		<?php if ( $depid ) : ?>
			data-depid="<?php echo esc_html( $depid ); ?>"
		<?php endif; ?>>
		<div class="form30">
			<label for="<?php echo esc_html( $id ); ?>" class="text_label">
				<?php echo esc_html( $label ); ?>
			</label>
		</div>
		<div class="form70">
			<input type="text" name="<?php echo esc_html( $id ); ?>" id="<?php echo esc_html( $id ); ?>"
				value="<?php echo esc_html( $option ); ?>" placeholder="<?php echo esc_html( $placeholder ); ?>" />
		</div>
	</div>
	<?php
}
/**
 * Render Form section title
 *
 * @param  string $label  section title.
 */
function render_form_section_title( $label ) {
	?>
	<h3 class="form_element_header">
		<button type="button" title="<?php echo esc_html( $label ); ?>">
			<?php echo esc_html( $label ); ?>
		</button>
		<span aria-hidden="true" class="toggle-wah-section">
			<span class="dashicons dashicons-arrow-down-alt2"></span>
		</span>
	</h3>
	<?php
}
/**
 * Render Logo position
 *
 * @param  string $label             label.
 * @param  string $wah_logo_top      top position in px.
 * @param  string $wah_logo_right    right position in px.
 * @param  string $wah_logo_bottom   bottom position in px.
 * @param  string $wah_logo_left     left position in px.
 */
function render_logo_position( $label, $wah_logo_top, $wah_logo_right, $wah_logo_bottom, $wah_logo_left ) {
	?>
	<div class="form_row" data-depid="wah_custom_logo_position">
		<div class="form30">
			<label for="upload_icon" class="text_label">
				<?php echo esc_html( $label ); ?>
			</label>
		</div>
		<div class="form70">
			<div class="wah-logo-controller">
				<div class="wah-logo-controller-inner">
				<div class="row top_row">
					<div class="col-full-width">
						<div class="logo-input-label"><?php echo esc_html_e( 'Top', 'wp-accessibility-helper' ); ?></div>
						<div class="logo-input logo-input-top">
							<input type="number" name="wah_logo_top" min="-2000" max="2000" value="<?php echo esc_html( $wah_logo_top ); ?>">
						</div>
					</div>
				</div>
				<div class="row middle_row">
					<div class="col-half">
						<div class="logo-input-label"><?php echo esc_html_e( 'Left', 'wp-accessibility-helper' ); ?></div>
						<div class="logo-input logo-input-left">
							<input type="number" name="wah_logo_left" min="-2000" max="2000" value="<?php echo esc_html( $wah_logo_left ); ?>">
						</div>
					</div>
					<div class="col-half">
						<div class="logo-input-label"><?php echo esc_html_e( 'Right', 'wp-accessibility-helper' ); ?></div>
						<div class="logo-input logo-input-right">
							<input type="number" name="wah_logo_right" min="-2000" max="2000" value="<?php echo esc_html( $wah_logo_right ); ?>">
						</div>
					</div>
				</div>
				<div class="row bottom_row">
					<div class="col-full-width">
						<div class="logo-input-label"><?php echo esc_html_e( 'Bottom', 'wp-accessibility-helper' ); ?></div>
						<div class="logo-input logo-input-bottom">
							<input type="number" name="wah_logo_bottom" min="-2000" max="2000" value="<?php echo esc_html( $wah_logo_bottom ); ?>">
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
	<?php
}
/**
 * WAH Header notice
 */
function render_wah_header_notice() {
	?>
	<div class="wah_admin_header">
		<div class="wah_admin_header_inner">
			<div class="wah_admin_header_overlay"></div>
			<div class="wah_admin_header_content">
				<h2>WP Accessibility Helper <span>by <a href="https://accessibility-helper.co.il/" target="_blank">WAH Accessibility Services</a></span></h2>
				<hr />
				<p>
					<?php esc_html_e( 'Author:', 'wp-accessibility-helper' ); ?>
					<a href="http://volkov.co.il/" target="_blank">Alexander Volkov</a>
				</p>
				<p>
					<?php esc_html_e( 'Official website:', 'wp-accessibility-helper' ); ?>
					<a href="https://accessibility-helper.co.il" target="_blank">https://accessibility-helper.co.il</a>
				</p>
				<p>
					<?php esc_html_e( 'Support forum:', 'wp-accessibility-helper' ); ?>
					<a href="https://wordpress.org/support/plugin/wp-accessibility-helper" target="_blank">
						<?php esc_html_e( 'Forum', 'wp-accessibility-helper' ); ?>
					</a>
				</p>
				<p>
					<?php esc_html_e( 'Rate us here:', 'wp-accessibility-helper' ); ?>
					<a href="https://wordpress.org/support/plugin/wp-accessibility-helper/reviews/?rate=5#new-post" style="text-decoration:none;" target="_blank">
						<?php for ( $i = 1;$i <= 5; $i++ ) : ?>
							<span class="dashicons dashicons-star-filled"></span>
						<?php endfor; ?>
					</a>
				</p>
				<p>License: <a href="https://www.gnu.org/licenses/gpl-2.0.html">GPL-2.0</a></p>
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BLMSYWA9YW8C2"
					target="_blank" class="donate-button">
					<?php esc_html_e( 'Donate here!', 'wp-accessibility-helper' ); ?>
				</a>
			</div>
			<div class="wah_admin_header_share">
				<ul>
					<li>
						<a href="https://www.facebook.com/sharer/sharer.php?u=https%3A//wordpress.org/plugins/wp-accessibility-helper/" title="Share on Facebook" class="wah-facebook-share" target="_blank"></a>
					</li>
					<li>
						<a href="https://www.linkedin.com/shareArticle?mini=true&url=https%3A//wordpress.org/plugins/wp-accessibility-helper/&title=WP%20Accessibility%20Helper&summary=&source=" title="Share on LinkedIn" class="wah-linkedin-share" target="_blank"></a>
					</li>
					<li>
						<a href="https://twitter.com/home?status=WP%20Accessibility%20Helper%20-%20https%3A//wordpress.org/plugins/wp-accessibility-helper/" title="Share on Twitter" class="wah-twitter-share" target="_blank"></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php
}
/**
 * WAH render admin sidebar
 */
function wah_render_admin_sidebar() {
	$banner_url = plugins_url( 'assets/images/wahpro-header-300.png', dirname( __FILE__ ) );
	?>
	<div class="wah-main-admin-sidebar">
		<h3><?php esc_html_e( 'WAH PRO', 'wp-accessibility-helper' ); ?></h3>
		<div class="wah-admin-sidebar-banner">
			<a href="<?php echo esc_url( WAHPRO_LINK ); ?>?from=wahfree" target="_blank" class="wahpro-banner-link">
				<img src="<?php echo esc_url( $banner_url ); ?>" alt="WAH PRO sidebar layouts manager">
			</a>
		</div>
		<div class="wah-admin-sidebar-inner">
			<div class="pro-button">
				<a href="<?php echo esc_url( WAHPRO_LINK ); ?>?from=wahfree" target="_blank" class="button">
					<?php esc_html_e( 'Upgrade to PRO', 'wp-accessibility-helper' ); ?>
				</a>
			</div>
			<div class="features-list">
				<br>
				<div class="pro-more">
					<p>
						<a href="https://accessibility-helper.co.il/features-comparison/?from=wahfree" target="_blank">
							<strong><?php esc_html_e( 'Features comprasion', 'wp-accessibility-helper' ); ?></strong>
						</a>
					</p>
				</div>
				<div class="links-wrapper">
					<p>
						<a href="https://accessibility-helper.co.il/wah-pro-admin-screenshots/" target="_blank">WAH PRO Admin area screenshots</a>
					</p>
				</div>
				<h4><?php esc_html_e( 'PRO version features list:', 'wp-accessibility-helper' ); ?></h4>
				<ol>
					<li><?php esc_html_e( 'Clean admin area without ads', 'wp-accessibility-helper' ); ?></li>
					<li>
						<a href="https://accessibility-helper.co.il/about/screenshots/" target="_blank">
							<?php esc_html_e( 'Screenshots', 'wp-accessibility-helper' ); ?>
						</a>
					</li>
					<li>
						<a href="https://accessibility-helper.co.il/wp-accessibility-helper-pro-modal-windows/?from=wahfree" target="_blank">
							<?php esc_html_e( 'Accessible Modal windows', 'wp-accessibility-helper' ); ?>
						</a>
					</li>
					<li>
						<a href="https://accessibility-helper.co.il/video-tutorials/?video=V9wJ-aJWoN4" target="_blank">
							<?php esc_html_e( 'Accessible accordions builder', 'wp-accessibility-helper' ); ?>
						</a>
					</li>
					<li>
						<a href="https://accessibility-helper.co.il/accessible-minibar/?from=wahfree" target="_blank">
							<?php esc_html_e( 'Mini bar', 'wp-accessibility-helper' ); ?>
						</a>
					</li>
					<li>
						<a href="https://accessibility-helper.co.il/video-tutorials/?video=bVBx1Ms7Ktk" target="_blank">
							<?php esc_html_e( 'Page to page settings', 'wp-accessibility-helper' ); ?>
						</a>
					</li>
					<li>
						<a href="https://accessibility-helper.co.il/docs/wpml-support/?from=wahfree" target="_blank">
							<?php esc_html_e( 'WPML Support', 'wp-accessibility-helper' ); ?>
						</a>
					</li>
					<li>
						<a href="https://accessibility-helper.co.il/video-tutorials/?video=lAf5FTGDykw" target="_blank">
							<?php esc_html_e( 'Sidebar layouts manager', 'wp-accessibility-helper' ); ?>
						</a>
						<br><small><strong><?php esc_html_e( '6 different layouts for your choice' ); ?></strong></small>
					</li>
					<li>
						<a href="https://accessibility-helper.co.il/wahpro-shortcodes/?from=wahfree" target="_blank">
							<?php esc_html_e( 'Shortcodes & widgets', 'wp-accessibility-helper' ); ?>
						</a>
					</li>
					<li><?php esc_html_e( 'Advanced tuning settings', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Highlight titles', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Large mouse cursor', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Mobile optimization', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Reset widgets order', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Buttons with icons', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Monochrome & Sepia modes', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'WPML Support', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Polylang Support', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Control cookies time', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Line height controls', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Letter spacing controls', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'Ability to hide accessibility sidebar on specific pages', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'JS and CSS optimization for better perfomance', 'wp-accessibility-helper' ); ?></li>
					<li><?php esc_html_e( 'And much more...', 'wp-accessibility-helper' ); ?></li>
				</ol>
				<div class="pro-button">
					<a href="<?php echo esc_url( WAHPRO_LINK ); ?>?from=wahfree" target="_blank" class="button">
						<?php esc_html_e( 'Upgrade to PRO', 'wp-accessibility-helper' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
	<?php
}
