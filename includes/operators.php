<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.
/**
 * Operators
 */

final class WDC_Operators
{
	static private $instance = null;

	/**
	 * Get instance
	 *
	 * @return WDC_Operators
	 */
	static public function get_instance()
	{
		if ( ! self::$instance ) 
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	private $operators = array();

	/**
	 * Constructor
	 */
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
	 * @return WDC_Operator
	 */
	public function create_operator( $id, $title, $args = array() )
	{
		$operator = new WDC_Operator( $id, $title, $args );

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
		if ( ! $operator instanceof WDC_Operator ) 
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
		return array_intersect_key( $this->operators, array_flip( (array) $operator_ids ) );
	}
}

function wdc_create_operator( $id, $title, $args = array() )
{
	$operators = WDC_Operators::get_instance();
	
	return $operators->create_operator( $id, $title, $args );
}

function wdc_register_operator( $operator )
{
	$operators = WDC_Operators::get_instance();

	$operators->register_operator( $operator );
}

function wdc_unregister_operator( $operator_id )
{
	$operators = WDC_Operators::get_instance();

	$operators->unregister_operator( $operator_id );
}

function wdc_get_operators()
{
	$operators = WDC_Operators::get_instance();

	return $operators->get_operators();
}

function wdc_get_operator( $operator_id )
{
	$operators = WDC_Operators::get_instance();

	return $operators->get_operator( $operator_id );
}

function wdc_get_operator_objects( $operator_ids )
{
	$operators = WDC_Operators::get_instance();

	return $operators->get_operator_objects( $operator_ids );
}
