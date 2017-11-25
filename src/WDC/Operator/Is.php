<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Operator_Is extends WDC_Operator_Base
{
	public function __construct()
	{
		parent::__construct( '==', __( 'Is equal to', 'wdc' ) );
	}

	public function apply( $param, $value )
	{
		return $param == $value;
	}
}