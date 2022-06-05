<?php
/**
 * WAH attachment page
 *
 * @package WordPress
 */

?>

<div class="wrap">
	<form method='post'>
		<h1><?php esc_html_e( 'WP Accessibility Helper - Attachments Control Center', 'wp-accessibility-helper' ); ?></h1>
		<?php render_wah_header_notice(); ?>
		<br/>
		</br/>
		<?php
		$attachments = get_posts(
			array(
				'post_type'      => 'attachment',
				'posts_per_page' => 10,
				'paged'          => wah_get_page_number(),
			)
		);
		$data_array  = array();
		foreach ( $attachments as $post ) {
			setup_postdata( $post );
			$post_link      = get_permalink( $post->ID );
			$alt            = get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
			$edit_post_link = get_edit_post_link( $post->ID ) ? get_edit_post_link( $post->ID ) : '';

			$data_array[ $post->ID ]['post_id']        = $post->ID;
			$data_array[ $post->ID ]['image']          = wp_get_attachment_image_src( $post->ID, array( 32, 32 ) );
			$data_array[ $post->ID ]['post_type']      = $post->post_type;
			$data_array[ $post->ID ]['post_title']     = $post->post_title;
			$data_array[ $post->ID ]['post_alt']       = $alt;
			$data_array[ $post->ID ]['permalink']      = $post_link;
			$data_array[ $post->ID ]['edit_post_link'] = $edit_post_link;

			if ( ! empty( $data_array[ $post->ID ]['image'] ) && is_array( $data_array[ $post->ID ]['image'] ) ) {
				$data_array[ $post->ID ]['image'] = reset( $data_array[ $post->ID ]['image'] );
			}
		}

		?>
	<div class="wah-main-admin-row">
		<?php wah_render_admin_sidebar(); ?>
		<div class="wah-main-admin-form">
			<div class="main_image_control_table">
				<table id="wp-accessibility-helper-image-control-table" class="accessibility_table">
					<tr>
						<th class="id_column"><?php esc_html_e( 'ID', 'wp-accessibility-helper' ); ?></th>
						<th class="preview_thumbnail"><?php esc_html_e( 'Thumbnail', 'wp-accessibility-helper' ); ?></th>
						<th class="title_column"><?php esc_html_e( 'Title', 'wp-accessibility-helper' ); ?></th>
						<th class="alt_column"><?php esc_html_e( 'Alt tag', 'wp-accessibility-helper' ); ?></th>
						<th class="edit_post_column"><?php esc_html_e( 'Edit image', 'wp-accessibility-helper' ); ?></th>
					</tr>

					<?php if ( $data_array ) : ?>
						<?php foreach ( $data_array as $data ) : ?>
							<tr data-item="<?php echo esc_html( $data['post_id'] ); ?>">
								<td><?php echo esc_html( $data['post_id'] ); ?></td>
								<td>
									<img class="athumb" src="<?php echo isset( $data['image'] ) ? esc_url( $data['image'] ) : ''; ?>" alt="" />
								</td>

								<td class="title_box">
									<input type="text" class="attachment_post_title" value="<?php echo wp_kses_post( $data['post_title'] ); ?>" />
									<a href="<?php echo esc_url( $data['permalink'] ); ?>" target="_blank">
										<?php esc_html_e( 'view', 'wp-accessibility-helper' ); ?>
									</a>
								</td>
								<td class="alt_box">
									<input type="text" class="attachment_post_alt" value="<?php echo esc_html( $data['post_alt'] ); ?>"
									<?php if ( empty( $data['post_alt'] ) || '' == $data['post_alt'] ) : ?>
									placeholder="<?php esc_html_e( 'no alt tag', 'wp-accessibility-helper' ); ?>"
									<?php endif; ?> />
								</td>
								<td>
									<a class="attachmentid" data-attachmentid="<?php echo esc_html( $data['post_id'] ); ?>"
									href="<?php echo esc_url( $data['edit_post_link'] ); ?>" target="_blank">
										[<?php esc_html_e( 'Edit image', 'wp-accessibility-helper' ); ?>]
									</a>
								</td>
							</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</table>
				<?php wah_get_pagination( 'attachment' ); ?>
			</div>
		</div>
	</div>

	</form>
</div>
