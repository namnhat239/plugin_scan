<?php


add_filter( 'et_builder_get_parent_modules', 'wadip_blurb_icon_additional_options' );

/**
 * Contains code copied from and/or based on Divi by Elegant Themes
 * See the license.txt file in the root directory for more information and licenses
 *
 */


/**
 * Update options in Blurb module/ Icon module.
 *
 * @param array $modules
 *
 * @return array
 */
function wadip_blurb_icon_additional_options( $modules ) {
	// Ensure we run this code only once because it's expensive.
	static $is_applied = false;
	if ( $is_applied ) {
		return $modules;
	}

	// Bail early if the modules list empty.
	if ( empty( $modules ) ) {
		return $modules;
	}

	foreach ( $modules as $module_slug => $module ) {

		// Bail early if current module is not Blurb module / Icon module.

		if ( 'et_pb_icon' !== $module_slug && 'et_pb_blurb' !== $module_slug ) {
			continue;
		}

		// Ensure toggles and fields list exist.
		if ( ! isset( $module->fields_unprocessed ) ) {
			continue;
		}

		/**
		 * Fields list on the module.
		 *
		 * @var array
		 *
		 * The structures:
		 * array(
		 *     'field_slug' => array(
		 *         'label'       => '',
		 *         'description' => '',
		 *         'type'        => '',
		 *         'toggle_slug' => '',
		 *         'tab_slug'    => '',
		 *     ),
		 *     ... Other fields.
		 * )
		 */

		$fields_list           = $module->fields_unprocessed;
		$wadip_field_icon_type = array();

		if ( 'et_pb_blurb' === $module_slug ) {
			$show_if    = array( 'multicolor_icon_enabled' => 'on', 'use_icon' => 'on' );
			$show_if_sc = array( 'multicolor_icon_enabled' => 'off', 'use_icon' => 'on' );
			$toggle     = 'image';
		} else {
			// 'et_pb_icon'
			$show_if    = array( 'multicolor_icon_enabled' => 'on' );
			$show_if_sc = array( 'multicolor_icon_enabled' => 'off' );
			$toggle     = 'main_content';
		}

		if ( ! isset ( $field_list['wadip_mc_notice'] ) && ! isset ( $field_list['wadip_sc_notice'] ) && ! isset ( $field_list['wadi_free_notice'] ) ) {

			$promo_fields = array(
				

				

				
				'wadi_free_notice' => array(
					'type'            => 'warning',
					'option_category' => 'basic_option',
					'value'           => true,
					'display_if'      => true,
					'message'         => sprintf( '<div class="wadip-promo-field"><p>%s</p></div>',
						sprintf(
						// translators: %s are <a></a> tags
							esc_html__( 'You are using the free version of WP and Divi Icons plugin. Get %1$sWP And Divi Icons Pro plugin%2$s to unlock additional 3000+ single color icons, and 500+ multicolor icons that you can easily style with the visual builder. If you are happy with the free version, please consider leaving us a %3$sreview%4$s.', 'ds-icon-expansion' ),
							'<a href="' . esc_url( admin_url( AGS_Divi_Icons::PLUGIN_PRODUCT_URL_PRO ) ) . '">',
							'</a>',
							'<a href="' . esc_url( AGS_Divi_Icons::PLUGIN_REVIEW_URL_FREE ) . '">',
							'</a>'
						),
					),
					'toggle_slug'     => $toggle
				),
				

			);

		}

		if ( get_option( 'agsdi_mc_packs', 'yes' ) === 'yes' ) {
			

			// Define icon type field that we will add later
			if ( ! isset( $fields_list['multicolor_icon_enabled'] ) && ! isset ( $fields_list['icon_pack_select'] ) && ! isset ( $fields_list['multicolor_icon_select'] ) ) {
				$path_svg              = AGS_Divi_Icons::$pluginDir . 'admin/icon-packs/';
				$wadip_field_icon_type = array(
					'multicolor_icon_enabled' => array(
						'label'           => esc_html__( 'Icon Type', 'ds-icon-expansion' ),
						'description'     => esc_html__( 'Choose the type of the icons you want to use.', 'ds-icon-expansion' ),
						'type'            => 'select',
						'option_category' => 'configuration',
						'options'         => array(
							'off' => esc_html__( 'Single Color Icon', 'ds-icon-expansion' ),
							'on'  => esc_html__( 'Multicolor Icon', 'ds-icon-expansion' ),
						),
						'default'         => 'off',
						'toggle_slug'     => $toggle,
					),
					'icon_pack_select'        => array(
						'label'           => esc_html__( 'Icon Style', 'ds-icon-expansion' ),
						'type'            => 'WadipETBuilderControlMultipleButtons',
						'option_category' => 'configuration',
						'options'         => array(),
						'toggle_slug'     => $toggle,
						'default'         => 'mul_mul',
						'advanced_fields' => true,
						'show_if'         => $show_if
					),
					'multicolor_icon_select'  => array(
						'type'            => 'text',
						'option_category' => 'basic_option',
						'toggle_slug'     => $toggle,
						'mobile_options'  => true,
						'hover'           => 'tabs',
						'show_if'         => $show_if
					)
				);

				if ( 'et_pb_blurb' === $module_slug ) {
					$wadip_field_icon_type['multicolor_icon_enabled']['show_if'] = array( 'use_icon' => 'on' );
				}

				$icon_packs = AGS_Divi_Icons::$icon_packs;

				foreach ( $icon_packs['multicolor'] as $prefix => $pack ) {

					$option  = array(
						$prefix => array(
							'title'   => esc_html( $pack['name'] ),
							'iconSvg' => file_get_contents( $path_svg . $pack['preview'] )
						)
					);
					$wadip_field_icon_type['icon_pack_select']['options'] = array_merge( $wadip_field_icon_type['icon_pack_select']['options'], $option );


				}

			}


			// Update field name when icon selected (single or multicolor)
			if ( isset ( $fields_list['icon_color'] ) ) {
				$fields_list['icon_color']['label'] = esc_html__( 'Primary Icon Color', 'ds-icon-expansion' );
			}
		}

		// Add our fields to existing fields

		if ( isset( $promo_fields ) ) {

			// We want to add field after 'use_icon' or 'icon_picker'
			if ( 'et_pb_blurb' === $module_slug ) {
				// Find position of the secondary option in the array,
				$field_position = array_search( "use_icon", array_keys( $fields_list ), true );

				
				$promo_fields['wadi_free_notice']['show_if'] = array( 'use_icon' => 'on' );
				

				// If field found, split arrays and add our field
				if ( ! empty ( $field_position ) ) {
					$fields_list = array_merge(
						array_slice( $fields_list, 0, (int) $field_position + 1, true ),
						$wadip_field_icon_type,
						array_slice( $fields_list, (int) $field_position + 1, null, true ),
						$promo_fields
					);
				}

			} elseif ( 'et_pb_icon' === $module_slug ) {
				$fields_list = array_merge( $wadip_field_icon_type, $fields_list, $promo_fields );
			}
		}

		


		$modules[ $module_slug ]->fields_unprocessed = $fields_list;

	}

	$is_applied = true;

	return $modules;
}

