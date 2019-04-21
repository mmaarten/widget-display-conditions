<?php 
/**
 * Operators API
 */

namespace wdc;

final class Operators
{
	static private $instance = null;

	static public function get_instance()
	{
		if ( ! self::$instance ) 
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected $operators = array();

	private function __construct()
	{
		
	}

	/**
	 * Register operator
	 *
	 * @param mixed $operator
	 */
	public function register_operator( $operator )
	{
		if ( ! $operator instanceof Operator ) 
		{
			$operator = new $operator();
		}

		$this->operators[ $operator->id ] = $operator;
	}

	/**
	 * Unregister operator
	 *
	 * @param string $operator_id
	 */
	public function unregister_operator( $operator_id )
	{
		unset( $this->operators[ $operator_id ] );
	}

	/**
	 * Get operators
	 *
	 * @return array
	 */
	public function get_operators()
	{
		return $this->operators;
	}

	/**
	 * Get operator
	 *
	 * @param string $operator_id
	 *
	 * @return mixed
	 */
	public function get_operator( $operator_id )
	{
		if ( isset( $this->operators[ $operator_id ] ) ) 
		{
			return $this->operators[ $operator_id ];
		}

		return null;
	}

	/**
	 * Apply operator
	 *
	 * @param string $operator_id
	 * @param mixed $a
	 * @param mixed $b
	 *
	 * @return mixed
	 */
	public function apply_operator( $operator_id, $a, $b )
	{
		$operator = $this->get_operator( $operator_id );

		if ( ! $operator ) 
		{
			trigger_error( sprintf( "Unable to find operator '%s'.", $operator_id ), E_USER_NOTICE );

			return null;
		}

		$return = $operator->apply( $a, $b );

		return null === $return ? $return : (bool) $return;
	}
}

function register_operator( $operator )
{
	$operators = Operators::get_instance();

	$operators->register_operator( $operator );
}

function unregister_operator( $operator_id )
{
	$operators = Operators::get_instance();

	$operators->unregister_operator( $operator_id );
}

function get_operators()
{
	$operators = Operators::get_instance();

	return $operators->get_operators();
}

function get_operator( $operator_id )
{
	$operators = Operators::get_instance();

	return $operators->get_operator( $operator_id );
}

function apply_operator( $operator_id, $a, $b )
{
	$operators = Operators::get_instance();

	return $operators->apply_operator( $operator_id, $a, $b );
}
