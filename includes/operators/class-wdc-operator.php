<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Operator
{
	public $id    = null;
	public $title = null;

	public function __construct( $id, $title )
	{
		$this->id    = $id;
		$this->title = $title;
	}

	public function apply( $param, $value )
	{
		return false;
	}
}