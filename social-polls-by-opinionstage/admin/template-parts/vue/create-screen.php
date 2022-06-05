<?php
/**
 * Create Screen template.
 *
 * @package OpinionStageWordPressPlugin
 */

?>
<div class="opinionstage-dashboard">
	<div class="opinionstage-dashboard-left">
		<div id="opinionstage-section-create" class="opinionstage-dashboard-section">
			<div class="opinionstage-section-header">
				<div class="opinionstage-section-title"><?php esc_html_e( 'Create', 'social-polls-by-opinionstage' ); ?></div>
			</div>
			<div class="opinionstage-section-content">
				<div class="opinionstage-section-raw">
					<div class="opinionstage-section-cell opinionstage-icon-cell">
						<div class="os-icon-plugin"><img
									src="<?php echo esc_url( plugins_url( '../images/poll.png', dirname( __FILE__ ) ) ); ?>">
						</div>
					</div>
					<div class="opinionstage-section-cell opinionstage-description-cell">
						<div class="title"><?php esc_html_e( 'Poll', 'social-polls-by-opinionstage' ); ?></div>
						<div class="example"><?php esc_html_e( 'Ask one question and define several answer choices', 'social-polls-by-opinionstage' ); ?></div>
					</div>
					<div class="opinionstage-section-cell opinionstage-btn-cell">
						<?php echo opinionstage_create_poll_link( 'opinionstage-blue-btn', __( 'From scratch', 'social-polls-by-opinionstage' ) ); ?>
						<a href="<?php echo esc_url( opinionstage_get_templates_url_for_type( 'polls' ) ); ?>"
						   class="opinionstage-blue-btn border"
						   target="_blank"><?php esc_html_e( 'From Template' ); ?></a>
					</div>
				</div>
				<div class="opinionstage-section-raw">
					<div class="opinionstage-section-cell opinionstage-icon-cell">
						<div class="os-icon-plugin"><img
									src="<?php echo esc_url( plugins_url( '../images/survey.png', dirname( __FILE__ ) ) ); ?>">
						</div>
					</div>
					<div class="opinionstage-section-cell opinionstage-description-cell">
						<div class="title"><?php esc_html_e( 'Survey', 'social-polls-by-opinionstage' ); ?></div>
						<div class="example"><?php esc_html_e( 'Ask multiple questions from a range of question types', 'social-polls-by-opinionstage' ); ?></div>
					</div>
					<div class="opinionstage-section-cell opinionstage-btn-cell">
						<?php echo opinionstage_create_survey_link( 'opinionstage-blue-btn', __( 'From scratch', 'social-polls-by-opinionstage' ) ); ?>
						<a href="<?php echo esc_url( opinionstage_get_templates_url_for_type( 'surveys' ) ); ?>"
						   class="opinionstage-blue-btn border"
						   target="_blank"><?php esc_html_e( 'From Template' ); ?></a>
					</div>
				</div>
				<div class="opinionstage-section-raw">
					<div class="opinionstage-section-cell opinionstage-icon-cell">
						<div class="os-icon-plugin"><img
									src="<?php echo esc_url( plugins_url( '../images/trivia.png', dirname( __FILE__ ) ) ); ?>">
						</div>
					</div>
					<div class="opinionstage-section-cell opinionstage-description-cell">
						<div class="title"><?php esc_html_e( 'Trivia Quiz', 'social-polls-by-opinionstage' ); ?></div>
						<div class="example"><?php esc_html_e( 'Create a knowledge test or assessment', 'social-polls-by-opinionstage' ); ?></div>
					</div>
					<div class="opinionstage-section-cell opinionstage-btn-cell">
						<?php echo opinionstage_create_trivia_link( 'opinionstage-blue-btn', __( 'From scratch', 'social-polls-by-opinionstage' ) ); ?>
						<a href="<?php echo esc_url( opinionstage_get_templates_url_for_type( 'trivia_quizzes' ) ); ?>"
						   class="opinionstage-blue-btn border"
						   target="_blank"><?php esc_html_e( 'From Template' ); ?></a>
					</div>
				</div>
				<div class="opinionstage-section-raw">
					<div class="opinionstage-section-cell opinionstage-icon-cell">
						<div class="os-icon-plugin"><img
									src="<?php echo esc_url( plugins_url( '../images/personality.png', dirname( __FILE__ ) ) ); ?>">
						</div>
					</div>
					<div class="opinionstage-section-cell opinionstage-description-cell">
						<div class="title"><?php esc_html_e( 'Personality Quiz', 'social-polls-by-opinionstage' ); ?></div>
						<div class="example"><?php esc_html_e( 'Create a personality test or a product/service selector', 'social-polls-by-opinionstage' ); ?></div>
					</div>
					<div class="opinionstage-section-cell opinionstage-btn-cell">
						<?php echo opinionstage_create_personality_link( 'opinionstage-blue-btn', __( 'From scratch', 'social-polls-by-opinionstage' ) ); ?>
						<a href="<?php echo esc_url( opinionstage_get_templates_url_for_type( 'personality_quizzes' ) ); ?>"
						   class="opinionstage-blue-btn border"
						   target="_blank"><?php esc_html_e( 'From Template' ); ?></a>
					</div>
				</div>
				<div class="opinionstage-section-raw">
					<div class="opinionstage-section-cell opinionstage-icon-cell">
						<div class="os-icon-plugin"><img
									src="<?php echo esc_url( plugins_url( '../images/form.png', dirname( __FILE__ ) ) ); ?>">
						</div>
					</div>
					<div class="opinionstage-section-cell opinionstage-description-cell">
						<div class="title"><?php esc_html_e( 'Standard Form', 'social-polls-by-opinionstage' ); ?></div>
						<div class="example"><?php esc_html_e( 'Display all fields on one page (use surveys for interactive forms)', 'social-polls-by-opinionstage' ); ?></div>
					</div>
					<div class="opinionstage-section-cell opinionstage-btn-cell">
						<?php echo opinionstage_create_form_link( 'opinionstage-blue-btn', __( 'From scratch', 'social-polls-by-opinionstage' ) ); ?>
						<a href="<?php echo esc_url( opinionstage_get_templates_url_for_type( 'classic_forms' ) ); ?>"
						   class="opinionstage-blue-btn border"
						   target="_blank"><?php esc_html_e( 'From Template' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
