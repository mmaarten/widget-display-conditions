<?php 
/**
 * Operators
 */

namespace wdc;

class Operators
{
	protected $operators = array();

	/**
	 * Constructor
	 */
	public function __construct()
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

get_instance()->operators = new Operators();

function create_operator( $id, $title, $args = array() )
{
	return get_instance()->operators->create_operator( $id, $title, $args );
}

function register_operator( $operator )
{
	get_instance()->operators->register_operator( $operator );
}

function unregister_operator( $operator_id )
{
	get_instance()->operators->unregister_operator( $operator_id );
}

function get_operators()
{
	return get_instance()->operators->get_operators();
}

function get_operator( $operator_id )
{
	return get_instance()->operators->get_operator( $operator_id );
}

function get_operator_objects( $operator_ids )
{
	return get_instance()->operators->get_operator_objects( $operator_ids );
}
