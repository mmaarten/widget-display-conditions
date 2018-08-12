<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly.

class WDC_Conditions
{
	protected $conditions = array();
	protected $categories = array();

	public function __construct()
	{
		
	}

	public function register_condition( $condition )
	{
		if ( ! $condition instanceof WDC_Condition ) 
		{
			$condition = new $condition();
		}

		$this->conditions[ $condition->id ] = $condition;
	}

	public function unregister_condition( $condition_id )
	{
		unset( $this->conditions[ $condition_id ] );
	}

	public function get_conditions( $category = '' )
	{
		if ( ! $category ) 
		{
			return $this->get_objects();
		}

		$conditions = array();

		foreach ( $this->conditions as $key => $condition ) 
		{
			if ( $condition->category == $category ) 
			{
				$conditions[ $key ] = $condition;
			}
		}

		return $conditions;
	}

	public function get_condition( $condition_id )
	{
		if ( isset( $this->conditions[ $condition_id ] ) ) 
		{
			return $this->conditions[ $condition_id ];
		}

		return null;
	}

	public function get_categories()
	{
		return $this->categories;
	}

	public function get_category( $id )
	{
		if ( isset( $this->categories[ $id ] ) ) 
		{
			return $this->categories[ $id ];
		}

		return null;
	}

	public function add_category( $id, $title )
	{
		$this->categories[ $id ] = array
		(
			'id'    => $id,
			'title' => $title
		);
	}

	public function remove_category( $id )
	{
		unset( $this->categories[ $id ] );
	}

	public function apply( $conditions )
	{
		if ( empty( $conditions ) ) 
		{
			return true;
		}

		$valid = false;
		
		foreach ( $conditions as $condition_group ) 
		{
			foreach ( $condition_group as $instance )
			{
				$condition = $this->get_condition( $instance->param );
				$operator  = wdc()->operators->get_operator( $instance->operator );

				if ( $condition && $operator ) 
				{
					$valid = $condition->apply( $instance->value, $operator );
				}

				else
				{
					$valid = false;
				}

				if ( ! $valid ) 
				{
					break;
				}
			}

			if ( $valid ) 
			{
				break;
			}
		}

		return $valid;
	}
}

wdc()->conditions = new WDC_Conditions();

function wdc_register_condition( $condition )
{
	wdc()->conditions->register_condition( $condition );
}

function wdc_unregister_condition( $condition_id )
{
	wdc()->conditions->unregister_condition( $condition_id );
}

function wdc_get_conditions( $category = '' )
{
	return wdc()->conditions->get_conditions( $category );
}

function wdc_get_condition( $condition_id )
{
	return wdc()->conditions->get_condition( $condition_id );
}

function wdc_add_category( $id, $title )
{
	return wdc()->conditions->add_category( $condition_id );
}

function wdc_remove_category( $category_id )
{
	return wdc()->conditions->remove_category( $category_id );
}

function wdc_get_categories()
{
	return wdc()->conditions->get_categories();
}

function wdc_get_category( $category_id )
{
	return wdc()->conditions->get_category( $category_id );
}

function wdc_apply_conditions( $conditions )
{
	return wdc()->conditions->apply( $conditions );
}

