<?php
/**
 * Migration Notice Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Ajax;

use RT\Team\Helpers\Fns;

/**
 * Migration Notice Class.
 */
class Migration {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_tlp_migrate_data', array( $this, 'response' ) );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$this->insert_default_data();

		if ( get_option( 'tlp_migrated_data' ) ) {
			delete_option( 'tlp_migrated_data' );
		}

		update_option( 'tlp_migrated_data_3_0_3', true );

		wp_send_json_success();
	}

	/**
	 * Insert Default Data.
	 *
	 * @return void
	 */
	private function insert_default_data() {
		set_transient( 'rttm_old_version_notice', true, 300 );

		$installed_version = get_option( rttlp_team()->options['installed_version'] );

		if ( ! $installed_version ) {
			$installed_version = rttlp_team()->migration_version;
		}

		if ( version_compare( $installed_version, rttlp_team()->migration_version, '<' ) ) {
			$this->social_fixer();

			if ( ! rttlp_team()->has_pro() ) {
				$this->designation_fixer();
			}

			$this->sc_layout_fixer();
		} else {
			// PRO version with free install.
			if ( ! $installed_version ) {
				$this->social_fixer();
				$this->sc_layout_fixer();
			}
		}

		if ( $installed_version && version_compare( $installed_version, '1.3', '<=' ) ) {
			$this->update_sc_data();
		}

		$this->generate_dynamic_css();

		if ( ! get_option( rttlp_team()->options['settings'] ) ) {
			if ( ! get_option( 'tlp_team_settings' ) ) { // check it for version 1.0.
				update_option( rttlp_team()->options['settings'], rttlp_team()->defaultSettings );
			} else {
				if ( ! $installed_version ) {
					$this->update_settings();
				}
			}
		}
		update_option( rttlp_team()->options['installed_version'], rttlp_team()->options['version'] );

		if ( get_option( 'tlp_migrated_data' ) ) {
			delete_option( 'tlp_migrated_data' );
		}

		update_option( 'tlp_migrated_data_3_0_3', true );
	}

	/**
	 * Social URL fixer.
	 *
	 * @return void
	 */
	private function social_fixer() {
		$all_members = get_posts(
			array(
				'post_type'      => rttlp_team()->post_type,
				'posts_per_page' => -1,
			)
		);

		if ( is_array( $all_members ) && ! empty( $all_members ) ) {
			foreach ( $all_members as $member ) {
				// Social URL fixer.
				/* Fix if code is base64 or new array style */
				$s              = get_post_meta( $member->ID, 'social', true );
				$need_migration = false;

				if ( ! is_array( $s ) && base64_decode( $s, true ) ) {
					$s              = unserialize( base64_decode( $s ) );
					$need_migration = true;
				} elseif ( ! is_array( $s ) ) {
					$s              = unserialize( $s );
					$need_migration = true;
				}

				if ( $need_migration && ! empty( $s ) && is_array( $s ) ) {
					$new_array = array();
					foreach ( $s as $key => $s_s ) {
						if ( ! is_array( $s_s ) ) {
							$new_array[] = array(
								'id'  => $key,
								'url' => $s_s,
							);
						} else {
							$new_array[] = $s_s;
						}
					}

					if ( $new_array ) {
						update_post_meta( $member->ID, 'social', $new_array );
					}
				}
			}
		}
	}

	/**
	 * Designation fixer.
	 *
	 * @return void
	 */
	private function designation_fixer() {
		$all_members = get_posts(
			array(
				'post_type'      => rttlp_team()->post_type,
				'posts_per_page' => -1,
			)
		);

		if ( is_array( $all_members ) && ! empty( $all_members ) ) {
			foreach ( $all_members as $member ) {
				$d = get_post_meta( $member->ID, 'designation', true );

				if ( $d ) {
					$cid = wp_insert_term( $d, 'team_designation' );
					if ( ! is_wp_error( $cid ) ) {
						$term_id = isset( $cid['term_id'] ) ? $cid['term_id'] : 0;
						if ( $term_id ) {
							$term = array( $term_id );
							wp_set_post_terms( $member->ID, $term, 'team_designation' );
						}
					} else {
						if ( isset( $cid->error_data['term_exists'] ) ) {
							$term = array( $cid->error_data['term_exists'] );
							wp_set_post_terms( $member->ID, $term, 'team_designation' );
						}
					}
				}
			}
		}
	}

	/**
	 * Shortcode layout fixer.
	 *
	 * @return void
	 */
	private function sc_layout_fixer() {
		$all_sc = get_posts(
			array(
				'post_type'      => rttlp_team()->shortCodePT,
				'posts_per_page' => -1,
			)
		);

		if ( is_array( $all_sc ) && ! empty( $all_sc ) ) {
			foreach ( $all_sc as $sc ) {
				$layout = get_post_meta( $sc->ID, 'layout', true );

				if ( ! rttlp_team()->has_pro() ) {
					/**
					 * Layout.
					 */
					switch ( $layout ) {
						case 'layout1':
						case 'layout2':
						case 'layout3':
						case 'layout4':
						case 'isotope-free':
						case 'carousel1':
							update_post_meta( $sc->ID, 'social_icon_bg', '#2254e8' );
							break;
					}

					if ( $layout == 'layout2' ) {
						update_post_meta( $sc->ID, 'ttl_image_column', 3 );
					} elseif ( $layout == 'layout3' ) {
						update_post_meta( $sc->ID, 'layout', 'layout2' );
						update_post_meta( $sc->ID, 'image_style', 'round' );
						update_post_meta( $sc->ID, 'ttl_image_column', 3 );
					} elseif ( $layout == 'layout4' || $layout == 'carousel1' ) {
						if ( $layout == 'layout4' ) {
							update_post_meta( $sc->ID, 'layout', 'layout3' );
							update_post_meta( $sc->ID, 'primary_color', 'rgba(61,73,198,0.8)' );
							update_post_meta( $sc->ID, 'designation', array( 'color' => 'rgba(61,73,198,0.8)' ) );
							update_post_meta( $sc->ID, 'social_icon_bg', 'rgba(61,73,198,0.8)' );
						}

						update_post_meta( $sc->ID, 'image_style', 'round' );

						/**
						 * Alignment.
						 */
						$name = get_post_meta( $sc->ID, 'name', true );
						if ( $name ) {
							$name['align'] = 'center';
							update_post_meta( $sc->ID, 'name', $name );
						} else {
							$name          = array();
							$name['align'] = 'center';
							update_post_meta( $sc->ID, 'name', $name );
						}

						/**
						 * Designation.
						 */
						$designation = get_post_meta( $sc->ID, 'designation', true );
						if ( $designation ) {
							$designation['align'] = 'center';
							update_post_meta( $sc->ID, 'designation', $designation );
						} else {
							$designation          = array();
							$designation['align'] = 'center';
							update_post_meta( $sc->ID, 'designation', $designation );
						}

						/**
						 * Short Bio.
						 */
						$short_bio = get_post_meta( $sc->ID, 'short_bio', true );
						if ( $short_bio ) {
							$short_bio['align'] = 'center';
							update_post_meta( $sc->ID, 'short_bio', $short_bio );
						} else {
							$short_bio          = array();
							$short_bio['align'] = 'center';
							update_post_meta( $sc->ID, 'short_bio', $short_bio );
						}
					} elseif ( $layout == 'isotope1' ) {
						update_post_meta( $sc->ID, 'layout', 'isotope-free' );
						update_post_meta( $sc->ID, 'ttp_detail_page_link', 1 );
					}

					/**
					 * Add selected field.
					 */
					delete_post_meta( $sc->ID, 'ttp_selected_field' );
					$selected_value = array( 'name', 'designation', 'short_bio', 'social' );
					if ( is_array( $selected_value ) && ! empty( $selected_value ) ) {
						foreach ( $selected_value as $item ) {
							add_post_meta( $sc->ID, 'ttp_selected_field', $item );
						}
					}
				} else {
					/**
					 * PRO version.
					 */
					switch ( $layout ) {
						case 'layout1':
							update_post_meta( $sc->ID, 'social_icon_bg', '#2254e8' );
							break;
					}
				}
			}
		}
	}

	/**
	 * Updates Shortcode data for update from <= v1.3.
	 *
	 * @return void
	 */
	private function update_sc_data() {
		$all_sc = get_posts(
			array(
				'post_type'      => rttlp_team()->shortCodePT,
				'posts_per_page' => -1,
			)
		);

		if ( is_array( $all_sc ) && ! empty( $all_sc ) ) {
			foreach ( $all_sc as $sc ) {
				/**
				 * Column option update.
				 */
				$col = get_post_meta( $sc->ID, 'column', true );

				if ( ! empty( $col ) ) {
					$new_val = array( 'desktop' => $col );
					update_post_meta( $sc->ID, 'ttp_column', $new_val );
				}

				/**
				 * Post per page update.
				 */
				$per_page = get_post_meta( $sc->ID, 'per_page', true );
				if ( ! empty( $per_page ) ) {
					update_post_meta( $sc->ID, 'ttp_posts_per_page', absint( $per_page ) );
				}

				/**
				 * Field selection option update.
				 */
				$fields = get_post_meta( $sc->ID, 'field', true );

				if ( ! empty( $fields ) && is_array( $fields ) ) {
					foreach ( $fields as $key => $field ) {
						add_post_meta( $sc->ID, 'ttp_selected_field', trim( $key ) );
					}
				}

				/**
				 * Update specific member option.
				 */
				$specific_members_action = get_post_meta( $sc->ID, 'specific_members_action', true );

				if ( $specific_members_action == true ) {
					$specific_members = get_post_meta( $sc->ID, 'specific_members', true );
					if ( ! empty( $specific_members ) && is_array( $specific_members ) ) {
						foreach ( $specific_members as $member ) {
							add_post_meta( $sc->ID, 'ttp_post__in', absint( $member ) );
						}
					}
				}

				/**
				 * Update department data.
				 */
				$departments = get_post_meta( $sc->ID, 'department_cat', true );

				if ( ! empty( $departments ) && is_array( $departments ) ) {
					foreach ( $departments as $department ) {
						add_post_meta( $sc->ID, 'ttp_departments', absint( $department ) );
					}
				}

				$btn = get_post_meta( $sc->ID, 'button_color', true );

				if ( ! empty( $btn ) && is_array( $btn ) ) {
					$new_btn = array();
					if ( ! empty( $btn['bg'] ) ) {
						$new_btn['bg'] = trim( $btn['bg'] );
					}
					if ( ! empty( $btn['hover'] ) ) {
						$new_btn['hover_bg'] = trim( $btn['hover'] );
					}
					if ( ! empty( $btn['active'] ) ) {
						$new_btn['active_bg'] = trim( $btn['active'] );
					}
					if ( ! empty( $btn['text'] ) ) {
						$new_btn['text'] = trim( $btn['text'] );
					}
					if ( ! empty( $new_btn ) ) {
						update_post_meta( $sc->ID, 'ttp_button_style', $new_btn );
					}
				}
			}
		}
	}

	/**
	 * Generates dynamic CSS.
	 *
	 * @return void
	 */
	private function generate_dynamic_css() {
		$sc_post_ids = get_posts(
			array(
				'post_type'      => rttlp_team()->shortCodePT,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'fields'         => 'ids',
			)
		);

		if ( is_array( $sc_post_ids ) && ! empty( $sc_post_ids ) ) {
			$css = null;
			foreach ( $sc_post_ids as $scPostId ) {
				Fns::generatorShortcodeCss( $scPostId );
			}
		}
	}

	/**
	 * Settings fix for update from v1.0 to v1.1.
	 *
	 * @return void
	 */
	private function update_settings() {
		$settings = get_option( 'tlp_team_settings' );

		if ( $settings ) {
			$new_settings = array();

			$new_settings['feature_img']      = isset( $settings['general']['img'] ) ? $settings['general']['img'] : rttlp_team()->defaultSettings['feature_img'];
			$new_settings['slug']             = isset( $settings['general']['slug'] ) ? $settings['general']['slug'] : rttlp_team()->defaultSettings['slug'];
			$new_settings['link_detail_page'] = isset( $settings['general']['link_detail_page'] ) ? $settings['general']['link_detail_page'] : rttlp_team()->defaultSettings['link_detail_page'];
			$new_settings['detail_page']      = isset( $settings['general']['detail_page'] ) ? $settings['general']['detail_page'] : rttlp_team()->defaultSettings['detail_page'];
			$new_settings['custom_css']       = isset( $settings['css'] ) ? $settings['css'] : rttlp_team()->defaultSettings['custom_css'];

			update_option( rttlp_team()->options['settings'], $new_settings );
		}
	}
}
