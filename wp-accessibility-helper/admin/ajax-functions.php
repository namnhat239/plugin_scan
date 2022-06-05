<?php
/**
 * WAH admin AJAX functions
 *
 * @package WAH
 */

add_action( 'wp_ajax_wah_update_attachment_title', 'wah_update_attachment_title' );
add_action( 'wp_ajax_update_attachment_alt', 'update_attachment_alt' );
add_action( 'wp_ajax_wah_update_image_alt', 'wah_update_image_alt' );
add_action( 'wp_ajax_nopriv_wah_update_image_alt', 'wah_update_image_alt' );
add_action( 'wp_ajax_wah_update_widgets_order', 'wah_update_widgets_order' );
add_action( 'wp_ajax_add_new_contrast_item', 'add_new_contrast_item' );
add_action( 'wp_ajax_remove_contrast_item', 'remove_contrast_item' );
add_action( 'wp_ajax_save_contrast_variations', 'save_contrast_variations' );
add_action( 'wp_ajax_save_empty_contrast_variations', 'save_empty_contrast_variations' );

/**
 * Update_attachment_title
 */
function wah_update_attachment_title() {

	$result = array();
	$pid    = isset( $_POST['pid'] ) ? sanitize_text_field( wp_unslash( $_POST['pid'] ) ) : '';
	$ptitle = isset( $_POST['ptitle'] ) ? sanitize_text_field( wp_unslash( $_POST['ptitle'] ) ) : '';

	if ( $pid ) {
		$result['plink'] = get_permalink( $pid );
		$result['pid']   = $pid;
		if ( $ptitle ) {
			$attachment_post = array(
				'ID'         => $pid,
				'post_title' => $ptitle,
			);
			wp_update_post( $attachment_post );
			$result['ptitle'] = $ptitle;
		}
	}

	wp_send_json( $result );
}

/**
 * Update attachment alt
 */
function update_attachment_alt() {

	$result = array();
	$pid    = isset( $_POST['pid'] ) ? sanitize_text_field( wp_unslash( $_POST['pid'] ) ) : '';
	$palt   = isset( $_POST['palt'] ) ? sanitize_text_field( wp_unslash( $_POST['palt'] ) ) : '';

	if ( $pid ) {

		$result['plink'] = get_permalink( $pid );
		$result['pid']   = $pid;

		if ( $palt ) {
			update_post_meta( $pid, '_wp_attachment_image_alt', $palt );
			$alt            = get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
			$result['palt'] = $alt;
		}

		wp_send_json( $result );

	}

	die();
}
/**
 * Get attachment id by src
 *
 * @param  string $image_url URL.
 * @return string            attachment ID
 */
function wah_get_attachment_id_by_src( $image_url ) {
	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
	if ( $attachment ) {
		return $attachment[0];
	}
}
/**
 * Update image alt - front scanner
 */
function wah_update_image_alt() {

	$response          = array();
	$attachment_source = isset( $_POST['target_src'] ) ? sanitize_text_field( $_POST['target_src'] ) : '';
	$wah_alt_input     = isset( $_POST['wah_alt_input'] ) ? sanitize_text_field( wp_unslash( $_POST['wah_alt_input'] ) ) : '';

	if ( $attachment_source ) {

		$attachment_id = wah_get_attachment_id( $attachment_source );

		if ( $attachment_id && $wah_alt_input ) {
			update_post_meta( $attachment_id, '_wp_attachment_image_alt', $wah_alt_input );
			$response['atid']    = $attachment_id;
			$response['status']  = 'ok';
			$response['message'] = __( 'Image has been updated.', 'wp-accessibility-helper' );
		} else {
			$response['atid']    = -1;
			$response['status']  = 'error';
			$response['message'] = __( 'It look likes, this image is not on your server...', 'wp-accessibility-helper' );
		}
	}

	wp_send_json( $response );
}
/**
 * Sanitation for an array
 *
 * @param array $array array.
 *
 * @return array
 */
function wah_sanitize_array( $array ) {
	foreach ( $array as $key => &$value ) {
		$value = sanitize_text_field( $value );
	}

	return $array;
}
/**
 * Save wah widgets order
 */
function wah_update_widgets_order() {
	$response = '';
	$data     = isset( $_POST['alldata'] ) ? array_map( 'wah_sanitize_array', (array) wp_unslash( $_POST['alldata'] ) ) : array();

	$widgets_status = wah_get_widgets_status();

	$widgets_object              = array();
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

	$s_data = array();
	foreach ( $data as $id ) {
		$s_data[ $id ] = $widgets_object[ $id ];
	}

	update_option( 'wah_sidebar_widgets_order', $s_data );
	$response = 'ok';

	wp_send_json( $response );
}

/**
 * Add new contrast item from repeater
 */
function add_new_contrast_item() {
	$response = array();
	ob_start();
	?>
	<li>
		<div class="contrast-mode-item bg-color">
			<label><?php esc_html_e( 'Background color', 'wp-accessibility-helper' ); ?></label>
			<input type="text" class="jscolor" placeholder="<?php esc_html_e( 'Background color', 'wp-accessibility-helper' ); ?>" />
		</div>
		<div class="contrast-mode-item text-color">
			<label><?php esc_html_e( 'Text color', 'wp-accessibility-helper' ); ?></label>
			<input type="text" class="jscolor" placeholder="<?php esc_html_e( 'Text color', 'wp-accessibility-helper' ); ?>" />
		</div>
		<div class="contrast-mode-item button-title-alt">
			<label><?php esc_html_e( 'Title', 'wp-accessibility-helper' ); ?></label>
			<input type="text" placeholder="<?php esc_html_e( 'Button title', 'wp-accessibility-helper' ); ?>" />
		</div>
		<div class="contrast-mode-item action">
			<button class="wah-button delete-contrast-params">
				<?php esc_html_e( 'Delete', 'wp-accessibility-helper' ); ?>
			</button>
			<span class="action-loader"></span>
		</div>
	</li>
	<?php
	$response['status'] = 'ok';
	$response['html']   = ob_get_clean();
	wp_send_json( $response );
}

/**
 * Remove contrast item from repeater
 */
function remove_contrast_item() {
	$response           = array();
	$response['status'] = 'ok';
	wp_send_json( $response );
}
/**
 * Save contrast variations
 */
function save_contrast_variations() {
	$response = array();
	$alldata  = isset( $_POST['alldata'] ) ? array_map( 'wah_sanitize_array', (array) wp_unslash( $_POST['alldata'] ) ) : array();

	if ( count( $alldata ) >= 5 ) {
		$response['status']  = 'error';
		$response['message'] = __( 'Maximum 4 variations. Need more variations? Go PRO!' );
		wp_send_json( $response );
	} else {
		if ( $alldata ) {
			$data = $alldata;
			update_option( 'wah_contrast_variations', $data );

			$response['status'] = 'ok';
			wp_send_json( $response );
		}
	}
	die();
}
/**
 * Save EMPTY contrast variations
 */
function save_empty_contrast_variations() {
	$response = array();
	$alldata  = '';
	update_option( 'wah_contrast_variations', $alldata );
	$response['status']  = 'ok';
	$response['message'] = __( 'Removed!', 'wp-accessibility-helper' );
	wp_send_json( $response );
}
/**
 * Get all contrast variations
 *
 * @return array variations
 */
function wah_get_contrast_variations() {
	$contrast_variations = get_option( 'wah_contrast_variations' );
	if ( $contrast_variations ) {
		return $contrast_variations;
	}
}
