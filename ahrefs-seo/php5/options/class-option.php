<?php

namespace ahrefs\AhrefsSeo\Options;

/**
 * Options class.
 *
 * @since 0.9.4
 */
abstract class Option {

	const OPTION_BASE = 'ahrefs-seo-options-a-';
	/** @var bool */
	protected $is_enabled = false;
	/**
	 * Is this option enabled by user?
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return $this->is_enabled;
	}
	/**
	 * Enable option.
	 *
	 * @param bool $enabled This option is enabled.
	 * @return Option
	 */
	public function set_enabled( $enabled = true ) {
		$this->load_options();
		$this->is_enabled = $enabled;
		$this->save_options();
		return $this;
	}
	/**
	 * Render view with options
	 *
	 * @return void
	 */
	abstract public function render_view();
	/**
	 * The view has a section with options vs single option only
	 *
	 * @return bool
	 */
	public function has_sub_options() {
		return false;
	}
	/**
	 * Get options hash.
	 *
	 * @return string
	 */
	public function get_options_hash() {
		return str_replace( __NAMESPACE__, '', get_class( $this ) ) . ( $this->has_sub_options() ? 'F' : 'S' ) . ( $this->is_enabled ? 'E' : 'D' );
	}
	/**
	 * Load options from request
	 *
	 * @return bool Success.
	 */
	public function load_options_from_request() {
		$this->save_options();
		return true;
	}
	/**
	 * Import options from older plugin versions.
	 *
	 * @return void
	 */
	abstract public function import_from_older_version();
	/**
	 * Save options to DB
	 *
	 * @return self
	 */
	abstract protected function save_options();
	/**
	 * Load options from DB
	 *
	 * @return self
	 */
	abstract protected function load_options();
	/**
	 * Get name for option save/load.
	 *
	 * @return string Option name.
	 */
	abstract protected function get_option_name();
	/**
	 * Return name of var for using in render and load from request option
	 *
	 * @param string $suffix Suffix for the variable name.
	 * @return string
	 */
	abstract protected function get_var_name( $suffix = '');
}
