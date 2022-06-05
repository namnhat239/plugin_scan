<?php
/**
 * Sidebar widget functional.
 *
 * @package   OpinionStageWordPressPlugin
 */

defined( 'ABSPATH' ) || die();

require_once plugin_dir_path( __FILE__ ) . 'opinionstage-client-session.php';

/**
 * Class OpinionStageWidget
 */
class OpinionStageWidget extends WP_Widget {

	/**
	 * OpinionStageWidget constructor.
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'   => 'opinionstage_widget',
			'description' => __( 'Add a quiz, poll, survey or form', 'social-polls-by-opinionstage' ),
		);
		parent::__construct(
			'opinionstage_widget',
			__( 'Opinion Stage Widget', 'social-polls-by-opinionstage' ),
			$widget_ops
		);
	}

	/**
	 * Returns the widget content
	 *
	 * @param array $args args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {

		if ( isset( $instance['opinionstage-widget-data'] ) && json_decode( $instance['opinionstage-widget-data'] ) ) {

			$os_widget_obj = json_decode( $instance['opinionstage-widget-data'] );

			if ( ! isset( $instance['enabled'] ) || intval( $instance['enabled'] ) !== 1 ) {
				return;
			}

			$before_widget = isset( $args['before_widget'] ) ? $args['before_widget'] : '';
			$after_widget  = isset( $args['after_widget'] ) ? $args['after_widget'] : '';

			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			//phpcs:disable
			echo $before_widget;
			if ( '' !== $title ) {
				echo $args['before_title'];
				echo $instance['title'];
				echo $args['after_title'];
			}

			echo do_shortcode( $os_widget_obj->shortcode );
			echo $after_widget;
			//phpcs:enable
			return;
		}

		// deprecated code - loads placement --start
		extract( $args );
		echo $before_widget;
		$title      = @$instance['title'];
		$os_options = (array) get_option( OPINIONSTAGE_OPTIONS_KEY );
		if ( ! empty( $title ) && $os_options['sidebar_placement_active'] == 'true' ) {
			echo $before_title . apply_filters( 'widget_title', $title ) . $after_title;
		}
		if ( ! empty( $os_options['sidebar_placement_id'] ) && $os_options['sidebar_placement_active'] == 'true' ) {
			echo opinionstage_widget_placement( opinionstage_placement_embed_code_url( $os_options['sidebar_placement_id'] ) );
		}
		echo $after_widget;
		// deprecated code - loads placement --end
	}

	/**
	 * Updates the widget settings (title and enabled flag)
	 *
	 * @param array $new_instance new_instance.
	 * @param array $old_instance old_instance.
	 * @return mixed
	 */
	public function update( $new_instance, $old_instance ) {
		$instance            = $old_instance;
		$instance['title']   = wp_strip_all_tags( $new_instance['title'] );
		$instance['enabled'] = wp_strip_all_tags( $new_instance['enabled'] );

		$instance['opinionstage-widget-data'] = $new_instance['opinionstage-widget-data'];

		return $instance;
	}

	/**
	 * Generates the admin form for the widget.
	 *
	 * @param array $instance instance.
	 */
	public function form( $instance ) {
		opinionstage_register_css_asset( 'icon-font', 'icon-font.css' );
		opinionstage_register_css_asset( 'sidebar-widget', 'sidebar-widget.css' );

		opinionstage_enqueue_css_asset( 'icon-font' );
		opinionstage_enqueue_css_asset( 'sidebar-widget' );

		$title   = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$enabled = isset( $instance['enabled'] ) ? intval( $instance['enabled'] ) : '';

		$os_widget_json = isset( $instance['opinionstage-widget-data'] ) ? $instance['opinionstage-widget-data'] : '';
		$os_widget_obj  = json_decode( $os_widget_json );

		$os_widget_id        = '';
		$os_widget_title     = '';
		$os_widget_img_url   = '';
		$os_widget_edit_url  = '';
		$os_widget_view_url  = '';
		$os_widget_stats_url = '';
		//phpcs:disable
		if ( is_object( $os_widget_obj ) ) {
			$os_widget_id        = $os_widget_obj->id;
			$os_widget_title     = $os_widget_obj->title;
			$os_widget_img_url   = $os_widget_obj->imageUrl;
			$os_widget_edit_url  = $os_widget_obj->editUrl;
			$os_widget_view_url  = $os_widget_obj->landingPageUrl;
			$os_widget_stats_url = $os_widget_obj->statsUrl;
		}
		//phpcs:enable

		$popup_button_title           = __( 'Select Item', 'social-polls-by-opinionstage' );
		$header_above_selected_widget = '';
		if ( '' !== $os_widget_id ) {
			$popup_button_title           = __( 'Change Item', 'social-polls-by-opinionstage' );
			$header_above_selected_widget = __( 'Selected Item:', 'social-polls-by-opinionstage' );
		}

		$os_client_logged_in = opinionstage_user_logged_in();
		$logo_url = plugins_url( 'admin/images/os-logo.png', dirname( __FILE__ ) );
        include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/template-parts/widget-form.php';
	}
}

/**
 * Register Sidebar Placement Widget.
 */
function opinionstage_init_widget() {
	register_widget( 'OpinionStageWidget' );
}

