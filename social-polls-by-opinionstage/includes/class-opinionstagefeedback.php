<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Opinionstage feedback.
 *
 * Opinionstage feedback handler class is responsible for feedback modal on
 * disabling plugin.
 *
 * @since 19.7.4
 */
class OpinionstageFeedback {

	/**
	 * Project token.
	 *
	 * @since 19.7.4
	 * @var string
	 */
	private $project_token = '1aef78541d1dc41570207ecaf9ec235a';

	/**
	 * OpinionstageFeedback constructor.
	 *
	 * @since 19.7.4
	 */
	public function __construct() {
		add_action(
			'current_screen',
			function () {
				if ( ! $this->is_plugins_screen() ) {
					return;
				}
				if ( class_exists( 'Mixpanel' )
						&& ! method_exists( 'Mixpanel', 'getInstance' )
						&& ! method_exists( 'Mixpanel', 'track' )
				) {
					return;
				}

				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_feedback_dialog_scripts' ) );
			}
		);

		add_action( 'wp_ajax_opinionstage_deactivate_feedback', array( $this, 'ajax_opinionstage_deactivate_feedback' ) );
	}

	/**
	 * Enqueue feedback dialog scripts.
	 *
	 * @since 19.7.4
	 */
	public function enqueue_feedback_dialog_scripts() {
		add_action( 'admin_footer', array( $this, 'print_deactivate_feedback_dialog' ) );

		wp_register_script(
			'opinionstage-admin-feedback',
			plugin_dir_url( OPINIONSTAGE_PLUGIN_FILE ) . 'admin/js/admin-feedback.js',
			array( 'jquery' ),
			OPINIONSTAGE_WIDGET_VERSION,
			true
		);

		wp_enqueue_script( 'opinionstage-admin-feedback' );

		wp_register_style(
			'opinionstage-admin-feedback',
			plugin_dir_url( OPINIONSTAGE_PLUGIN_FILE ) . 'admin/css/admin-feedback.css',
			array(),
			OPINIONSTAGE_WIDGET_VERSION
		);
		wp_enqueue_style( 'opinionstage-admin-feedback' );
	}

	/**
	 * Return answers for deactivate feedback poll.
	 *
	 * @since 19.7.4
	 * @return array[]
	 */
	private function get_deactivate_feedback_reasons() {
		return array(
			'no_longer_needed'               => array(
				'title'             => esc_html__( 'I no longer need the plugin', 'social-polls-by-opinionstage' ),
				'input_placeholder' => '',
			),
			'found_a_better_plugin'          => array(
				'title'             => esc_html__( 'I found a better plugin', 'social-polls-by-opinionstage' ),
				'input_placeholder' => esc_html__( 'Please share which plugin', 'social-polls-by-opinionstage' ),
			),
			'couldnt_get_the_plugin_to_work' => array(
				'title'             => esc_html__( 'I couldn\'t get the plugin to work', 'social-polls-by-opinionstage' ),
				'input_placeholder' => esc_html__( 'What was the problem', 'social-polls-by-opinionstage' ),
			),
			'price_of_paid_plan'         => array(
				'title'             => esc_html__( 'Price of paid plan', 'social-polls-by-opinionstage' ),
				'input_placeholder' => '',
			),
			'hosted_on_external_site'         => array(
				'title'             => esc_html__( 'Hosted on external site', 'social-polls-by-opinionstage' ),
				'input_placeholder' => '',
			),
			'temporary_deactivation'         => array(
				'title'             => esc_html__( 'It\'s a temporary deactivation', 'social-polls-by-opinionstage' ),
				'input_placeholder' => '',
			),
			'other'                          => array(
				'title'             => esc_html__( 'Other', 'social-polls-by-opinionstage' ),
				'input_placeholder' => esc_html__( 'Please share the reason', 'social-polls-by-opinionstage' ),
			),
		);
	}

	/**
	 * Print deactivate feedback modal.
	 *
	 * @since 19.7.4
	 */
	public function print_deactivate_feedback_dialog() {
		$deactivate_reasons = $this->get_deactivate_feedback_reasons();
		?>
		<div class="opinionstage-dialog-box-wrapper" id="opinionistage-deactivate-feedback-modal" >
			<div class="opinionstage-dialog-box-content">
				<div class="opinionstage-deactivate-feedback-dialog-header">
					<img src="<?php echo esc_url( plugins_url( 'admin/images/os-icon.png', plugin_dir_path( __FILE__ ) ) ); ?>" class="opinionstage-modal-logo" >
					<span class="opinionstage-modal-header"><?php esc_html_e( 'Quick Feedback', 'social-polls-by-opinionstage' ); ?></span>
					<span id="opinionstage-dialog-close" class="opinionstage-close"></span>
				</div>
				<div class="opinionstage-dialog-message">
					<form id="opinionstage-deactivate-feedback-dialog-form" method="post">
						<?php
						wp_nonce_field( '_opinionstage_deactivate_feedback_nonce' );
						?>
						<input type="hidden" name="action" value="opinionstage_deactivate_feedback" />

						<div class="opinionstage-deactivate-feedback-dialog-form-caption"><?php esc_html_e( 'Please share why you are disabling Opinion Stage:', 'social-polls-by-opinionstage' ); ?></div>
						<div class="opinionstage-choices-wrapper">
							<?php foreach ( $deactivate_reasons as $reason_key => $reason ) : ?>
								<div class="opinionstage-deactivate-feedback-dialog-input-wrapper">
									<input id="opinionstage-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="opinionstage-deactivate-feedback-dialog-input" type="radio" name="reason_key" value="<?php echo esc_attr( $reason_key ); ?>" />
									<label for="opinionstage-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" ><?php echo esc_html( $reason['title'] ); ?></label>
									<?php if ( ! empty( $reason['input_placeholder'] ) ) : ?>
										<input class="opinionstage-feedback-text" type="text" name="reason_<?php echo esc_attr( $reason_key ); ?>" placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>" />
									<?php endif; ?>
									<?php if ( ! empty( $reason['alert'] ) ) : ?>
										<div><?php echo esc_html( $reason['alert'] ); ?></div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</form>
				</div>
				<div class="opinionstage-dialog-buttons">
					<button id="opinionstage-dialog-submit" class="opinionstage-dialog-submit"><div class="opinionstage-loading-ring"></div><?php esc_html_e( 'Submit & Deactivate', 'social-polls-by-opinionstage' ); ?></button>
					<button id="opinionstage-dialog-skip" class="opinionstage-dialog-skip"><?php esc_html_e( 'Skip & Deactivate', 'social-polls-by-opinionstage' ); ?></button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Ajax opinionstage deactivate feedback.
	 *
	 * @since 19.7.4
	 */
	public function ajax_opinionstage_deactivate_feedback() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), '_opinionstage_deactivate_feedback_nonce' ) ) {
			wp_send_json_error();
		}

		$reason = '';

		if ( ! empty( $_POST['reason_key'] ) ) {
			$reason = sanitize_text_field( wp_unslash( $_POST['reason_key'] ) );
		}

		$possible_reasons = $this->get_deactivate_feedback_reasons();

		if ( key_exists( $reason, $possible_reasons ) ) {
			$reason_text_key = '';
			$reason_text     = '';
			if ( isset( $_POST[ 'reason_' . $reason ] ) && trim( sanitize_text_field( wp_unslash( $_POST[ 'reason_' . $reason ] ) ) ) !== '' && ! empty( $possible_reasons[ $reason ]['input_placeholder'] ) ) {
				$reason_text_key = 'reason_' . $reason;
				$reason_text     = sanitize_text_field( wp_unslash( $_POST[ 'reason_' . $reason ] ) );
			}

			$data = array(
				'reason'            => $reason,
				'plugin_version'    => OPINIONSTAGE_WIDGET_VERSION,
				'wp_version'        => get_bloginfo( 'version' ),
				'site_lang'         => get_bloginfo( 'language' ),
				'plugin_was_active' => $this->plugin_active_time_pretty_string(),
				'is_connected'      => $this->is_connected_pretty_string(),
			);

			if ( '' !== $reason_text ) {
				$data[ $reason_text_key ] = $reason_text;
			}

			if ( ! class_exists( 'Mixpanel' ) ) {
				require_once plugin_dir_path( __FILE__ ) . 'mixpanel-php/lib/Mixpanel.php';
			} else if ( ! method_exists( 'Mixpanel', 'getInstance' ) || ! method_exists( 'Mixpanel', 'track' ) ) {
				wp_send_json_error();
			}
			try {
				$mp = Mixpanel::getInstance( $this->project_token );
				$mp->track( 'plugin_disabled', $data );
			} catch ( Exception $e ) {
				wp_send_json_error();
			}

			wp_send_json_success();
		}
		wp_send_json_error();
	}

	/**
	 * Returns plugin active time pretty string
	 *
	 * @since 19.8.6
	 */
	private function plugin_active_time_pretty_string() {
		$activated_time = get_option( 'oswp_installation_date' );
		if ( intval( $activated_time ) === 0 ) {
			return 'no-activation-time';
		}

		$time_diff = time() - $activated_time;

		$seconds_in_hour  = 3600;
		$seconds_in_day   = $seconds_in_hour * 24;
		$seconds_in_week  = $seconds_in_day * 7;
		$seconds_in_month = $seconds_in_day * 30;
		$seconds_in_year  = $seconds_in_day * 365;

		if ( $time_diff < $seconds_in_hour ) {
			$out = 'less than hour';
		} elseif ( $time_diff < $seconds_in_day ) {
			$out = 'less than day';
		} elseif ( $time_diff < $seconds_in_week ) {
			$out = 'less than week';
		} elseif ( $time_diff < $seconds_in_month ) {
			$out = 'less than month';
		} elseif ( $time_diff < $seconds_in_year ) {
			$out = 'less than year';
		} else {
			$out = 'more than year';
		}

		return $out;
	}

	/**
	 * Returns pretty string if opinionstage user connected
	 *
	 * @since 19.8.6
	 */
	private function is_connected_pretty_string() {
		return opinionstage_user_logged_in() ? 'yes' : 'no';
	}

	/**
	 * Determine if current page is plugins page
	 *
	 * @since 19.7.4
	 * @return bool
	 */
	private function is_plugins_screen() {
		return in_array( get_current_screen()->id, array( 'plugins', 'plugins-network' ), true );
	}
}

$opinionstage_feedback = new OpinionstageFeedback();
