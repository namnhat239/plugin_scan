<?php
/**
 * Export Import Ajax Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers\Admin\Ajax;

/**
 * Export Import Ajax Class.
 */
class ExportImport {
	use \RT\Team\Traits\SingletonTrait;

	private $memberMetas = array(
		'short_bio',
		'experience_year',
		'email',
		'telephone',
		'mobile',
		'web_url',
		'location',
		'skill',
		'social',
		'department',
		'designation',
	);

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_team_member_import', array( $this, 'import' ) );
		add_action( 'wp_ajax_tlp_team_export', array( $this, 'export' ) );
	}

	/**
	 * Import.
	 *
	 * @return void
	 */
	public function import() {
		$error  = true;
		$member = ! empty( $_REQUEST['member'] ) ? $_REQUEST['member'] : null;
		if ( $member ) {
			$member_post = array(
				'post_title'   => wp_strip_all_tags( ! empty( $member['name'] ) ? $member['name'] : null ),
				'post_content' => ! empty( $member['content'] ) ? $member['content'] : null,
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'post_type'    => rttlp_team()->post_type,
			);
			$member_id   = wp_insert_post( $member_post );
			if ( $member_id ) {
				if ( ! empty( $member['feature_image'] ) ) {
					$this->set_remote_featured_image( esc_url( $member['feature_image'] ), $member_id );
				}
				foreach ( $this->memberMetas as $metaKey ) {
					if ( ! empty( $member[ $metaKey ] ) ) {
						if ( 'short_bio' == $metaKey ) {
							update_post_meta( $member_id, $metaKey, wp_kses_post( $member['short_bio'] ) );
						} elseif ( 'skill' == $metaKey ) {
							$sK = array_filter( $member['skill'] );
							update_post_meta( $member_id, $metaKey, serialize( $sK ) );
							foreach ( $sK as $skill ) {
								if ( ! empty( $skill['id'] ) ) {
									$skill_exist = term_exists( $skill['id'], rttlp_team()->taxonomies[ $metaKey ] );
									if ( $skill_exist == 0 && $skill_exist == null ) {
										wp_insert_term( $skill['id'], rttlp_team()->taxonomies[ $metaKey ] );
									}
								}
							}
						} elseif ( 'social' == $metaKey ) {
							$s = array_filter( $member['social'] );
							update_post_meta( $member_id, 'social', base64_encode( serialize( $s ) ) );
						} elseif ( 'department' == $metaKey || 'designation' == $metaKey ) {
							$terms_list = $member[ $metaKey ];
							wp_set_object_terms( $member_id, $terms_list, rttlp_team()->taxonomies[ $metaKey ] );
						} else {
							update_post_meta( $member_id, $metaKey, sanitize_text_field( $member[ $metaKey ] ) );
						}
					}
				}
				$error = false;
			}
		}
		wp_send_json(
			array(
				'error' => $error,
			)
		);

		die();
	}
	/**
	 * Export.
	 *
	 * @return void
	 */
	public function export() {
		$data = $membersAllData = array();
		$type = ! empty( $_REQUEST['data'] ) ? $_REQUEST['data'] : array();
		if ( in_array( 'members', $type ) ) {
			$members_obg = get_posts(
				array(
					'post_type'      => rttlp_team()->post_type,
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				)
			);
			if ( ! empty( $members_obg ) ) {
				$membersData = array();
				foreach ( $members_obg as $member ) {
					$membersData['title']   = $member->post_title;
					$membersData['content'] = $member->post_content;
					if ( has_post_thumbnail( $member->ID ) ) {
						$image                        = wp_get_attachment_image_src( get_post_thumbnail_id( $member->ID ) );
						$membersData['feature_image'] = $image[0];
					}
					foreach ( $this->memberMetas as $metaKey ) {
						switch ( $metaKey ) {

							case 'skill':
								break;
							case 'social':
								$membersData[ $metaKey ] = get_post_meta( $member->ID, $metaKey, true );
								break;
							default:
								$membersData[ $metaKey ] = get_post_meta( $member->ID, $metaKey, true );
								break;

						}
					}
					$membersAllData[] = $membersData;
				}
				$data['members'] = $membersAllData;
			}
		}
		if ( in_array( 'short_code', $type ) ) {

		}
		if ( in_array( 'settings', $type ) ) {

		}

		die();
	}

	public function set_remote_featured_image( $file, $post_id ) {
		if ( ! empty( $file ) ) {
			// Download file to temp location
			$tmp = download_url( $file );

			// Set variables for storage
			// fix file filename for query strings
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
			$file_array['name']     = basename( $matches[0] );
			$file_array['tmp_name'] = $tmp;

			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp ) ) {
				@unlink( $file_array['tmp_name'] );
				$file_array['tmp_name'] = '';
			}

			// do the validation and storage stuff
			$id = media_handle_sideload( $file_array, $post_id );
			// If error storing permanently, unlink
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );

				return $id;
			}

			set_post_thumbnail( $post_id, $id );
		}
	}
}
