<?php
/**
 * Option callback class.
 *
 * @package SocialMediaFeather
 */

/**
 * Option callback class.
 */
class SynvedOptionCallback {
	/**
	 * Object var.
	 *
	 * @var mixed|null
	 */
	private $object;

	/**
	 * Callback.
	 *
	 * @var callable Callback.
	 */
	private $callback;

	/**
	 * Default.
	 *
	 * @var mixed|null Default value.
	 */
	private $default;

	/**
	 * Callback params.
	 *
	 * @var array|null Callback parameters array.
	 */
	private $callback_parameters;

	/**
	 * Constructor.
	 *
	 * @param callable   $callback            Callback function.
	 * @param object     $object              Callback object.
	 * @param mixed|null $default             Default fallback value.
	 * @param array|null $callback_parameters Callback parameters.
	 */
	public function __construct( $callback, $object = null, $default = null, array $callback_parameters = null ) {
		$this->object              = $object;
		$this->callback            = $callback;
		$this->default             = $default;
		$this->callback_parameters = $callback_parameters;
	}

	/**
	 * Invoke function.
	 *
	 * @param array|null $arguments Arguments array.
	 *
	 * @return false|mixed|null
	 */
	public function __invoke( $arguments = null ) {
		if ( false === is_array( $arguments ) || func_num_args() > 1 ) {
			$arguments = func_get_args();
		}

		return $this->invoke_internal( $arguments );
	}

	/**
	 * Invoke function.
	 *
	 * @param array|null $arguments Arguments array.
	 *
	 * @return false|mixed|null
	 */
	public function invoke( $arguments = null ) {
		if ( false === is_array( $arguments ) || func_num_args() > 1 ) {
			$arguments = func_get_args();
		}

		return $this->invoke_internal( $arguments );
	}

	/**
	 * Internal invoke.
	 *
	 * @param array|null $arguments Arguments array.
	 *
	 * @return false|mixed|null
	 */
	protected function invoke_internal( array $arguments = null ) {
		$func = $this->callback;

		if ( false === empty( $this->object ) ) {
			$func = array( $this->object, $func );
		}

		$parameters = $this->callback_parameters;

		if ( false === empty( $parameters ) ) {
			$parameter_keys = array_keys( $parameters );
			$count          = count( $parameter_keys );
			$argument_list  = array();

			for ( $i = 0; $i < $count; $i ++ ) {
				$key       = $parameter_keys[ $i ];
				$parameter = $parameters[ $key ];
				$value     = true === isset( $parameter['default'] ) ? $parameter['default'] : null;

				if ( isset( $arguments[ $key ] ) ) {
					$value = $arguments[ $key ];
				} elseif ( isset( $arguments[ $i ] ) ) {
					$value = $arguments[ $i ];
				}

				$argument_list[ $i ] = $value;
			}

			$arguments = $argument_list;
		}

		if ( false === isset( $arguments[0] ) || true === empty( $arguments[0] ) ) {
			$arguments[0] = $this->default;
		}

		if ( is_callable( $func ) ) {
			return call_user_func_array( $func, $arguments );
		}

		return $arguments[0];
	}
}
