<?php
/**
 * Abstract Class for Controller.
 *
 * @package RT_Team
 */

namespace RT\Team\Abstracts;

/**
 * Abstract Class for Controller.
 */
abstract class Controller {
	/**
	 * Classes to include.
	 *
	 * @return array
	 */
	abstract public function classes();

	/**
	 * Init Classes.
	 *
	 * @return void
	 */
	protected function init() {
		foreach ( $this->classes() as $class ) {
			$class::get_instance();
		}
	}
}
