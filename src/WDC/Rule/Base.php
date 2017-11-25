<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Rule_Base extends WDC_Registrable_Base
{
	protected $id        = null;
	protected $title     = null;
	protected $category  = null;
	protected $operators = array();

	public function __construct( $id, $title, $args = null )
	{
		parent::__construct();
	
		$defaults = array
		(
			'id'        => $id,
			'title'     => $title,
			'category'  => 'default',
			'operators' => array( 'WDC_Operator_Is', 'WDC_Operator_IsNot' )
		);

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$this->id        = $id;
		$this->title     = $title;
		$this->category  = $category;
		$this->operators = $operators;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function get_category()
	{
		return $this->category;
	}

	public function get_operators()
	{
		$operators = array();

		foreach ( $this->operators as $class ) 
		{
			$operators[] = WDC_API::get_operator( $class );
		}

		return $operators;
	}

	public function choices()
	{
		return array();
	}

	public function apply( $value, $operator )
	{
		return false;
	}
}

