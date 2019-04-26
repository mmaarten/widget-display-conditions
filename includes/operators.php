<?php 
/**
 * Operators
 */

namespace wdc;

class Operators
{
	protected $operators = array();

	public function __construct()
	{
		
	}

	public function create_operator( $id, $title, $args = array() )
	{
		$operator = new Operator( $id, $title, $args );

		$this->register_operator( $operator );

		return $operator;
	}

	public function register_operator( $operator )
	{
		if ( ! $operator instanceof Operator ) 
		{
			$operator = new $operator();
		}

		$this->operators[ $operator->id ] = $operator;
	}

	public function unregister_operator( $operator_id )
	{
		unset( $this->operators[ $operator_id ] );
	}

	public function get_operators()
	{
		return $this->operators;
	}

	public function get_operator( $operator_id )
	{
		if ( isset( $this->operators[ $operator_id ] ) ) 
		{
			return $this->operators[ $operator_id ];
		}

		return null;
	}

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
