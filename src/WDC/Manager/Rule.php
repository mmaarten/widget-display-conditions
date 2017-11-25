<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Manager_Rule extends WDC_Manager_Base
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

	public function __construct()
	{
		parent::__construct();
	}

	public function add( $class )
	{
		if ( ! is_subclass_of( $class, 'WDC_Rule_Base' ) ) 
		{
			trigger_error( "'$class' is not a WDC_Rule_Base." );

			return;
		}

		parent::add( $class );
	}

	public function apply_rule( $data )
	{
		$rule = $this->get( $data->param );

		if ( ! $rule ) 
		{
			return true;
		}

		$operator = WDC_API::get_operator( $data->operator );

		if ( ! $operator ) 
		{
			return true;
		}

		return $rule->apply( $data->value, $operator );
	}

	public function apply_rules( $rule_data )
	{
		if ( empty( $rule_data ) ) 
		{
			return true;
		}

		$valid = false;
		
		foreach ( $rule_data as $rule_group ) 
		{
			foreach ( $rule_group as $rule )
			{
				$valid = $this->apply_rule( $rule );

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
