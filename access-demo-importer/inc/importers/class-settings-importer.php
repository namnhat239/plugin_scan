<?php
/**
 * Class for the settings importer.
 */

class ADI_Settings_Importer {

	/**
	 * Process import file - this parses the settings data and returns it.
	 *
	 * @param string $file path to json file.
	 */
	public function process_import_file( $file ) {

		if( !current_user_can('edit_theme_options') )
			die('You do not have required capability to edit theme options.');

		// Get file contents.
		$data = ADI_Demos_Helpers::get_remote( $file );

		// Return from this function if there was an error.
		if ( is_wp_error( $data ) ) {
			return $data;
		}
		
		$raw  = $data;
		$data = @unserialize( $raw );

		// Delete import file
		unlink( $file );



		if ( isset( $data['options'] ) ) {

			// Load WordPress Customize Setting Class.
			if ( ! class_exists( 'WP_Customize_Setting' ) ) {
				require_once ABSPATH . WPINC . '/class-wp-customize-setting.php';
			}

			// Include Customizer Demo Importer Setting class.
			include ADI_PATH . 'inc/importers/customizer-settings.php';

			foreach ( $data['options'] as $option_key => $option_value ) {
				$option = new ADI_Customizer_Setting(
					$wp_customize,
					$option_key,
					array(
						'default'    => '',
						'type'       => 'option',
						'capability' => 'edit_theme_options',
					)
				);

				$option->import( $option_value );
			}
		}



		// If wp_css is set then import it.
		if ( function_exists( 'wp_update_custom_css_post' ) && isset( $data['wp_css'] ) && '' !== $data['wp_css'] ) {
			wp_update_custom_css_post( $data['wp_css'] );
		}

		// Import the data
    	return $this->import_data( $data['mods'] );

	}

	/**
	 * Sanitization callback
	 *
	 *
	 */
	private function import_data( $file ) {

		if( !current_user_can('edit_theme_options') )
			die('You do not have required capability to edit theme options.');

		// Import the file
		if ( ! empty( $file ) ) {

			if ( '0' == json_last_error() ) {

				// Loop through mods and add them
				foreach ( $file as $mod => $value ) {
					set_theme_mod( $mod, $value );
				}

			}

		}

		// Return file
		return $file;

	}
}