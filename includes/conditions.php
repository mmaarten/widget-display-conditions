<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * Conditions
 */

final class WDC_Conditions
{
	static private $instance = null;

	/**
	 * Get instance
	 *
	 * @return WDC_Conditions
	 */
	static public function get_instance()
	{
		if ( ! self::$instance ) 
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	private $conditions = array();
	private $categories = array();

	/**
	 * Constructor
	 */
	private function __construct()
	{
		
	}

	/**
	 * Create condition
	 *
	 * @param string $id
	 * @param string $title
	 * @param array  $args
	 *
	 * @return WDC_Condition
	 */
	public function create_condition( $id, $title, $args = array() )
	{
		$condition = new WDC_Condition( $id, $title, $args );

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
		if ( ! $condition instanceof WDC_Condition ) 
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

		$category = array
		(
			'id'    => $id,
			'title' => $title,
			'order' => (int) $args['order'],
		);
		
		$category = apply_filters( 'wdc/condition_category', $category );

		return $this->categories[ $category['id'] ] = $category;
	}

	/**
	 * Remove condition category
	 *
	 * @param string $category_id
	 *
	 * @return mixed
	 */
	public function remove_condition_category( $category_id )
	{
		unset( $this->categories[ $category_id ] );
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

	/**
	 * Get condition category
	 *
	 * @param string $category_id
	 *
	 * @return mixed
	 */
	public function get_condition_category( $category_id )
	{
		if ( isset( $this->categories[ $category_id ] ) ) 
		{
			return $this->categories[ $category_id ];
		}

		return null;
	}
}

function wdc_create_condition( $id, $title, $args = array() )
{
	$conditions = WDC_Conditions::get_instance();
	
	return $conditions->create_condition( $id, $title, $args );
}

function wdc_register_condition( $condition )
{
	$conditions = WDC_Conditions::get_instance();

	$conditions->register_condition( $condition );
}

function wdc_unregister_condition( $condition_id )
{
	$conditions = WDC_Conditions::get_instance();

	$conditions->unregister_condition( $condition_id );
}

function wdc_get_conditions()
{
	$conditions = WDC_Conditions::get_instance();

	return $conditions->get_conditions();
}

function wdc_get_condition( $condition_id )
{
	$conditions = WDC_Conditions::get_instance();

	return $conditions->get_condition( $condition_id );
}

function wdc_add_condition_category( $id, $title, $args = array() )
{
	$conditions = WDC_Conditions::get_instance();

	$conditions->add_condition_category( $id, $title, $args );
}

function wdc_remove_condition_category( $category_id )
{
	$conditions = WDC_Conditions::get_instance();

	$conditions->remove_condition_category( $category_id );
}

function wdc_get_condition_categories()
{
	$conditions = WDC_Conditions::get_instance();

	return $conditions->get_condition_categories();
}

function wdc_get_condition_category( $category_id )
{
	$conditions = WDC_Conditions::get_instance();

	return $conditions->get_condition_category( $category_id );
}
