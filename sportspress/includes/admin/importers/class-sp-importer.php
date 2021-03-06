<?php
/**
 * SportsPress Importer
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Importers
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( class_exists( 'WP_Importer' ) ) {
	class SP_Importer extends WP_Importer {

		var $id;
		var $file_url;
		var $import_page;
		var $delimiter;
		var $posts = array();
		var $imported;
		var $skipped;
		var $import_label;
		var $columns   = array();
		var $optionals = array();

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		}

		/**
		 * Enqueue scripts
		 */
		public function admin_scripts() {
			wp_enqueue_script( 'sportspress-admin', SP()->plugin_url() . '/assets/js/admin/sportspress-admin.js', array( 'jquery', 'chosen', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'jquery-tiptip', 'jquery-caret' ), SP_VERSION, true );
		}

		/**
		 * Registered callback function for the WordPress Importer
		 *
		 * Manages the three separate stages of the CSV import process
		 */
		function dispatch() {
			$this->header();

			if ( ! empty( $_POST['delimiter'] ) ) {
				$this->delimiter = stripslashes( trim( sanitize_text_field( wp_unslash( $_POST['delimiter'] ) ) ) );
			}

			if ( ! $this->delimiter ) {
				$this->delimiter = ',';
			}

			$step = empty( $_GET['step'] ) ? 0 : (int) $_GET['step'];
			switch ( $step ) :
				case 0:
					$this->greet();
					break;
				case 1:
					check_admin_referer( 'import-upload' );
					if ( $this->handle_upload() ) :

						if ( $this->id ) {
							$file = get_attached_file( $this->id );
						} else {
							$file = ABSPATH . $this->file_url;
						}

						add_filter( 'http_request_timeout', array( $this, 'bump_request_timeout' ) );

						if ( function_exists( 'gc_enable' ) ) {
							gc_enable();
						}

						@set_time_limit( 0 );
						@ob_flush();
						@flush();

						$this->table( $file );
					endif;
					break;
				case 2:
					check_admin_referer( 'import-upload' );
					if ( isset( $_POST['sp_import'] ) ) :
						$columns = array_filter( array_map( 'sanitize_key', array_map( 'wp_unslash', sp_array_value( $_POST, 'sp_columns', array( 'post_title' ) ) ) ) );
						$this->import( $_POST['sp_import'], array_values( $columns ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
					endif;
					break;
			endswitch;
			$this->footer();
		}

		/**
		 * dropdown function.
		 *
		 * @access public
		 * @param mixed $file
		 * @return void
		 */
		function dropdown( $selected ) {
			?>
			<select name="sp_columns[]" data-index="<?php echo esc_attr( array_search( $selected, array_keys( $this->columns ) ) ); ?>">
				<option value="0">&mdash; <?php esc_html_e( 'Disable', 'sportspress' ); ?> &mdash;</option>
				<?php foreach ( $this->columns as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $selected, $key ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
			<?php
		}

		/**
		 * table function.
		 *
		 * @access public
		 * @param mixed $file
		 * @return void
		 */
		function table( $file ) {
			global $wpdb;

			$this->imported = $this->skipped = 0;

			if ( ! is_file( $file ) ) :
				$this->footer();
				die();
			endif;

			ini_set( 'auto_detect_line_endings', '1' );

			if ( ( $handle = fopen( $file, 'r' ) ) !== false ) :

				$header = fgetcsv( $handle, 0, $this->delimiter );

				if ( sizeof( $header ) >= 1 ) :
					$action = 'admin.php?import=' . $this->import_page . '&step=2';
					?>
					<form enctype="multipart/form-data" id="import-upload-form" class="sportspress" method="post" action="<?php echo esc_attr( wp_nonce_url( $action, 'import-upload' ) ); ?>">
						<?php $this->options(); ?>
						<table class="wp-list-table sp-data-table sp-import-table widefat fixed pages">
							<thead>
								<tr>
									<?php foreach ( $this->columns as $key => $label ) : ?>
										<th scope="col" class="manage-column">
											<?php $this->dropdown( $key ); ?>
										</th>
									<?php endforeach; ?>
									<th scope="col" class="sp-actions-column">
										<a href="#" title="<?php esc_attr_e( 'Insert row after', 'sportspress' ); ?>" class="dashicons dashicons-plus-alt sp-add-row sp-add-first"></a>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php while ( ( $row = fgetcsv( $handle, 0, $this->delimiter ) ) !== false ) : ?>
									<tr>
										<?php
										$index = 0;
										foreach ( $this->columns as $key => $label ) :
											$value = sp_array_value( $row, $index );
											?>
											<td>
												<input type="text" class="widefat" value="<?php echo esc_attr( $value ); ?>" name="sp_import[]"
																									 <?php
																										if ( in_array( $key, $this->optionals ) ) {
																											?>
										 placeholder="<?php esc_attr_e( 'Default', 'sportspress' ); ?>"<?php } ?>>
											</td>
											<?php
											$index ++;
endforeach;
										?>
										<td class="sp-actions-column">
											<a href="#" title="<?php esc_attr_e( 'Delete row', 'sportspress' ); ?>" class="dashicons dashicons-dismiss sp-delete-row"></a>
											<a href="#" title="<?php esc_attr_e( 'Insert row after', 'sportspress' ); ?>" class="dashicons dashicons-plus-alt sp-add-row"></a>
										</td>
									</tr>
									<?php
									$this->imported++;
endwhile;
								?>
								<tr>
									<?php foreach ( $this->columns as $key => $label ) : ?>
										<td>
											<input type="text" class="widefat" name="sp_import[]">
										</td>
									<?php endforeach; ?>
									<td class="sp-actions-column">
										<a href="#" title="<?php esc_attr_e( 'Insert row after', 'sportspress' ); ?>" class="dashicons dashicons-plus-alt sp-add-row"></a>
									</td>
								</tr>
							</tbody>
						</table>
						<p class="sp-post-count alignright">
							<?php printf( esc_html__( 'Displaying %1$s&#8211;%2$s of %3$s', 'sportspress' ), 1, esc_html( $this->imported + 1 ), esc_html( $this->imported + 1 ) ); ?>
						</p>
						<p class="submit">
							<input type="submit" class="button button-primary button-hero" value="<?php echo esc_attr( $this->import_label ); ?>" />
						</p>
					</form>
					<?php
				else :

					echo '<p><strong>' . esc_html__( 'Sorry, there has been an error.', 'sportspress' ) . '</strong><br />';
					esc_html_e( 'The CSV is invalid.', 'sportspress' ) . '</p>';
					$this->footer();
					die();

				endif;

				fclose( $handle );
			endif;
		}

		/**
		 * format_data_from_csv function.
		 *
		 * @access public
		 * @param mixed  $data
		 * @param string $enc
		 * @return string
		 */
		function format_data_from_csv( $data, $enc ) {
			return ( $enc == 'UTF-8' ) ? $data : utf8_encode( $data );
		}

		/**
		 * Handles the CSV upload and initial parsing of the file to prepare for
		 * displaying author import options
		 *
		 * @return bool False if error uploading or invalid file, true otherwise
		 */
		function handle_upload() {

			if ( empty( $_POST['file_url'] ) ) {

				$file = wp_import_handle_upload();

				if ( isset( $file['error'] ) ) {
					echo '<p><strong>' . esc_html__( 'Sorry, there has been an error.', 'sportspress' ) . '</strong><br />';
					echo esc_html( $file['error'] ) . '</p>';
					return false;
				}

				$this->id = (int) $file['id'];

			} else {

				if ( file_exists( ABSPATH . sanitize_url( wp_unslash( $_POST['file_url'] ) ) ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

					$this->file_url = sanitize_url( wp_unslash( $_POST['file_url'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

				} else {

					echo '<p><strong>' . esc_html__( 'Sorry, there has been an error.', 'sportspress' ) . '</strong></p>';
					return false;

				}
			}

			return true;
		}

		/**
		 * header function.
		 *
		 * @access public
		 * @return void
		 */
		function header() {
			echo '<div class="wrap"><h2>' . esc_html( $this->import_label ) . '</h2>';
		}

		/**
		 * footer function.
		 *
		 * @access public
		 * @return void
		 */
		function footer() {
			echo '</div>';
		}

		/**
		 * options function.
		 *
		 * @access public
		 * @return void
		 */
		function options() {
		}

		/**
		 * Added to http_request_timeout filter to force timeout at 60 seconds during import
		 *
		 * @param  int $val
		 * @return int 60
		 */
		function bump_request_timeout( $val ) {
			return 60;
		}
	}
}
