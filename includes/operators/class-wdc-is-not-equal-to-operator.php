<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

class WDC_is_Not_Equal_To_Operator extends WDC_Operator
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

wdc_register_operator( 'WDC_is_Not_Equal_To_Operator' );
