<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

class WDC_Operators
{
	protected $operators = array();

	public function __construct()
	{
		
	}

	public function register_operator( $operator )
	{
		if ( ! $operator instanceof WDC_Operator ) 
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
}

wdc()->operators = new WDC_Operators();

function wdc_register_operator( $operator )
{
	wdc()->operators->register_operator( $operator );
}

function wdc_unregister_operator( $operator_id )
{
	wdc()->operators->unregister_condition( $operator_id );
}

function wdc_get_operators()
{
	return wdc()->operators->get_operators();
}

function wdc_get_operator( $operator_id )
{
	return wdc()->operators->get_operator( $operator_id );
}
