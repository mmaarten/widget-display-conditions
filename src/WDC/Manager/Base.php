<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Manager_Base extends WDC_Base
{
	protected $objects = array();

	public function __construct()
	{
		parent::__construct();
	}

	public function get_objects()
	{
		return $this->objects;
	}

	public function get( $class )
	{
		if ( isset( $this->objects[ $class ] ) ) 
		{
			return $this->objects[ $class ];
		}

		return null;
	}

	public function add( $class )
	{
		if ( ! is_subclass_of( $class, 'WDC_Registrable_Base' ) ) 
		{
			trigger_error( "'$class' is not a WDC_Registrable_Base." );

			return;
		}

		$this->objects[ $class ] = new $class();
	}

	public function remove( $class )
	{
		if ( isset( $this->objects[ $class ] ) ) 
		{
			unset( $this->objects[ $class ] );
		}
	}
}