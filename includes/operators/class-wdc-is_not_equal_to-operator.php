<?php 

namespace wdc;

/**
 * Is not equal to operator
 */
class Is_Not_Equal_To_Operator extends Operator
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

register_operator( __NAMESPACE__ . '\Is_Not_Equal_To_Operator' );
