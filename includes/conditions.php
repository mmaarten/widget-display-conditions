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
	protected $categories = array();

	private function __construct()
	{
		
	}

	/**
	 * Get condition param field items
	 *
	 * @return array
	 */
	function get_condition_param_field_items()
	{
		$items = array();

		foreach ( $this->categories as $category ) 
		{
			$conditions = wp_filter_object_list( $this->conditions, array( 'category' => $category['id'] ) );

			if ( ! $conditions ) continue;

			$group = array
			(
				'id'       => $category['id'],
				'text'     => $category['title'],
				'children' => array(),
			);

			foreach ( $conditions as $condition ) 
			{
				$group['children'][ $condition->id ] = array
				(
					'id'   => $condition->id,
					'text' => $condition->title,
				);
			}

			$items[ $group['id'] ] = $group;
		}

		return $items;
	}

	/**
	 * Get condition operator field items
	 *
	 * @param string $condition_id
	 *
	 * @return mixed
	 */
	function get_condition_operator_field_items( $condition_id )
	{
		$condition = $this->get_condition( $condition_id );

		if ( $condition ) 
		{
			return $condition->get_operator_field_items();
		}

		return null;
	}

	/**
	 * Get condition value field items
	 *
	 * @param string $condition_id
	 *
	 * @return mixed
	 */
	function get_condition_value_field_items( $condition_id )
	{
		$condition = $this->get_condition( $condition_id );

		if ( ! $condition ) 
		{
			return null;
		}

		$return = $condition->get_value_field_items();

		$return = apply_filters( "wdc/condition_value_field_items/condition={$condition->id}", $return, $condition );

		return $return;
	}

	/**
	 * Create condition
	 *
	 * @param string $id
	 * @param string $title
	 * @param array  $args
	 *
	 * @return Condition
	 */
	public function create_condition( $id, $title, $args = array() )
	{
		$condition = new Condition( $id, $title, $args );

		$this->register_condition( $condition );

		return $condition;
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
			trigger_error( "Invalid rule: 'param', 'operator' and 'value' are required.", E_USER_WARNING );

			return null;
		}

		// Get condition

		$condition_id = $rule['param'];

		$condition = $this->get_condition( $condition_id );

		if ( ! $condition ) 
		{
			trigger_error( sprintf( "Unable to find condition '%s'.", $condition_id ), E_USER_WARNING );

			return null;
		}

		// Apply condition

		$return = $condition->apply( $rule['operator'], $rule['value'] );

		$return = apply_filters( "wdc/apply_condition/condition={$condition->id}", $return, $rule['operator'], $rule['value'], $condition );

		// Return

		return null === $return ? $return : (bool) $return;
	}

	/**
	 * Add condition category
	 *
	 * @param string $id
	 * @param string $title
	 * @param array  $args
	 */
	public function add_condition_category( $id, $title, $args = array() )
	{
		$args = wp_parse_args( $args, array
		(
			'order' => 10,
		));

		extract( $args, EXTR_SKIP );

		$category = array
		(
			'id'    => $id,
			'title' => $title,
			'order' => $order,
		);

		$category = apply_filters( "wdc/condition_category", $category );

		if ( ! $category ) 
		{
			return;
		}

		$this->categories[ $category['id'] ] = $category;
	}

	/**
	 * Get condition categories
	 *
	 * @return array
	 */
	public function get_condition_categories()
	{
		return $this->categories;
	}
}

function create_condition( $id, $title, $args = array() )
{
	$conditions = Conditions::get_instance();

	return $conditions->create_condition( $id, $title, $args );
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

function add_condition_category( $id, $title, $args = array() )
{
	$conditions = Conditions::get_instance();

	$conditions->add_condition_category( $id, $title, $args );
}

function get_condition_categories()
{
	$conditions = Conditions::get_instance();

	return $conditions->get_condition_categories();
}

function get_condition_param_field_items()
{
	$conditions = Conditions::get_instance();

	return $conditions->get_condition_param_field_items();
}

function get_condition_operator_field_items( $condition_id )
{
	$conditions = Conditions::get_instance();

	return $conditions->get_condition_operator_field_items( $condition_id );
}

function get_condition_value_field_items( $condition_id )
{
	$conditions = Conditions::get_instance();

	return $conditions->get_condition_value_field_items( $condition_id );
}
