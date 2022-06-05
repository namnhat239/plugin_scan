<?php
/**
 * Opinionstage Getting Started Admin page
 *
 * @package OpinionStageWordPressPlugin */

defined( 'ABSPATH' ) || die();

$links_columns_items = array(
	array(
		'title' => __( 'Quiz Templates', 'social-polls-by-opinionstage' ),
		'items' => array(
			array(
				'path'  => 't/personality-quiz-template',
				'title' => __( 'Personality Quiz', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 't/trivia-quiz-template',
				'title' => __( 'Trivia Quiz', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 't/lead-quiz-template',
				'title' => __( 'Lead Quiz', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 't/quiz-competition-template',
				'title' => __( 'Competition Quiz', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 'c/quizzes/types',
				'title' => __( 'All Quiz Types', 'social-polls-by-opinionstage' ),
			),
		),
	),
	array(
		'title' => __( 'Poll Templates', 'social-polls-by-opinionstage' ),
		'items' => array(
			array(
				'path'  => 't/list-poll-single-answer',
				'title' => __( 'Standard poll', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 't/image-poll',
				'title' => __( 'Image poll', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 't/thumbnail-poll',
				'title' => __( 'Thumbnail poll', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 't/head-to-head-poll',
				'title' => __( 'Head to Head poll', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 't/rounded-image-poll',
				'title' => __( 'Rounded-Image Poll', 'social-polls-by-opinionstage' ),
			),
		),
	),
	array(
		'title' => __( 'Survey Templates', 'social-polls-by-opinionstage' ),
		'items' => array(
			array(
				'path'  => 't/customer-feedback-survey',
				'title' => __( 'Feedback survey', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 't/how-do-you-feel-about-working-from-home-',
				'title' => __( 'Satisfaction survey', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 't/user-experience-questionnaire',
				'title' => __( 'User experience survey', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 't/website-design-questionnaire',
				'title' => __( 'Website design survey ', 'social-polls-by-opinionstage' ),
			),
			array(
				'path'  => 'c/surveys',
				'title' => __( 'All Survey Templates', 'social-polls-by-opinionstage' ),
			),
		),
	),
);

$plugin_logos_path_url = plugin_dir_url( OPINIONSTAGE_PLUGIN_FILE ) . 'admin/images/logos/';
$client_logos          = array(
	array(
		'url'   => $plugin_logos_path_url . 'nbc.png',
		'alt'   => __( 'NBC', 'social-polls-by-opinionstage' ),
		'width' => 73.5,
	),
	array(
		'url'   => $plugin_logos_path_url . 'wb.png',
		'alt'   => __( 'WP', 'social-polls-by-opinionstage' ),
		'width' => 28.5,
	),
	array(
		'url'   => $plugin_logos_path_url . 'uber.png',
		'alt'   => __( 'Uber', 'social-polls-by-opinionstage' ),
		'width' => 54.5,
	),
	array(
		'url'   => $plugin_logos_path_url . 'ipg.png',
		'alt'   => __( 'IPG', 'social-polls-by-opinionstage' ),
		'width' => 38.5,
	),
	array(
		'url'   => $plugin_logos_path_url . 'bbdo.png',
		'alt'   => __( 'BBDO', 'social-polls-by-opinionstage' ),
		'width' => 57,
	),
	array(
		'url'   => $plugin_logos_path_url . 'harvard.png',
		'alt'   => __( 'Harvard Law School Wordmark', 'social-polls-by-opinionstage' ),
		'width' => 103,
	),
	array(
		'url'   => $plugin_logos_path_url . 'virgin.png',
		'alt'   => __( 'Virgin Group', 'social-polls-by-opinionstage' ),
		'width' => 49,
	),
	array(
		'url'   => $plugin_logos_path_url . 'pepsico.png',
		'alt'   => __( 'Pepsico', 'social-polls-by-opinionstage' ),
		'width' => 129,
	),
);



/**
 * Generates template preview url.
 *
 * @param string $path part or url.
 * @return mixed
 */
function opinionstage_generate_template_url( $path ) {
	return add_query_arg(
		OPINIONSTAGE_UTM_PARAMETERS,
		OPINIONSTAGE_SERVER_BASE . '/templates/' . $path
	);
}
?>
<div id="opinionstage-content">
	<div class="opinionstage-bg-white">
		<div class="opinionstage-getting-started-section opinionstage-getting-started-section__no-padding">
			<div class="opinionstage-logo opinionstage-logo__dark"></div>
		</div>
		<div class="opinionstage-getting-started-section opinionstage-two-columns">
			<div class="opinionstage-two-columns__text">
				<h1 class="opinionstage-two-columns__title"><?php esc_html_e( 'Add Quizzes, Polls & Surveys to Your Website in Seconds', 'social-polls-by-opinionstage' ); ?></h1>
				<div>
					<p><?php esc_html_e( 'Drive engagement and traffic to your site, gather leads, and collect reliable data. Beautiful designs. Fully customizable. Easy to set up, manage, and track.', 'social-polls-by-opinionstage' ); ?></p>
				</div>

				<?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'template-parts/signup-form.php'; ?>
				<div>
				</div>
			</div>
			<div class="opinionstage-two-columns__img">
				<img src="<?php echo esc_url( plugins_url( 'images/welcome-to-opinionstage.jpg', dirname( __FILE__ ) ) ); ?>" alt="<?php esc_html_e( 'Welcome to Opinion Stage', 'social-polls-by-opinionstage' ); ?>">
			</div>
		</div>

		<div class="opinionstage-getting-started-section">
			<h2 class="opinionstage-trusted"><?php esc_html_e( 'Trusted by', 'social-polls-by-opinionstage' ); ?> <span class="optinoinstage-black">150,000+</span> <?php esc_html_e( 'Customers across 190 countries', 'social-polls-by-opinionstage' ); ?></h2>
			<ul class="opinionstage-clients">
				<?php
				foreach ( $client_logos as $client_logo ) {
					?>
					<li><img src="<?php echo esc_url( $client_logo['url'] ); ?>" alt="<?php echo esc_attr( $client_logo['url'] ); ?>" width="<?php echo esc_attr( $client_logo['width'] ); ?>"></li>
					<?php
				}
				?>
			</ul>
		</div>
	</div>

	<div class="opinionstage-bg-white">
		<div class="opinionstage-getting-started-section">
			<div class="opinionstage-getting-started-examples">
				<h2 class="opinionstage-getting-started-examples__title"><?php esc_html_e( 'Check out these examples:', 'social-polls-by-opinionstage' ); ?></h2>
				<div class="opinionstage-getting-started-examples__items">
					<?php
					foreach ( $links_columns_items as $col ) {
						?>
						<div class="opinionstage-getting-started-examples__item">
							<h3 class="opinionstage-getting-started-examples__item__title"><?php echo esc_html( $col['title'] ); ?></h3>
							<ul class="opinionstage-getting-started-examples__list">
								<?php
								foreach ( $col['items'] as $anchor ) {
									?>
									<li>
										<a href="<?php echo esc_url( opinionstage_generate_template_url( $anchor['path'] ) ); ?>" target="_blank"><?php echo esc_html( $anchor['title'] ); ?></a>
									</li>
									<?php
								}
								?>
							</ul>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="opinionstage-bg-blue">
		<div class="opinionstage-getting-started-section opinionstage-getting-started-video">
			<h2 class="opinionstage-getting-started-examples__title"><?php esc_html_e( 'How to use the Plugin', 'social-polls-by-opinionstage' ); ?></h2>

			<p class="opinionstage-getting-started-video__description"><?php esc_html_e( 'Follow these steps to create a poll, survey or quiz and add it to your site in minutes', 'social-polls-by-opinionstage' ); ?></p>

			<iframe width="600" height="337" 
					src="https://www.youtube.com/embed/DMcosYCBFDs?controls=0&showinfo=0"
					title="YouTube video player" frameborder="0"
					allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
					allowfullscreen></iframe>

		</div>
	</div>
</div>
