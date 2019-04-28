<?php 

namespace wdc;

/**
 * Is equal to operator
 */
class Is_Equal_To_Operator extends Operator
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct( '==', __( 'Is equal to', 'wdc' ), array
		(
			'order' => 10,
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
		return $a == $b;
	}
}

register_operator( __NAMESPACE__ . '\Is_Equal_To_Operator' );
