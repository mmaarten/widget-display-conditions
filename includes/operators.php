<?php 
/**
 * Operators
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
	 * Create operator
	 *
	 * @param string $id
	 * @param string $title
	 * @param array  $args
	 *
	 * @return Operator
	 */
	public function create_operator( $id, $title, $args = array() )
	{
		$operator = new Operator( $id, $title, $args );

		$this->register_operator( $operator );

		return $operator;
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
	 * Get operator objects
	 *
	 * @param array $operator_ids
	 *
	 * @return array
	 */
	public function get_operator_objects( $operator_ids )
	{
		return array_intersect_key( get_operators(), array_flip( (array) $operator_ids ) );
	}
}

function create_operator( $id, $title, $args = array() )
{
	$operators = Operators::get_instance();

	return $operators->create_operator( $id, $title, $args );
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

function get_operator_objects( $operator_ids )
{
	$operators = Operators::get_instance();

	return $operators->get_operator_objects( $operator_ids );
}
