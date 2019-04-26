<?php 
/**
 * Conditions
 */

namespace wdc;

class Conditions
{
	protected $conditions = array();

	public function __construct()
	{
		
	}

	public function create_condition( $id, $title, $args = array() )
	{
		$condition = new Condition( $id, $title, $args );

		$this->register_condition( $condition );

		return $condition;
	}

	public function register_condition( $condition )
	{
		if ( ! $condition instanceof Condition ) 
		{
			$condition = new $condition();
		}

		$this->conditions[ $condition->id ] = $condition;
	}

	public function unregister_condition( $condition_id )
	{
		unset( $this->conditions[ $condition_id ] );
	}

	public function get_conditions()
	{
		return $this->conditions;
	}

	public function get_condition( $condition_id )
	{
		if ( isset( $this->conditions[ $condition_id ] ) ) 
		{
			return $this->conditions[ $condition_id ];
		}

		return null;
	}
}

get_instance()->conditions = new Conditions();

function create_condition( $id, $title, $args = array() )
{
	return get_instance()->conditions->create_condition( $id, $title, $args );
}

function register_condition( $condition )
{
	get_instance()->conditions->register_condition( $condition );
}

function unregister_condition( $condition_id )
{
	get_instance()->conditions->unregister_condition( $condition_id );
}

function get_conditions()
{
	return get_instance()->conditions->get_conditions();
}

function get_condition( $condition_id )
{
	return get_instance()->conditions->get_condition( $condition_id );
}
