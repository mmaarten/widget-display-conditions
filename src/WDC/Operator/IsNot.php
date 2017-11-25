<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Operator_IsNot extends WDC_Operator_Base
{
	public function __construct()
	{
		parent::__construct( '!=', __( 'Is not equal to', 'wdc' ) );
	}

	public function apply( $param, $value )
	{
		return $param != $value;
	}
}