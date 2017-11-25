<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Manager_Operator extends WDC_Manager_Base
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
		if ( ! is_subclass_of( $class, 'WDC_Operator_Base' ) ) 
		{
			trigger_error( "'$class' is not a WDC_Operator_Base." );

			return;
		}

		parent::add( $class );
	}
}