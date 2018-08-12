<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_Is_Equal_To_Operator extends WDC_Operator
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

wdc_register_operator( 'WDC_Is_Equal_To_Operator' );