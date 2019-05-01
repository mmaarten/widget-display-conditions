<?php defined( 'ABSPATH' ) or exit; // Exit when accessed directly.

/**
 * Is not equal to operator
 */
class WDC_Is_Not_Equal_To_Operator extends WDC_Operator
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( '!=', __( 'Is not equal to', 'wdc' ), array
		(
			'order' => 20,
		));
	}

	/**
	 * Apply
	 *
	 * @param bool  $return
	 * @param mixed $a
	 * @param mixed $b
	 *
	 * @return bool
	 */
	public function apply( $return, $a, $b )
	{
		return $a != $b;
	}
}

wdc_register_operator( 'WDC_Is_Not_Equal_To_Operator' );
