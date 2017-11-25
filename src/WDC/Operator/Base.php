<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Operator_Base extends WDC_Registrable_Base
{
	protected $id    = null;
	protected $title = null;

	public function __construct( $id, $title )
	{
		parent::__construct();
		
		$this->id    = $id;
		$this->title = $title;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function apply( $param, $value )
	{
		return false;
	}
}