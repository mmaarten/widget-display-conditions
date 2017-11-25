<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_API
{
	/**
	 * Rules
	 * ---------------------------------------------------------------
	 */

	static public function get_rules()
	{
		$manager = WDC_Manager_Rule::get_instance();

		return $manager->get_objects();
	}

	static public function get_rule( $class )
	{
		$manager = WDC_Manager_Rule::get_instance();

		return $manager->get( $class );
	}

	static public function register_rule( $class )
	{
		$manager = WDC_Manager_Rule::get_instance();

		$manager->add( $class );
	}

	static public function unregister_rule( $class )
	{
		$manager = WDC_Manager_Rule::get_instance();

		$manager->remove( $class );
	}

	static public function apply_rules( $widget_id )
	{
		$manager = WDC_Manager_Rule::get_instance();

		return $manager->apply_rules( $widget_id );
	}

	/**
	 * Operators
	 * ---------------------------------------------------------------
	 */
	
	static public function get_operators()
	{
		$manager = WDC_Manager_Operator::get_instance();

		return $manager->get_objects();
	}

	static public function get_operator( $class )
	{
		$manager = WDC_Manager_Operator::get_instance();

		return $manager->get( $class );
	}

	static public function register_operator( $class )
	{
		$manager = WDC_Manager_Operator::get_instance();

		$manager->add( $class );
	}

	static public function unregister_operator( $class )
	{
		$manager = WDC_Manager_Operator::get_instance();

		$manager->remove( $class );
	}
}

