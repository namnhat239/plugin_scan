<?php
/**
 * Social Widget.
 *
 * @package SocialMediaFeather
 */

/**
 * Social Widget Class.
 */
class SynvedSocialWidget extends WP_Widget {
	/**
	 * Constructor.
	 *
	 * @param string $id_base         Optional. Base ID for the widget, lowercase and unique. If left empty,
	 *                                a portion of the widget's PHP class name will be used. Has to be unique.
	 * @param string $name            Name for the widget displayed on the configuration page.
	 * @param array  $widget_options  Optional. Widget options. See wp_register_sidebar_widget() for
	 *                                information on accepted arguments. Default empty array.
	 * @param array  $control_options Optional. Widget control options. See wp_register_widget_control() for
	 *                                information on accepted arguments. Default empty array.
	 */
	public function __construct(
		$id_base = false,
		$name = null,
		$widget_options = array(),
		$control_options = array()
	) {
		if ( true === empty( $name ) ) {
			$name = 'Social Media Feather';
		}

		parent::__construct( $id_base, $name, $widget_options, $control_options );
	}

	/**
	 * Echoes the widget content.
	 *
	 * Subclasses should override this function to generate their widget code.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		// Intentionally keeping extract() for now.
		extract( $args ); // phpcs:ignore
		extract( $instance ); // phpcs:ignore

		ob_start();

		// Escaped in wp_kses_post below.
		echo $before_widget; // phpcs:ignore

		if ( false === empty( $title ) ) {

			// Escaped in wp_kses_post below.
			echo $before_title . $title . $after_title; // phpcs:ignore
		}

		echo '<div>';

		$params              = array();
		$params['alignment'] = 'none';

		if ( 'default' !== $icon_skin ) {
			$params['skin'] = $icon_skin;
		}

		if ( 'default' !== $icon_size ) {
			$params['size'] = $icon_size;
		}

		if ( false === empty( $icon_spacing ) ) {
			$params['spacing'] = $icon_spacing;
		}

		$this->render_social_markup( $params );

		echo '</div>';

		// Escaped in wp_kses_post below.
		echo $after_widget; // phpcs:ignore

		$html = ob_get_clean();

		echo wp_kses_post( $html );
	}

	/**
	 * Get default values.
	 *
	 * @return string[] Values array.
	 */
	public function get_defaults() {
		return array(
			'icon_skin'    => 'default',
			'icon_size'    => 'default',
			'icon_spacing' => '',
		);
	}

	/**
	 * Render social markup.
	 *
	 * @param array $params Parameters array.
	 *
	 * @return void
	 */
	public function render_social_markup( $params = null ) {
		// Do nothing.
	}

	/**
	 * Update.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                 = $old_instance;
		$instance['title']        = wp_strip_all_tags( $new_instance['title'] );
		$instance['icon_skin']    = wp_strip_all_tags( $new_instance['icon_skin'] );
		$instance['icon_size']    = wp_strip_all_tags( $new_instance['icon_size'] );
		$instance['icon_spacing'] = wp_strip_all_tags( $new_instance['icon_spacing'] );

		return $instance;
	}

	/**
	 * Form render.
	 *
	 * NOTE: Escaped via wp_kses_post().
	 *
	 * @param array $instance Current settings.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->get_defaults() );

		ob_start();
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title', 'social-media-feather' ); ?>:
			</label>
			<input type="text" class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				value="<?php echo esc_attr( $instance['title'] ); ?>"
			/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_skin' ) ); ?>">
				<?php esc_html_e( 'Icon Skin', 'social-media-feather' ); ?>:
			</label>
			<?php
			$params = array(
				'tip'         => '',
				'output_name' => $this->get_field_name( 'icon_skin' ),
				'value'       => $instance['icon_skin'],
				'set_before'  => array( array( 'default' => __( 'Use Default' ) ) ),
			);

			$item = synved_option_item( 'synved_social', 'icon_skin' );

			if ( false === empty( $item ) ) {
				if ( true === is_object( $item ) ) {
					$item = clone $item;
				}

				unset( $item['render'] );

				synved_option_render_item( 'synved_social', 'icon_skin', $item, true, $params, 'widget' );
			}
			?>
			<br/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_size' ) ); ?>">
				<?php esc_html_e( 'Icon Size', 'social-media-feather' ); ?>:
			</label>
			<?php
			$params = array(
				'tip'         => '',
				'output_name' => $this->get_field_name( 'icon_size' ),
				'value'       => $instance['icon_size'],
				'set_before'  => array( array( 'default' => __( 'Use Default' ) ) ),
			);

			synved_option_render_item( 'synved_social', 'icon_size', null, true, $params, 'widget' );
			?>
			<br/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_spacing' ) ); ?>">
				<?php esc_html_e( 'Icon Spacing', 'social-media-feather' ); ?>:
			</label>
			<input type="text" size="3" class="" id="<?php echo esc_attr( $this->get_field_id( 'icon_spacing' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'icon_spacing' ) ); ?>"
				value="<?php echo esc_attr( $instance['icon_spacing'] ); ?>"/>
		</p>
		<?php

		$form = ob_get_clean();

		echo wp_kses( $form, synved_get_allowed_html_array() );
	}
}
