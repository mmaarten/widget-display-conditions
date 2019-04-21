<?php 
/**
 * Conditions API
 */

namespace wdc;

final class Conditions
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

	protected $conditions = array();

	private function __construct()
	{
		
	}

	/**
	 * Get Param Field Items
	 *
	 * @return array
	 */
	function get_param_field_items()
	{
		$conditions = $this->get_conditions();

		$items = array();

		foreach ( $conditions as $condition ) 
		{
			$items[ $condition->id ] = array
			(
				'id'   => $condition->id,
				'text' => $condition->title,
			);
		}

		return $items;
	}

	/**
	 * Get Operator Field Items
	 *
	 * @param string $condition_id
	 *
	 * @return mixed
	 */
	function get_operator_field_items( $condition_id )
	{
		$condition = $this->get_condition( $condition_id );

		if ( $condition ) 
		{
			return $condition->get_operator_field_items();
		}

		return null;
	}

	/**
	 * Get Value Field Items
	 *
	 * @param string $condition_id
	 *
	 * @return mixed
	 */
	function get_value_field_items( $condition_id )
	{
		$condition = $this->get_condition( $condition_id );

		if ( $condition ) 
		{
			return $condition->get_value_field_items();
		}

		return null;
	}

	/**
	 * Register condition
	 *
	 * @param mixed $condition
	 */
	public function register_condition( $condition )
	{
		if ( ! $condition instanceof Condition ) 
		{
			$condition = new $condition();
		}

		$this->conditions[ $condition->id ] = $condition;
	}

	/**
	 * Unregister condition
	 *
	 * @param string $condition_id
	 */
	public function unregister_condition( $condition_id )
	{
		unset( $this->conditions[ $condition_id ] );
	}

	/**
	 * Get conditions
	 *
	 * @return array
	 */
	public function get_conditions()
	{
		return $this->conditions;
	}

	/**
	 * Get condition
	 *
	 * @param string $condition_id
	 *
	 * @return mixed
	 */
	public function get_condition( $condition_id )
	{
		if ( isset( $this->conditions[ $condition_id ] ) ) 
		{
			return $this->conditions[ $condition_id ];
		}

		return null;
	}

	/**
	 * Apply Conditions
	 *
	 * @param array $rules
	 *
	 * @return bool
	 */
	public function apply_conditions( $rules )
	{
		if ( ! $rules ) return true;

		$return = false;

		foreach ( $rules as $group ) 
		{
			foreach ( $group as $rule ) 
			{
				$return = $this->apply_condition( $rule );

				if ( ! $return ) break;
			}

			if ( $return ) break;
		}

		return $return;
	}

	/**
	 * Apply Condition
	 *
	 * @param array $rule
	 *
	 * @return mixed
	 */
	public function apply_condition( $rule )
	{
		// Check rule

		if ( ! is_array( $rule ) || ! isset( $rule['param'], $rule['operator'], $rule['value'] ) ) 
		{
			trigger_error( "Invalid rule: 'param', 'operator' and 'value' are required.", E_USER_NOTICE );

			return null;
		}

		// Get condition

		$condition_id = $rule['param'];

		$condition = $this->get_condition( $condition_id );

		if ( ! $condition ) 
		{
			trigger_error( sprintf( "Unable to find condition '%s'.", $condition_id ), E_USER_NOTICE );

			return null;
		}

		// Apply condition

		$return = $condition->apply( $rule['operator'], $rule['value'] );

		return null === $return ? $return : (bool) $return;
	}
}

function register_condition( $condition )
{
	$conditions = Conditions::get_instance();

	$conditions->register_condition( $condition );
}

function unregister_condition( $condition_id )
{
	$conditions = Conditions::get_instance();

	$conditions->unregister_condition( $condition_id );
}

function get_conditions()
{
	$conditions = Conditions::get_instance();

	return $conditions->get_conditions();
}

function get_condition( $condition_id )
{
	$conditions = Conditions::get_instance();

	return $conditions->get_condition( $condition_id );
}

function apply_conditions( $rules )
{
	$conditions = Conditions::get_instance();

	return $conditions->apply_conditions( $rules );
}

function apply_condition( $rule )
{
	$conditions = Conditions::get_instance();

	return $conditions->apply_condition( $rule );
}

function get_param_field_items()
{
	$conditions = Conditions::get_instance();

	return $conditions->get_param_field_items();
}

function get_operator_field_items( $condition_id )
{
	$conditions = Conditions::get_instance();

	return $conditions->get_operator_field_items( $condition_id );
}

function get_value_field_items( $condition_id )
{
	$conditions = Conditions::get_instance();

	return $conditions->get_value_field_items( $condition_id );
}
