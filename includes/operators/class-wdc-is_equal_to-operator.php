<?php 
/**
 * Is equal to operator
 */

namespace wdc;

class Is_Equal_To_Operator extends Operator
{
	public function __construct()
	{
		parent::__construct( '==', __( 'Is equal to', 'wdc' ), array
		(
			'order' => 10,
		));
	}

	public function apply( $return, $a, $b )
	{
		return $a == $b;
	}
}

register_operator( 'wdc\Is_Equal_To_Operator' );
