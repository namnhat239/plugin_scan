<?php
/**
 * Widget Controller Class.
 *
 * @package RT_Team
 */

namespace RT\Team\Controllers;

use RT\Team\Widgets as Widgets;

/**
 * Widget Controller Class.
 */
class WidgetsController {
	use \RT\Team\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'widgets_init', array( $this, 'load_widgets' ) );
	}

	/**
	 * Load widgets.
	 *
	 * @return void
	 */
	public function load_widgets() {
		$widgets = array(
			Widgets\TeamWidget::class,
			Widgets\TeamCarousel::class,
			Widgets\TeamShortcodeWidget::class,
		);

		foreach ( $widgets as $widget ) {
			register_widget( $widget );
		}
	}
}
